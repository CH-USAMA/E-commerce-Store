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

    public function payments()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        return view('admin.settings.payments', compact('settings'));
    }

    public function updatePayments(Request $request)
    {
        $data = $request->validate([
            'stripe_public_key' => 'nullable|string',
            'stripe_secret_key' => 'nullable|string',
            'stripe_enabled' => 'nullable|in:0,1',
            'max_delivery_km' => 'nullable|numeric|min:0',
        ]);

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }

        return back()->with('success', 'Payment gateway settings updated successfully.');
    }
}
