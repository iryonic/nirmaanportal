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
        $query = Course::where('is_published', true)->with('examCategory');

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
            $s = $request->search;
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
            default:
                $query->orderBy('sort_order', 'asc');
        }

        $courses = $query->paginate(9)->withQueryString();
        $categories = ExamCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('courses.index', compact('courses', 'categories'));
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
}
