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
        Schema::table('study_materials', function (Blueprint $table) {
            $table->string('file_name')->nullable()->after('file_path');
            $table->string('external_url')->nullable()->after('file_size');
            $table->boolean('is_published')->default(true)->after('is_free');
            $table->boolean('is_featured')->default(false)->after('is_published');
            $table->foreignId('created_by')->nullable()->after('downloads_count')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('study_materials', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'file_name',
                'external_url',
                'is_published',
                'is_featured',
                'created_by',
            ]);
        });
    }
};
