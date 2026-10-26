<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\Faq;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class ContentManagementController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->get();
        $inquiries = ContactInquiry::latest()->paginate(10);
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('admin.content.index', compact('faqs', 'inquiries', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        $keys = [
            'hero_title',
            'hero_subtitle',
            'contact_phone',
            'contact_email',
            'contact_address',
            'youtube_url',
            'telegram_url',
            'instagram_url',
            'facebook_url',
            'twitter_url',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::set($key, $request->input($key));
            }
        }

        return back()->with('success', 'Website settings updated successfully.');
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
