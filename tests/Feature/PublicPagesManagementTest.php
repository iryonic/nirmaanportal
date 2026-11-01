<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_loads_and_displays_dynamic_content(): void
    {
        SiteSetting::set('about_hero_title', 'Shaping True Indian Defence Cadets');
        SiteSetting::set('about_director_name', 'Brig. Vikram Rawat (Retd.)');

        $response = $this->get(route('about'));

        $response->assertStatus(200);
        $response->assertSeeText('Shaping True Indian Defence Cadets');
        $response->assertSeeText('Brig. Vikram Rawat (Retd.)');
        $response->assertSee('Rigorous Academic Caliber');
        $response->assertSee('Veteran & Ex-SSB Mentorship');
    }

    public function test_contact_page_loads_and_accepts_inquiries(): void
    {
        SiteSetting::set('contact_phone', '+91 99999 88888');

        $response = $this->get(route('contact'));
        $response->assertStatus(200);
        $response->assertSee('+91 99999 88888');

        $postResponse = $this->post(route('contact.submit'), [
            'name' => 'Karan Thapar',
            'email' => 'karan@gmail.com',
            'phone' => '+91 98765 00000',
            'inquiry_type' => 'Admissions & Batches',
            'subject' => 'NDA Crash Course details',
            'message' => 'Please provide information regarding offline physical training grounds.',
        ]);

        $postResponse->assertSessionHas('success');
        $this->assertDatabaseHas('contact_inquiries', [
            'name' => 'Karan Thapar',
            'email' => 'karan@gmail.com',
            'type' => 'inquiry',
            'status' => 'new',
        ]);

        $inquiry = ContactInquiry::where('email', 'karan@gmail.com')->first();
        $this->assertNotNull($inquiry->ticket_number);
        $this->assertStringStartsWith('INQ-', $inquiry->ticket_number);
    }

    public function test_privacy_policy_page_loads_with_dpdp_compliance(): void
    {
        SiteSetting::set('privacy_dpo_name', 'Col. S. N. Bannerjee (Retd.)');

        $response = $this->get(route('privacy'));
        $response->assertStatus(200);
        $response->assertSeeText('Privacy Policy & Cadet Data Protection');
        $response->assertSee('DPDP Act 2023 Compliant');
        $response->assertSeeText('Col. S. N. Bannerjee (Retd.)');

        // Test alias route
        $aliasResponse = $this->get(route('privacy.alias'));
        $aliasResponse->assertStatus(200);
    }

    public function test_terms_of_service_page_loads_with_legal_clauses(): void
    {
        SiteSetting::set('terms_jurisdiction', 'High Court of Delhi, New Delhi');
        SiteSetting::set('terms_refund_period_days', '14');

        $response = $this->get(route('terms'));
        $response->assertStatus(200);
        $response->assertSeeText('Terms of Service & Code of Discipline');
        $response->assertSeeText('High Court of Delhi, New Delhi');
        $response->assertSee('14-Day Admission Refund Window');

        // Test alias route
        $aliasResponse = $this->get(route('terms.alias'));
        $aliasResponse->assertStatus(200);
    }

    public function test_help_and_grievances_page_loads_and_submits_docket(): void
    {
        SiteSetting::set('grievance_officer_name', 'Air Commodore R. Sen (Retd.)');

        $response = $this->get(route('grievances'));
        $response->assertStatus(200);
        $response->assertSeeText('Cadet Help & Grievance Redressal Cell');
        $response->assertSeeText('Air Commodore R. Sen (Retd.)');
        $response->assertSee('LEVEL 1 — DESK RESOLUTION');
        $response->assertSee('LEVEL 2 — GRIEVANCE OFFICER');

        // Test alias route
        $aliasResponse = $this->get(route('grievances.alias'));
        $aliasResponse->assertStatus(200);

        // Submit grievance
        $postResponse = $this->post(route('grievances.submit'), [
            'name' => 'Rahul Sharma',
            'email' => 'rahul@gmail.com',
            'phone' => '+91 91234 99999',
            'cadet_roll' => 'CADET-2026-88',
            'category' => 'Payment & Fee Refund Dispute',
            'subject' => 'Double deduction on Razorpay during checkout',
            'message' => 'Amount was debited twice but invoice shows single attempt. Please review.',
        ]);

        $postResponse->assertSessionHas('success');
        $this->assertDatabaseHas('contact_inquiries', [
            'name' => 'Rahul Sharma',
            'email' => 'rahul@gmail.com',
            'type' => 'grievance',
            'status' => 'new',
        ]);

        $grievance = ContactInquiry::where('email', 'rahul@gmail.com')->first();
        $this->assertNotNull($grievance->ticket_number);
        $this->assertStringStartsWith('GRV-', $grievance->ticket_number);
    }

    public function test_admin_can_update_content_settings(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.content.settings'), [
            'about_hero_title' => 'Custom Tested Title',
            'contact_admission_phone' => '+91 88888 77777',
            'terms_refund_period_days' => '10',
            'grievance_officer_name' => 'Lt. Gen. K. P. Singh (Retd.)',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('Custom Tested Title', SiteSetting::get('about_hero_title'));
        $this->assertEquals('+91 88888 77777', SiteSetting::get('contact_admission_phone'));
        $this->assertEquals('10', SiteSetting::get('terms_refund_period_days'));
        $this->assertEquals('Lt. Gen. K. P. Singh (Retd.)', SiteSetting::get('grievance_officer_name'));
    }
}
