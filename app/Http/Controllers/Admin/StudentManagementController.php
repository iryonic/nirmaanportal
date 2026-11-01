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

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'exists:users,id'],
            'action' => ['required', 'string', 'in:activate,suspend,export'],
        ]);

        $ids = $validated['ids'];
        $count = count($ids);

        switch ($validated['action']) {
            case 'activate':
                User::where('role', 'student')->whereIn('id', $ids)->update(['is_active' => true]);
                return back()->with('success', "{$count} cadet accounts activated successfully.");

            case 'suspend':
                User::where('role', 'student')->whereIn('id', $ids)->update(['is_active' => false]);
                return back()->with('success', "{$count} cadet accounts suspended.");

            case 'export':
                $students = User::where('role', 'student')->whereIn('id', $ids)->get();
                $filename = 'cadets_export_' . now()->format('Ymd_His') . '.csv';

                return response()->streamDownload(function () use ($students) {
                    $handle = fopen('php://output', 'w');
                    fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'Target Exam', 'Status', 'Registered Date']);

                    foreach ($students as $st) {
                        fputcsv($handle, [
                            $st->id,
                            $st->name,
                            $st->email,
                            $st->phone ?? 'N/A',
                            $st->target_exam ?? 'N/A',
                            $st->is_active ? 'Active' : 'Suspended',
                            $st->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                    fclose($handle);
                }, $filename, ['Content-Type' => 'text/csv']);

            default:
                return back()->with('error', 'Unknown bulk action.');
        }
    }
}

