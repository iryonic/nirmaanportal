<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Extract 11-character YouTube video ID from various URL formats.
     */
    public static function getYouTubeId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/|youtube\.com/shorts/)([^"&?/\s]{11})%i', $url, $match)) {
            return $match[1];
        }

        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        return null;
    }

    /**
     * Get dynamic featured YouTube video configuration with defaults.
     */
    public static function getFeaturedVideo(): array
    {
        $enabled = filter_var(static::get('home_video_enabled', '1'), FILTER_VALIDATE_BOOLEAN);
        $url = static::get('home_video_url', 'https://youtu.be/3t5M10gBVCc');
        $videoId = static::getYouTubeId($url) ?? '3t5M10gBVCc';

        return [
            'enabled' => $enabled,
            'url' => $url,
            'video_id' => $videoId,
            'embed_url' => "https://www.youtube-nocookie.com/embed/{$videoId}?autoplay=1&rel=0&modestbranding=1",
            'badge' => static::get('home_video_badge', 'INSIDE NIRMAAN ACADEMY'),
            'title' => static::get('home_video_title', 'Experience Training, Drills & Classroom Life at Nirmaan'),
            'subtitle' => static::get('home_video_subtitle', 'Get an exclusive inside look at our smart digital classrooms, physical obstacle grounds, and SSB interview chambers guided by former defence officers.'),
            'highlight_1' => static::get('home_video_highlight_1', 'Ex-Officers & SSB Assessors Mentorship'),
            'highlight_2' => static::get('home_video_highlight_2', 'Complete Written & Physical Ground Prep'),
            'highlight_3' => static::get('home_video_highlight_3', 'Digital Live & Recorded HD Lectures'),
            'cta_text' => static::get('home_video_cta_text', 'Explore All Courses'),
            'cta_url' => static::get('home_video_cta_url', ''),
            'thumbnail' => static::get('home_video_thumbnail') ?: "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg",
        ];
    }

    /**
     * Get comprehensive dynamic payment gateways, direct UPI, bank transfer, and methods configuration.
     */
    public static function getPaymentConfig(): array
    {
        $qrPath = static::get('upi_qr_image');
        $qrImageUrl = ($qrPath && Storage::disk('public')->exists($qrPath))
            ? Storage::disk('public')->url($qrPath)
            : null;

        $razorpayMode = static::get('razorpay_mode', config('services.razorpay.mode', 'test'));
        $razorpayKeyId = $razorpayMode === 'live' 
            ? static::get('razorpay_live_key_id', '') 
            : static::get('razorpay_key_id', config('services.razorpay.key_id', ''));

        return [
            'razorpay' => [
                'enabled' => filter_var(static::get('razorpay_enabled', config('services.razorpay.enabled', true)), FILTER_VALIDATE_BOOLEAN),
                'mode' => $razorpayMode,
                'key_id' => $razorpayKeyId,
                'merchant_name' => static::get('razorpay_merchant_name', 'Nirmaan Academy'),
                'theme_color' => static::get('razorpay_theme_color', '#172554'),
                'fallback_simulation' => filter_var(static::get('razorpay_fallback_simulation', config('services.razorpay.fallback_simulation', true)), FILTER_VALIDATE_BOOLEAN),
            ],
            'upi' => [
                'enabled' => filter_var(static::get('upi_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
                'vpa' => static::get('upi_vpa', 'nirmaanacademy@icici'),
                'merchant_name' => static::get('upi_merchant_name', 'Nirmaan Defence Academy'),
                'qr_image_url' => $qrImageUrl,
                'instructions' => static::get('upi_instructions', 'Scan QR code with any UPI app (GPay, PhonePe, Paytm, BHIM) or pay directly to the verified VPA.'),
            ],
            'bank' => [
                'enabled' => filter_var(static::get('bank_transfer_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
                'bank_name' => static::get('bank_name', 'State Bank of India'),
                'account_name' => static::get('bank_account_name', 'Nirmaan Academy Educational Foundation'),
                'account_number' => static::get('bank_account_number', '4092101004521'),
                'ifsc_code' => static::get('bank_ifsc_code', 'SBIN0001234'),
                'branch' => static::get('bank_branch', 'Defence Cantt Branch, Jaipur'),
                'instructions' => static::get('bank_instructions', 'Transfer via IMPS/NEFT/RTGS and submit your 12-digit UTR transaction reference.'),
            ],
            'card' => [
                'enabled' => filter_var(static::get('method_card_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
            ],
            'netbanking' => [
                'enabled' => filter_var(static::get('method_netbanking_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
            ],
            'emi' => [
                'enabled' => filter_var(static::get('method_emi_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
            ],
            'offline' => [
                'enabled' => filter_var(static::get('method_offline_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
                'campus_address' => static::get('offline_campus_address', 'Nirmaan Defence Academy, Officer Training Campus, Plot 42, Civil Lines'),
                'contact_phone' => static::get('offline_contact_phone', '+91 98765 43210'),
                'instructions' => static::get('offline_instructions', 'Visit our academy admissions desk during working hours (9 AM - 6 PM) to finalize registration in cash or DD.'),
            ],
            'methods' => [
                'card' => filter_var(static::get('method_card_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
                'netbanking' => filter_var(static::get('method_netbanking_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
                'emi' => filter_var(static::get('method_emi_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
                'offline' => filter_var(static::get('method_offline_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
            ],
        ];
    }

    /**
     * Get dynamic configuration for the About Us page.
     */
    public static function getAboutConfig(): array
    {
        return [
            'hero_badge' => static::get('about_hero_badge', 'DEDICATED TO NATIONAL DEFENCE'),
            'hero_title' => static::get('about_hero_title', 'Shaping the Guardians of Tomorrow'),
            'hero_subtitle' => static::get('about_hero_subtitle', 'Nirmaan was founded with one resolute mission: to provide world-class, structured, and affordable guidance to every passionate young Indian striving to serve in the armed and paramilitary forces.'),
            'director_name' => static::get('about_director_name', 'Col. R. K. Verma (Retd.)'),
            'director_role' => static::get('about_director_role', 'Director & Chief Academic Mentor'),
            'director_bio' => static::get('about_director_bio', 'Decorated Indian Army veteran with over 28 years of active command, staff appointments, and extensive tenure at Services Selection Board (SSB) evaluation boards.'),
            'director_message' => static::get('about_director_message', 'At Nirmaan, we do not merely teach for a competitive exam; we build the character, fortitude, and officer-like qualities (OLQs) needed to lead soldiers in the face of adversity. Our cadets are trained to think decisively, act with honor, and serve with relentless commitment.'),
            'mission_statement' => static::get('about_mission_statement', 'To democratize premier pre-commissioning defense coaching across India by blending the disciplined pedigree of retired armed forces officers with cutting-edge digital test simulations and physical drill guidance.'),
            'vision_statement' => static::get('about_vision_statement', 'To stand as India’s highest-rated pre-service training institution, enabling over 50,000 patriotic youths annually to achieve uniform commission in the Army, Navy, Air Force, and Paramilitary forces.'),
            'stats_selections' => static::get('about_stats_selections', '12,500+'),
            'stats_selections_label' => static::get('about_stats_selections_label', 'Cadet Recommendations'),
            'stats_faculty' => static::get('about_stats_faculty', '45+'),
            'stats_faculty_label' => static::get('about_stats_faculty_label', 'Veteran Officers & Mentors'),
            'stats_centers' => static::get('about_stats_centers', '18+'),
            'stats_centers_label' => static::get('about_stats_centers_label', 'Drill & Obstacle Grounds'),
            'stats_success_rate' => static::get('about_stats_success_rate', '94.2%'),
            'stats_success_rate_label' => static::get('about_stats_success_rate_label', 'Written Clear Rate'),
            'pillar_1_title' => static::get('about_pillar_1_title', 'Rigorous Academic Caliber'),
            'pillar_1_desc' => static::get('about_pillar_1_desc', 'Our syllabus blueprints strictly mirror the latest UPSC NDA/CDS, AFCAT, and Agniveer testing matrices, eliminating fluff and focusing on high-probability concepts.'),
            'pillar_2_title' => static::get('about_pillar_2_title', 'Veteran & Ex-SSB Mentorship'),
            'pillar_2_desc' => static::get('about_pillar_2_desc', 'Classroom lectures, psychological profiling, and GTO tasks are supervised directly by retired colonels, wing commanders, and psychologists.'),
            'pillar_3_title' => static::get('about_pillar_3_title', 'Real-Time CBT Engine & Drills'),
            'pillar_3_desc' => static::get('about_pillar_3_desc', 'Sub-second digital test environment with negative marking audits, topic diagnostic heatmaps, and national benchmarking.'),
            'pillar_4_title' => static::get('about_pillar_4_title', 'Physical Fitness & Medical Protocol'),
            'pillar_4_desc' => static::get('about_pillar_4_desc', 'Comprehensive running, obstacle clearing, and medical screening advice ensuring cadets clear official military medical boards effortlessly.'),
            'custom_content' => static::get('about_custom_content', ''),
        ];
    }

    /**
     * Get dynamic configuration for the Contact page.
     */
    public static function getContactConfig(): array
    {
        return [
            'hero_badge' => static::get('contact_hero_badge', 'WE ARE HERE TO HELP YOU'),
            'hero_title' => static::get('contact_hero_title', 'Get in Touch with Nirmaan Academy'),
            'hero_subtitle' => static::get('contact_hero_subtitle', 'Whether you have questions regarding upcoming batches, CBT mock test subscriptions, or need guidance on SSB interview preparation, our academic counsellors are available 6 days a week.'),
            'helpline_phone' => static::get('contact_phone', '+91 98765 43210'),
            'admission_phone' => static::get('contact_admission_phone', '+91 90158 51387'),
            'whatsapp_phone' => static::get('contact_whatsapp_phone', '+91 98765 43210'),
            'support_email' => static::get('contact_email', 'support@nirmaan.com'),
            'admissions_email' => static::get('contact_admissions_email', 'admissions@nirmaan.com'),
            'headquarters_address' => static::get('contact_address', 'Sector 12, Defence Colony, New Delhi - 110024, India'),
            'working_hours' => static::get('contact_working_hours', 'Monday to Saturday: 08:00 AM – 08:00 PM IST | Sunday: 09:00 AM – 02:00 PM IST'),
            'map_embed_url' => static::get('contact_map_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3503.71427181607!2d77.2289668!3d28.5783359!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce24317b70c7f%3A0xc6cb5a646cbbff94!2sDefence%20Colony%2C%20New%20Delhi%2C%20Delhi%20110024!5e0!3m2!1sen!2sin!4v1700000000000'),
        ];
    }

    /**
     * Get dynamic configuration for the Privacy Policy page.
     */
    public static function getPrivacyConfig(): array
    {
        return [
            'effective_date' => static::get('privacy_effective_date', 'November 1, 2026'),
            'dpo_name' => static::get('privacy_dpo_name', 'Major Arvind Joshi (Retd.)'),
            'dpo_email' => static::get('privacy_dpo_email', 'privacy@nirmaan.com'),
            'dpo_phone' => static::get('privacy_dpo_phone', '+91 11 2468 1357'),
            'dpo_address' => static::get('privacy_dpo_address', 'Data Protection Officer, Nirmaan Academy HQ, Sector 12, Defence Colony, New Delhi - 110024'),
            'custom_content' => static::get('privacy_custom_content', ''),
        ];
    }

    /**
     * Get dynamic configuration for the Terms of Service page.
     */
    public static function getTermsConfig(): array
    {
        return [
            'effective_date' => static::get('terms_effective_date', 'November 1, 2026'),
            'jurisdiction' => static::get('terms_jurisdiction', 'Courts of New Delhi, India'),
            'refund_period_days' => static::get('terms_refund_period_days', '7'),
            'contact_email' => static::get('terms_contact_email', 'legal@nirmaan.com'),
            'custom_content' => static::get('terms_custom_content', ''),
        ];
    }

    /**
     * Get dynamic configuration for the Help & Grievances page.
     */
    public static function getGrievanceConfig(): array
    {
        return [
            'officer_name' => static::get('grievance_officer_name', 'Wg Cdr S. P. Saxena (Retd.)'),
            'officer_designation' => static::get('grievance_officer_designation', 'Chief Grievance Redressal Officer'),
            'officer_email' => static::get('grievance_officer_email', 'grievances@nirmaan.com'),
            'officer_phone' => static::get('grievance_officer_phone', '+91 11 2468 9900'),
            'officer_address' => static::get('grievance_officer_address', 'Grievance Redressal Cell, 2nd Floor, Nirmaan Academy Headquarters, Sector 12, Defence Colony, New Delhi - 110024'),
            'officer_timings' => static::get('grievance_officer_timings', 'Monday to Friday, 10:00 AM – 05:00 PM IST'),
            'sla_level_1' => static::get('grievance_sla_level_1', 'Within 24 business hours (Desk Level / Course & CBT Queries)'),
            'sla_level_2' => static::get('grievance_sla_level_2', 'Within 48 business hours (Escalated Review by Grievance Officer)'),
            'sla_level_3' => static::get('grievance_sla_level_3', 'Within 5 business days (Final Appellate Review & Director Hearing)'),
            'custom_content' => static::get('grievance_custom_content', ''),
        ];
    }
}
