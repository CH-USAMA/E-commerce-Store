<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Moves the seasonal specials out of a hardcoded array in the Blade template
     * and into the database, so branch flyers can be swapped from the admin.
     *
     * The three existing cards are seeded with their current image paths, so the
     * page renders identically the moment this runs — no visual change, no gap
     * between deploying and re-uploading.
     */
    public function up(): void
    {
        Schema::create('specials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();

            // The grid thumbnail. Auto-generated from `image_full` by
            // ImageThumbnailer, but nullable so a failed encode degrades to
            // showing the full image rather than losing the row.
            $table->string('image')->nullable();

            // The full-resolution flyer opened in the lightbox.
            $table->string('image_full');

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        // Preserve exactly what the page shows today. Paths are the legacy
        // public/images/ scheme, which image_url() resolves (ARCHITECTURE.md § 7).
        $now = now();
        DB::table('specials')->insert([
            [
                'title' => 'Mt Frere Specials',
                'subtitle' => 'Available at Branch Only',
                'image' => 'images/mtfrere_special.webp',
                'image_full' => 'images/mtfrere_special.png',
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Qumbu Specials',
                'subtitle' => 'Available at Branch Only',
                'image' => 'images/qumbu_special.webp',
                'image_full' => 'images/qumbu_special.png',
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Tsolo Specials',
                'subtitle' => 'Available at Branch Only',
                'image' => 'images/tsolo_special_compressed.webp',
                'image_full' => 'images/tsolo_special.png',
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // The specials hero background pointed at images/qumbu_special_compressed.webp,
        // which does not exist — a 404 on every load of /specials and /track-order.
        // Seed the setting with a file that is actually there; the admin can replace it.
        if (! DB::table('settings')->where('key', 'specials_hero_image')->exists()) {
            DB::table('settings')->insert([
                'key' => 'specials_hero_image',
                'value' => 'images/qumbu_special.webp',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('specials');
        DB::table('settings')->where('key', 'specials_hero_image')->delete();
    }
};
