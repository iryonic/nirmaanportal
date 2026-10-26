<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class StudentManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')->withCount(['enrollments', 'testAttempts']);

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'suspended') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('exam')) {
            $query->where('target_exam', $request->exam);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('target_exam', 'like', "%{$s}%");
            });
        }

        $students = $query->latest()->paginate(15)->withQueryString();
        $totalActive = User::where('role', 'student')->where('is_active', true)->count();
        $totalSuspended = User::where('role', 'student')->where('is_active', false)->count();

        return view('admin.students.index', compact('students', 'totalActive', 'totalSuspended'));
    }

    public function show(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $student->load(['enrollments.course', 'orders.course', 'testAttempts.mockTest']);
        $courses = Course::where('is_published', true)->get();

        return view('admin.students.show', compact('student', 'courses'));
    }

    public function toggleStatus(User $student)
    {
        $student->is_active = !$student->is_active;
        $student->save();

        $status = $student->is_active ? 'activated' : 'suspended';
        return back()->with('success', "Student account has been {$status}.");
    }

    public function manualEnroll(Request $request, User $student)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $validated['course_id']],
            [
                'status' => 'active',
                'enrolled_at' => now(),
                'expires_at' => now()->addMonths(6),
            ]
        );

        return back()->with('success', 'Student manually enrolled in course.');
    }
}
