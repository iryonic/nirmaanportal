<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NotificationManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@nirmaan.test'],
            [
                'name' => 'Admin Officer',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $this->student = User::firstOrCreate(
            ['email' => 'student_test@nirmaan.test'],
            [
                'name' => 'Cadet Student',
                'password' => bcrypt('password123'),
                'role' => 'student',
                'is_active' => true,
            ]
        );
    }

    public function test_public_homepage_displays_published_notifications_with_new_badge()
    {
        // 1. Notice within 72 hours (NEW badge)
        $newNotice = Notification::create([
            'title' => 'Test Fresh Notice 123',
            'category' => 'Indian Army',
            'is_published' => true,
            'published_at' => Carbon::now()->subHours(12),
        ]);

        // 2. Older notice (> 72 hours)
        $oldNotice = Notification::create([
            'title' => 'Test Older Notice 456',
            'category' => 'Indian Navy',
            'is_published' => true,
            'published_at' => Carbon::now()->subHours(80),
        ]);

        // 3. Draft notice (should NOT appear on public homepage)
        $draftNotice = Notification::create([
            'title' => 'Test Hidden Draft Notice 789',
            'category' => 'NDA / CDS',
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Test Fresh Notice 123');
        $response->assertSee('Test Older Notice 456');
        $response->assertDontSee('Test Hidden Draft Notice 789');

        $this->assertTrue($newNotice->fresh()->is_new);
        $this->assertFalse($oldNotice->fresh()->is_new);
        $this->assertFalse($draftNotice->fresh()->is_new);
    }

    public function test_72_hour_countdown_logic_and_expiration()
    {
        $notice = Notification::create([
            'title' => 'Countdown Evaluation Notice',
            'category' => 'Indian Air Force',
            'is_published' => true,
            'published_at' => Carbon::now()->subHours(71),
        ]);

        // At 71 hours ago: still within 72h window -> true
        $this->assertTrue($notice->isNew());

        // Set published_at to 73 hours ago: expired -> false
        $notice->update(['published_at' => Carbon::now()->subHours(73)]);
        $this->assertFalse($notice->fresh()->isNew());
    }

    public function test_unauthenticated_or_student_cannot_access_admin_notifications()
    {
        $this->get(route('admin.notifications.index'))
            ->assertRedirect(route('login'));

        $this->actingAs($this->student)
            ->get(route('admin.notifications.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_notification_with_attachment_and_draft_status()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('rally_circular.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.notifications.store'), [
            'title' => 'Army Rally Circular Document',
            'category' => 'Indian Army',
            'description' => 'Important details for army rally aspirants.',
            'attachment' => $file,
            'is_published' => '1',
            'is_pinned' => '1',
        ]);

        $response->assertRedirect(route('admin.notifications.index'));

        $this->assertDatabaseHas('notifications', [
            'title' => 'Army Rally Circular Document',
            'category' => 'Indian Army',
            'is_published' => true,
            'is_pinned' => true,
            'attachment_name' => 'rally_circular.pdf',
        ]);

        $created = Notification::where('title', 'Army Rally Circular Document')->first();
        $this->assertNotNull($created->attachment_path);
        Storage::disk('public')->assertExists($created->attachment_path);
        $this->assertTrue($created->is_new);
    }

    public function test_editing_notification_does_not_reset_published_at_countdown()
    {
        $originalPublishedAt = Carbon::now()->subHours(40);

        $notice = Notification::create([
            'title' => 'Original Notice Title',
            'category' => 'Indian Army',
            'is_published' => true,
            'published_at' => $originalPublishedAt,
        ]);

        $this->actingAs($this->admin)->put(route('admin.notifications.update', $notice->id), [
            'title' => 'Updated Notice Title (Edited)',
            'category' => 'Indian Army',
            'is_published' => '1',
        ]);

        $fresh = $notice->fresh();
        $this->assertEquals('Updated Notice Title (Edited)', $fresh->title);
        // Ensure published_at is NOT overwritten by now()!
        $this->assertEquals(
            $originalPublishedAt->format('Y-m-d H:i:s'),
            $fresh->published_at->format('Y-m-d H:i:s')
        );
    }

    public function test_toggling_unpublish_and_republish_restarts_72_hour_countdown()
    {
        $oldDate = Carbon::now()->subDays(5); // 120 hours ago (expired)

        $notice = Notification::create([
            'title' => 'Republish Test Notice',
            'category' => 'NDA / CDS',
            'is_published' => true,
            'published_at' => $oldDate,
        ]);

        $this->assertFalse($notice->fresh()->is_new);

        // 1. Unpublish
        $this->actingAs($this->admin)->post(route('admin.notifications.toggle', $notice->id));
        $this->assertFalse($notice->fresh()->is_published);
        $this->assertNull($notice->fresh()->published_at);

        // 2. Republish -> Restarts countdown from current timestamp!
        $this->actingAs($this->admin)->post(route('admin.notifications.toggle', $notice->id));
        $fresh = $notice->fresh();
        $this->assertTrue($fresh->is_published);
        $this->assertNotNull($fresh->published_at);
        $this->assertTrue($fresh->is_new);
    }

    public function test_admin_can_delete_notification()
    {
        $notice = Notification::create([
            'title' => 'Temporary Notice to Delete',
            'category' => 'General',
            'is_published' => false,
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.notifications.destroy', $notice->id))
            ->assertRedirect(route('admin.notifications.index'));

        $this->assertDatabaseMissing('notifications', ['id' => $notice->id]);
    }

    public function test_announcements_page_search_and_category_filter()
    {
        Notification::create([
            'title' => 'UniqueSpecialAviationNoticeXYZ',
            'category' => 'Indian Air Force',
            'is_published' => true,
            'published_at' => Carbon::now()->subHour(),
        ]);

        $response = $this->get(route('announcements', ['q' => 'UniqueSpecialAviationNoticeXYZ']));
        $response->assertStatus(200);
        $response->assertSee('UniqueSpecialAviationNoticeXYZ');

        $responseEmpty = $this->get(route('announcements', ['q' => 'NonExistentStringRandom999']));
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertDontSee('UniqueSpecialAviationNoticeXYZ');
    }
}
