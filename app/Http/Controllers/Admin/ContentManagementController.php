<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\Faq;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class ContentManagementController extends Controller
{
    public function index(Request $request)
    {
        $faqs = Faq::orderBy('sort_order')->get();
        
        $inquiriesQuery = ContactInquiry::latest();
        if ($request->filled('type') && in_array($request->type, ['inquiry', 'grievance'])) {
            $inquiriesQuery->where('type', $request->type);
        }
        $inquiries = $inquiriesQuery->paginate(15)->withQueryString();

        $settings = SiteSetting::all()->pluck('value', 'key');
        $featuredVideo = SiteSetting::getFeaturedVideo();

        $aboutConfig = SiteSetting::getAboutConfig();
        $contactConfig = SiteSetting::getContactConfig();
        $privacyConfig = SiteSetting::getPrivacyConfig();
        $termsConfig = SiteSetting::getTermsConfig();
        $grievanceConfig = SiteSetting::getGrievanceConfig();

        return view('admin.content.index', compact(
            'faqs',
            'inquiries',
            'settings',
            'featuredVideo',
            'aboutConfig',
            'contactConfig',
            'privacyConfig',
            'termsConfig',
            'grievanceConfig'
        ));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->except('_token') as $key => $val) {
            SiteSetting::set($key, (string) ($val ?? ''));
        }

        return back()->with('success', 'Page content and website settings updated successfully.');
    }

    public function updateFeaturedVideo(Request $request)
    {
        $validated = $request->validate([
            'home_video_url' => ['nullable', 'string', 'max:255'],
            'home_video_badge' => ['nullable', 'string', 'max:100'],
            'home_video_title' => ['nullable', 'string', 'max:255'],
            'home_video_subtitle' => ['nullable', 'string'],
            'home_video_highlight_1' => ['nullable', 'string', 'max:150'],
            'home_video_highlight_2' => ['nullable', 'string', 'max:150'],
            'home_video_highlight_3' => ['nullable', 'string', 'max:150'],
            'home_video_cta_text' => ['nullable', 'string', 'max:100'],
            'home_video_cta_url' => ['nullable', 'string', 'max:255'],
            'home_video_thumbnail' => ['nullable', 'string', 'max:500'],
        ]);

        SiteSetting::set('home_video_enabled', $request->has('home_video_enabled') ? '1' : '0');

        foreach ($validated as $key => $val) {
            SiteSetting::set($key, (string) ($val ?? ''));
        }

        return back()->with('success', 'Homepage featured video settings updated successfully.');
    }

    public function storeFaq(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'category' => ['required', 'string'],
        ]);

        $maxSort = Faq::max('sort_order') ?? 0;
        $validated['sort_order'] = $maxSort + 1;
        $validated['is_active'] = true;

        Faq::create($validated);

        return back()->with('success', 'FAQ added successfully.');
    }

    public function destroyFaq(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted.');
    }

    public function toggleInquiry(ContactInquiry $inquiry)
    {
        $inquiry->status = $inquiry->status === 'new' ? 'replied' : 'new';
        $inquiry->save();

        return back()->with('success', 'Inquiry status updated.');
    }
}
