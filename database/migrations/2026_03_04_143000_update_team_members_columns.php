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
        Schema::table('team_members', function (Blueprint $table) {
            $table->renameColumn('role', 'designation');
            $table->string('location')->nullable()->after('designation');
            $table->dropColumn('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->renameColumn('designation', 'role');
            $table->text('bio')->nullable()->after('role');
            $table->dropColumn('location');
        });
    }
};
