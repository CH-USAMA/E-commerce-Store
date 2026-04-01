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
        $tables = ['users', 'orders', 'stores', 'categories', 'brands', 'products'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                // We'll use a nullable column first so we can generate UUIDs for existing records
                $table->uuid('uuid')->nullable()->after('id')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['users', 'orders', 'stores', 'categories', 'brands', 'products'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('uuid');
            });
        }
    }
};
