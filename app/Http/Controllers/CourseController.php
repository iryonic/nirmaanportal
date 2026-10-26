<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ExamCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::where('is_published', true)
            ->with(['examCategory', 'modules.lessons'])
            ->withCount('lessons');

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('examCategory', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by price type
        if ($request->filled('type')) {
            if ($request->type === 'free') {
                $query->where('is_free', true);
            } elseif ($request->type === 'paid') {
                $query->where('is_free', false);
            }
        }

        // Search query
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('subtitle', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        // Sorting
        switch ($request->get('sort')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'lessons':
                $query->orderBy('lessons_count', 'desc');
                break;
            default:
                $query->orderBy('sort_order', 'asc');
        }

        $courses = $query->paginate(9)->withQueryString();

        $categories = ExamCategory::where('is_active', true)
            ->withCount(['courses' => function ($q) {
                $q->where('is_published', true);
            }])
            ->orderBy('sort_order')
            ->get();

        $totalPublishedCount = Course::where('is_published', true)->count();

        $enrolledCourseIds = [];
        if (Auth::check()) {
            $enrolledCourseIds = Auth::user()->enrollments()
                ->where('status', 'active')
                ->pluck('course_id')
                ->toArray();
        }

        return view('courses.index', compact('courses', 'categories', 'totalPublishedCount', 'enrolledCourseIds'));
    }

    public function show(string $slug)
    {
        $course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->with(['examCategory', 'modules.lessons'])
            ->firstOrFail();

        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Auth::user()->isEnrolledIn($course);
        }

        $relatedCourses = Course::where('is_published', true)
            ->where('id', '!=', $course->id)
            ->where('exam_category_id', $course->exam_category_id)
            ->take(3)
            ->get();

        return view('courses.show', compact('course', 'isEnrolled', 'relatedCourses'));
    }

    public function watch(string $slug, Request $request)
    {
        $course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->with(['examCategory', 'modules.lessons'])
            ->firstOrFail();

        $allLessons = $course->modules->flatMap->lessons->values();

        if ($allLessons->isEmpty()) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'No video lectures or episodes have been published for this course yet.');
        }

        $currentLesson = null;
        if ($request->filled('lesson')) {
            $currentLesson = $allLessons->firstWhere('id', (int)$request->lesson);
        }
        if (!$currentLesson) {
            $currentLesson = $allLessons->first();
        }

        $currentIndex = $allLessons->search(fn($l) => $l->id === $currentLesson->id);
        if ($currentIndex === false) {
            $currentIndex = 0;
        }

        $isEnrolled = false;
        $completedLessonsCount = 0;
        $isCompleted = false;

        if (Auth::check()) {
            $user = Auth::user();
            $isEnrolled = $user->isEnrolledIn($course);
            $completedLessonsCount = $user->lessonCompletions()
                ->whereIn('lesson_id', $allLessons->pluck('id'))
                ->count();
            $isCompleted = $currentLesson->isCompletedBy($user);
        }

        $previousLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < ($allLessons->count() - 1) ? $allLessons[$currentIndex + 1] : null;

        return view('courses.watch', compact(
            'course',
            'allLessons',
            'currentLesson',
            'currentIndex',
            'isEnrolled',
            'isCompleted',
            'completedLessonsCount',
            'previousLesson',
            'nextLesson'
        ));
    }
}
