<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add image column to categories
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image')->nullable()->after('parent_id');
        });

        // Add subcategory_id to products (points to a child category)
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('subcategory_id')
                ->nullable()
                ->after('category_id')
                ->constrained('categories')
                ->onDelete('set null');
            // Add image column if it doesn't exist yet
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('subcategory_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['subcategory_id']);
            $table->dropColumn('subcategory_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
