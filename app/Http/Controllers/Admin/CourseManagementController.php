<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\ExamCategory;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseManagementController extends Controller
{
    public function index()
    {
        $courses = Course::with('examCategory')
            ->withCount(['modules', 'enrollments'])
            ->latest()
            ->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = ExamCategory::where('is_active', true)->get();
        return view('admin.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_category_id' => ['required', 'exists:exam_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'gte:price'],
            'instructor_name' => ['nullable', 'string', 'max:255'],
            'duration_text' => ['nullable', 'string', 'max:100'],
            'has_live_classes' => ['boolean'],
            'has_mock_tests' => ['boolean'],
            'has_study_material' => ['boolean'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['has_live_classes'] = $request->boolean('has_live_classes');
        $validated['has_mock_tests'] = $request->boolean('has_mock_tests');
        $validated['has_study_material'] = $request->boolean('has_study_material');
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');

        $course = Course::create($validated);

        return redirect()->route('admin.courses.edit', $course->id)
            ->with('success', 'Course created! Now you can organize modules and lessons.');
    }

    public function edit(Course $course)
    {
        $course->load(['modules.lessons', 'examCategory']);
        $categories = ExamCategory::where('is_active', true)->get();

        return view('admin.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'exam_category_id' => ['required', 'exists:exam_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric'],
            'instructor_name' => ['nullable', 'string', 'max:255'],
            'duration_text' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['has_live_classes'] = $request->boolean('has_live_classes');
        $validated['has_mock_tests'] = $request->boolean('has_mock_tests');
        $validated['has_study_material'] = $request->boolean('has_study_material');
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');

        $course->update($validated);

        return back()->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course removed successfully.');
    }

    public function storeModule(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $maxSort = $course->modules()->max('sort_order') ?? 0;
        $course->modules()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'sort_order' => $maxSort + 1,
        ]);

        return back()->with('success', 'Module added successfully.');
    }

    public function storeLesson(Request $request, CourseModule $module)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'video_provider' => ['required', 'string'],
            'video_id' => ['required', 'string'],
            'attachment_title' => ['nullable', 'string'],
        ]);

        $validated['is_preview'] = $request->boolean('is_preview');
        $maxSort = $module->lessons()->max('sort_order') ?? 0;
        $validated['sort_order'] = $maxSort + 1;

        $module->lessons()->create($validated);

        return back()->with('success', 'Lesson added to module successfully.');
    }

    public function destroyLesson(Lesson $lesson)
    {
        $lesson->delete();
        return back()->with('success', 'Lesson deleted.');
    }
}
