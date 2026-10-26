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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_category_id')->nullable()->constrained('exam_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable(); // e.g. Agniveer / Soldier / Clerk / Technical
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 10, 2)->default(2999.00);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->boolean('is_free')->default(false);
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(true);
            $table->string('instructor_name')->nullable()->default('Ex-Defence Officers & Top Faculty');
            $table->string('duration_text')->nullable()->default('6 Months Access');
            $table->boolean('has_live_classes')->default(true);
            $table->boolean('has_mock_tests')->default(true);
            $table->boolean('has_study_material')->default(true);
            $table->json('features')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
