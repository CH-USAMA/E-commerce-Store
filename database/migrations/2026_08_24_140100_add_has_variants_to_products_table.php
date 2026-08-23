<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The "single product" vs "product with sizes" switch on the product form.
     *
     * Kept as an explicit flag rather than inferred from `variants()->exists()` so
     * that a product can be switched back to single without deleting its sizes —
     * useful when a range is discontinued for a season and comes back. It also
     * means every read path has one cheap boolean to check instead of a count.
     *
     * Existing rows default to false, which is what all 292 of them are.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_variants')->default(false)->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('has_variants');
        });
    }
};
