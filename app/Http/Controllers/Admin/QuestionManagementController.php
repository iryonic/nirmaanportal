<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamCategory;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with('examCategory');

        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $questions = $query->latest()->paginate(15)->withQueryString();
        $categories = ExamCategory::where('is_active', true)->get();

        return view('admin.questions.index', compact('questions', 'categories'));
    }

    public function create()
    {
        $categories = ExamCategory::where('is_active', true)->get();
        return view('admin.questions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_category_id' => ['nullable', 'exists:exam_categories,id'],
            'subject' => ['required', 'string', 'max:100'],
            'topic' => ['nullable', 'string', 'max:100'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'question_text' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_option' => ['required', 'in:A,B,C,D'],
            'explanation' => ['nullable', 'string'],
            'marks' => ['required', 'numeric', 'min:0.5'],
            'negative_marks' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Question::create($validated);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question added to repository successfully.');
    }

    public function edit(Question $question)
    {
        $categories = ExamCategory::where('is_active', true)->get();
        return view('admin.questions.edit', compact('question', 'categories'));
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'exam_category_id' => ['nullable', 'exists:exam_categories,id'],
            'subject' => ['required', 'string', 'max:100'],
            'topic' => ['nullable', 'string', 'max:100'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'question_text' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_option' => ['required', 'in:A,B,C,D'],
            'explanation' => ['nullable', 'string'],
            'marks' => ['required', 'numeric', 'min:0.5'],
            'negative_marks' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $question->update($validated);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Question deleted.');
    }
}
