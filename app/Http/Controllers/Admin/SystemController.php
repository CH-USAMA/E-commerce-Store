<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Storage;

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

    public function invoiceSettings()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $eft_accounts = json_decode($settings['invoice_eft_accounts'] ?? '[]', true);
        return view('admin.settings.invoice', compact('settings', 'eft_accounts'));
    }

    public function updateInvoiceSettings(Request $request)
    {
        $request->validate([
            'invoice_company_name'    => 'nullable|string|max:255',
            'invoice_company_address' => 'nullable|string',
            'invoice_company_phone'   => 'nullable|string|max:50',
            'invoice_company_email'   => 'nullable|email|max:255',
            'invoice_registration_number' => 'nullable|string|max:100',
            'invoice_footer_text'     => 'nullable|string',
            'invoice_logo'            => 'nullable|image|max:1024',
            'eft_accounts'            => 'nullable|array',
        ]);

        $settings = $request->only([
            'invoice_company_name',
            'invoice_company_address',
            'invoice_company_phone',
            'invoice_company_email',
            'invoice_registration_number',
            'invoice_footer_text',
        ]);

        // Handle EFT accounts
        $eftAccounts = [];
        if ($request->has('eft_accounts')) {
            foreach ($request->eft_accounts as $account) {
                if (!empty($account['bank']) && !empty($account['number'])) {
                    $eftAccounts[] = $account;
                }
            }
        }
        $settings['invoice_eft_accounts'] = json_encode($eftAccounts);

        // Handle Logo
        if ($request->hasFile('invoice_logo')) {
            $oldLogo = \App\Models\Setting::where('key', 'invoice_logo')->first()?->value;
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $settings['invoice_logo'] = $request->file('invoice_logo')->store('settings', 'public');
        }

        foreach ($settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }

        return back()->with('success', 'Invoice settings updated successfully.');
    }
}
