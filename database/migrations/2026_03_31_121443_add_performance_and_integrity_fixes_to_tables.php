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
        Schema::table('addresses', function (Blueprint $table) {
            $indexes = collect(Schema::getIndexes('addresses'));
            
            if (!$indexes->contains('name', 'addresses_postal_code_index')) {
                $table->index('postal_code');
            }
            if (!$indexes->contains('name', 'addresses_type_index')) {
                $table->index('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $indexes = collect(Schema::getIndexes('addresses'));

            if ($indexes->contains('name', 'addresses_postal_code_index')) {
                $table->dropIndex(['postal_code']);
            }
            if ($indexes->contains('name', 'addresses_type_index')) {
                $table->dropIndex(['type']);
            }
        });
    }
};
