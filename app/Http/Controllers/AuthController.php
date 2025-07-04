<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (is_null($user->email_verified_at)) {
                Auth::logout();
                return back()->withErrors(['email' => 'Please check your email before logging in..']);
            }

            if (Auth::user()->blocked) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is blocked.']);
            }

            CartController::syncCartAfterLogin(Auth::user());
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'gender' => 'required|in:M,F',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nif' => 'nullable|string|max:20',
            'default_delivery_address' => 'nullable|string|max:255',
            'default_payment_type' => 'nullable|in:Visa,PayPal,MB WAY',
            'default_payment_reference' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('profile_photos', 'public');
        }

        $user = User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'type' => 'pending_member',
        ]);

        event(new Registered($user));

        Card::create([
            'id' => $user->id,
            'card_number' => rand(100000, 999999),
            'balance' => 0,
        ]);

        Auth::login($user);

        return redirect()->route('verification.send')->with('success', 'Account created successfully! Check your email to activate the account.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function showVerificationNotice()
    {
        return view('auth.verify');
    }

    // Verificar email (link do email)
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/');
    }

    // Reenviar email de verificação
    public function resendVerificationEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification email resent successfully!');
    }

    // Check manual para saber se o email já foi verificado (ex: botão "Já verifiquei")
    public function checkEmailVerified(Request $request)
    {
        $user = $request->user();
        if ($user->email_verified_at !== null) {
            return redirect()->route('login')->with('status', 'Email verified successfully. You can log in.');
        }

        return back()->withErrors(['email' => 'Email verification has not been completed yet. Please check your inbox..']);
    }
}
