<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\MockTest;
use App\Models\Order;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\RazorpayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RazorpayPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;
    protected Course $course;
    protected MockTest $mockTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@nirmaan.test',
        ]);

        $this->student = User::factory()->create([
            'role' => 'student',
            'email' => 'cadet@nirmaan.test',
            'phone' => '9876543210',
        ]);

        $this->course = Course::create([
            'title' => 'NDA Crash Course 2026',
            'slug' => 'nda-crash-course-2026',
            'description' => 'Target NDA written & SSB',
            'price' => 3999.00,
            'is_published' => true,
        ]);

        $this->mockTest = MockTest::create([
            'title' => 'CDS Combined Defence Mock #1',
            'slug' => 'cds-mock-1',
            'description' => 'General Knowledge and Elementary Mathematics',
            'duration_minutes' => 120,
            'price' => 499.00,
            'is_free' => false,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_view_payment_settings_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.payment'));

        $response->assertStatus(200);
        $response->assertSee('Razorpay Gateway Configuration');
        $response->assertSee('Test Key ID');
        $response->assertSee('Live Key ID');
        $response->assertSee('Webhook Callback URL');
    }

    public function test_non_admin_cannot_access_payment_settings(): void
    {
        $response = $this->actingAs($this->student)->get(route('admin.settings.payment'));
        $response->assertStatus(403);
    }

    public function test_admin_can_update_razorpay_settings(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.payment.update'), [
            'razorpay_enabled' => '1',
            'razorpay_mode' => 'test',
            'razorpay_key_id' => 'rzp_test_exampleKey123',
            'razorpay_key_secret' => 'secret1234567890',
            'razorpay_live_key_id' => 'rzp_live_exampleLiveKey456',
            'razorpay_live_key_secret' => 'liveSecret0987654321',
            'razorpay_webhook_secret' => 'whsec_abcdef123456',
            'razorpay_fallback_simulation' => '1',
            'razorpay_merchant_name' => 'Nirmaan Defence Institute',
            'razorpay_theme_color' => '#172554',
        ]);

        $response->assertRedirect(route('admin.settings.payment'));
        $response->assertSessionHas('success');

        $this->assertEquals('rzp_test_exampleKey123', SiteSetting::get('razorpay_key_id'));
        $this->assertEquals('secret1234567890', SiteSetting::get('razorpay_key_secret'));
        $this->assertEquals('test', SiteSetting::get('razorpay_mode'));
        $this->assertEquals('Nirmaan Defence Institute', SiteSetting::get('razorpay_merchant_name'));
        $this->assertEquals('1', SiteSetting::get('razorpay_enabled'));
    }

    public function test_admin_can_test_connection_via_mocked_http(): void
    {
        SiteSetting::set('razorpay_mode', 'test');
        SiteSetting::set('razorpay_key_id', 'rzp_test_validKey');
        SiteSetting::set('razorpay_key_secret', 'validSecretKey123');

        Http::fake([
            'https://api.razorpay.com/v1/payments*' => Http::response(['items' => []], 200),
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('admin.settings.payment.test'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_student_can_create_razorpay_order_via_api(): void
    {
        SiteSetting::set('razorpay_fallback_simulation', '1');

        $response = $this->actingAs($this->student)->postJson(route('student.checkout.create-razorpay-order'), [
            'type' => 'course',
            'item_id' => $this->course->id,
            'coupon_code' => '',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'order_id',
            'amount',
            'currency',
            'key_id',
            'merchant_name',
            'item_title',
        ]);
        $this->assertTrue($response->json('success'));
        $this->assertEquals(399900, $response->json('amount'));
    }

    public function test_student_can_complete_payment_with_valid_signature_and_enroll(): void
    {
        SiteSetting::set('razorpay_mode', 'test');
        SiteSetting::set('razorpay_key_id', 'rzp_test_key123');
        SiteSetting::set('razorpay_key_secret', 'secret99999');

        $orderId = 'order_test_123456';
        $paymentId = 'pay_test_7891011';
        $signature = hash_hmac('sha256', $orderId . '|' . $paymentId, 'secret99999');

        $response = $this->actingAs($this->student)->post(route('student.checkout.pay', $this->course->id), [
            'payment_method' => 'razorpay',
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature,
        ]);

        $order = Order::where('razorpay_order_id', $orderId)->first();
        $this->assertNotNull($order);
        $this->assertEquals('paid', $order->status);
        $this->assertEquals('razorpay', $order->payment_gateway);
        $this->assertEquals($paymentId, $order->payment_id);
        $this->assertEquals($signature, $order->payment_signature);

        $response->assertRedirect(route('student.orders.receipt', $order->id));
        $this->assertTrue($this->student->isEnrolledIn($this->course));
    }

    public function test_payment_with_tampered_signature_is_rejected(): void
    {
        SiteSetting::set('razorpay_mode', 'test');
        SiteSetting::set('razorpay_key_id', 'rzp_test_key123');
        SiteSetting::set('razorpay_key_secret', 'secret99999');

        $orderId = 'order_test_123456';
        $paymentId = 'pay_test_7891011';
        $invalidSignature = 'tampered_invalid_signature_hash_0000000000';

        $response = $this->actingAs($this->student)->post(route('student.checkout.pay', $this->course->id), [
            'payment_method' => 'razorpay',
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $invalidSignature,
        ]);

        $response->assertSessionHas('error');
        $this->assertFalse($this->student->isEnrolledIn($this->course));
        $this->assertDatabaseMissing('orders', [
            'razorpay_order_id' => $orderId,
        ]);
    }

    public function test_razorpay_webhook_captures_payment_and_enrolls_student(): void
    {
        SiteSetting::set('razorpay_webhook_secret', 'whsec_mysecrettoken');

        $order = Order::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'order_number' => 'NIR-20261028-TEST01',
            'amount' => 3999.00,
            'currency' => 'INR',
            'status' => 'pending',
            'payment_gateway' => 'razorpay',
            'razorpay_order_id' => 'order_webhook_test_999',
        ]);

        $payload = json_encode([
            'event' => 'order.paid',
            'payload' => [
                'order' => [
                    'entity' => [
                        'id' => 'order_webhook_test_999',
                        'amount' => 399900,
                        'status' => 'paid',
                    ],
                ],
                'payment' => [
                    'entity' => [
                        'id' => 'pay_webhook_payment_888',
                        'order_id' => 'order_webhook_test_999',
                        'status' => 'captured',
                    ],
                ],
            ],
        ]);

        $signature = hash_hmac('sha256', $payload, 'whsec_mysecrettoken');

        $response = $this->call(
            'POST',
            route('webhooks.razorpay'),
            [],
            [],
            [],
            [
                'HTTP_X-Razorpay-Signature' => $signature,
                'CONTENT_TYPE' => 'application/json',
            ],
            $payload
        );

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $order->refresh();
        $this->assertEquals('paid', $order->status);
        $this->assertEquals('pay_webhook_payment_888', $order->payment_id);
        $this->assertTrue($this->student->isEnrolledIn($this->course));
    }
}
