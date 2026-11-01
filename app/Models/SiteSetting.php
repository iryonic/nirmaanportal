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
        $url = static::get('home_video_url', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        $videoId = static::getYouTubeId($url) ?? 'dQw4w9WgXcQ';

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

        return [
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
        ];
    }
}
