<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Database\Seeders\PracticeQuestionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PracticeQuestionBankTest extends TestCase
{
    use RefreshDatabase;

    public function test_practice_questions_seeder_creates_over_200_questions(): void
    {
        $this->seed(PracticeQuestionsSeeder::class);

        $totalQuestions = Question::count();
        $this->assertGreaterThanOrEqual(200, $totalQuestions, 'Seeder must create at least 200 questions.');

        // Verify key subjects exist
        $subjects = Question::distinct()->pluck('subject')->toArray();
        $this->assertContains('Mathematics', $subjects);
        $this->assertContains('General Science', $subjects);
        $this->assertContains('General Knowledge', $subjects);
        $this->assertContains('Reasoning', $subjects);
        $this->assertContains('English', $subjects);
    }

    public function test_student_can_view_practice_page_with_subjects_and_counts(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $this->seed(PracticeQuestionsSeeder::class);

        $response = $this->actingAs($student)->get(route('student.practice'));

        $response->assertStatus(200);
        $response->assertSeeText('Practice Question Bank');
        $response->assertSee('Mathematics');
        $response->assertSee('General Science');
        $response->assertSee('General Knowledge');
        $response->assertSee('Reasoning');
        $response->assertSee('English');
    }

    public function test_student_can_filter_practice_by_subject_and_difficulty(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $this->seed(PracticeQuestionsSeeder::class);

        $response = $this->actingAs($student)->get(route('student.practice', [
            'subject' => 'Mathematics',
            'difficulty' => 'easy',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('selectedSubject', 'Mathematics');
        $response->assertViewHas('selectedDifficulty', 'easy');
    }

    public function test_student_can_search_practice_questions(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $this->seed(PracticeQuestionsSeeder::class);

        $response = $this->actingAs($student)->get(route('student.practice', [
            'q' => 'trigonometry',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('search', 'trigonometry');
    }

    public function test_student_can_check_practice_answer(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $question = Question::create([
            'question_text' => 'What is the value of sin(90°)?',
            'option_a' => '0',
            'option_b' => '1',
            'option_c' => '1/2',
            'option_d' => 'Undefined',
            'correct_option' => 'B',
            'explanation' => 'sin(90°) = 1.',
            'subject' => 'Mathematics',
            'topic' => 'Trigonometry',
            'difficulty' => 'easy',
            'marks' => 2.0,
            'negative_marks' => 0.5,
            'is_active' => true,
        ]);

        // Correct answer
        $response = $this->actingAs($student)->postJson(route('student.practice.check'), [
            'question_id' => $question->id,
            'selected_option' => 'B',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'is_correct' => true,
            'correct_option' => 'B',
            'explanation' => 'sin(90°) = 1.',
        ]);

        // Incorrect answer
        $response2 = $this->actingAs($student)->postJson(route('student.practice.check'), [
            'question_id' => $question->id,
            'selected_option' => 'A',
        ]);

        $response2->assertStatus(200);
        $response2->assertJson([
            'is_correct' => false,
            'correct_option' => 'B',
        ]);
    }

    public function test_student_can_request_practice_questions_as_json(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $this->seed(PracticeQuestionsSeeder::class);

        $response = $this->actingAs($student)->getJson(route('student.practice', [
            'subject' => 'Mathematics',
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'total',
            'data',
        ]);
    }
}
