<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Welcome to Nirmaan');
    }

    public function test_user_can_view_register_page(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Create Student Account');
    }

    public function test_student_can_register_successfully(): void
    {
        $response = $this->post('/register', [
            'name' => 'Kavita Singh',
            'email' => 'kavita@example.com',
            'phone' => '9876543210',
            'target_exam' => 'NDA',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'kavita@example.com',
            'role' => 'student',
            'target_exam' => 'NDA',
        ]);
        $this->assertAuthenticated();
    }

    public function test_student_is_redirected_to_student_dashboard_on_login(): void
    {
        $student = User::factory()->create([
            'email' => 'student@test.com',
            'password' => bcrypt('password123'),
            'role' => 'student',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'student@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticatedAs($student);
    }

    public function test_admin_is_redirected_to_admin_dashboard_on_login(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_non_admin_cannot_access_admin_portal(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $response = $this->actingAs($student)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
