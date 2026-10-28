<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeaturedVideoManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_extracts_youtube_id_from_various_url_formats(): void
    {
        $this->assertEquals('dQw4w9WgXcQ', SiteSetting::getYouTubeId('https://www.youtube.com/watch?v=dQw4w9WgXcQ'));
        $this->assertEquals('dQw4w9WgXcQ', SiteSetting::getYouTubeId('https://youtu.be/dQw4w9WgXcQ'));
        $this->assertEquals('dQw4w9WgXcQ', SiteSetting::getYouTubeId('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ'));
        $this->assertEquals('dQw4w9WgXcQ', SiteSetting::getYouTubeId('https://www.youtube.com/shorts/dQw4w9WgXcQ'));
        $this->assertEquals('dQw4w9WgXcQ', SiteSetting::getYouTubeId('dQw4w9WgXcQ'));
        $this->assertNull(SiteSetting::getYouTubeId('invalid-url-or-text'));
    }

    public function test_homepage_renders_featured_video_section_when_enabled(): void
    {
        SiteSetting::set('home_video_enabled', '1');
        SiteSetting::set('home_video_url', 'https://www.youtube.com/watch?v=ScMzIvxBSi4');
        SiteSetting::set('home_video_title', 'Cadet Orientation Tour');

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('featured-video', false);
        $response->assertSee('Cadet Orientation Tour');
        $response->assertSee('ScMzIvxBSi4');
    }

    public function test_homepage_hides_featured_video_section_when_disabled(): void
    {
        SiteSetting::set('home_video_enabled', '0');
        SiteSetting::set('home_video_title', 'Should Not Appear');

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertDontSee('Should Not Appear');
    }

    public function test_admin_can_update_featured_video_settings(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.content.video'), [
            'home_video_enabled' => '1',
            'home_video_url' => 'https://youtu.be/L_LUpnjgPso',
            'home_video_badge' => 'SPECIAL DEMO',
            'home_video_title' => 'SSB Interview Demonstration',
            'home_video_subtitle' => 'Detailed psychological tests and ground obstacle walkthrough.',
            'home_video_highlight_1' => 'GTO Ground Test Analysis',
            'home_video_highlight_2' => 'Personal Interview Prep',
            'home_video_highlight_3' => 'Conference Day Tips',
            'home_video_cta_text' => 'Join SSB Batch',
            'home_video_cta_url' => '/courses',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('1', SiteSetting::get('home_video_enabled'));
        $this->assertEquals('https://youtu.be/L_LUpnjgPso', SiteSetting::get('home_video_url'));
        $this->assertEquals('SSB Interview Demonstration', SiteSetting::get('home_video_title'));
        $this->assertEquals('GTO Ground Test Analysis', SiteSetting::get('home_video_highlight_1'));
    }
}
