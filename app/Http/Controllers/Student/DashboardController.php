<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\MockTest;
use App\Models\TestAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Enrolled courses with modules and lessons
        $enrollments = Enrollment::where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['course.modules.lessons'])
            ->latest()
            ->get();

        // Completed lessons count
        $completedLessonIds = $user->lessonCompletions()->pluck('lesson_id')->toArray();

        // Available mock tests
        $availableTests = MockTest::where('is_published', true)
            ->with('examCategory')
            ->take(4)
            ->get();

        // Recent test attempts
        $recentAttempts = TestAttempt::where('user_id', $user->id)
            ->with('mockTest')
            ->latest('submitted_at')
            ->take(5)
            ->get();

        // Stats
        $totalTestsAttempted = TestAttempt::where('user_id', $user->id)->where('status', 'completed')->count();
        $averageAccuracy = TestAttempt::where('user_id', $user->id)->where('status', 'completed')->avg('accuracy_percentage') ?? 0;

        return view('student.dashboard', compact(
            'user',
            'enrollments',
            'completedLessonIds',
            'availableTests',
            'recentAttempts',
            'totalTestsAttempted',
            'averageAccuracy'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('student.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'target_exam' => ['required', 'string', 'max:255'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->target_exam = $validated['target_exam'];

        if (!empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', 'Your profile details have been updated successfully.');
    }
}
