<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseLearnController extends Controller
{
    public function learn(Course $course, ?Lesson $lesson = null)
    {
        $user = Auth::user();
        $isEnrolled = $user->isEnrolledIn($course);

        // Load modules with lessons
        $course->load(['modules.lessons']);

        // Collect all lessons in sequence
        $allLessons = $course->modules->flatMap->lessons;

        if ($allLessons->isEmpty()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'No lessons have been uploaded to this course yet.');
        }

        // If no lesson specified, default to first incomplete lesson or the first lesson
        if (!$lesson) {
            $completedIds = $user->lessonCompletions()->pluck('lesson_id')->toArray();
            $lesson = $allLessons->first(fn($l) => !in_array($l->id, $completedIds)) ?? $allLessons->first();
        }

        // Authorization check: If user is not enrolled and lesson is not a free preview, deny access
        if (!$isEnrolled && !$lesson->is_preview) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'You must be enrolled in this course to access protected lessons.');
        }

        // Calculate course progress percentage
        $totalLessons = $allLessons->count();
        $completedLessonsCount = $user->lessonCompletions()
            ->whereIn('lesson_id', $allLessons->pluck('id'))
            ->count();
        $progressPercent = $totalLessons > 0 ? round(($completedLessonsCount / $totalLessons) * 100) : 0;

        // Find previous and next lessons
        $currentIndex = $allLessons->search(fn($l) => $l->id === $lesson->id);
        $previousLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex !== false && $currentIndex < ($totalLessons - 1) ? $allLessons[$currentIndex + 1] : null;

        $isCompleted = $lesson->isCompletedBy($user);

        return view('student.learn', compact(
            'course',
            'lesson',
            'allLessons',
            'isEnrolled',
            'isCompleted',
            'progressPercent',
            'completedLessonsCount',
            'totalLessons',
            'previousLesson',
            'nextLesson'
        ));
    }

    public function toggleComplete(Lesson $lesson, Request $request)
    {
        $user = Auth::user();

        $completion = LessonCompletion::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        if ($completion) {
            $completion->delete();
            $completed = false;
            $message = 'Lesson marked as incomplete.';
        } else {
            LessonCompletion::create([
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'completed_at' => now(),
            ]);
            $completed = true;
            $message = 'Lesson marked as completed! Great job.';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'completed' => $completed,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
