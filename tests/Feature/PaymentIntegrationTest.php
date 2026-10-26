<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\ExamCategory;
use App\Models\MockTest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_coupon_validation_endpoint_with_valid_code(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $category = ExamCategory::create(['name' => 'NDA', 'slug' => 'nda']);
        $course = Course::create([
            'title' => 'NDA Pathfinder Batch',
            'slug' => 'nda-pathfinder',
            'exam_category_id' => $category->id,
            'price' => 2000.00,
            'is_published' => true,
        ]);

        $response = $this->actingAs($student)->postJson(route('student.checkout.validate-coupon'), [
            'code' => 'AGNIVEER50',
            'type' => 'course',
            'item_id' => $course->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'valid' => true,
            'code' => 'AGNIVEER50',
            'discount_value' => 50,
            'discount_amount' => 1000.00,
            'final_amount' => 1000.00,
        ]);
    }

    public function test_coupon_validation_with_invalid_code(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $category = ExamCategory::create(['name' => 'NDA', 'slug' => 'nda']);
        $course = Course::create([
            'title' => 'NDA Pathfinder Batch',
            'slug' => 'nda-pathfinder',
            'exam_category_id' => $category->id,
            'price' => 2000.00,
            'is_published' => true,
        ]);

        $response = $this->actingAs($student)->postJson(route('student.checkout.validate-coupon'), [
            'code' => 'FAKEDISCOUNT99',
            'type' => 'course',
            'item_id' => $course->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'valid' => false,
        ]);
    }

    public function test_course_checkout_with_coupon_and_upi_payment(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $category = ExamCategory::create(['name' => 'CDS', 'slug' => 'cds']);
        $course = Course::create([
            'title' => 'CDS Officer Batch',
            'slug' => 'cds-officer',
            'exam_category_id' => $category->id,
            'price' => 3000.00,
            'is_published' => true,
        ]);

        $response = $this->actingAs($student)->post(route('student.checkout.pay', $course), [
            'payment_method' => 'upi',
            'coupon_code' => 'OFFICER20',
            'payment_reference' => 'pay_upi_test9988',
            'method_meta' => 'cadet@okhdfcbank',
        ]);

        $order = Order::where('user_id', $student->id)->where('course_id', $course->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(2400.00, (float) $order->amount); // 3000 - 20%
        $this->assertEquals('paid', $order->status);
        $this->assertEquals('upi', $order->payment_gateway);

        // Verify enrollment is created
        $this->assertTrue($student->isEnrolledIn($course));

        $response->assertRedirect(route('student.orders.receipt', $order));
    }

    public function test_mock_test_checkout_with_full_scholarship_waiver(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $category = ExamCategory::create(['name' => 'AFCAT', 'slug' => 'afcat']);
        $test = MockTest::create([
            'title' => 'AFCAT Flying Branch Mock 1',
            'slug' => 'afcat-mock-1',
            'exam_category_id' => $category->id,
            'duration_minutes' => 120,
            'total_marks' => 300,
            'pass_marks' => 150,
            'is_published' => true,
            'is_free' => false,
            'price' => 499.00,
        ]);

        $response = $this->actingAs($student)->post(route('student.checkout.test.pay', $test), [
            'payment_method' => 'card',
            'coupon_code' => 'CADET100',
            'payment_reference' => 'pay_sch_100',
            'method_meta' => 'rupay ending 4242',
        ]);

        $order = Order::where('user_id', $student->id)->where('mock_test_id', $test->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(0.00, (float) $order->amount); // 100% discount
        $this->assertEquals('paid', $order->status);

        $response->assertRedirect(route('student.orders.receipt', $order));

        // Test receipt view
        $receiptResponse = $this->actingAs($student)->get(route('student.orders.receipt', $order));
        $receiptResponse->assertStatus(200);
        $receiptResponse->assertSee('TAX INVOICE / RECEIPT');
        $receiptResponse->assertSee('CADET100');
        $receiptResponse->assertSee('999293');
    }
}
