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
use Illuminate\Support\Str;

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
        $aboutConfig = SiteSetting::getAboutConfig();
        $realStudents = User::where('role', 'student')->count();
        $realMockTests = MockTest::where('is_published', true)->count();
        $categories = ExamCategory::where('is_active', true)->orderBy('sort_order')->take(6)->get();

        return view('pages.about', compact('aboutConfig', 'realStudents', 'realMockTests', 'categories'));
    }

    public function contact()
    {
        $contactConfig = SiteSetting::getContactConfig();
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->take(5)->get();

        return view('pages.contact', compact('contactConfig', 'faqs'));
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'inquiry_type' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $inquiryType = $validated['inquiry_type'] ?? 'General Enquiry';
        $fullSubject = "[{$inquiryType}] " . $validated['subject'];

        $ticketNumber = 'INQ-' . date('ymd') . '-' . strtoupper(Str::random(4));

        ContactInquiry::create([
            'ticket_number' => $ticketNumber,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'type' => 'inquiry',
            'subject' => $fullSubject,
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        return back()->with('success', "Thank you for reaching out! Your inquiry reference is #{$ticketNumber}. A Nirmaan academic counsellor will contact you shortly.");
    }

    public function privacy()
    {
        $privacyConfig = SiteSetting::getPrivacyConfig();
        $contactConfig = SiteSetting::getContactConfig();

        return view('pages.privacy', compact('privacyConfig', 'contactConfig'));
    }

    public function terms()
    {
        $termsConfig = SiteSetting::getTermsConfig();
        $contactConfig = SiteSetting::getContactConfig();

        return view('pages.terms', compact('termsConfig', 'contactConfig'));
    }

    public function grievances()
    {
        $grievanceConfig = SiteSetting::getGrievanceConfig();
        $contactConfig = SiteSetting::getContactConfig();

        return view('pages.grievances', compact('grievanceConfig', 'contactConfig'));
    }

    public function submitGrievance(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'cadet_roll' => ['nullable', 'string', 'max:50'],
            'category' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $ticketNumber = 'GRV-' . date('ymd') . '-' . strtoupper(Str::random(4));
        $cadetNote = !empty($validated['cadet_roll']) ? " [Roll/Order: {$validated['cadet_roll']}]" : '';
        $fullSubject = "[GRIEVANCE: {$validated['category']}]{$cadetNote} {$validated['subject']}";

        ContactInquiry::create([
            'ticket_number' => $ticketNumber,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'type' => 'grievance',
            'subject' => $fullSubject,
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        return back()->with('success', "Your formal grievance has been filed with the Grievance Redressal Cell under Docket #{$ticketNumber}. An acknowledgment has been generated and our Grievance Officer will review within the statutory SLA timeline.");
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
