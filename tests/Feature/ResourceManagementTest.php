<?php

namespace Tests\Feature;

use App\Models\ExamCategory;
use App\Models\StudyMaterial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResourceManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;
    protected ExamCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_resource@nirmaan.test'],
            [
                'name' => 'HQ Admin Officer',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $this->student = User::firstOrCreate(
            ['email' => 'cadet_student@nirmaan.test'],
            [
                'name' => 'Cadet Aspirant',
                'password' => bcrypt('password123'),
                'role' => 'student',
                'is_active' => true,
            ]
        );

        $this->category = ExamCategory::firstOrCreate(
            ['slug' => 'army-test-wing'],
            [
                'name' => 'Indian Army Wing',
                'description' => 'Official Army examinations',
                'is_active' => true,
            ]
        );
    }

    public function test_guest_and_student_cannot_access_admin_resource_management()
    {
        // Guests redirected to login
        $response = $this->get(route('admin.resources.index'));
        $response->assertRedirect(route('login'));

        // Students forbidden / redirected
        $response = $this->actingAs($this->student)->get(route('admin.resources.index'));
        $this->assertTrue(in_array($response->status(), [302, 403]));
    }

    public function test_admin_can_view_resource_management_index_with_statistics()
    {
        StudyMaterial::create([
            'title' => 'Agniveer GD Syllabus 2027',
            'slug' => 'agniveer-gd-syllabus-2027',
            'type' => 'syllabus',
            'exam_category_id' => $this->category->id,
            'is_published' => true,
            'downloads_count' => 150,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.resources.index'));
        $response->assertStatus(200);
        $response->assertSee('Study Resources &amp; Downloads Management', false);
        $response->assertSee('Agniveer GD Syllabus 2027');
        $response->assertSee('150');
    }

    public function test_admin_can_upload_new_study_resource_with_file()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('army_clerk_notes.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.resources.store'), [
            'title' => 'Indian Army Clerk Complete Mathematics Guide',
            'exam_category_id' => $this->category->id,
            'type' => 'pdf',
            'description' => 'Formula sheet and high-yield question bank for Army Clerk CBT.',
            'file' => $file,
            'is_free' => '1',
            'is_published' => '1',
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin.resources.index'));
        $response->assertSessionHas('success');

        $material = StudyMaterial::where('title', 'Indian Army Clerk Complete Mathematics Guide')->first();
        $this->assertNotNull($material);
        $this->assertEquals('pdf', $material->type);
        $this->assertTrue($material->is_featured);
        $this->assertTrue($material->is_published);
        $this->assertEquals('army_clerk_notes.pdf', $material->file_name);
        $this->assertNotNull($material->file_path);

        Storage::disk('public')->assertExists($material->file_path);
    }

    public function test_admin_can_create_resource_with_external_cloud_url()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.resources.store'), [
            'title' => 'NDA 2026 Previous 10 Years Solved Papers Drive',
            'exam_category_id' => $this->category->id,
            'type' => 'previous_paper',
            'description' => 'Complete compilation of UPSC NDA I & II solved papers.',
            'external_url' => 'https://drive.google.com/file/d/test-nda-papers',
            'manual_file_size' => '45 MB',
            'is_free' => '1',
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('admin.resources.index'));
        
        $material = StudyMaterial::where('slug', 'nda-2026-previous-10-years-solved-papers-drive')->first();
        $this->assertNotNull($material);
        $this->assertEquals('https://drive.google.com/file/d/test-nda-papers', $material->external_url);
        $this->assertEquals('45 MB', $material->file_size);
    }

    public function test_admin_can_update_resource_and_replace_attachment()
    {
        Storage::fake('public');

        $oldFile = UploadedFile::fake()->create('old_guide.pdf', 500, 'application/pdf');
        $oldPath = $oldFile->store('resources', 'public');

        $material = StudyMaterial::create([
            'title' => 'Navy SSR Revision Sheet 2026',
            'slug' => 'navy-ssr-revision-sheet-2026',
            'type' => 'pdf',
            'file_path' => $oldPath,
            'file_name' => 'old_guide.pdf',
            'file_size' => '500 KB',
            'is_published' => true,
        ]);

        Storage::disk('public')->assertExists($oldPath);

        $newFile = UploadedFile::fake()->create('updated_navy_guide.pdf', 1200, 'application/pdf');

        $response = $this->actingAs($this->admin)->put(route('admin.resources.update', $material->id), [
            'title' => 'Navy SSR/MR Complete Revision Guide 2027',
            'type' => 'formula_sheet',
            'description' => 'Revised syllabus updated version.',
            'file' => $newFile,
            'is_published' => '1',
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin.resources.index'));

        // Old file must be deleted from storage
        Storage::disk('public')->assertMissing($oldPath);

        $material->refresh();
        $this->assertEquals('Navy SSR/MR Complete Revision Guide 2027', $material->title);
        $this->assertEquals('formula_sheet', $material->type);
        $this->assertTrue($material->is_featured);
        $this->assertEquals('updated_navy_guide.pdf', $material->file_name);
        Storage::disk('public')->assertExists($material->file_path);
    }

    public function test_admin_can_toggle_published_and_featured_status()
    {
        $material = StudyMaterial::create([
            'title' => 'AFCAT Model Paper 2027',
            'slug' => 'afcat-model-paper-2027',
            'type' => 'previous_paper',
            'is_published' => true,
            'is_featured' => false,
        ]);

        // Toggle to draft
        $response = $this->actingAs($this->admin)->post(route('admin.resources.toggle', $material->id), [
            'field' => 'is_published',
        ]);
        $response->assertSessionHas('success');
        $this->assertFalse($material->fresh()->is_published);

        // Toggle featured
        $response = $this->actingAs($this->admin)->post(route('admin.resources.toggle', $material->id), [
            'field' => 'is_featured',
        ]);
        $response->assertSessionHas('success');
        $this->assertTrue($material->fresh()->is_featured);
    }

    public function test_admin_can_delete_resource_and_cleaned_from_storage()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('to_delete.pdf', 200, 'application/pdf');
        $path = $file->store('resources', 'public');

        $material = StudyMaterial::create([
            'title' => 'Temporary Paper',
            'slug' => 'temporary-paper',
            'type' => 'syllabus',
            'file_path' => $path,
            'is_published' => true,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->delete(route('admin.resources.destroy', $material->id));
        $response->assertRedirect(route('admin.resources.index'));

        $this->assertDatabaseMissing('study_materials', ['id' => $material->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_public_resources_page_filters_drafts_and_allows_search()
    {
        // Published resource
        StudyMaterial::create([
            'title' => 'Indian Coast Guard Navik DB Complete Syllabus',
            'slug' => 'icg-navik-db-syllabus',
            'type' => 'syllabus',
            'is_published' => true,
        ]);

        // Draft resource
        StudyMaterial::create([
            'title' => 'Unpublished Internal Notes 999',
            'slug' => 'unpublished-notes-999',
            'type' => 'pdf',
            'is_published' => false,
        ]);

        $response = $this->get(route('resources.index'));
        $response->assertStatus(200);
        $response->assertSee('Indian Coast Guard Navik DB Complete Syllabus');
        $response->assertDontSee('Unpublished Internal Notes 999');

        // Search test
        $searchResponse = $this->get(route('resources.index', ['q' => 'Coast Guard']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Indian Coast Guard Navik DB Complete Syllabus');
    }

    public function test_public_download_serves_real_file_and_increments_count()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('real_defence_notes.pdf', 400, 'application/pdf');
        $path = $file->store('resources', 'public');

        $material = StudyMaterial::create([
            'title' => 'Official Current Affairs Capsule',
            'slug' => 'official-current-affairs-capsule',
            'type' => 'current_affairs',
            'file_path' => $path,
            'file_name' => 'real_defence_notes.pdf',
            'is_published' => true,
            'downloads_count' => 5,
        ]);

        $response = $this->get(route('resources.download', $material->id));
        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=real_defence_notes.pdf');

        $this->assertEquals(6, $material->fresh()->downloads_count);
    }
}
