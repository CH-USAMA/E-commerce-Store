<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class InvoiceSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'invoice_company_name' => 'Jabulani Group of Companies (Pty) Ltd',
            'invoice_company_address' => "Main Street\nMt Frere, 5090\nEastern Cape, South Africa",
            'invoice_company_phone' => '+27 660 684 585',
            'invoice_company_email' => 'info@jabulanigroupofcompanies.co.za',
            'invoice_registration_number' => 'Reg No: 2023/123456/07',
            'invoice_footer_text' => 'Thank you for choosing Jabulani Group. All goods remain property of Jabulani until paid in full. Payment is due within 7 days of invoice date.',
            'invoice_eft_accounts' => json_encode([
                [
                    'bank' => 'First National Bank (FNB)',
                    'name' => 'Jabulani Group of Companies',
                    'number' => '62866895166',
                    'code' => '250655'
                ],
                [
                    'bank' => 'Standard Bank',
                    'name' => 'Jabulani Group of Companies',
                    'number' => '10186543210',
                    'code' => '051001'
                ]
            ])
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        echo "Invoice Settings Seeded Successfully.\n";
    }
}
