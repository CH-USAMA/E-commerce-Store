<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

/**
 * Jabulani Store - Production UUID Repair Script
 * Phase 4: Security Hardening
 * Run this after migrating to populate missing UUIDs on the live server.
 */

$tables = ['users', 'orders', 'stores', 'products', 'categories', 'brands'];

foreach ($tables as $table) {
    if (!Schema::hasTable($table)) {
        echo "Skipping table (not found): $table\n";
        continue;
    }

    $records = DB::table($table)->whereNull('uuid')->get();
    $count = 0;
    foreach ($records as $record) {
        DB::table($table)->where('id', $record->id)->update([
            'uuid' => (string) Str::uuid()
        ]);
        $count++;
    }
    echo "Successfully fixed $count missing UUIDs in [$table].\n";
}

echo "--- DEPLOYMENT DATA REPAIR COMPLETE ---\n";
