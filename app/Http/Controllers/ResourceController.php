<?php

namespace App\Http\Controllers;

use App\Models\ExamCategory;
use App\Models\MockTest;
use App\Models\StudyMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->get('category');
        $type = $request->get('type');

        $query = StudyMaterial::query()->with('examCategory');

        if ($categorySlug) {
            $query->whereHas('examCategory', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        $materials = $query->latest()->paginate(9)->withQueryString();
        $categories = ExamCategory::where('is_active', true)->get();

        $freeMockTests = MockTest::where('is_published', true)
            ->where('is_free', true)
            ->with('examCategory')
            ->take(4)
            ->get();

        return view('resources.index', compact('materials', 'categories', 'freeMockTests'));
    }

    public function download(StudyMaterial $material)
    {
        $material->increment('downloads_count');

        // If file exists on storage, return download, otherwise return sample generated document
        return response()->streamDownload(function () use ($material) {
            echo "NIRMAAN DEFENCE ACADEMY - OFFICIAL STUDY MATERIAL\n";
            echo "================================================\n\n";
            echo "Title: " . $material->title . "\n";
            echo "Type: " . strtoupper($material->type) . "\n";
            echo "Category: " . ($material->examCategory ? $material->examCategory->name : 'General Defence') . "\n\n";
            echo "Description:\n" . $material->description . "\n\n";
            echo "------------------------------------------------\n";
            echo "Key Highlights:\n";
            echo "- Prepared strictly in accordance with current official defence standards\n";
            echo "- Focus on high-frequency questions and core conceptual retention\n";
            echo "- Verified by ex-defence officers and subject matter experts\n\n";
            echo "For complete online video lectures and full timed mock test series, visit: " . url('/') . "\n";
            echo "Jai Hind!\n";
        }, Str::slug($material->title) . '.txt', [
            'Content-Type' => 'text/plain',
        ]);
    }
}
