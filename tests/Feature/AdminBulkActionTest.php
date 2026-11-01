<?php

namespace Tests\Feature;

use App\Models\Question;
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

    public function test_bulk_action_validates_input_and_enforces_auth(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_active' => true]);

        // Student unauthorized
        $unauthorized = $this->actingAs($student)->post(route('admin.questions.bulk'), [
            'ids' => [1],
            'action' => 'delete',
        ]);
        $unauthorized->assertStatus(403);

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

        // 1. Question bank index
        $qResponse = $this->actingAs($admin)->get(route('admin.questions.index'));
        $qResponse->assertStatus(200);
        $qResponse->assertSee('bulkActionManager', false);
        $qResponse->assertSee('toggleSelectAll()', false);
        $qResponse->assertSee('aria-label="Bulk actions bar"', false);

        // 2. Student index
        $sResponse = $this->actingAs($admin)->get(route('admin.students.index'));
        $sResponse->assertStatus(200);
        $sResponse->assertSee('bulkActionManager', false);
        $sResponse->assertSee('toggleSelectAll()', false);
        $sResponse->assertSee('aria-label="Bulk actions bar"', false);
    }
}

