<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ExamCategory;
use App\Models\Faq;
use App\Models\MockTest;
use App\Models\Notification;
use App\Models\ContactInquiry;
use App\Models\Lesson;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        // Latest published notifications ordered by pinned status, then publication time
        $latestNotifications = Notification::published()
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->take(6)
            ->get();

        // Dynamic impact statistics with live database counts and configurable baselines
        $realStudents = User::where('role', 'student')->count();
        $realMockTests = MockTest::where('is_published', true)->count();
        $realLessons = Lesson::count();

        $impactStats = [
            'students' => [
                'target' => (int) SiteSetting::get('stat_students_count', 50000 + $realStudents),
                'suffix' => '+',
                'decimals' => 0,
                'label' => 'Happy Students',
            ],
            'mock_tests' => [
                'target' => (int) SiteSetting::get('stat_mock_tests_count', 2500 + $realMockTests),
                'suffix' => '+',
                'decimals' => 0,
                'label' => 'Mock Tests',
            ],
            'video_lectures' => [
                'target' => (int) SiteSetting::get('stat_videos_count', 1000 + $realLessons),
                'suffix' => '+',
                'decimals' => 0,
                'label' => 'Video Lectures',
            ],
            'rating' => [
                'target' => (float) SiteSetting::get('stat_avg_rating', 4.8),
                'suffix' => '/5',
                'decimals' => 1,
                'label' => 'Average Rating',
            ],
        ];

        $featuredVideo = SiteSetting::getFeaturedVideo();

        return view('pages.home', compact('categories', 'featuredCourses', 'faqs', 'freeMockTest', 'latestNotifications', 'impactStats', 'featuredVideo'));
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

    public function announcements(Request $request)
    {
        $query = Notification::published();

        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $notifications = $query->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        $categories = Notification::published()
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('pages.announcements', compact('notifications', 'categories'));
    }

    public function showNotification(Notification $notification)
    {
        if (! $notification->is_published) {
            abort(404);
        }

        $notification->increment('views_count');

        if ($notification->attachment_path) {
            return redirect()->route('notifications.download', $notification->id);
        }

        if ($notification->external_url) {
            return redirect()->away($notification->external_url);
        }

        return redirect()->route('announcements', ['q' => $notification->title]);
    }

    public function downloadNotificationAttachment(Notification $notification)
    {
        if (! $notification->attachment_path || ! Storage::disk('public')->exists($notification->attachment_path)) {
            // If attachment does not exist physically, fallback gracefully to target URL or announcements
            if ($notification->external_url) {
                return redirect()->away($notification->external_url);
            }
            return redirect()->route('announcements')->with('error', 'The requested notification document is not available or has expired.');
        }

        $notification->increment('views_count');

        $filePath = Storage::disk('public')->path($notification->attachment_path);
        $downloadName = $notification->attachment_name ?: basename($filePath);

        return response()->download($filePath, $downloadName);
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
