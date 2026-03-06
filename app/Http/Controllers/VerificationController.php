<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice.
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('user.dashboard')
            : view('auth.verify');
    }

    /**
     * Handle the email verification link.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('user.dashboard')->with('success', 'Email verified successfully!');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard');
        }

        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent!');
    }
}
