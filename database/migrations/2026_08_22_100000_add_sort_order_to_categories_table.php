<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Gives categories an explicit display order, the same way `banners.sort_order`
     * works for the hero slider.
     *
     * The homepage "Shop By Category" grid, the /products sidebar and the admin
     * product dropdowns all rendered in primary-key order, so the sequence could
     * only be changed by deleting and re-creating rows.
     *
     * The order is scoped **per parent**: top-level categories are ordered among
     * themselves and each parent's children are ordered within that parent. Hence
     * the composite `(parent_id, sort_order)` index — every ordering query filters
     * by parent first.
     *
     * Existing rows are backfilled with `sort_order = id`, which preserves the
     * current on-screen sequence exactly *inside each parent group* as well as at
     * the top level. Without it every row would default to 0 and the live category
     * grid could silently reshuffle on deploy.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('parent_id');
            $table->index(['parent_id', 'sort_order']);
        });

        // Preserve the order the storefront is showing right now.
        DB::table('categories')->orderBy('id')->update(['sort_order' => DB::raw('id')]);
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['parent_id', 'sort_order']);
            $table->dropColumn('sort_order');
        });
    }
};
