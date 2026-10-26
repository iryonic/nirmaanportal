<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ExamCategory;
use App\Models\Faq;
use App\Models\MockTest;
use App\Models\ContactInquiry;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = ExamCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $featuredCourses = Course::where('is_published', true)
            ->where('is_featured', true)
            ->with('examCategory')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $faqs = Faq::where('is_active', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $freeMockTest = MockTest::where('is_published', true)
            ->where('is_free', true)
            ->first();

        return view('pages.home', compact('categories', 'featuredCourses', 'faqs', 'freeMockTest'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactInquiry::create($validated);

        return back()->with('success', 'Thank you for reaching out! A Nirmaan academic counsellor will contact you shortly.');
    }

    public function announcements()
    {
        return view('pages.announcements');
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');

        $courses = Course::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('subtitle', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('examCategory')
            ->get();

        $tests = MockTest::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('examCategory')
            ->get();

        return view('pages.search', compact('query', 'courses', 'tests'));
    }
}
