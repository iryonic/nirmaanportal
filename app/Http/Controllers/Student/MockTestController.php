<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\MockTest;
use App\Models\Question;
use App\Models\TestAttempt;
use App\Models\TestAttemptAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MockTestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tests = MockTest::where('is_published', true)
            ->with(['examCategory', 'questions'])
            ->withCount('questions')
            ->get();

        $attempts = TestAttempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('mockTest')
            ->latest('submitted_at')
            ->get();

        return view('student.tests.index', compact('tests', 'attempts'));
    }

    public function instructions(MockTest $test)
    {
        $test->load(['examCategory', 'questions']);
        $totalQuestions = $test->questions->count();

        // Check if there's an ongoing attempt
        $existingAttempt = TestAttempt::where('mock_test_id', $test->id)
            ->where('user_id', Auth::id())
            ->where('status', 'in_progress')
            ->first();

        return view('student.tests.instructions', compact('test', 'totalQuestions', 'existingAttempt'));
    }

    public function start(MockTest $test)
    {
        $user = Auth::user();

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

            // Initialize answers records
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
                ->with('info', 'This mock test has already been submitted.');
        }

        $test = $attempt->mockTest()->with('questions')->first();

        // Server-side timer calculation
        $durationSeconds = $test->duration_minutes * 60;
        $elapsedSeconds = now()->diffInSeconds($attempt->started_at);
        $remainingSeconds = max(0, $durationSeconds - $elapsedSeconds);

        if ($remainingSeconds <= 0) {
            // Auto-submit if expired
            return $this->processSubmission($attempt);
        }

        $answers = $attempt->answers()->pluck('selected_option', 'question_id')->toArray();
        $reviewFlags = $attempt->answers()->pluck('is_marked_for_review', 'question_id')->toArray();

        return view('student.tests.attempt', compact('attempt', 'test', 'remainingSeconds', 'answers', 'reviewFlags'));
    }

    public function saveAnswer(TestAttempt $attempt, Request $request)
    {
        if ($attempt->user_id !== Auth::id() || $attempt->status !== 'in_progress') {
            return response()->json(['error' => 'Unauthorized or test finished'], 403);
        }

        $validated = $request->validate([
            'question_id' => ['required', 'exists:questions,id'],
            'selected_option' => ['nullable', 'in:A,B,C,D'],
            'is_marked_for_review' => ['nullable', 'boolean'],
        ]);

        $attemptAnswer = TestAttemptAnswer::firstOrNew([
            'test_attempt_id' => $attempt->id,
            'question_id' => $validated['question_id'],
        ]);

        if (array_key_exists('selected_option', $validated)) {
            $attemptAnswer->selected_option = $validated['selected_option'];
        }

        if (isset($validated['is_marked_for_review'])) {
            $attemptAnswer->is_marked_for_review = $validated['is_marked_for_review'];
        }

        $attemptAnswer->save();

        return response()->json(['success' => true]);
    }

    public function submit(TestAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        if ($attempt->status === 'completed') {
            return redirect()->route('student.tests.result', $attempt->id);
        }

        return $this->processSubmission($attempt);
    }

    private function processSubmission(TestAttempt $attempt)
    {
        $test = $attempt->mockTest;
        $answers = $attempt->answers()->with('question')->get();

        $correctCount = 0;
        $incorrectCount = 0;
        $unansweredCount = 0;
        $totalScore = 0.00;

        foreach ($answers as $ans) {
            $question = $ans->question;
            if (empty($ans->selected_option)) {
                $unansweredCount++;
                $ans->is_correct = null;
                $ans->marks_obtained = 0.00;
            } elseif (strtoupper($ans->selected_option) === strtoupper($question->correct_option)) {
                $correctCount++;
                $ans->is_correct = true;
                $ans->marks_obtained = $question->marks;
                $totalScore += (float) $question->marks;
            } else {
                $incorrectCount++;
                $ans->is_correct = false;
                $negative = (float) $question->negative_marks;
                $ans->marks_obtained = -$negative;
                $totalScore -= $negative;
            }
            $ans->save();
        }

        $totalQuestions = $answers->count();
        $totalAttempted = $correctCount + $incorrectCount;
        $accuracy = $totalAttempted > 0 ? round(($correctCount / $totalAttempted) * 100, 2) : 0;
        $timeTaken = min($test->duration_minutes * 60, now()->diffInSeconds($attempt->started_at));

        $attempt->update([
            'status' => 'completed',
            'submitted_at' => now(),
            'time_taken_seconds' => $timeTaken,
            'score' => max(0, round($totalScore, 2)),
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

        $attempt->load(['mockTest.examCategory', 'answers.question']);

        return view('student.tests.result', compact('attempt'));
    }
}
