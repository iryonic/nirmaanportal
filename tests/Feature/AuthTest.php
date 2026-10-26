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

    public function test_student_can_login_using_phone_number(): void
    {
        $student = User::factory()->create([
            'email' => 'phonecadet@test.com',
            'phone' => '+91 9876543210',
            'password' => bcrypt('password123'),
            'role' => 'student',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => '9876543210',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticatedAs($student);
    }

    public function test_deactivated_student_cannot_login(): void
    {
        $student = User::factory()->create([
            'email' => 'suspended@test.com',
            'password' => bcrypt('password123'),
            'role' => 'student',
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'suspended@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_rate_limiting_locks_out_after_5_failed_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'brute@test.com',
                'password' => 'wrongpass',
            ]);
        }

        // 6th attempt should hit rate limiter lockout
        $response = $this->post('/login', [
            'email' => 'brute@test.com',
            'password' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors('email');
        $errors = session('errors')->get('email');
        $this->assertTrue(str_contains($errors[0], 'Too many failed sign-in attempts'));
    }

    public function test_password_recovery_and_reset_flow(): void
    {
        $user = User::factory()->create([
            'email' => 'resetme@test.com',
            'password' => bcrypt('oldpassword123'),
        ]);

        // Request reset
        $reqResponse = $this->post('/forgot-password', [
            'email' => 'resetme@test.com',
        ]);
        $reqResponse->assertSessionHas('status');

        $token = session('simulation_token');
        $this->assertNotNull($token);

        // Reset password
        $resetResponse = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'resetme@test.com',
            'password' => 'newstrongpass123',
            'password_confirmation' => 'newstrongpass123',
        ]);

        $resetResponse->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_custom_404_page_renders_cleanly(): void
    {
        $response = $this->get('/non-existent-defence-route-xyz');
        $response->assertStatus(404);
        $response->assertSee('Mission Objective Not Found');
        $response->assertSee('HTTP 404');
    }

    public function test_custom_403_page_renders_cleanly(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
        ]);

        $response = $this->actingAs($student)->get(route('admin.dashboard'));
        $response->assertStatus(403);
        $response->assertSee('Security Clearance Restricted');
        $response->assertSee('HTTP 403');
    }
}
