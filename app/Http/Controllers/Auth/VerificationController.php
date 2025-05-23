<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Mostra o aviso para verificar o email.
     */
    public function notice()
    {
        return view('auth.verify');
    }

    /**
     * Cumpre a verificação quando o utilizador clica no link do email.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/dashboard');
    }

    /**
     * Reenvia o email de verificação.
     */
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('resent', true);
    }
}
