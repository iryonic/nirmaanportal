<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamCategory;
use App\Models\StudyMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResourceManagementController extends Controller
{
    /**
     * Display a searchable, filterable repository of all study resources.
     */
    public function index(Request $request)
    {
        $query = StudyMaterial::query()->with(['examCategory', 'creator']);

        // 1. Text Keyword Search
        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        // 2. Exam Category Filter
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('exam_category_id', $request->category_id);
        }

        // 3. Resource Type Filter
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // 4. Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            switch ($request->status) {
                case 'published':
                    $query->where('is_published', true);
                    break;
                case 'draft':
                    $query->where('is_published', false);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'with_file':
                    $query->whereNotNull('file_path');
                    break;
                case 'external':
                    $query->whereNotNull('external_url');
                    break;
            }
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'downloads':
                $query->orderByDesc('downloads_count');
                break;
            case 'title':
                $query->orderBy('title');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'latest':
            default:
                $query->orderByDesc('is_featured')->latest();
                break;
        }

        // Executive Statistics
        $totalCount = StudyMaterial::count();
        $publishedCount = StudyMaterial::where('is_published', true)->count();
        $draftCount = StudyMaterial::where('is_published', false)->count();
        $featuredCount = StudyMaterial::where('is_featured', true)->count();
        $totalDownloads = (int) StudyMaterial::sum('downloads_count');

        $resources = $query->paginate(12)->withQueryString();
        $categories = ExamCategory::where('is_active', true)->orderBy('name')->get();
        $types = StudyMaterial::getTypes();

        return view('admin.resources.index', compact(
            'resources',
            'categories',
            'types',
            'totalCount',
            'publishedCount',
            'draftCount',
            'featuredCount',
            'totalDownloads'
        ));
    }

    /**
     * Show form to create a new study resource.
     */
    public function create()
    {
        $categories = ExamCategory::where('is_active', true)->orderBy('name')->get();
        $types = StudyMaterial::getTypes();

        return view('admin.resources.create', compact('categories', 'types'));
    }

    /**
     * Store a newly created study resource with file upload.
     */
    public function store(Request $request)
    {
        $types = array_keys(StudyMaterial::getTypes());

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'exam_category_id' => ['nullable', 'exists:exam_categories,id'],
            'type' => ['required', 'string', 'in:' . implode(',', $types)],
            'description' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,txt', 'max:51200'], // 50MB
            'external_url' => ['nullable', 'url', 'max:500'],
            'manual_file_size' => ['nullable', 'string', 'max:50'],
            'is_free' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        // File handling
        $filePath = null;
        $fileName = null;
        $fileSize = $request->input('manual_file_size');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('resources', 'public');
            $fileName = $file->getClientOriginalName();
            $fileSize = $this->formatFileSize($file->getSize());
        } elseif (!empty($validated['external_url']) && empty($fileSize)) {
            $fileSize = 'Cloud / Link';
        }

        $slug = $this->generateUniqueSlug($validated['title']);

        $material = StudyMaterial::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'exam_category_id' => $validated['exam_category_id'] ?? null,
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize ?: 'PDF',
            'external_url' => $validated['external_url'] ?? null,
            'is_free' => $request->boolean('is_free', true),
            'is_published' => $request->boolean('is_published', true),
            'is_featured' => $request->boolean('is_featured', false),
            'downloads_count' => 0,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.resources.index')
            ->with('success', 'Study Resource "' . $material->title . '" created successfully.');
    }

    /**
     * Show form to edit an existing study resource.
     */
    public function edit(StudyMaterial $resource)
    {
        $categories = ExamCategory::where('is_active', true)->orderBy('name')->get();
        $types = StudyMaterial::getTypes();

        return view('admin.resources.edit', [
            'material' => $resource,
            'categories' => $categories,
            'types' => $types,
        ]);
    }

    /**
     * Update an existing study resource.
     */
    public function update(Request $request, StudyMaterial $resource)
    {
        $types = array_keys(StudyMaterial::getTypes());

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'exam_category_id' => ['nullable', 'exists:exam_categories,id'],
            'type' => ['required', 'string', 'in:' . implode(',', $types)],
            'description' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,txt', 'max:51200'],
            'remove_file' => ['nullable', 'boolean'],
            'external_url' => ['nullable', 'url', 'max:500'],
            'manual_file_size' => ['nullable', 'string', 'max:50'],
            'is_free' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $filePath = $resource->file_path;
        $fileName = $resource->file_name;
        $fileSize = $resource->file_size;

        // Check if user requested to remove existing file
        if ($request->boolean('remove_file')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = null;
            $fileName = null;
            $fileSize = $request->input('manual_file_size') ?: null;
        }

        // New file upload replaces old file
        if ($request->hasFile('file')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $file = $request->file('file');
            $filePath = $file->store('resources', 'public');
            $fileName = $file->getClientOriginalName();
            $fileSize = $this->formatFileSize($file->getSize());
        } elseif ($request->filled('manual_file_size')) {
            $fileSize = $request->input('manual_file_size');
        }

        // Update slug if title changed
        $slug = $resource->slug;
        if ($resource->title !== $validated['title']) {
            $slug = $this->generateUniqueSlug($validated['title'], $resource->id);
        }

        $resource->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'exam_category_id' => $validated['exam_category_id'] ?? null,
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'external_url' => $validated['external_url'] ?? null,
            'is_free' => $request->boolean('is_free', true),
            'is_published' => $request->boolean('is_published', true),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        return redirect()->route('admin.resources.index')
            ->with('success', 'Study Resource "' . $resource->title . '" updated successfully.');
    }

    /**
     * Delete a study resource and clean up stored file.
     */
    public function destroy(StudyMaterial $resource)
    {
        $title = $resource->title;

        if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        return redirect()->route('admin.resources.index')
            ->with('success', 'Study Resource "' . $title . '" has been permanently removed.');
    }

    /**
     * Quick toggle for publish / draft or featured status.
     */
    public function toggleStatus(Request $request, StudyMaterial $resource)
    {
        $field = $request->input('field', 'is_published');

        if ($field === 'is_featured') {
            $resource->update([
                'is_featured' => !$resource->is_featured,
            ]);
            $msg = $resource->is_featured ? 'Marked as Featured Resource.' : 'Removed from Featured Resources.';
        } else {
            $resource->update([
                'is_published' => !$resource->is_published,
            ]);
            $msg = $resource->is_published ? 'Resource published on website.' : 'Resource converted to draft.';
        }

        return back()->with('success', $msg);
    }

    /**
     * Direct verified download for admin verification.
     */
    public function download(StudyMaterial $resource)
    {
        if ($resource->hasFile()) {
            $fullPath = Storage::disk('public')->path($resource->file_path);
            $ext = pathinfo($resource->file_path, PATHINFO_EXTENSION);
            $downloadName = $resource->file_name ?: (Str::slug($resource->title) . ($ext ? ".{$ext}" : '.pdf'));
            
            return response()->download($fullPath, $downloadName);
        }

        if (!empty($resource->external_url)) {
            return redirect()->away($resource->external_url);
        }

        return back()->with('error', 'No physical file or external URL attached to this resource.');
    }

    /**
     * Helper to format bytes into readable format.
     */
    protected function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Generate a unique slug for study materials.
     */
    protected function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 1;

        while (
            StudyMaterial::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $counter++;
            $slug = "{$base}-{$counter}";
        }

        return $slug;
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'exists:study_materials,id'],
            'action' => ['required', 'string', 'in:publish,draft,delete,export'],
        ]);

        $ids = $validated['ids'];
        $count = count($ids);

        switch ($validated['action']) {
            case 'publish':
                StudyMaterial::whereIn('id', $ids)->update(['is_published' => true]);
                return back()->with('success', "{$count} study materials were published successfully.");

            case 'draft':
                StudyMaterial::whereIn('id', $ids)->update(['is_published' => false]);
                return back()->with('success', "{$count} study materials moved to draft.");

            case 'delete':
                StudyMaterial::whereIn('id', $ids)->delete();
                return back()->with('success', "{$count} study materials were deleted successfully.");

            case 'export':
                $materials = StudyMaterial::with('examCategory')->whereIn('id', $ids)->get();
                $filename = 'resources_export_' . now()->format('Ymd_His') . '.csv';

                return response()->streamDownload(function () use ($materials) {
                    $handle = fopen('php://output', 'w');
                    fputcsv($handle, ['ID', 'Title', 'Category', 'Type', 'File Name', 'Downloads', 'Status', 'Created Date']);

                    foreach ($materials as $m) {
                        fputcsv($handle, [
                            $m->id,
                            $m->title,
                            $m->examCategory ? $m->examCategory->name : 'N/A',
                            $m->type,
                            $m->file_name ?? 'N/A',
                            $m->downloads_count ?? 0,
                            $m->is_published ? 'Published' : 'Draft',
                            $m->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                    fclose($handle);
                }, $filename, ['Content-Type' => 'text/csv']);

            default:
                return back()->with('error', 'Unknown bulk action.');
        }
    }
}
