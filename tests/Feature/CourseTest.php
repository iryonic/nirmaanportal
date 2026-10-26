<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\ExamCategory;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_courses_catalog(): void
    {
        $category = ExamCategory::create([
            'name' => 'Indian Army',
            'slug' => 'indian-army',
            'code' => 'ARMY',
            'is_active' => true,
        ]);

        $course = Course::create([
            'exam_category_id' => $category->id,
            'title' => 'Indian Army GD & Technical Mastery',
            'slug' => 'indian-army-gd-technical-mastery',
            'short_description' => 'Comprehensive preparation for Indian Army recruitment',
            'price' => 2999,
            'original_price' => 5999,
            'is_featured' => true,
            'status' => 'published',
        ]);

        $response = $this->get('/courses');
        $response->assertStatus(200);
        $response->assertSee('Indian Army GD & Technical Mastery');
        $response->assertSee('2,999');
    }

    public function test_can_filter_courses_by_category(): void
    {
        $armyCategory = ExamCategory::create([
            'name' => 'Indian Army',
            'slug' => 'indian-army',
            'code' => 'ARMY',
            'is_active' => true,
        ]);

        $navyCategory = ExamCategory::create([
            'name' => 'Indian Navy',
            'slug' => 'indian-navy',
            'code' => 'NAVY',
            'is_active' => true,
        ]);

        $armyCourse = Course::create([
            'exam_category_id' => $armyCategory->id,
            'title' => 'Indian Army Agniveer Special',
            'slug' => 'indian-army-agniveer-special',
            'price' => 2499,
            'status' => 'published',
        ]);

        $navyCourse = Course::create([
            'exam_category_id' => $navyCategory->id,
            'title' => 'Indian Navy SSR & MR Comprehensive',
            'slug' => 'indian-navy-ssr-mr-comprehensive',
            'price' => 2499,
            'status' => 'published',
        ]);

        $response = $this->get('/courses?category=' . $armyCategory->slug);
        $response->assertStatus(200);
        $response->assertSee('Indian Army Agniveer Special');
        $response->assertDontSee('Indian Navy SSR & MR Comprehensive');
    }

    public function test_can_view_course_detail_and_curriculum(): void
    {
        $category = ExamCategory::create([
            'name' => 'Air Force',
            'slug' => 'air-force',
            'code' => 'AFCAT',
            'is_active' => true,
        ]);

        $course = Course::create([
            'exam_category_id' => $category->id,
            'title' => 'Air Force Group X & Y Mastery',
            'slug' => 'air-force-group-x-y-mastery',
            'short_description' => 'Master physics, math, and english for AFCAT and Airmen',
            'price' => 2999,
            'status' => 'published',
        ]);

        $module = CourseModule::create([
            'course_id' => $course->id,
            'title' => 'Module 1: General English & Vocabulary',
            'sort_order' => 1,
        ]);

        $lesson = Lesson::create([
            'course_module_id' => $module->id,
            'title' => 'Comprehension & Grammar Strategy',
            'type' => 'video',
            'video_provider' => 'youtube',
            'video_url' => 'dQw4w9WgXcQ',
            'is_free_preview' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/courses/' . $course->slug);
        $response->assertStatus(200);
        $response->assertSee('Air Force Group X & Y Mastery');
        $response->assertSee('Module 1: General English & Vocabulary');
        $response->assertSee('Comprehension & Grammar Strategy');
    }

    public function test_can_view_dedicated_course_video_watch_page(): void
    {
        $category = ExamCategory::create([
            'name' => 'NDA Exam',
            'slug' => 'nda-exam',
            'is_active' => true,
        ]);

        $course = Course::create([
            'exam_category_id' => $category->id,
            'title' => 'NDA 2026 Mathematics Masterclass',
            'slug' => 'nda-2026-mathematics-masterclass',
            'price' => 2999,
            'is_published' => true,
        ]);

        $module = CourseModule::create([
            'course_id' => $course->id,
            'title' => 'Calculus & Vectors',
            'sort_order' => 1,
        ]);

        $lesson1 = Lesson::create([
            'course_module_id' => $module->id,
            'title' => 'Limits and Continuity Introduction',
            'video_provider' => 'youtube',
            'video_id' => 'M9DoUszVCFc',
            'duration_minutes' => 35,
            'duration_text' => '35:55',
            'is_preview' => true,
            'sort_order' => 1,
        ]);

        $lesson2 = Lesson::create([
            'course_module_id' => $module->id,
            'title' => 'Differential Equations Drill',
            'video_provider' => 'youtube',
            'video_id' => '1kPEQ-MqlFI',
            'duration_minutes' => 42,
            'duration_text' => '42:10',
            'is_preview' => false,
            'sort_order' => 2,
        ]);

        // Visit dedicated watch view
        $response = $this->get('/courses/' . $course->slug . '/watch');
        $response->assertStatus(200);
        $response->assertSee('NDA 2026 Mathematics Masterclass', false);
        $response->assertSee('Course Lesson Directory');
        $response->assertSee('2 Lessons Available');
        $response->assertSee('Limits and Continuity Introduction');
        $response->assertSee('Play');
        $response->assertSee('35:55');
    }

    public function test_admin_can_save_manual_episodes(): void
    {
        $admin = \App\Models\User::factory()->create([
            'role' => 'admin',
        ]);

        $course = Course::create([
            'title' => 'CDS General Knowledge Series',
            'slug' => 'cds-general-knowledge-series',
            'price' => 1999,
            'is_published' => true,
        ]);

        $response = $this->actingAs($admin)->post('/admin/courses/' . $course->id . '/save-episodes', [
            'episodes' => [
                ['title' => 'Indian Constitution & Polity', 'video_input' => 'https://www.youtube.com/watch?v=M9DoUszVCFc'],
                ['title' => 'Modern History & Battles', 'video_input' => '<iframe src="https://www.youtube.com/embed/1kPEQ-MqlFI"></iframe>'],
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('lessons', [
            'title' => 'Indian Constitution & Polity',
            'video_id' => 'M9DoUszVCFc',
        ]);
        $this->assertDatabaseHas('lessons', [
            'title' => 'Modern History & Battles',
            'video_id' => '1kPEQ-MqlFI',
        ]);
    }
}
