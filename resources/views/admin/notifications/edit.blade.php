@extends('layouts.admin')

@section('title', 'Edit Notice — Admin Command')
@section('header_title', 'Edit Notification')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs">
        <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.notifications.index') }}" class="hover:text-blue-950">Notice Board</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold truncate max-w-xs">Edit #{{ $notification->id }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ $notification->action_url }}" target="_blank" class="px-2.5 py-1 rounded-md bg-white hover:bg-slate-50 text-xs font-medium text-blue-950 border border-slate-200 shadow-2xs transition-colors">
                Live Target ↗
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                &larr; Back
            </a>
        </div>
    </div>

    <!-- Live Status Banner -->
    <div class="rounded-xl p-4 border {{ $notification->is_new ? 'bg-rose-50/70 border-rose-200' : ($notification->is_published ? 'bg-emerald-50/70 border-emerald-200' : 'bg-slate-50 border-slate-200') }}">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center space-x-3">
                @if($notification->is_new)
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-rose-600 text-white shadow-2xs">
                        NEW BADGE ACTIVE
                    </span>
                    <div>
                        <span class="text-xs font-semibold text-rose-950 block">
                            {{ $notification->new_badge_remaining_hours }} Hours Remaining
                        </span>
                        <span class="text-[11px] text-rose-700 block">
                            Published {{ $notification->published_at->format('M d, Y h:i A') }} (Expires {{ $notification->published_at->addHours(72)->format('M d, Y h:i A') }})
                        </span>
                    </div>
                @elseif($notification->is_published)
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Live Published
                    </span>
                    <div>
                        <span class="text-xs font-semibold text-emerald-950 block">
                            NEW Badge Expired (&gt;72 Hours)
                        </span>
                        <span class="text-[11px] text-emerald-700 block">
                            Published {{ $notification->published_at->format('M d, Y') }} &bull; Views: {{ $notification->views_count }}
                        </span>
                    </div>
                @else
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-200 text-slate-700">
                        Draft Mode
                    </span>
                    <div>
                        <span class="text-xs font-semibold text-slate-800 block">Hidden from Public</span>
                        <span class="text-[11px] text-slate-500 block">Publishing will start a fresh 72-hour NEW badge countdown.</span>
                    </div>
                @endif
            </div>

            <!-- Quick Toggle Status Button -->
            <form action="{{ route('admin.notifications.toggle', $notification->id) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors shadow-2xs {{ $notification->is_published ? 'bg-amber-100 text-amber-900 hover:bg-amber-200' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}">
                    {{ $notification->is_published ? 'Unpublish to Draft' : 'Publish Live Now' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.notifications.update', $notification->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Section 1: Details -->
            <div class="space-y-4">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">
                    1. Notice Details &amp; Category
                </h3>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Notice Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required value="{{ old('title', $notification->title) }}" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    @error('title')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Category / Defence Wing <span class="text-red-500">*</span>
                        </label>
                        <div x-data="{ customCategory: false, selectedCategory: '{{ old('category', $notification->category) }}' }">
                            <select name="category_select" 
                                    x-show="!customCategory"
                                    x-model="selectedCategory"
                                    @change="if (selectedCategory === '__custom__') { customCategory = true; selectedCategory = ''; }"
                                    class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ $notification->category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                                <option value="__custom__">+ Enter Custom Category...</option>
                            </select>

                            <div x-show="customCategory" class="relative mt-2" style="display: none;">
                                <input type="text" 
                                       placeholder="Type custom category name..." 
                                       @input="selectedCategory = $event.target.value"
                                       class="w-full text-xs sm:text-sm bg-white border border-blue-950 rounded-lg px-3.5 py-2 pr-20 outline-none shadow-2xs">
                                <button type="button" 
                                        @click="customCategory = false; selectedCategory = '{{ $notification->category }}'"
                                        class="absolute right-1.5 top-1.5 px-2 py-0.5 text-[10px] font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded border border-slate-200">
                                    Cancel
                                </button>
                            </div>
                            <input type="hidden" name="category" :value="selectedCategory">
                        </div>
                        @error('category')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Display Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $notification->sort_order) }}" min="0" 
                               class="w-full text-xs sm:text-sm font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Priority index (lower numbers display higher).</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Brief Summary / Excerpt</label>
                    <textarea name="description" rows="2" 
                              class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ old('description', $notification->description) }}</textarea>
                </div>
            </div>

            <!-- Section 2: Destination & Document Attachment -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">
                    2. Destination Links &amp; PDF Attachment
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">External Destination URL</label>
                        <input type="url" name="external_url" value="{{ old('external_url', $notification->external_url) }}" 
                               placeholder="https://joinindianarmy.nic.in" 
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        @error('external_url')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Replace Attachment</label>
                        <input type="file" name="attachment" accept=".pdf,.doc,.docx,.zip" 
                               class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200 bg-white border border-slate-200 rounded-lg cursor-pointer p-1 shadow-2xs">
                        @if($notification->attachment_name)
                            <div class="flex items-center space-x-1.5 mt-1.5 text-xs text-slate-600">
                                <span class="font-medium text-slate-700">Current:</span>
                                <a href="{{ route('notifications.download', $notification->id) }}" target="_blank" class="text-blue-950 underline truncate max-w-xs font-mono text-[11px]">
                                    {{ $notification->attachment_name }} ({{ $notification->attachment_size }})
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Optional Detailed Bulletin Content</label>
                    <textarea name="content" rows="3" 
                              class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ old('content', $notification->content) }}</textarea>
                </div>
            </div>

            <!-- Section 3: Publication & 72-Hour NEW Badge Logic -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">
                    3. Publication &amp; 72-Hour &quot;NEW&quot; Badge Control
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Publication Status</label>
                        <select name="is_published" 
                                class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                            <option value="1" {{ old('is_published', $notification->is_published ? '1' : '0') == '1' ? 'selected' : '' }}>
                                Live Published (Visible to Public)
                            </option>
                            <option value="0" {{ old('is_published', $notification->is_published ? '1' : '0') == '0' ? 'selected' : '' }}>
                                Draft Notice (Hidden from Public)
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Publication Timestamp
                        </label>
                        <input type="datetime-local" name="published_at" 
                               value="{{ old('published_at', $notification->published_at ? $notification->published_at->format('Y-m-d\TH:i') : '') }}" 
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Preserves original published date so countdown remains accurate.</span>
                    </div>
                </div>

                <!-- Checkboxes: Pin & Open in Tab -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 pt-1">
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned', $notification->is_pinned) ? 'checked' : '' }} 
                               class="rounded text-blue-950 focus:ring-blue-950">
                        <span class="text-xs font-semibold text-slate-700">Pin Notice to Top of Homepage List</span>
                    </label>

                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="opens_in_new_tab" value="1" {{ old('opens_in_new_tab', $notification->opens_in_new_tab) ? 'checked' : '' }} 
                               class="rounded text-blue-950 focus:ring-blue-950">
                        <span class="text-xs font-semibold text-slate-700">Open External Links in New Tab</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="button" 
                        onclick="if(confirm('Delete this notification permanently?')) { document.getElementById('delete-form').submit(); }"
                        class="text-xs font-medium text-red-600 hover:text-red-800 transition-colors">
                    &times; Delete Notice
                </button>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.notifications.index') }}" 
                       class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                        Update Notice Changes
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-form" action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

</div>
@endsection
