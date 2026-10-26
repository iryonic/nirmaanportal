<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\ExamCategory;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    public function index(Request $request)
    {
        $subjects = ['General Knowledge', 'General Science', 'Mathematics', 'Reasoning', 'English'];
        $difficulties = ['easy' => 'Easy', 'medium' => 'Medium', 'hard' => 'Hard'];

        $selectedSubject = $request->get('subject', 'General Knowledge');
        $selectedDifficulty = $request->get('difficulty', null);

        $query = Question::where('is_active', true);

        if ($selectedSubject) {
            $query->where('subject', $selectedSubject);
        }

        if ($selectedDifficulty) {
            $query->where('difficulty', $selectedDifficulty);
        }

        $questions = $query->paginate(10)->withQueryString();
        $totalAvailable = Question::where('is_active', true)->count();

        return view('student.practice', compact(
            'subjects',
            'difficulties',
            'selectedSubject',
            'selectedDifficulty',
            'questions',
            'totalAvailable'
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
        ]);
    }
}
