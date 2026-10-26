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
}
