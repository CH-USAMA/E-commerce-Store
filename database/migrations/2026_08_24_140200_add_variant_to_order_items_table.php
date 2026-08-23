<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Records which size was ordered.
     *
     * Two columns on purpose. `variant_id` is the live link, but it is nullable
     * with nullOnDelete because an order must survive its variant being removed
     * from the catalog. `variant_label` is a snapshot of the text at the moment of
     * purchase — the same reasoning that already makes `order_items.price` a copy
     * rather than a join back to `products.price`. Without it, deleting a
     * discontinued size would silently rewrite what old invoices say was bought.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->after('product_id')
                ->constrained('product_variants')->nullOnDelete();
            $table->string('variant_label')->nullable()->after('variant_id');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('variant_id');
            $table->dropColumn('variant_label');
        });
    }
};
