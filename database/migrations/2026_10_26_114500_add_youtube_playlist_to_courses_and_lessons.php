<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('youtube_playlist_url')->nullable()->after('thumbnail');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->string('duration_text')->nullable()->after('duration_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('youtube_playlist_url');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('duration_text');
        });
    }
};
