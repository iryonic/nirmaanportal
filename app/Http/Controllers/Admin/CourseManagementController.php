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
    public function index(Request $request)
    {
        $query = Course::with('examCategory')->withCount(['modules', 'enrollments']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('exam_category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $courses = $query->latest()->paginate(10)->withQueryString();
        $categories = ExamCategory::where('is_active', true)->get();

        return view('admin.courses.index', compact('courses', 'categories'));
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

    public function importPlaylist(Request $request, Course $course, \App\Services\YouTubePlaylistService $service)
    {
        $request->validate([
            'playlist_url' => ['required', 'string'],
        ]);

        $playlistId = \App\Services\YouTubePlaylistService::extractPlaylistId($request->playlist_url);
        
        if (!$playlistId) {
            // Check if user entered a single video URL or video ID instead
            $singleVideoId = \App\Services\YouTubePlaylistService::extractVideoId($request->playlist_url);
            if ($singleVideoId) {
                $videoInfo = $service->fetchSingleVideo($singleVideoId);
                $module = $course->modules()->first();
                if (!$module) {
                    $module = $course->modules()->create([
                        'title' => $course->title . ' — Video Lectures',
                        'sort_order' => 1,
                    ]);
                }
                $maxSort = (int)($module->lessons()->max('sort_order') ?? 0);
                $module->lessons()->create([
                    'title' => $videoInfo['title'] ?: ($course->title . ' — Lecture ' . ($maxSort + 1)),
                    'video_id' => $singleVideoId,
                    'video_provider' => 'youtube',
                    'duration_minutes' => 30,
                    'sort_order' => $maxSort + 1,
                    'is_preview' => ($maxSort === 0),
                ]);

                return back()->with('success', "Detected single YouTube video. Successfully added lesson: " . ($videoInfo['title'] ?: $singleVideoId));
            }

            return back()->with('error', 'Invalid YouTube link. Please enter a valid playlist link (e.g. https://www.youtube.com/playlist?list=...) or YouTube video URL.');
        }

        $result = $service->fetchPlaylist($playlistId);
        if (!$result['success'] || empty($result['videos'])) {
            return back()->with('error', $result['message'] ?? 'Could not fetch videos from this playlist. Please ensure the playlist is public or unlisted.');
        }

        // Store playlist url on course
        $course->update(['youtube_playlist_url' => $request->playlist_url]);

        // Find or create a module for this course
        $module = $course->modules()->first();
        if (!$module) {
            $module = $course->modules()->create([
                'title' => $result['title'] ?: ($course->title . ' — All Lectures'),
                'description' => 'Complete video lecture curriculum imported directly from YouTube playlist.',
                'sort_order' => 1,
            ]);
        }

        $startSort = (int)($module->lessons()->max('sort_order') ?? 0);
        $importedCount = 0;

        foreach ($result['videos'] as $index => $v) {
            $existing = Lesson::whereHas('module', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })->where('video_id', $v['video_id'])->first();

            if ($existing) {
                $existing->update([
                    'title' => $v['title'],
                    'duration_minutes' => $v['duration_minutes'],
                    'duration_text' => $v['duration_text'],
                ]);
            } else {
                $module->lessons()->create([
                    'title' => $v['title'],
                    'duration_minutes' => $v['duration_minutes'],
                    'duration_text' => $v['duration_text'],
                    'video_provider' => 'youtube',
                    'video_id' => $v['video_id'],
                    'is_preview' => ($index === 0 && $startSort === 0), // First lesson preview
                    'sort_order' => $startSort + $index + 1,
                ]);
                $importedCount++;
            }
        }

        return back()->with('success', "Successfully imported " . count($result['videos']) . " episodes from YouTube playlist: " . $result['title']);
    }

    public function saveEpisodes(Request $request, Course $course)
    {
        $validated = $request->validate([
            'episodes' => ['required', 'array'],
            'episodes.*.title' => ['required', 'string', 'max:255'],
            'episodes.*.video_input' => ['required', 'string'],
        ]);

        $module = $course->modules()->first();
        if (!$module) {
            $module = $course->modules()->create([
                'title' => $course->title . ' — Video Lectures',
                'sort_order' => 1,
            ]);
        }

        $addedCount = 0;
        $maxSort = (int)($module->lessons()->max('sort_order') ?? 0);

        foreach ($validated['episodes'] as $epData) {
            $videoId = \App\Services\YouTubePlaylistService::extractVideoId($epData['video_input']);
            if (!$videoId) continue;

            $maxSort++;
            $module->lessons()->create([
                'title' => $epData['title'],
                'video_id' => $videoId,
                'video_provider' => 'youtube',
                'duration_minutes' => 30,
                'sort_order' => $maxSort,
                'is_preview' => false,
            ]);
            $addedCount++;
        }

        return back()->with('success', "Successfully saved {$addedCount} episodes under this course!");
    }

    public function toggleStatus(Course $course)
    {
        $course->is_published = !$course->is_published;
        $course->save();

        $label = $course->is_published ? 'published' : 'moved to draft';
        return back()->with('success', "Course batch '{$course->title}' has been {$label}.");
    }
}
