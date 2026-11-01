<?php

namespace Tests\Feature;

use App\Models\MockTest;
use App\Models\Question;
use App\Models\TestAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MockTestEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_start_mock_test_attempt(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $test = MockTest::create([
            'title' => 'NDA General Ability Paper 1',
            'slug' => 'nda-general-ability-paper-1',
            'duration_minutes' => 60,
            'total_marks' => 100,
            'pass_marks' => 40,
            'positive_mark' => 4.0,
            'negative_mark' => 1.33,
            'is_published' => true,
        ]);

        $question = Question::create([
            'question_text' => 'What is the highest gallantry award in India during peacetime?',
            'option_a' => 'Param Vir Chakra',
            'option_b' => 'Ashoka Chakra',
            'option_c' => 'Maha Vir Chakra',
            'option_d' => 'Kirti Chakra',
            'correct_option' => 'B',
            'explanation' => 'Ashoka Chakra is India highest peacetime military decoration awarded for valor or self-sacrifice.',
            'subject' => 'General Knowledge',
            'topic' => 'Defence Honours',
            'difficulty' => 'easy',
            'marks' => 4.0,
            'negative_marks' => 1.33,
            'is_active' => true,
        ]);

        $test->questions()->attach($question->id, ['sort_order' => 1]);

        // Start test attempt
        $response = $this->actingAs($student)->post(route('student.tests.start', $test));

        $attempt = TestAttempt::where('user_id', $student->id)->where('mock_test_id', $test->id)->first();
        $this->assertNotNull($attempt);
        $this->assertEquals('in_progress', $attempt->status);

        $response->assertRedirect(route('student.tests.attempt', $attempt));

        // Attempt page rendering
        $attemptPage = $this->actingAs($student)->get(route('student.tests.attempt', $attempt));
        $attemptPage->assertStatus(200);
        $attemptPage->assertSee('NDA General Ability Paper 1');
        $attemptPage->assertSee('What is the highest gallantry award in India during peacetime?');
    }

    public function test_student_can_autosave_answer_and_submit_test_with_correct_score(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $test = MockTest::create([
            'title' => 'Air Force Group X Physics Drill',
            'slug' => 'air-force-group-x-physics-drill',
            'duration_minutes' => 30,
            'total_marks' => 8,
            'pass_marks' => 4,
            'positive_mark' => 4.0,
            'negative_mark' => 1.0,
            'is_published' => true,
        ]);

        $q1 = Question::create([
            'question_text' => 'The rate of change of momentum is directly proportional to applied force.',
            'option_a' => 'Newton First Law',
            'option_b' => 'Newton Second Law',
            'option_c' => 'Newton Third Law',
            'option_d' => 'Law of Gravitation',
            'correct_option' => 'B',
            'explanation' => 'Newton second law states F = dp/dt.',
            'subject' => 'Physics',
            'marks' => 4.0,
            'negative_marks' => 1.0,
            'is_active' => true,
        ]);

        $q2 = Question::create([
            'question_text' => 'What is the SI unit of electric potential difference?',
            'option_a' => 'Ampere',
            'option_b' => 'Volt',
            'option_c' => 'Ohm',
            'option_d' => 'Watt',
            'correct_option' => 'B',
            'explanation' => 'The SI unit of electric potential difference is the Volt.',
            'subject' => 'Physics',
            'marks' => 4.0,
            'negative_marks' => 1.0,
            'is_active' => true,
        ]);

        $test->questions()->attach([
            $q1->id => ['sort_order' => 1],
            $q2->id => ['sort_order' => 2],
        ]);

        $attempt = TestAttempt::create([
            'user_id' => $student->id,
            'mock_test_id' => $test->id,
            'started_at' => now(),
            'status' => 'in_progress',
            'total_questions' => 2,
        ]);

        // Autosave answer via AJAX for Question 1 (Correct: B)
        $saveResponse1 = $this->actingAs($student)->postJson(route('student.tests.save', $attempt), [
            'question_id' => $q1->id,
            'selected_option' => 'B',
        ]);
        $saveResponse1->assertJson(['success' => true]);

        // Autosave answer via AJAX for Question 2 (Incorrect: C)
        $saveResponse2 = $this->actingAs($student)->postJson(route('student.tests.save', $attempt), [
            'question_id' => $q2->id,
            'selected_option' => 'C',
        ]);
        $saveResponse2->assertJson(['success' => true]);

        // Submit the test attempt
        $submitResponse = $this->actingAs($student)->post(route('student.tests.submit', $attempt));
        $submitResponse->assertRedirect(route('student.tests.result', $attempt));

        $attempt->refresh();
        $this->assertEquals('completed', $attempt->status);
        $this->assertEquals(1, $attempt->correct_count);
        $this->assertEquals(1, $attempt->incorrect_count);
        // Score: +4 for Q1, -1 for Q2 = 3.0
        $this->assertEquals(3.0, (float) $attempt->score);

        // Verify scorecard view
        $resultPage = $this->actingAs($student)->get(route('student.tests.result', $attempt));
        $resultPage->assertStatus(200);
        $resultPage->assertSee('EXAMINATION SCORECARD');
        $resultPage->assertSee('Newton Second Law');
        $resultPage->assertSee('The SI unit of electric potential difference is the Volt');
    }

    public function test_paid_mock_test_redirects_to_checkout_when_not_purchased(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $paidTest = MockTest::create([
            'title' => 'AFCAT Premium Officer Test Series',
            'slug' => 'afcat-premium-officer-test-series',
            'duration_minutes' => 120,
            'total_marks' => 300,
            'pass_marks' => 150,
            'is_free' => false,
            'price' => 499.00,
            'is_published' => true,
        ]);

        // Attempting to start paid test without purchase redirects to checkout
        $response = $this->actingAs($student)->post(route('student.tests.start', $paidTest));
        $response->assertRedirect(route('student.checkout.test', $paidTest));
    }

    public function test_student_can_purchase_and_unlock_mock_test_series(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $paidTest = MockTest::create([
            'title' => 'CDS Combined Defence Exam Mock',
            'slug' => 'cds-combined-defence-exam-mock',
            'duration_minutes' => 120,
            'total_marks' => 300,
            'pass_marks' => 150,
            'is_free' => false,
            'price' => 499.00,
            'is_published' => true,
        ]);

        // Complete checkout payment for test series
        $checkoutResponse = $this->actingAs($student)->post(route('student.checkout.test.pay', $paidTest), [
            'payment_method' => 'upi',
        ]);

        $order = \App\Models\Order::where('user_id', $student->id)->where('mock_test_id', $paidTest->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals('paid', $order->status);
        $this->assertEquals(499.00, (float) $order->amount);

        $checkoutResponse->assertRedirect(route('student.orders.receipt', $order));

        // Now student can view instructions and has access
        $instructions = $this->actingAs($student)->get(route('student.tests.instructions', $paidTest));
        $instructions->assertStatus(200);
        $instructions->assertSee('I Have Read All Instructions — Begin Test');
    }

    public function test_student_can_view_official_order_receipt(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $order = \App\Models\Order::create([
            'user_id' => $student->id,
            'order_number' => 'NIR-20261026-TEST99',
            'amount' => 499.00,
            'currency' => 'INR',
            'status' => 'paid',
            'payment_gateway' => 'upi',
            'payment_id' => 'tx_test123456',
            'paid_at' => now(),
            'notes' => 'Test order receipt',
        ]);

        $response = $this->actingAs($student)->get(route('student.orders.receipt', $order));
        $response->assertStatus(200);
        $response->assertSee('TAX INVOICE / RECEIPT');
        $response->assertSee('NIR-20261026-TEST99');
        $response->assertSee('499.00');
    }

    public function test_cbt_timer_is_robust_to_page_reload_and_does_not_reset_to_full_duration(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $test = MockTest::create([
            'title' => 'Countdown Verification Drill',
            'slug' => 'countdown-verification-drill',
            'duration_minutes' => 60,
            'total_marks' => 20,
            'pass_marks' => 10,
            'is_published' => true,
        ]);

        $q = Question::create([
            'question_text' => 'What is 10 + 15?',
            'option_a' => '20',
            'option_b' => '25',
            'option_c' => '30',
            'option_d' => '35',
            'correct_option' => 'B',
            'subject' => 'Mathematics',
            'marks' => 4.0,
            'negative_marks' => 1.0,
            'is_active' => true,
        ]);
        $test->questions()->attach($q->id, ['sort_order' => 1]);

        // Create attempt started 15 minutes ago
        $startedAt = now()->subMinutes(15);
        $attempt = TestAttempt::create([
            'mock_test_id' => $test->id,
            'user_id' => $student->id,
            'started_at' => $startedAt,
            'status' => 'in_progress',
        ]);

        // Reload the attempt page as the student
        $response = $this->actingAs($student)->get(route('student.tests.attempt', $attempt));

        $response->assertStatus(200);
        
        // Assert remaining seconds is ~45 minutes (approx 2700s), definitely NOT 3600 (60 minutes)
        $viewRemaining = $response->viewData('remainingSeconds');
        $this->assertLessThanOrEqual(2705, $viewRemaining);
        $this->assertGreaterThanOrEqual(2690, $viewRemaining);

        // Assert endTimestampMs is provided for client synchronization
        $this->assertNotNull($response->viewData('endTimestampMs'));
    }

    public function test_evaluation_calculates_precise_marks_negative_penalties_and_qualification(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $test = MockTest::create([
            'title' => 'Industry Standard Evaluation Paper',
            'slug' => 'industry-standard-evaluation-paper',
            'duration_minutes' => 45,
            'total_marks' => 12.00,
            'pass_marks' => 6.00,
            'is_published' => true,
        ]);

        $q1 = Question::create([
            'question_text' => 'Question 1 positive test',
            'option_a' => 'Option Alpha',
            'option_b' => 'Option Beta',
            'option_c' => 'Option Gamma',
            'option_d' => 'Option Delta',
            'correct_option' => 'A',
            'subject' => 'Science',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'is_active' => true,
        ]);

        $q2 = Question::create([
            'question_text' => 'Question 2 negative test',
            'option_a' => 'Option Alpha',
            'option_b' => 'Option Beta',
            'option_c' => 'Option Gamma',
            'option_d' => 'Option Delta',
            'correct_option' => 'B',
            'subject' => 'Science',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'is_active' => true,
        ]);

        $q3 = Question::create([
            'question_text' => 'Question 3 unattempted test',
            'option_a' => 'Option Alpha',
            'option_b' => 'Option Beta',
            'option_c' => 'Option Gamma',
            'option_d' => 'Option Delta',
            'correct_option' => 'C',
            'subject' => 'Science',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'is_active' => true,
        ]);

        $test->questions()->attach([
            $q1->id => ['sort_order' => 1],
            $q2->id => ['sort_order' => 2],
            $q3->id => ['sort_order' => 3],
        ]);

        $attempt = TestAttempt::create([
            'user_id' => $student->id,
            'mock_test_id' => $test->id,
            'started_at' => now(),
            'status' => 'in_progress',
            'total_questions' => 3,
        ]);

        // Save Q1 with lowercase 'a' (case-insensitive validation)
        $this->actingAs($student)->postJson(route('student.tests.save', $attempt), [
            'question_id' => $q1->id,
            'selected_option' => 'a',
        ])->assertJson(['success' => true]);

        // Save Q2 with incorrect option 'C'
        $this->actingAs($student)->postJson(route('student.tests.save', $attempt), [
            'question_id' => $q2->id,
            'selected_option' => 'C',
        ])->assertJson(['success' => true]);

        // Submit attempt (Q3 left unattempted)
        $response = $this->actingAs($student)->post(route('student.tests.submit', $attempt));
        $response->assertRedirect(route('student.tests.result', $attempt));

        $attempt->refresh();
        $this->assertEquals('completed', $attempt->status);
        $this->assertEquals(3, $attempt->total_questions);
        $this->assertEquals(1, $attempt->correct_count);
        $this->assertEquals(1, $attempt->incorrect_count);
        $this->assertEquals(1, $attempt->unanswered_count);
        // Score: +4.00 - 1.00 + 0 = 3.00
        $this->assertEquals(3.00, (float) $attempt->score);
        // Accuracy: 1 correct out of 2 attempted = 50.00%
        $this->assertEquals(50.00, (float) $attempt->accuracy_percentage);

        // Verify scorecard view includes calculated total marks and review details
        $scorecard = $this->actingAs($student)->get(route('student.tests.result', $attempt));
        $scorecard->assertStatus(200);
        $scorecard->assertSee('3.00');
        $scorecard->assertSee('12.0 Marks');
        $scorecard->assertSee('Not Attempted (0.00 Marks)');
        $scorecard->assertSee('+4.00 Marks');
        $scorecard->assertSee('-1.00 Marks');
    }

    public function test_client_answers_payload_is_synchronized_during_submission(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $test = MockTest::create([
            'title' => 'Direct Submit Payload Verification',
            'slug' => 'direct-submit-payload-verification',
            'duration_minutes' => 30,
            'total_marks' => 8.00,
            'pass_marks' => 4.00,
            'is_published' => true,
        ]);

        $q1 = Question::create([
            'question_text' => 'Direct Question 1',
            'option_a' => 'Alpha',
            'option_b' => 'Beta',
            'option_c' => 'Gamma',
            'option_d' => 'Delta',
            'correct_option' => 'A',
            'subject' => 'General Knowledge',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'is_active' => true,
        ]);

        $q2 = Question::create([
            'question_text' => 'Direct Question 2',
            'option_a' => 'Alpha',
            'option_b' => 'Beta',
            'option_c' => 'Gamma',
            'option_d' => 'Delta',
            'correct_option' => 'D',
            'subject' => 'General Knowledge',
            'marks' => 4.00,
            'negative_marks' => 1.00,
            'is_active' => true,
        ]);

        $test->questions()->attach([
            $q1->id => ['sort_order' => 1],
            $q2->id => ['sort_order' => 2],
        ]);

        $attempt = TestAttempt::create([
            'user_id' => $student->id,
            'mock_test_id' => $test->id,
            'started_at' => now(),
            'status' => 'in_progress',
            'total_questions' => 2,
        ]);

        // Submit directly with client_answers JSON payload (without prior AJAX calls)
        $response = $this->actingAs($student)->post(route('student.tests.submit', $attempt), [
            'client_answers' => json_encode([
                $q1->id => 'A',
                $q2->id => 'D',
            ]),
        ]);

        $response->assertRedirect(route('student.tests.result', $attempt));

        $attempt->refresh();
        $this->assertEquals('completed', $attempt->status);
        $this->assertEquals(2, $attempt->correct_count);
        $this->assertEquals(0, $attempt->incorrect_count);
        $this->assertEquals(8.00, (float) $attempt->score);
        $this->assertEquals(100.00, (float) $attempt->accuracy_percentage);
    }
}

