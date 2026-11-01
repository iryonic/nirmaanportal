<?php

namespace App\Http\Controllers;

use App\Models\ExamCategory;
use App\Models\MockTest;
use App\Models\StudyMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->get('category');
        $type = $request->get('type');
        $search = trim($request->get('q', ''));

        $query = StudyMaterial::query()->with('examCategory');

        // Only show published resources on public portal unless admin is logged in
        if (!auth()->check() || !auth()->user()->is_admin) {
            $query->where('is_published', true);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($categorySlug) && $categorySlug !== 'all') {
            $query->whereHas('examCategory', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if (!empty($type) && $type !== 'all') {
            $query->where('type', $type);
        }

        // Order featured items first, then latest
        $materials = $query->orderByDesc('is_featured')
            ->latest()
            ->paginate(9)
            ->withQueryString();

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
        // Enforce access control for draft items
        if (!$material->is_published && (!auth()->check() || !auth()->user()->is_admin)) {
            abort(404);
        }

        $material->increment('downloads_count');

        // 1. If physical file exists in storage, download it
        if ($material->hasFile()) {
            $fullPath = Storage::disk('public')->path($material->file_path);
            $ext = pathinfo($material->file_path, PATHINFO_EXTENSION);
            $downloadName = $material->file_name ?: (Str::slug($material->title) . ($ext ? ".{$ext}" : '.pdf'));
            
            return response()->download($fullPath, $downloadName);
        }

        // 2. If external resource URL is configured, redirect to it
        if (!empty($material->external_url)) {
            return redirect()->away($material->external_url);
        }

        // 3. Fallback: stream generated official summary document
        return response()->streamDownload(function () use ($material) {
            echo "NIRMAAN DEFENCE ACADEMY - OFFICIAL STUDY MATERIAL\n";
            echo "================================================\n\n";
            echo "Title: " . $material->title . "\n";
            echo "Type: " . strtoupper($material->typeName) . "\n";
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
