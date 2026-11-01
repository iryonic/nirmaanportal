<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    public function index(Request $request)
    {
        $subjects = ['General Knowledge', 'General Science', 'Mathematics', 'Reasoning', 'English'];
        $difficulties = ['easy' => 'Easy', 'medium' => 'Medium', 'hard' => 'Hard'];

        $selectedSubject = $request->get('subject', 'all');
        $selectedDifficulty = $request->get('difficulty', null);
        $selectedTopic = $request->get('topic', null);
        $search = trim((string) $request->get('q', ''));
        $perPage = in_array((int) $request->get('per_page', 10), [10, 20, 50]) ? (int) $request->get('per_page', 10) : 10;

        $query = Question::where('is_active', true);

        if ($selectedSubject && $selectedSubject !== 'all') {
            $query->where('subject', $selectedSubject);
        }

        if ($selectedDifficulty && in_array($selectedDifficulty, ['easy', 'medium', 'hard'])) {
            $query->where('difficulty', $selectedDifficulty);
        }

        if ($selectedTopic) {
            $query->where('topic', $selectedTopic);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('question_text', 'like', "%{$search}%")
                  ->orWhere('topic', 'like', "%{$search}%")
                  ->orWhere('explanation', 'like', "%{$search}%");
            });
        }

        // Subject counts
        $subjectCounts = Question::where('is_active', true)
            ->selectRaw('subject, count(*) as count')
            ->groupBy('subject')
            ->pluck('count', 'subject')
            ->toArray();

        // Topics for current subject
        $topicsQuery = Question::where('is_active', true)
            ->whereNotNull('topic')
            ->where('topic', '!=', '');

        if ($selectedSubject && $selectedSubject !== 'all') {
            $topicsQuery->where('subject', $selectedSubject);
        }

        $topics = $topicsQuery->distinct()->pluck('topic')->sort()->values();

        $totalAvailable = Question::where('is_active', true)->count();
        $questions = $query->latest('id')->paginate($perPage)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'total' => $questions->total(),
                'data' => $questions->items(),
            ]);
        }

        return view('student.practice', compact(
            'subjects',
            'difficulties',
            'selectedSubject',
            'selectedDifficulty',
            'selectedTopic',
            'topics',
            'search',
            'perPage',
            'questions',
            'totalAvailable',
            'subjectCounts'
        ));
    }

    public function checkAnswer(Request $request)
    {
        $validated = $request->validate([
            'question_id' => ['required', 'exists:questions,id'],
            'selected_option' => ['required', 'in:A,B,C,D'],
        ]);

        $question = Question::findOrFail($validated['question_id']);
        $isCorrect = strtoupper($validated['selected_option']) === strtoupper($question->correct_option);

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'correct_option' => $question->correct_option,
            'explanation' => $question->explanation,
            'marks' => $question->marks,
            'negative_marks' => $question->negative_marks,
        ]);
    }
}
