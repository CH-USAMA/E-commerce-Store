<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Repair missing UUIDs for routing
        $tables = ['users', 'orders', 'stores', 'products', 'categories', 'brands'];

        foreach ($tables as $table) {
            if (!\Illuminate\Support\Facades\Schema::hasTable($table)) continue;

            \Illuminate\Support\Facades\DB::table($table)->whereNull('uuid')->get()->each(function ($record) use ($table) {
                \Illuminate\Support\Facades\DB::table($table)->where('id', $record->id)->update([
                    'uuid' => (string) \Illuminate\Support\Str::uuid()
                ]);
            });
        }

        // 2. Seed Invoice Branding and EFT Details
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
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse for data repair
    }
};
