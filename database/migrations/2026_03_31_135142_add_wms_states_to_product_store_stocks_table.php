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
        Schema::table('product_store_stocks', function (Blueprint $table) {
            $table->integer('incoming')->default(0)->after('quantity')->comment('Stock ordered but not yet received');
            $table->integer('reserved')->default(0)->after('incoming')->comment('Stock reserved but not yet fulfilled');
            $table->integer('damaged')->default(0)->after('reserved')->comment('Damaged or unusable stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_store_stocks', function (Blueprint $table) {
            $table->dropColumn(['incoming', 'reserved', 'damaged']);
        });
    }
};
