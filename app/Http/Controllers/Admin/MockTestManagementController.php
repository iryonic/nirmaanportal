<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamCategory;
use App\Models\MockTest;
use App\Models\Question;
use App\Models\TestAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MockTestManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = MockTest::with('examCategory')->withCount(['questions', 'attempts']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('exam_category_id', $request->category);
        }

        $tests = $query->latest()->paginate(10)->withQueryString();
        $categories = ExamCategory::where('is_active', true)->get();

        return view('admin.tests.index', compact('tests', 'categories'));
    }

    public function create()
    {
        $categories = ExamCategory::where('is_active', true)->get();
        $questions = Question::where('is_active', true)->get();

        return view('admin.tests.create', compact('categories', 'questions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_category_id' => ['nullable', 'exists:exam_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:300'],
            'total_marks' => ['required', 'numeric', 'min:1'],
            'pass_marks' => ['required', 'numeric', 'min:1'],
            'negative_marking_ratio' => ['required', 'numeric', 'min:0', 'max:1'],
            'instructions' => ['nullable', 'string'],
            'question_ids' => ['required', 'array', 'min:1'],
            'question_ids.*' => ['exists:questions,id'],
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['is_free'] = $request->boolean('is_free');
        $validated['is_published'] = $request->boolean('is_published');

        $test = MockTest::create($validated);

        foreach ($request->question_ids as $index => $qId) {
            $test->questions()->attach($qId, ['sort_order' => $index + 1]);
        }

        return redirect()->route('admin.tests.index')
            ->with('success', 'Mock test created with ' . count($request->question_ids) . ' questions.');
    }

    public function edit(MockTest $test)
    {
        $test->load(['questions', 'examCategory']);
        $categories = ExamCategory::where('is_active', true)->get();
        $questions = Question::where('is_active', true)->get();
        $selectedQuestionIds = $test->questions->pluck('id')->toArray();

        return view('admin.tests.edit', compact('test', 'categories', 'questions', 'selectedQuestionIds'));
    }

    public function update(Request $request, MockTest $test)
    {
        $validated = $request->validate([
            'exam_category_id' => ['nullable', 'exists:exam_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:300'],
            'total_marks' => ['required', 'numeric', 'min:1'],
            'pass_marks' => ['required', 'numeric', 'min:1'],
            'negative_marking_ratio' => ['required', 'numeric', 'min:0', 'max:1'],
            'instructions' => ['nullable', 'string'],
            'question_ids' => ['required', 'array', 'min:1'],
            'question_ids.*' => ['exists:questions,id'],
        ]);

        $validated['is_free'] = $request->boolean('is_free');
        $validated['is_published'] = $request->boolean('is_published');

        $test->update($validated);

        // Sync questions with order
        $syncData = [];
        foreach ($request->question_ids as $index => $qId) {
            $syncData[$qId] = ['sort_order' => $index + 1];
        }
        $test->questions()->sync($syncData);

        return redirect()->route('admin.tests.index')
            ->with('success', 'Mock test updated successfully.');
    }

    public function destroy(MockTest $test)
    {
        $test->delete();
        return back()->with('success', 'Mock test deleted.');
    }

    public function attempts(MockTest $test)
    {
        $attempts = TestAttempt::where('mock_test_id', $test->id)
            ->with('user')
            ->latest('submitted_at')
            ->paginate(20);

        return view('admin.tests.attempts', compact('test', 'attempts'));
    }

    public function toggleStatus(MockTest $test)
    {
        $test->is_published = !$test->is_published;
        $test->save();

        $label = $test->is_published ? 'published (live for candidates)' : 'moved to draft';
        return back()->with('success', "Mock test '{$test->title}' has been {$label}.");
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'exists:mock_tests,id'],
            'action' => ['required', 'string', 'in:publish,unpublish,delete,export'],
        ]);

        $ids = $validated['ids'];
        $count = count($ids);

        switch ($validated['action']) {
            case 'publish':
                MockTest::whereIn('id', $ids)->update(['is_published' => true]);
                return back()->with('success', "{$count} mock tests were published successfully.");

            case 'unpublish':
                MockTest::whereIn('id', $ids)->update(['is_published' => false]);
                return back()->with('success', "{$count} mock tests moved to draft.");

            case 'delete':
                MockTest::whereIn('id', $ids)->delete();
                return back()->with('success', "{$count} mock tests were deleted successfully.");

            case 'export':
                $tests = MockTest::with('examCategory')->whereIn('id', $ids)->get();
                $filename = 'tests_export_' . now()->format('Ymd_His') . '.csv';

                return response()->streamDownload(function () use ($tests) {
                    $handle = fopen('php://output', 'w');
                    fputcsv($handle, ['ID', 'Title', 'Category', 'Duration (mins)', 'Total Marks', 'Pass Marks', 'Status', 'Created Date']);

                    foreach ($tests as $t) {
                        fputcsv($handle, [
                            $t->id,
                            $t->title,
                            $t->examCategory ? $t->examCategory->name : 'N/A',
                            $t->duration_minutes,
                            $t->total_marks,
                            $t->pass_marks,
                            $t->is_published ? 'Published' : 'Draft',
                            $t->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                    fclose($handle);
                }, $filename, ['Content-Type' => 'text/csv']);

            default:
                return back()->with('error', 'Unknown bulk action.');
        }
    }
}
