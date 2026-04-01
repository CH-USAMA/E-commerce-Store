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
        $tables = ['users', 'orders', 'stores', 'products', 'categories', 'brands'];

        foreach ($tables as $table) {
            if (!\Illuminate\Support\Facades\Schema::hasTable($table)) continue;

            \Illuminate\Support\Facades\DB::table($table)->whereNull('uuid')->get()->each(function ($record) use ($table) {
                \Illuminate\Support\Facades\DB::table($table)->where('id', $record->id)->update([
                    'uuid' => (string) \Illuminate\Support\Str::uuid()
                ]);
            });
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
