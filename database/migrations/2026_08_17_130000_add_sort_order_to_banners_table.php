<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Gives banners an explicit display order.
     *
     * The hero slider previously rendered in primary-key order, so the sequence could
     * only be changed by deleting and re-creating rows.
     *
     * Existing rows are backfilled with `sort_order = id` so the current on-screen
     * order is preserved exactly — without this every row would default to 0, ties
     * would fall back to an unspecified order, and the live slider could silently
     * reshuffle on deploy.
     */
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('link')->index();
        });

        // Preserve the order the slider is showing right now.
        DB::table('banners')->orderBy('id')->update(['sort_order' => DB::raw('id')]);
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropIndex(['sort_order']);
            $table->dropColumn('sort_order');
        });
    }
};
