<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds an optional portrait crop for the homepage hero.
     *
     * This is art direction, not resolution switching: the desktop banner is a wide
     * landscape image, and `object-cover` on a portrait phone crops it hard to the
     * centre, often cutting the subject and any baked-in text. A separate column lets
     * the hero use `<picture>` with a `media` query so the browser picks the right
     * file BEFORE downloading either one.
     *
     * Nullable on purpose — when it is empty the hero falls back to `image`, so
     * existing banners keep working untouched and mobile art can be added per banner
     * rather than all at once.
     *
     * A `-mobile` filename convention was considered and rejected: nothing would
     * validate that the partner file exists, a rename would break it silently, and it
     * would be invisible in the admin UI.
     */
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('image_mobile')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('image_mobile');
        });
    }
};
