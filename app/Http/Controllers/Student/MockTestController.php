<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamCategory;
use App\Models\MockTest;
use App\Models\Order;
use App\Models\Question;
use App\Models\TestAttempt;
use App\Models\TestAttemptAnswer;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MockTestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = MockTest::where('is_published', true)
            ->with(['examCategory', 'questions'])
            ->withCount('questions');

        // Search by keyword
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category (by ID or slug)
        if ($request->filled('category')) {
            $cat = $request->category;
            $query->where(function ($q) use ($cat) {
                if (is_numeric($cat)) {
                    $q->where('exam_category_id', $cat);
                } else {
                    $q->whereHas('examCategory', fn ($sq) => $sq->where('slug', $cat));
                }
            });
        }

        // Filter by access type: free or paid
        $accessFilter = $request->get('access', $request->get('type'));
        if ($accessFilter === 'free') {
            $query->where('is_free', true);
        } elseif ($accessFilter === 'paid') {
            $query->where('is_free', false);
        }

        // Sort options
        if ($request->sort === 'duration_asc') {
            $query->orderBy('duration_minutes', 'asc');
        } elseif ($request->sort === 'marks_desc') {
            $query->orderBy('total_marks', 'desc');
        } else {
            $query->latest();
        }

        $tests = $query->paginate(9)->withQueryString();

        // Attach latest attempts and access entitlements
        $attemptsByTest = TestAttempt::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('mock_test_id');

        $enrolledCategoryIds = Enrollment::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('course')
            ->get()
            ->pluck('course.exam_category_id')
            ->filter()
            ->unique()
            ->toArray();

        $purchasedTestIds = Order::where('user_id', $user->id)
            ->whereNotNull('mock_test_id')
            ->where('status', 'paid')
            ->pluck('mock_test_id')
            ->toArray();

        foreach ($tests as $t) {
            $testAttempts = $attemptsByTest->get($t->id);
            $t->in_progress_attempt = $testAttempts?->firstWhere('status', 'in_progress');
            $t->completed_attempt = $testAttempts?->firstWhere('status', 'completed');

            $t->has_access = $t->is_free 
                || $user->isAdmin() 
                || in_array($t->id, $purchasedTestIds)
                || ($t->exam_category_id && in_array($t->exam_category_id, $enrolledCategoryIds));
        }

        $categories = ExamCategory::where('is_active', true)
            ->withCount(['mockTests' => fn ($q) => $q->where('is_published', true)])
            ->orderBy('sort_order')
            ->get();

        $totalPublishedTestsCount = MockTest::where('is_published', true)->count();

        $allUserCompleted = TestAttempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->get();

        $userCompletedCount = $allUserCompleted->count();
        $userAvgAccuracy = $userCompletedCount > 0 ? round($allUserCompleted->avg('accuracy_percentage'), 1) : 0;
        $userBestScore = $userCompletedCount > 0 ? round($allUserCompleted->max('score'), 1) : 0;

        $recentAttempts = TestAttempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with(['mockTest.examCategory'])
            ->latest('submitted_at')
            ->take(8)
            ->get();

        $attempts = $recentAttempts;

        return view('student.tests.index', compact(
            'tests',
            'categories',
            'recentAttempts',
            'attempts',
            'totalPublishedTestsCount',
            'userCompletedCount',
            'userAvgAccuracy',
            'userBestScore'
        ));
    }

    public function instructions(MockTest $test)
    {
        $test->load(['examCategory', 'questions']);
        $totalQuestions = $test->questions->count();
        $user = Auth::user();

        $hasAccess = $this->checkTestAccess($user, $test);

        $existingAttempt = TestAttempt::where('mock_test_id', $test->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        $completedAttempt = TestAttempt::where('mock_test_id', $test->id)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest('submitted_at')
            ->first();

        return view('student.tests.instructions', compact('test', 'totalQuestions', 'existingAttempt', 'completedAttempt', 'hasAccess'));
    }

    public function start(MockTest $test)
    {
        $user = Auth::user();

        // Enforce server-side entitlement
        if (!$this->checkTestAccess($user, $test)) {
            return redirect()->route('student.checkout.test', $test->id)
                ->with('warning', 'This is a premium CBT test series. Please unlock access to begin your attempt.');
        }

        // Look for an existing in-progress attempt
        $attempt = TestAttempt::where('mock_test_id', $test->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$attempt) {
            $test->load('questions');
            $attempt = TestAttempt::create([
                'mock_test_id' => $test->id,
                'user_id' => $user->id,
                'started_at' => now(),
                'status' => 'in_progress',
                'total_questions' => $test->questions->count(),
            ]);

            // Initialize answer records
            foreach ($test->questions as $question) {
                TestAttemptAnswer::create([
                    'test_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'selected_option' => null,
                    'is_marked_for_review' => false,
                ]);
            }
        }

        return redirect()->route('student.tests.attempt', $attempt->id);
    }

    public function attempt(TestAttempt $attempt)
    {
        // Enforce ownership
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to test attempt.');
        }

        if ($attempt->status === 'completed') {
            return redirect()->route('student.tests.result', $attempt->id)
                ->with('info', 'This examination has already been submitted and scored.');
        }

        $test = $attempt->mockTest()->with('questions')->first();

        // Server-side strict integer countdown calculation
        $durationSeconds = (int) ($test->duration_minutes * 60);
        $elapsedSeconds = (int) $attempt->started_at->diffInSeconds(now());
        $remainingSeconds = max(0, $durationSeconds - $elapsedSeconds);

        if ($remainingSeconds <= 0) {
            return $this->processSubmission($attempt);
        }

        $endTimestampMs = $attempt->started_at->copy()->addSeconds($durationSeconds)->getTimestamp() * 1000;

        $answers = $attempt->answers()->pluck('selected_option', 'question_id')->toArray();
        $reviewFlags = $attempt->answers()->pluck('is_marked_for_review', 'question_id')->toArray();

        return view('student.tests.attempt', compact('attempt', 'test', 'remainingSeconds', 'endTimestampMs', 'answers', 'reviewFlags'));
    }

    public function saveAnswer(TestAttempt $attempt, Request $request)
    {
        if ($attempt->user_id !== Auth::id() || $attempt->status !== 'in_progress') {
            return response()->json(['error' => 'Unauthorized or test finished'], 403);
        }

        $validated = $request->validate([
            'question_id' => ['required', 'exists:questions,id'],
            'selected_option' => ['nullable', 'string'],
            'is_marked_for_review' => ['nullable', 'boolean'],
        ]);

        $selectedOption = null;
        if (!empty($validated['selected_option'])) {
            $opt = strtoupper(trim((string) $validated['selected_option']));
            if (in_array($opt, ['A', 'B', 'C', 'D'])) {
                $selectedOption = $opt;
            }
        }

        $attemptAnswer = TestAttemptAnswer::firstOrNew([
            'test_attempt_id' => $attempt->id,
            'question_id' => $validated['question_id'],
        ]);

        $attemptAnswer->selected_option = $selectedOption;

        if (array_key_exists('is_marked_for_review', $validated)) {
            $attemptAnswer->is_marked_for_review = (bool) $validated['is_marked_for_review'];
        }

        $attemptAnswer->save();

        return response()->json([
            'success' => true,
            'selected_option' => $selectedOption,
            'is_marked_for_review' => $attemptAnswer->is_marked_for_review,
        ]);
    }

    public function submit(Request $request, TestAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        if ($attempt->status === 'completed') {
            return redirect()->route('student.tests.result', $attempt->id);
        }

        // Resilient payload synchronization: process any client-side answers submitted with the form
        if ($request->filled('client_answers')) {
            $clientAnswers = json_decode($request->input('client_answers'), true);
            if (is_array($clientAnswers)) {
                foreach ($clientAnswers as $qId => $opt) {
                    if (!empty($opt)) {
                        $cleanOpt = strtoupper(trim((string) $opt));
                        if (in_array($cleanOpt, ['A', 'B', 'C', 'D'])) {
                            TestAttemptAnswer::updateOrCreate(
                                ['test_attempt_id' => $attempt->id, 'question_id' => (int) $qId],
                                ['selected_option' => $cleanOpt]
                            );
                        }
                    }
                }
            }
        }

        if ($request->filled('client_reviews')) {
            $clientReviews = json_decode($request->input('client_reviews'), true);
            if (is_array($clientReviews)) {
                foreach ($clientReviews as $qId => $isRev) {
                    TestAttemptAnswer::updateOrCreate(
                        ['test_attempt_id' => $attempt->id, 'question_id' => (int) $qId],
                        ['is_marked_for_review' => (bool) $isRev]
                    );
                }
            }
        }

        return $this->processSubmission($attempt);
    }

    private function processSubmission(TestAttempt $attempt)
    {
        $test = $attempt->mockTest;

        // Fetch all questions for the mock test in the authoritative examination sequence
        $questions = $test->questions()
            ->withPivot('sort_order')
            ->orderBy('mock_test_questions.sort_order')
            ->get();

        $existingAnswers = $attempt->answers()->get()->keyBy('question_id');

        $correctCount = 0;
        $incorrectCount = 0;
        $unansweredCount = 0;
        $totalScore = 0.00;

        foreach ($questions as $question) {
            $ans = $existingAnswers->get($question->id);
            if (!$ans) {
                $ans = new TestAttemptAnswer([
                    'test_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'selected_option' => null,
                    'is_marked_for_review' => false,
                ]);
            }

            // Accurate question marking scheme
            $positiveMarks = (float) ($question->marks > 0 ? $question->marks : 1.00);

            if ($question->negative_marks !== null && $question->negative_marks >= 0) {
                $negativeMarks = (float) $question->negative_marks;
            } elseif ($test->negative_marking_ratio !== null && $test->negative_marking_ratio > 0) {
                $negativeMarks = round($positiveMarks * (float) $test->negative_marking_ratio, 2);
            } else {
                $negativeMarks = 0.00;
            }

            $selected = strtoupper(trim((string) $ans->selected_option));
            $correct = strtoupper(trim((string) $question->correct_option));

            if ($selected === '' || !in_array($selected, ['A', 'B', 'C', 'D'])) {
                // Unanswered response
                $unansweredCount++;
                $ans->selected_option = null;
                $ans->is_correct = null;
                $ans->marks_obtained = 0.00;
            } elseif ($selected === $correct) {
                // Correct response
                $correctCount++;
                $ans->selected_option = $selected;
                $ans->is_correct = true;
                $ans->marks_obtained = round($positiveMarks, 2);
                $totalScore += $positiveMarks;
            } else {
                // Incorrect response
                $incorrectCount++;
                $ans->selected_option = $selected;
                $ans->is_correct = false;
                $ans->marks_obtained = -round($negativeMarks, 2);
                $totalScore -= $negativeMarks;
            }

            $ans->save();
        }

        $totalQuestions = $questions->count();
        $totalAttempted = $correctCount + $incorrectCount;
        $accuracy = $totalAttempted > 0 ? round(($correctCount / $totalAttempted) * 100, 2) : 0.00;

        $durationSeconds = (int) ($test->duration_minutes * 60);
        $timeTaken = min($durationSeconds, max(1, (int) floor($attempt->started_at->diffInSeconds(now()))));

        // Precision net examination score
        $finalScore = round($totalScore, 2);

        $attempt->update([
            'status' => 'completed',
            'submitted_at' => now(),
            'time_taken_seconds' => $timeTaken,
            'score' => $finalScore,
            'total_questions' => $totalQuestions,
            'correct_count' => $correctCount,
            'incorrect_count' => $incorrectCount,
            'unanswered_count' => $unansweredCount,
            'accuracy_percentage' => $accuracy,
        ]);

        return redirect()->route('student.tests.result', $attempt->id)
            ->with('success', 'Your test has been successfully submitted and scored!');
    }

    public function result(TestAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $attempt->load(['mockTest.examCategory']);
        $test = $attempt->mockTest;

        $questions = $test->questions()
            ->withPivot('sort_order')
            ->orderBy('mock_test_questions.sort_order')
            ->get();

        $answers = $attempt->answers()
            ->with('question')
            ->get()
            ->keyBy('question_id');

        $reviewItems = $questions->map(function ($q) use ($answers) {
            return [
                'question' => $q,
                'answer' => $answers->get($q->id),
            ];
        });

        $totalMarksPossible = (float) ($questions->sum('marks') > 0 ? $questions->sum('marks') : $test->total_marks);
        $isQualified = (float) $attempt->score >= (float) $test->pass_marks;

        return view('student.tests.result', compact('attempt', 'test', 'reviewItems', 'totalMarksPossible', 'isQualified'));
    }

    private function checkTestAccess($user, MockTest $test): bool
    {
        if ($test->is_free) {
            return true;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($test->exam_category_id) {
            $enrolled = Enrollment::where('user_id', $user->id)
                ->where('status', 'active')
                ->whereHas('course', function ($q) use ($test) {
                    $q->where('exam_category_id', $test->exam_category_id);
                })->exists();

            if ($enrolled) {
                return true;
            }
        }

        return Order::where('user_id', $user->id)
            ->where('mock_test_id', $test->id)
            ->where('status', 'paid')
            ->exists();
    }
}
