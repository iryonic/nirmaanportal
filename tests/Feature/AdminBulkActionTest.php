<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Coupon;
use App\Models\MockTest;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Question;
use App\Models\StudyMaterial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBulkActionTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_bulk_delete_questions(): void
    {
        $admin = $this->createAdmin();

        $q1 = Question::create([
            'question_text' => 'Question 1',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
            'subject' => 'Physics',
            'marks' => 4.0,
            'negative_marks' => 1.0,
            'difficulty' => 'easy',
            'is_active' => true,
        ]);

        $q2 = Question::create([
            'question_text' => 'Question 2',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'B',
            'subject' => 'Physics',
            'marks' => 4.0,
            'negative_marks' => 1.0,
            'difficulty' => 'easy',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.questions.bulk'), [
            'ids' => [$q1->id, $q2->id],
            'action' => 'delete',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('questions', ['id' => $q1->id]);
        $this->assertDatabaseMissing('questions', ['id' => $q2->id]);
    }

    public function test_admin_can_bulk_toggle_questions_active_status(): void
    {
        $admin = $this->createAdmin();

        $q = Question::create([
            'question_text' => 'Question Active Toggle',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
            'subject' => 'Mathematics',
            'marks' => 4.0,
            'negative_marks' => 1.0,
            'difficulty' => 'easy',
            'is_active' => true,
        ]);

        // Deactivate
        $respDeactivate = $this->actingAs($admin)->post(route('admin.questions.bulk'), [
            'ids' => [$q->id],
            'action' => 'deactivate',
        ]);
        $respDeactivate->assertRedirect();
        $this->assertFalse((bool) $q->fresh()->is_active);

        // Activate
        $respActivate = $this->actingAs($admin)->post(route('admin.questions.bulk'), [
            'ids' => [$q->id],
            'action' => 'activate',
        ]);
        $respActivate->assertRedirect();
        $this->assertTrue((bool) $q->fresh()->is_active);
    }

    public function test_admin_can_bulk_export_questions_csv(): void
    {
        $admin = $this->createAdmin();

        $q = Question::create([
            'question_text' => 'Unique Export Sample Prompt',
            'option_a' => 'Opt A',
            'option_b' => 'Opt B',
            'option_c' => 'Opt C',
            'option_d' => 'Opt D',
            'correct_option' => 'C',
            'subject' => 'General Science',
            'marks' => 4.0,
            'negative_marks' => 1.0,
            'difficulty' => 'easy',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.questions.bulk'), [
            'ids' => [$q->id],
            'action' => 'export',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('questions_export_', $response->headers->get('Content-Disposition'));
    }

    public function test_admin_can_bulk_activate_and_suspend_students(): void
    {
        $admin = $this->createAdmin();

        $st1 = User::factory()->create(['role' => 'student', 'is_active' => true]);
        $st2 = User::factory()->create(['role' => 'student', 'is_active' => true]);

        // Bulk suspend
        $suspendResponse = $this->actingAs($admin)->post(route('admin.students.bulk'), [
            'ids' => [$st1->id, $st2->id],
            'action' => 'suspend',
        ]);

        $suspendResponse->assertRedirect();
        $suspendResponse->assertSessionHas('success');
        $this->assertFalse((bool) $st1->fresh()->is_active);
        $this->assertFalse((bool) $st2->fresh()->is_active);

        // Bulk activate
        $activateResponse = $this->actingAs($admin)->post(route('admin.students.bulk'), [
            'ids' => [$st1->id, $st2->id],
            'action' => 'activate',
        ]);

        $activateResponse->assertRedirect();
        $activateResponse->assertSessionHas('success');
        $this->assertTrue((bool) $st1->fresh()->is_active);
        $this->assertTrue((bool) $st2->fresh()->is_active);
    }

    public function test_admin_can_bulk_publish_unpublish_delete_and_export_courses(): void
    {
        $admin = $this->createAdmin();

        $c1 = Course::create([
            'title' => 'NDA Crash Course 2026',
            'slug' => 'nda-crash-course-2026',
            'is_published' => false,
            'price' => 2999,
        ]);
        $c2 = Course::create([
            'title' => 'CDS Combined Batch 2026',
            'slug' => 'cds-combined-batch-2026',
            'is_published' => false,
            'price' => 3499,
        ]);

        // 1. Bulk publish
        $pubResp = $this->actingAs($admin)->post(route('admin.courses.bulk'), [
            'ids' => [$c1->id, $c2->id],
            'action' => 'publish',
        ]);
        $pubResp->assertRedirect();
        $this->assertTrue((bool) $c1->fresh()->is_published);
        $this->assertTrue((bool) $c2->fresh()->is_published);

        // 2. Bulk unpublish
        $unpubResp = $this->actingAs($admin)->post(route('admin.courses.bulk'), [
            'ids' => [$c1->id, $c2->id],
            'action' => 'unpublish',
        ]);
        $unpubResp->assertRedirect();
        $this->assertFalse((bool) $c1->fresh()->is_published);

        // 3. Bulk export
        $exportResp = $this->actingAs($admin)->post(route('admin.courses.bulk'), [
            'ids' => [$c1->id],
            'action' => 'export',
        ]);
        $exportResp->assertStatus(200);
        $this->assertStringContainsString('text/csv', $exportResp->headers->get('Content-Type'));

        // 4. Bulk delete
        $delResp = $this->actingAs($admin)->post(route('admin.courses.bulk'), [
            'ids' => [$c1->id, $c2->id],
            'action' => 'delete',
        ]);
        $delResp->assertRedirect();
        $this->assertDatabaseMissing('courses', ['id' => $c1->id]);
        $this->assertDatabaseMissing('courses', ['id' => $c2->id]);
    }

    public function test_admin_can_bulk_publish_draft_delete_and_export_resources(): void
    {
        $admin = $this->createAdmin();

        $r1 = StudyMaterial::create([
            'title' => 'Physics Formula Sheet 2026',
            'slug' => 'physics-formula-sheet-2026',
            'type' => StudyMaterial::TYPE_FORMULA_SHEET,
            'is_published' => false,
            'created_by' => $admin->id,
        ]);
        $r2 = StudyMaterial::create([
            'title' => 'General Science Notes',
            'slug' => 'general-science-notes',
            'type' => StudyMaterial::TYPE_PDF,
            'is_published' => false,
            'created_by' => $admin->id,
        ]);

        // 1. Bulk publish
        $pubResp = $this->actingAs($admin)->post(route('admin.resources.bulk'), [
            'ids' => [$r1->id, $r2->id],
            'action' => 'publish',
        ]);
        $pubResp->assertRedirect();
        $this->assertTrue((bool) $r1->fresh()->is_published);

        // 2. Bulk draft
        $draftResp = $this->actingAs($admin)->post(route('admin.resources.bulk'), [
            'ids' => [$r1->id],
            'action' => 'draft',
        ]);
        $draftResp->assertRedirect();
        $this->assertFalse((bool) $r1->fresh()->is_published);

        // 3. Bulk export
        $exportResp = $this->actingAs($admin)->post(route('admin.resources.bulk'), [
            'ids' => [$r1->id],
            'action' => 'export',
        ]);
        $exportResp->assertStatus(200);
        $this->assertStringContainsString('text/csv', $exportResp->headers->get('Content-Type'));

        // 4. Bulk delete
        $delResp = $this->actingAs($admin)->post(route('admin.resources.bulk'), [
            'ids' => [$r1->id, $r2->id],
            'action' => 'delete',
        ]);
        $delResp->assertRedirect();
        $this->assertDatabaseMissing('study_materials', ['id' => $r1->id]);
        $this->assertDatabaseMissing('study_materials', ['id' => $r2->id]);
    }

    public function test_admin_can_bulk_publish_unpublish_delete_and_export_tests(): void
    {
        $admin = $this->createAdmin();

        $t1 = MockTest::create([
            'title' => 'AFCAT Full Mock Test I',
            'slug' => 'afcat-full-mock-test-1',
            'duration_minutes' => 120,
            'total_marks' => 300,
            'pass_marks' => 150,
            'is_published' => false,
        ]);
        $t2 = MockTest::create([
            'title' => 'AFCAT Full Mock Test II',
            'slug' => 'afcat-full-mock-test-2',
            'duration_minutes' => 120,
            'total_marks' => 300,
            'pass_marks' => 150,
            'is_published' => false,
        ]);

        // 1. Bulk publish
        $pubResp = $this->actingAs($admin)->post(route('admin.tests.bulk'), [
            'ids' => [$t1->id, $t2->id],
            'action' => 'publish',
        ]);
        $pubResp->assertRedirect();
        $this->assertTrue((bool) $t1->fresh()->is_published);

        // 2. Bulk unpublish
        $unpubResp = $this->actingAs($admin)->post(route('admin.tests.bulk'), [
            'ids' => [$t1->id],
            'action' => 'unpublish',
        ]);
        $unpubResp->assertRedirect();
        $this->assertFalse((bool) $t1->fresh()->is_published);

        // 3. Bulk export
        $exportResp = $this->actingAs($admin)->post(route('admin.tests.bulk'), [
            'ids' => [$t1->id],
            'action' => 'export',
        ]);
        $exportResp->assertStatus(200);
        $this->assertStringContainsString('text/csv', $exportResp->headers->get('Content-Type'));

        // 4. Bulk delete
        $delResp = $this->actingAs($admin)->post(route('admin.tests.bulk'), [
            'ids' => [$t1->id, $t2->id],
            'action' => 'delete',
        ]);
        $delResp->assertRedirect();
        $this->assertDatabaseMissing('mock_tests', ['id' => $t1->id]);
        $this->assertDatabaseMissing('mock_tests', ['id' => $t2->id]);
    }

    public function test_admin_can_bulk_update_orders_status_and_export(): void
    {
        $admin = $this->createAdmin();
        $student = User::factory()->create(['role' => 'student']);

        $ord1 = Order::create([
            'user_id' => $student->id,
            'order_number' => 'ORD-TEST-001',
            'amount' => 1999.00,
            'currency' => 'INR',
            'status' => 'pending',
            'payment_gateway' => 'razorpay',
        ]);
        $ord2 = Order::create([
            'user_id' => $student->id,
            'order_number' => 'ORD-TEST-002',
            'amount' => 2499.00,
            'currency' => 'INR',
            'status' => 'pending',
            'payment_gateway' => 'razorpay',
        ]);

        // 1. Bulk mark paid
        $paidResp = $this->actingAs($admin)->post(route('admin.orders.bulk'), [
            'ids' => [$ord1->id, $ord2->id],
            'action' => 'mark_paid',
        ]);
        $paidResp->assertRedirect();
        $this->assertEquals('paid', $ord1->fresh()->status);
        $this->assertEquals('paid', $ord2->fresh()->status);

        // 2. Bulk mark pending
        $pendResp = $this->actingAs($admin)->post(route('admin.orders.bulk'), [
            'ids' => [$ord1->id],
            'action' => 'mark_pending',
        ]);
        $pendResp->assertRedirect();
        $this->assertEquals('pending', $ord1->fresh()->status);

        // 3. Bulk export
        $exportResp = $this->actingAs($admin)->post(route('admin.orders.bulk'), [
            'ids' => [$ord1->id],
            'action' => 'export',
        ]);
        $exportResp->assertStatus(200);
        $this->assertStringContainsString('text/csv', $exportResp->headers->get('Content-Type'));

        // 4. Bulk delete
        $delResp = $this->actingAs($admin)->post(route('admin.orders.bulk'), [
            'ids' => [$ord1->id, $ord2->id],
            'action' => 'delete',
        ]);
        $delResp->assertRedirect();
        $this->assertDatabaseMissing('orders', ['id' => $ord1->id]);
        $this->assertDatabaseMissing('orders', ['id' => $ord2->id]);
    }

    public function test_admin_can_bulk_activate_deactivate_delete_and_export_coupons(): void
    {
        $admin = $this->createAdmin();

        $cp1 = Coupon::create([
            'code' => 'DISC50',
            'name' => '50 Percent Off',
            'type' => Coupon::TYPE_PERCENT,
            'value' => 50,
            'is_active' => false,
            'created_by' => $admin->id,
        ]);
        $cp2 = Coupon::create([
            'code' => 'FLAT500',
            'name' => 'Flat 500 Off',
            'type' => Coupon::TYPE_FIXED,
            'value' => 500,
            'is_active' => false,
            'created_by' => $admin->id,
        ]);

        // 1. Bulk activate
        $actResp = $this->actingAs($admin)->post(route('admin.coupons.bulk'), [
            'ids' => [$cp1->id, $cp2->id],
            'action' => 'activate',
        ]);
        $actResp->assertRedirect();
        $this->assertTrue((bool) $cp1->fresh()->is_active);
        $this->assertTrue((bool) $cp2->fresh()->is_active);

        // 2. Bulk deactivate
        $deactResp = $this->actingAs($admin)->post(route('admin.coupons.bulk'), [
            'ids' => [$cp1->id],
            'action' => 'deactivate',
        ]);
        $deactResp->assertRedirect();
        $this->assertFalse((bool) $cp1->fresh()->is_active);

        // 3. Bulk export
        $exportResp = $this->actingAs($admin)->post(route('admin.coupons.bulk'), [
            'ids' => [$cp1->id],
            'action' => 'export',
        ]);
        $exportResp->assertStatus(200);
        $this->assertStringContainsString('text/csv', $exportResp->headers->get('Content-Type'));

        // 4. Bulk delete
        $delResp = $this->actingAs($admin)->post(route('admin.coupons.bulk'), [
            'ids' => [$cp1->id, $cp2->id],
            'action' => 'delete',
        ]);
        $delResp->assertRedirect();
        $this->assertDatabaseMissing('coupons', ['id' => $cp1->id]);
        $this->assertDatabaseMissing('coupons', ['id' => $cp2->id]);
    }

    public function test_admin_can_bulk_publish_draft_delete_and_export_notifications(): void
    {
        $admin = $this->createAdmin();

        $n1 = Notification::create([
            'title' => 'Admit Card Alert 2026',
            'slug' => 'admit-card-alert-2026',
            'category' => 'Admit Card',
            'description' => 'Admit cards are now available',
            'is_published' => false,
            'created_by' => $admin->id,
        ]);
        $n2 = Notification::create([
            'title' => 'Exam Syllabus Update',
            'slug' => 'exam-syllabus-update',
            'category' => 'Circular',
            'description' => 'Revised exam pattern',
            'is_published' => false,
            'created_by' => $admin->id,
        ]);

        // 1. Bulk publish
        $pubResp = $this->actingAs($admin)->post(route('admin.notifications.bulk'), [
            'ids' => [$n1->id, $n2->id],
            'action' => 'publish',
        ]);
        $pubResp->assertRedirect();
        $this->assertTrue((bool) $n1->fresh()->is_published);

        // 2. Bulk draft
        $draftResp = $this->actingAs($admin)->post(route('admin.notifications.bulk'), [
            'ids' => [$n1->id],
            'action' => 'draft',
        ]);
        $draftResp->assertRedirect();
        $this->assertFalse((bool) $n1->fresh()->is_published);

        // 3. Bulk export
        $exportResp = $this->actingAs($admin)->post(route('admin.notifications.bulk'), [
            'ids' => [$n1->id],
            'action' => 'export',
        ]);
        $exportResp->assertStatus(200);
        $this->assertStringContainsString('text/csv', $exportResp->headers->get('Content-Type'));

        // 4. Bulk delete
        $delResp = $this->actingAs($admin)->post(route('admin.notifications.bulk'), [
            'ids' => [$n1->id, $n2->id],
            'action' => 'delete',
        ]);
        $delResp->assertRedirect();
        $this->assertDatabaseMissing('notifications', ['id' => $n1->id]);
        $this->assertDatabaseMissing('notifications', ['id' => $n2->id]);
    }

    public function test_bulk_action_validates_input_and_enforces_auth(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_active' => true]);

        // Student unauthorized on questions
        $unauthorized = $this->actingAs($student)->post(route('admin.questions.bulk'), [
            'ids' => [1],
            'action' => 'delete',
        ]);
        $unauthorized->assertStatus(403);

        // Student unauthorized on courses
        $unauthorizedCourse = $this->actingAs($student)->post(route('admin.courses.bulk'), [
            'ids' => [1],
            'action' => 'delete',
        ]);
        $unauthorizedCourse->assertStatus(403);

        $admin = $this->createAdmin();

        // Empty IDs validation
        $emptyIds = $this->actingAs($admin)->post(route('admin.questions.bulk'), [
            'ids' => [],
            'action' => 'delete',
        ]);
        $emptyIds->assertSessionHasErrors('ids');
    }

    public function test_admin_index_pages_render_with_bulk_action_bar_and_checkboxes(): void
    {
        $admin = $this->createAdmin();

        $routes = [
            'admin.questions.index',
            'admin.students.index',
            'admin.courses.index',
            'admin.resources.index',
            'admin.tests.index',
            'admin.orders.index',
            'admin.coupons.index',
            'admin.notifications.index',
        ];

        foreach ($routes as $route) {
            $resp = $this->actingAs($admin)->get(route($route));
            $resp->assertStatus(200);
            $resp->assertSee('bulkActionManager', false);
            $resp->assertSee('toggleSelectAll()', false);
            $resp->assertSee('aria-label="Bulk actions bar"', false);
        }
    }
}


