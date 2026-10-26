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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_category_id')->nullable()->constrained('exam_categories')->nullOnDelete();
            $table->string('subject'); // General Science, Mathematics, GK, Reasoning, English
            $table->string('topic')->nullable();
            $table->string('difficulty')->default('medium'); // easy, medium, hard
            $table->text('question_text');
            $table->text('option_a');
            $table->text('option_b');
            $table->text('option_c');
            $table->text('option_d');
            $table->string('correct_option', 2); // A, B, C, D
            $table->text('explanation')->nullable();
            $table->decimal('marks', 4, 2)->default(1.00);
            $table->decimal('negative_marks', 4, 2)->default(0.25);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('mock_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_category_id')->nullable()->constrained('exam_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->default(60);
            $table->decimal('total_marks', 6, 2)->default(100.00);
            $table->decimal('pass_marks', 6, 2)->default(40.00);
            $table->decimal('negative_marking_ratio', 4, 2)->default(0.25);
            $table->boolean('is_free')->default(true);
            $table->boolean('is_published')->default(true);
            $table->text('instructions')->nullable();
            $table->timestamps();
        });

        Schema::create('mock_test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mock_test_id')->constrained('mock_tests')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['mock_test_id', 'question_id']);
        });

        Schema::create('test_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mock_test_id')->constrained('mock_tests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('submitted_at')->nullable();
            $table->integer('time_taken_seconds')->default(0);
            $table->decimal('score', 6, 2)->default(0.00);
            $table->integer('total_questions')->default(0);
            $table->integer('correct_count')->default(0);
            $table->integer('incorrect_count')->default(0);
            $table->integer('unanswered_count')->default(0);
            $table->decimal('accuracy_percentage', 5, 2)->default(0.00);
            $table->string('status')->default('in_progress'); // in_progress, completed, abandoned
            $table->timestamps();
        });

        Schema::create('test_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_attempt_id')->constrained('test_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->string('selected_option', 2)->nullable(); // A, B, C, D
            $table->boolean('is_correct')->nullable();
            $table->decimal('marks_obtained', 4, 2)->default(0.00);
            $table->boolean('is_marked_for_review')->default(false);
            $table->timestamps();

            $table->unique(['test_attempt_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_attempt_answers');
        Schema::dropIfExists('test_attempts');
        Schema::dropIfExists('mock_test_questions');
        Schema::dropIfExists('mock_tests');
        Schema::dropIfExists('questions');
    }
};
