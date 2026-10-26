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
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'mock_test_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('course_id')->nullable()->change();
                $table->foreignId('mock_test_id')->nullable()->after('course_id')->constrained('mock_tests')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'mock_test_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['mock_test_id']);
                $table->dropColumn('mock_test_id');
            });
        }
    }
};
