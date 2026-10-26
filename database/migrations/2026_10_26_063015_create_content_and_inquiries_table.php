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
        Schema::create('study_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_category_id')->nullable()->constrained('exam_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->default('pdf'); // pdf, notes, syllabus, previous_paper
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_size')->nullable();
            $table->boolean('is_free')->default(true);
            $table->integer('downloads_count')->default(0);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->default('general'); // general, courses, tests, payment
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('new'); // new, read, replied
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('contact_inquiries');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('study_materials');
    }
};
