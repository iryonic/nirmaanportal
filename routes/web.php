<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\CourseLearnController;
use App\Http\Controllers\Student\PracticeController;
use App\Http\Controllers\Student\MockTestController;
use App\Http\Controllers\Student\OrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CourseManagementController;
use App\Http\Controllers\Admin\QuestionManagementController;
use App\Http\Controllers\Admin\MockTestManagementController;
use App\Http\Controllers\Admin\StudentManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\ContentManagementController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/announcements', [HomeController::class, 'announcements'])->name('announcements');
Route::get('/search', [HomeController::class, 'search'])->name('search');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');

Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
Route::get('/resources/download/{material}', [ResourceController::class, 'download'])->name('resources.download');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Student Portal Routes (Authenticated Students)
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [StudentDashboardController::class, 'updateProfile'])->name('profile.update');

    // Course Learning & Lessons
    Route::get('/courses/{course}/learn/{lesson?}', [CourseLearnController::class, 'learn'])->name('courses.learn');
    Route::post('/lessons/{lesson}/complete', [CourseLearnController::class, 'toggleComplete'])->name('lessons.toggle');

    // Practice Question Bank
    Route::get('/practice', [PracticeController::class, 'index'])->name('practice');
    Route::post('/practice/check', [PracticeController::class, 'checkAnswer'])->name('practice.check');

    // Mock Tests
    Route::get('/tests', [MockTestController::class, 'index'])->name('tests.index');
    Route::get('/tests/{test}/instructions', [MockTestController::class, 'instructions'])->name('tests.instructions');
    Route::post('/tests/{test}/start', [MockTestController::class, 'start'])->name('tests.start');
    Route::get('/tests/attempt/{attempt}', [MockTestController::class, 'attempt'])->name('tests.attempt');
    Route::post('/tests/attempt/{attempt}/save', [MockTestController::class, 'saveAnswer'])->name('tests.save');
    Route::post('/tests/attempt/{attempt}/submit', [MockTestController::class, 'submit'])->name('tests.submit');
    Route::get('/tests/result/{attempt}', [MockTestController::class, 'result'])->name('tests.result');

    // Orders & Checkout
    Route::get('/checkout/{course}', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/{course}/pay', [OrderController::class, 'processPayment'])->name('checkout.pay');
    Route::get('/orders', [OrderController::class, 'history'])->name('orders');
});

/*
|--------------------------------------------------------------------------
| Admin Portal Routes (Restricted Staff)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Courses & Curriculum Management
    Route::resource('courses', CourseManagementController::class);
    Route::post('/courses/{course}/modules', [CourseManagementController::class, 'storeModule'])->name('courses.modules.store');
    Route::post('/modules/{module}/lessons', [CourseManagementController::class, 'storeLesson'])->name('courses.lessons.store');
    Route::delete('/lessons/{lesson}', [CourseManagementController::class, 'destroyLesson'])->name('courses.lessons.destroy');

    // Question Bank Management
    Route::resource('questions', QuestionManagementController::class);

    // Mock Test Management
    Route::resource('tests', MockTestManagementController::class);
    Route::get('/tests/{test}/attempts', [MockTestManagementController::class, 'attempts'])->name('tests.attempts');

    // Student Management
    Route::get('/students', [StudentManagementController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [StudentManagementController::class, 'show'])->name('students.show');
    Route::post('/students/{student}/toggle', [StudentManagementController::class, 'toggleStatus'])->name('students.toggle');
    Route::post('/students/{student}/enroll', [StudentManagementController::class, 'manualEnroll'])->name('students.enroll');

    // Order & Financial Audits
    Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderManagementController::class, 'show'])->name('orders.show');

    // Site Content & Settings Management
    Route::get('/content', [ContentManagementController::class, 'index'])->name('content.index');
    Route::post('/content/settings', [ContentManagementController::class, 'updateSettings'])->name('content.settings');
    Route::post('/content/faqs', [ContentManagementController::class, 'storeFaq'])->name('content.faqs.store');
    Route::delete('/content/faqs/{faq}', [ContentManagementController::class, 'destroyFaq'])->name('content.faqs.destroy');
    Route::post('/content/inquiries/{inquiry}/toggle', [ContentManagementController::class, 'toggleInquiry'])->name('content.inquiries.toggle');
});
