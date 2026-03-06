<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class SystemController extends Controller
{
    public function sendTestEmail(Request $request)
    {
        try {
            Mail::to($request->user()->email)->send(new TestMail());
            return back()->with('success', 'Test email sent successfully to ' . $request->user()->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Mail Error: ' . $e->getMessage());
        }
    }
}
