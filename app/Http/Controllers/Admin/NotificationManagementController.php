<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NotificationManagementController extends Controller
{
    /**
     * Preset categories for defence academy notices.
     */
    protected array $presetCategories = [
        'Indian Army',
        'Indian Air Force',
        'Indian Navy',
        'NDA / CDS',
        'Paramilitary',
        'Admissions',
        'Admit Card',
        'Exam Schedule',
        'Results',
        'General',
    ];

    /**
     * Display a searchable, filterable list of all notifications.
     */
    public function index(Request $request)
    {
        $query = Notification::query();

        // Keyword Search
        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Status Filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'published':
                    $query->where('is_published', true);
                    break;
                case 'draft':
                    $query->where('is_published', false);
                    break;
                case 'new_active':
                    $query->where('is_published', true)
                          ->whereNotNull('published_at')
                          ->where('published_at', '<=', now())
                          ->where('published_at', '>=', now()->subHours(72));
                    break;
                case 'pinned':
                    $query->where('is_pinned', true);
                    break;
            }
        }

        // Statistics
        $totalCount = Notification::count();
        $publishedCount = Notification::where('is_published', true)->count();
        $draftCount = Notification::where('is_published', false)->count();
        $activeNewCount = Notification::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('published_at', '>=', now()->subHours(72))
            ->count();

        // Retrieve paginated records ordered by pinned, then published date / created date
        $notifications = $query->orderByDesc('is_pinned')
            ->orderByRaw('CASE WHEN published_at IS NOT NULL THEN published_at ELSE created_at END DESC')
            ->paginate(12)
            ->withQueryString();

        // Distinct existing categories from DB combined with presets
        $allCategories = collect($this->presetCategories)
            ->merge(Notification::select('category')->distinct()->pluck('category'))
            ->unique()
            ->filter()
            ->values();

        return view('admin.notifications.index', compact(
            'notifications',
            'totalCount',
            'publishedCount',
            'draftCount',
            'activeNewCount',
            'allCategories'
        ));
    }

    /**
     * Show form to create a new notification.
     */
    public function create()
    {
        $categories = $this->presetCategories;
        return view('admin.notifications.create', compact('categories'));
    }

    /**
     * Store a newly created notification.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'external_url' => ['nullable', 'url', 'max:500'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:15360'], // 15MB
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'opens_in_new_tab' => ['nullable', 'boolean'],
            'is_pinned' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $isPublished = $request->boolean('is_published');
        
        // Publication time logic:
        // When published, set published_at to custom date or now. If draft, set null.
        if ($isPublished) {
            $publishedAt = $request->filled('published_at') ? Carbon::parse($request->published_at) : now();
        } else {
            $publishedAt = null;
        }

        $attachmentPath = null;
        $attachmentName = null;
        $attachmentSize = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('notifications', 'public');
            $attachmentName = $file->getClientOriginalName();
            $attachmentSize = $this->formatFileSize($file->getSize());
        }

        Notification::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'content' => $validated['content'] ?? null,
            'external_url' => $validated['external_url'] ?? null,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_size' => $attachmentSize,
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
            'opens_in_new_tab' => $request->boolean('opens_in_new_tab', true),
            'is_pinned' => $request->boolean('is_pinned', false),
            'sort_order' => $validated['sort_order'] ?? 0,
            'created_by' => auth()->id(),
        ]);

        $message = $isPublished
            ? 'Notification published successfully. The "NEW" badge is active for 72 hours.'
            : 'Notification saved as draft.';

        return redirect()->route('admin.notifications.index')->with('success', $message);
    }

    /**
     * Show form to edit an existing notification.
     */
    public function edit(Notification $notification)
    {
        $categories = $this->presetCategories;
        return view('admin.notifications.edit', compact('notification', 'categories'));
    }

    /**
     * Update an existing notification.
     */
    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'external_url' => ['nullable', 'url', 'max:500'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:15360'],
            'remove_attachment' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'opens_in_new_tab' => ['nullable', 'boolean'],
            'is_pinned' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $isPublished = $request->boolean('is_published');
        $wasPublished = (bool) $notification->is_published;

        // 72-HOUR NEW BADGE INTEGRITY LOGIC:
        // 1. If currently published and remains published: editing must NOT reset countdown.
        //    Retain existing published_at unless the admin explicitly entered a custom published_at.
        // 2. If transitioning from draft (or unpublished) to published: countdown starts/restarts from now.
        // 3. If transitioning to draft: published_at becomes null.
        if ($isPublished) {
            if (! $wasPublished) {
                // Was draft/unpublished: publishing starts countdown
                $publishedAt = $request->filled('published_at') ? Carbon::parse($request->published_at) : now();
            } else {
                // Was already published: preserve original published_at unless admin deliberately modified the date input
                if ($request->filled('published_at') && Carbon::parse($request->published_at)->ne($notification->published_at)) {
                    $publishedAt = Carbon::parse($request->published_at);
                } else {
                    $publishedAt = $notification->published_at ?? now();
                }
            }
        } else {
            $publishedAt = null;
        }

        $attachmentPath = $notification->attachment_path;
        $attachmentName = $notification->attachment_name;
        $attachmentSize = $notification->attachment_size;

        // Handle attachment removal request
        if ($request->boolean('remove_attachment') && $attachmentPath) {
            Storage::disk('public')->delete($attachmentPath);
            $attachmentPath = null;
            $attachmentName = null;
            $attachmentSize = null;
        }

        // Handle new attachment upload
        if ($request->hasFile('attachment')) {
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }
            $file = $request->file('attachment');
            $attachmentPath = $file->store('notifications', 'public');
            $attachmentName = $file->getClientOriginalName();
            $attachmentSize = $this->formatFileSize($file->getSize());
        }

        $notification->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'content' => $validated['content'] ?? null,
            'external_url' => $validated['external_url'] ?? null,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_size' => $attachmentSize,
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
            'opens_in_new_tab' => $request->boolean('opens_in_new_tab', true),
            'is_pinned' => $request->boolean('is_pinned', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.notifications.index')->with('success', 'Notification updated successfully.');
    }

    /**
     * Delete a notification and its attachment if present.
     */
    public function destroy(Notification $notification)
    {
        if ($notification->attachment_path) {
            Storage::disk('public')->delete($notification->attachment_path);
        }

        $notification->delete();

        return redirect()->route('admin.notifications.index')->with('success', 'Notification deleted successfully.');
    }

    /**
     * Quick publish / unpublish toggle.
     * When unpublished: state becomes draft.
     * When republished: restarts the 72-hour NEW countdown from current timestamp as requested!
     */
    public function toggleStatus(Notification $notification)
    {
        if ($notification->is_published) {
            $notification->update([
                'is_published' => false,
                'published_at' => null,
            ]);
            $message = 'Notification saved as draft (unpublished).';
        } else {
            // Republishing starts a fresh 72-hour countdown
            $notification->update([
                'is_published' => true,
                'published_at' => now(),
            ]);
            $message = 'Notification published! "NEW" badge is live for 72 hours.';
        }

        return back()->with('success', $message);
    }

    /**
     * Helper to format bytes into readable KB/MB.
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
}
