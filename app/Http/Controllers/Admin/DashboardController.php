<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Question;
use App\Models\MockTest;
use App\Models\TestAttempt;
use App\Models\User;
use App\Models\ContactInquiry;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalEnrollments = Enrollment::where('status', 'active')->count();
        $totalCourses = Course::count();
        $totalQuestions = Question::count();
        $totalMockTests = MockTest::count();
        $totalRevenue = Order::where('status', 'paid')->sum('amount');
        $newInquiriesCount = ContactInquiry::where('status', 'new')->count();

        $recentOrders = Order::with(['user', 'course'])
            ->latest()
            ->take(6)
            ->get();

        $recentStudents = User::where('role', 'student')
            ->latest()
            ->take(5)
            ->get();

        $recentAttempts = TestAttempt::with(['user', 'mockTest'])
            ->where('status', 'completed')
            ->latest('submitted_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalEnrollments',
            'totalCourses',
            'totalQuestions',
            'totalMockTests',
            'totalRevenue',
            'newInquiriesCount',
            'recentOrders',
            'recentStudents',
            'recentAttempts'
        ));
    }
}
