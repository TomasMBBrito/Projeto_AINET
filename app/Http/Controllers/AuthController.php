<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;

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
                return back()->withErrors(['email' => 'Por favor verifique o seu email antes de iniciar sessão.']);
            }

            if (Auth::user()->blocked) {
                Auth::logout();
                return back()->withErrors(['email' => 'A sua conta está bloqueada.']);
            }

            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas.'])->onlyInput('email');
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

        // Criar cartão virtual associado
        Card::create([
            'id' => $user->id,
            'card_number' => rand(100000, 999999),
            'balance' => 0,
        ]);

        // Disparar evento de registo para verificação de email
        event(new Registered($user));

        return redirect()->route('login')->with('success', 'Conta criada com sucesso! Verifique o seu email para ativar a conta.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
