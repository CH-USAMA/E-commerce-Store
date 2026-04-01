<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PurgeGuestOrderData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-guest-order-data {--email= : Specific guest email to purge} {--days=2555 : Days of retention (default 7 years)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Anonymize personal data for guest orders to comply with POPIA/GDPR.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $email = $this->option('email');
        $cutoffDate = now()->subDays($days);

        $query = \App\Models\Order::whereNull('user_id');

        if ($email) {
            $query->where('customer_email', $email);
            $this->info("Purging data for guest email: {$email}");
        } else {
            $query->where('created_at', '<=', $cutoffDate);
            $this->info("Purging guest data older than {$days} days (Cutoff: {$cutoffDate->toDateString()})");
        }

        $count = $query->count();

        if ($count === 0) {
            $this->info("No guest records found matching the criteria.");
            return;
        }

        if (!$this->confirm("Found {$count} guest records. Proceed with anonymization?", true)) {
            $this->warn("Operation cancelled.");
            return;
        }

        $query->update([
            'customer_name' => '[PURGED]',
            'customer_email' => '[PURGED]',
            'customer_phone' => '[PURGED]',
            'customer_address' => '[PURGED]',
            'customer_city' => '[PURGED]',
            'customer_postal_code' => '[PURGED]',
            'notes' => '[PII REMOVED]',
            'payment_screenshot' => null,
        ]);

        $this->info("Successfully anonymized {$count} guest records.");
    }
}
