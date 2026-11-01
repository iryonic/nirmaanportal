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
use App\Http\Controllers\Admin\NotificationManagementController;
use App\Http\Controllers\Admin\ResourceManagementController;
use App\Http\Controllers\Admin\CouponManagementController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('privacy.alias');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/terms-of-service', [HomeController::class, 'terms'])->name('terms.alias');
Route::get('/grievances', [HomeController::class, 'grievances'])->name('grievances');
Route::get('/help-and-grievances', [HomeController::class, 'grievances'])->name('grievances.alias');
Route::post('/grievances', [HomeController::class, 'submitGrievance'])->name('grievances.submit');
Route::get('/announcements', [HomeController::class, 'announcements'])->name('announcements');
Route::get('/notifications/{notification}', [HomeController::class, 'showNotification'])->name('notifications.show');
Route::get('/notifications/{notification}/download', [HomeController::class, 'downloadNotificationAttachment'])->name('notifications.download');
Route::get('/search', [HomeController::class, 'search'])->name('search');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{slug}/watch', [CourseController::class, 'watch'])->name('courses.watch');

Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
Route::get('/resources/download/{material}', [ResourceController::class, 'download'])->name('resources.download');

/*
|--------------------------------------------------------------------------
| Authentication & Recovery Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
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
    Route::get('/tests-all', [MockTestController::class, 'index'])->name('tests');
    Route::get('/tests/{test}/instructions', [MockTestController::class, 'instructions'])->name('tests.instructions');
    Route::post('/tests/{test}/start', [MockTestController::class, 'start'])->name('tests.start');
    Route::get('/tests/attempt/{attempt}', [MockTestController::class, 'attempt'])->name('tests.attempt');
    Route::post('/tests/attempt/{attempt}/save', [MockTestController::class, 'saveAnswer'])->name('tests.save');
    Route::post('/tests/attempt/{attempt}/submit', [MockTestController::class, 'submit'])->name('tests.submit');
    Route::get('/tests/result/{attempt}', [MockTestController::class, 'result'])->name('tests.result');

    // Orders & Checkout
    Route::post('/checkout/validate-coupon', [OrderController::class, 'validateCoupon'])->name('checkout.validate-coupon');
    Route::post('/checkout/create-razorpay-order', [OrderController::class, 'createRazorpayOrder'])->name('checkout.create-razorpay-order');
    Route::get('/checkout/{course}', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/{course}/pay', [OrderController::class, 'processPayment'])->name('checkout.pay');
    Route::get('/checkout/test/{test}', [OrderController::class, 'checkoutTest'])->name('checkout.test');
    Route::post('/checkout/test/{test}/pay', [OrderController::class, 'processTestPayment'])->name('checkout.test.pay');
    Route::get('/orders', [OrderController::class, 'history'])->name('orders');
    Route::get('/orders/receipt/{order}', [OrderController::class, 'receipt'])->name('orders.receipt');
});

// Asynchronous Payment Webhook Listeners
Route::post('/webhooks/razorpay', [WebhookController::class, 'handleRazorpay'])->name('webhooks.razorpay');

/*
|--------------------------------------------------------------------------
| Admin Portal Routes (Restricted Staff)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Courses & Curriculum Management
    Route::post('/courses/bulk', [CourseManagementController::class, 'bulk'])->name('courses.bulk');
    Route::resource('courses', CourseManagementController::class);
    Route::post('/courses/{course}/toggle', [CourseManagementController::class, 'toggleStatus'])->name('courses.toggle');
    Route::post('/courses/{course}/import-playlist', [CourseManagementController::class, 'importPlaylist'])->name('courses.import-playlist');
    Route::post('/courses/{course}/save-episodes', [CourseManagementController::class, 'saveEpisodes'])->name('courses.save-episodes');
    Route::post('/courses/{course}/modules', [CourseManagementController::class, 'storeModule'])->name('courses.modules.store');
    Route::post('/modules/{module}/lessons', [CourseManagementController::class, 'storeLesson'])->name('courses.lessons.store');
    Route::delete('/lessons/{lesson}', [CourseManagementController::class, 'destroyLesson'])->name('courses.lessons.destroy');

    // Study Resources & Materials Management
    Route::post('/resources/bulk', [ResourceManagementController::class, 'bulk'])->name('resources.bulk');
    Route::resource('resources', ResourceManagementController::class);
    Route::post('/resources/{resource}/toggle', [ResourceManagementController::class, 'toggleStatus'])->name('resources.toggle');
    Route::get('/resources/{resource}/download', [ResourceManagementController::class, 'download'])->name('resources.download');

    // Question Bank Management
    Route::post('/questions/bulk', [QuestionManagementController::class, 'bulk'])->name('questions.bulk');
    Route::resource('questions', QuestionManagementController::class);

    // Mock Test Management
    Route::post('/tests/bulk', [MockTestManagementController::class, 'bulk'])->name('tests.bulk');
    Route::resource('tests', MockTestManagementController::class);
    Route::post('/tests/{test}/toggle', [MockTestManagementController::class, 'toggleStatus'])->name('tests.toggle');
    Route::get('/tests/{test}/attempts', [MockTestManagementController::class, 'attempts'])->name('tests.attempts');

    // Student Management
    Route::post('/students/bulk', [StudentManagementController::class, 'bulk'])->name('students.bulk');
    Route::get('/students', [StudentManagementController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [StudentManagementController::class, 'show'])->name('students.show');
    Route::post('/students/{student}/toggle', [StudentManagementController::class, 'toggleStatus'])->name('students.toggle');
    Route::post('/students/{student}/enroll', [StudentManagementController::class, 'manualEnroll'])->name('students.enroll');

    // Order & Financial Audits
    Route::post('/orders/bulk', [OrderManagementController::class, 'bulk'])->name('orders.bulk');
    Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderManagementController::class, 'show'])->name('orders.show');

    // Coupon & Scholarship Discounts Management
    Route::post('/coupons/bulk', [CouponManagementController::class, 'bulk'])->name('coupons.bulk');
    Route::resource('coupons', CouponManagementController::class);
    Route::post('/coupons/{coupon}/toggle', [CouponManagementController::class, 'toggleStatus'])->name('coupons.toggle');

    // Site Content & Settings Management
    Route::get('/content', [ContentManagementController::class, 'index'])->name('content.index');
    Route::post('/content/settings', [ContentManagementController::class, 'updateSettings'])->name('content.settings');
    Route::post('/content/video', [ContentManagementController::class, 'updateFeaturedVideo'])->name('content.video');
    Route::post('/content/faqs', [ContentManagementController::class, 'storeFaq'])->name('content.faqs.store');
    Route::delete('/content/faqs/{faq}', [ContentManagementController::class, 'destroyFaq'])->name('content.faqs.destroy');
    Route::post('/content/inquiries/{inquiry}/toggle', [ContentManagementController::class, 'toggleInquiry'])->name('content.inquiries.toggle');

    // Notice Board & Notification Management
    Route::post('/notifications/bulk', [NotificationManagementController::class, 'bulk'])->name('notifications.bulk');
    Route::resource('notifications', NotificationManagementController::class);
    Route::post('/notifications/{notification}/toggle', [NotificationManagementController::class, 'toggleStatus'])->name('notifications.toggle');

    // Payment Gateway & Razorpay Settings
    Route::get('/settings/payment', [PaymentSettingController::class, 'index'])->name('settings.payment');
    Route::post('/settings/payment', [PaymentSettingController::class, 'update'])->name('settings.payment.update');
    Route::post('/settings/payment/test-connection', [PaymentSettingController::class, 'testConnection'])->name('settings.payment.test');
});
