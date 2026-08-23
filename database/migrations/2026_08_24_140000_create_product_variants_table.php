<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Size variants for a product — "Lintel" in 1.2m / 1.5m / 4.6m / 4.8m.
     *
     * The catalog previously had no way to express this, and both workarounds are
     * visible in the live data: "Lintels from 1M to 6M" is one product with the
     * range stuffed into its name (so no price can be shown and nothing can be
     * picked), while "Paint Brush 50MM / 100MM / 150MM" is three separate products
     * that would sort 100, 150, 50 alphabetically.
     *
     * Deliberately NOT stock-bearing. `product_store_stocks` holds 60 rows across
     * 292 products and every one is zero, so per-store stock is not maintained;
     * giving variants their own stock would mean threading variant_id through the
     * stock table, the CSV importer and the WMS screens to serve nothing. When
     * stock starts being tracked for real, that is the moment to add it.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // Free text rather than a number+unit pair: sizes here are written as
            // "1.2m", "100MM", "4,8" — the branch's own vocabulary, which a
            // normalised numeric column would fight rather than help.
            $table->string('label');

            // Optional per-size code. Not unique: the catalog's existing `sku`
            // column is not unique either, and enforcing it here would block
            // saving a product whose codes are not yet filled in.
            $table->string('sku')->nullable();

            $table->decimal('price', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            // Sizes are listed per product and always in display order.
            $table->index(['product_id', 'sort_order']);

            // Two "1.2m" rows on one product is always a data-entry mistake, and
            // would make the cart's product:variant key ambiguous to a human
            // reading it.
            $table->unique(['product_id', 'label']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
