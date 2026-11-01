<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Course;
use App\Models\ExamCategory;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;
    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_coupons@nirmaan.test'],
            [
                'name' => 'HQ Finance Admin',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $this->student = User::firstOrCreate(
            ['email' => 'cadet_coupons@nirmaan.test'],
            [
                'name' => 'Cadet Buyer',
                'password' => bcrypt('password123'),
                'role' => 'student',
                'is_active' => true,
            ]
        );

        $category = ExamCategory::create([
            'name' => 'NDA Test Category',
            'slug' => 'nda-test-category',
            'is_active' => true,
        ]);

        $this->course = Course::create([
            'title' => 'NDA Crash Course 2026',
            'slug' => 'nda-crash-course-2026',
            'exam_category_id' => $category->id,
            'price' => 3000.00,
            'original_price' => 5000.00,
            'is_active' => true,
            'is_published' => true,
        ]);
    }

    public function test_admin_can_access_coupons_index_page_and_see_kpis(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.coupons.index'));

        $response->assertStatus(200);
        $response->assertSee('Coupons &amp; Scholarship Discount Codes', false);
        $response->assertSee('Total Codes');
        $response->assertSee('Active &amp; Valid', false);
    }

    public function test_admin_can_create_new_percent_coupon(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.coupons.store'), [
            'code' => 'TESTVICTORY30',
            'name' => 'Victory 30% Concession',
            'type' => 'percent',
            'value' => 30.00,
            'max_discount' => 1500.00,
            'min_order_amount' => 1000.00,
            'usage_limit' => 50,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', [
            'code' => 'TESTVICTORY30',
            'type' => 'percent',
            'value' => 30.00,
            'max_discount' => 1500.00,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_new_fixed_coupon(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.coupons.store'), [
            'code' => 'FLAT500SCHOLAR',
            'name' => 'Special 500 Rupee Concession',
            'type' => 'fixed',
            'value' => 500.00,
            'min_order_amount' => 1000.00,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', [
            'code' => 'FLAT500SCHOLAR',
            'type' => 'fixed',
            'value' => 500.00,
            'is_active' => true,
        ]);
    }

    public function test_coupon_code_must_be_unique(): void
    {
        Coupon::create([
            'code' => 'DUPLICATE10',
            'type' => 'percent',
            'value' => 10.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.coupons.store'), [
            'code' => 'duplicate10',
            'type' => 'percent',
            'value' => 10.00,
        ]);

        $response->assertSessionHasErrors(['code']);
    }

    public function test_admin_can_edit_and_update_coupon(): void
    {
        $coupon = Coupon::create([
            'code' => 'UPDATE20',
            'name' => 'Original Name',
            'type' => 'percent',
            'value' => 20.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.coupons.update', $coupon), [
            'code' => 'UPDATE25',
            'name' => 'Updated Name to 25%',
            'type' => 'percent',
            'value' => 25.00,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'code' => 'UPDATE25',
            'value' => 25.00,
            'name' => 'Updated Name to 25%',
        ]);
    }

    public function test_admin_can_toggle_coupon_status(): void
    {
        $coupon = Coupon::create([
            'code' => 'TOGGLEME',
            'type' => 'percent',
            'value' => 15.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.coupons.toggle', $coupon));

        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_coupon(): void
    {
        $coupon = Coupon::create([
            'code' => 'DELETEME',
            'type' => 'percent',
            'value' => 10.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.coupons.destroy', $coupon));

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    }

    public function test_non_admin_cannot_access_coupon_management(): void
    {
        $response = $this->actingAs($this->student)->get(route('admin.coupons.index'));
        $response->assertStatus(403);
    }

    public function test_student_can_validate_dynamic_coupon_in_checkout(): void
    {
        Coupon::create([
            'code' => 'MERIT40',
            'name' => 'Merit Concession 40%',
            'type' => 'percent',
            'value' => 40.00,
            'max_discount' => 2000.00,
            'min_order_amount' => 500.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->student)->postJson(route('student.checkout.validate-coupon'), [
            'code' => 'merit40',
            'base_price' => 3000.00,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'valid' => true,
            'code' => 'MERIT40',
            'discount_amount' => 1200.00,
            'final_amount' => 1800.00,
        ]);
    }

    public function test_inactive_and_expired_coupons_are_rejected(): void
    {
        Coupon::create([
            'code' => 'EXPIRED50',
            'type' => 'percent',
            'value' => 50.00,
            'expires_at' => now()->subDay(),
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->student)->postJson(route('student.checkout.validate-coupon'), [
            'code' => 'EXPIRED50',
            'base_price' => 3000.00,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'valid' => false,
        ]);
    }

    public function test_order_completion_increments_coupon_usage(): void
    {
        $coupon = Coupon::create([
            'code' => 'CLAIMTEST',
            'type' => 'fixed',
            'value' => 200.00,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->student)->post(route('student.checkout.pay', $this->course), [
            'payment_method' => 'mock',
            'coupon_code' => 'CLAIMTEST',
            'payment_reference' => 'mock_order_123',
        ]);

        $this->assertEquals(1, $coupon->fresh()->used_count);
    }

    public function test_admin_can_update_payment_settings(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.payment.update'), [
            'razorpay_enabled' => '1',
            'razorpay_mode' => 'test',
            'razorpay_merchant_name' => 'Nirmaan National Academy',
            'razorpay_theme_color' => '#0f172a',
            'upi_enabled' => '1',
            'upi_vpa' => 'nirmaan@axis',
            'upi_merchant_name' => 'Nirmaan Defence Axis',
            'upi_instructions' => 'Pay via Axis UPI',
            'bank_transfer_enabled' => '1',
            'bank_name' => 'Punjab National Bank',
            'bank_account_name' => 'Nirmaan Trust',
            'bank_account_number' => '123456789012',
            'bank_ifsc_code' => 'PUNB0123400',
            'bank_branch' => 'Jaipur Cantt',
            'bank_instructions' => 'Direct IMPS only',
            'method_card_enabled' => '1',
            'method_netbanking_enabled' => '1',
            'method_emi_enabled' => '1',
            'method_offline_enabled' => '1',
            'offline_campus_address' => 'Nirmaan HQ, Road 1',
            'offline_contact_phone' => '+91 99999 88888',
        ]);

        $response->assertRedirect();
        $this->assertEquals('nirmaan@axis', SiteSetting::get('upi_vpa'));
        $this->assertEquals('Punjab National Bank', SiteSetting::get('bank_name'));
        $this->assertEquals('Nirmaan National Academy', SiteSetting::get('razorpay_merchant_name'));
    }
}
