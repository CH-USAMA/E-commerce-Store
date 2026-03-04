<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add pivot table for multiple managers
        Schema::create('store_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Migrate existing data if any (optional but good practice)
        $stores = DB::table('stores')->whereNotNull('manager_id')->get();
        foreach ($stores as $store) {
            DB::table('store_user')->insert([
                'store_id' => $store->id,
                'user_id' => $store->manager_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Remove old column
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('manager_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->foreignId('manager_id')->nullable()->after('contact_details');
        });

        Schema::dropIfExists('store_user');
    }
};
