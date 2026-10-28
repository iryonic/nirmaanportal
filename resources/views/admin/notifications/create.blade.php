@extends('layouts.admin')

@section('title', 'Post New Notice — Admin Command')
@section('header_title', 'Create Notification')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs">
        <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.notifications.index') }}" class="hover:text-blue-950">Notice Board</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Post Notice</span>
        </div>
        <a href="{{ route('admin.notifications.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            &larr; Back to Notices
        </a>
    </div>

    <!-- Main Creation Form Card -->
    <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Section 1: Notice Identity -->
            <div class="space-y-4">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">
                    1. Notice Details &amp; Category
                </h3>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Notice Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required value="{{ old('title') }}" 
                           placeholder="e.g. Indian Army Agniveer Rally 2027 CEE Examination Schedule &amp; Online Dates" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    @error('title')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Category / Defence Wing <span class="text-red-500">*</span>
                        </label>
                        <div x-data="{ customCategory: false, selectedCategory: '{{ old('category', 'Indian Army') }}' }">
                            <select name="category_select" 
                                    x-show="!customCategory"
                                    x-model="selectedCategory"
                                    @change="if (selectedCategory === '__custom__') { customCategory = true; selectedCategory = ''; }"
                                    class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                                <option value="__custom__">+ Enter Custom Category...</option>
                            </select>

                            <div x-show="customCategory" class="relative mt-2" style="display: none;">
                                <input type="text" 
                                       x-ref="customInput"
                                       placeholder="Type custom category name..." 
                                       @input="selectedCategory = $event.target.value"
                                       class="w-full text-xs sm:text-sm bg-white border border-blue-950 rounded-lg px-3.5 py-2 pr-20 outline-none shadow-2xs">
                                <button type="button" 
                                        @click="customCategory = false; selectedCategory = '{{ $categories[0] ?? 'General' }}'"
                                        class="absolute right-1.5 top-1.5 px-2 py-0.5 text-[10px] font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded border border-slate-200">
                                    Presets
                                </button>
                            </div>
                            <input type="hidden" name="category" :value="selectedCategory">
                        </div>
                        @error('category')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Display Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" 
                               placeholder="0" 
                               class="w-full text-xs sm:text-sm font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Lower numbers display first (priority ordering).</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Brief Summary / Excerpt</label>
                    <textarea name="description" rows="2" 
                              placeholder="Concise 1-2 sentence overview for the homepage list..." 
                              class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ old('description') }}</textarea>
                    @error('description')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
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
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            </span>
                            <input type="url" name="external_url" value="{{ old('external_url') }}" 
                                   placeholder="https://joinindianarmy.nic.in" 
                                   class="w-full pl-8 pr-3 py-2 text-xs sm:text-sm bg-white border border-slate-200 rounded-lg outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        </div>
                        <span class="text-[10px] text-slate-400 mt-0.5 block">If provided, clicking the notice directly opens this official link.</span>
                        @error('external_url')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Upload PDF / Document Attachment</label>
                        <input type="file" name="attachment" accept=".pdf,.doc,.docx,.zip" 
                               class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200 bg-white border border-slate-200 rounded-lg cursor-pointer p-1 shadow-2xs">
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Supported: PDF, Word docs, ZIP (Max: 15 MB).</span>
                        @error('attachment')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Optional Detailed Bulletin Content</label>
                    <textarea name="content" rows="3" 
                              placeholder="Full circular or announcement text for aspirants reading the dedicated notices page..." 
                              class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ old('content') }}</textarea>
                </div>
            </div>

            <!-- Section 3: Publication & 72-Hour NEW Badge Logic -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">
                    3. Publication &amp; 72-Hour &quot;NEW&quot; Badge Automation
                </h3>

                <div class="bg-slate-50/70 rounded-lg p-3.5 border border-slate-200/80 flex items-start space-x-2.5">
                    <span class="w-5 h-5 rounded-md bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">
                        ★
                    </span>
                    <div class="text-xs text-slate-600 leading-relaxed">
                        <span class="font-semibold text-slate-900 block">Automatic 72-Hour &quot;NEW&quot; Badge Countdown</span>
                        Once published, a subtle <strong class="text-rose-600 font-semibold uppercase">NEW</strong> badge will appear beside this notice on the homepage and notices page for exactly <strong>72 hours</strong>. After 3 days, it automatically expires with zero manual maintenance.
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Publication Status</label>
                        <select name="is_published" 
                                class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                            <option value="1" {{ old('is_published', '1') == '1' ? 'selected' : '' }}>
                                Live Published (Visible to Public)
                            </option>
                            <option value="0" {{ old('is_published') === '0' ? 'selected' : '' }}>
                                Draft Notice (Hidden from Public)
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Publication Timestamp (Optional Custom Date)
                        </label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" 
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Leave empty to set to current time when publishing.</span>
                    </div>
                </div>

                <!-- Checkboxes: Pin & Open in Tab -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 pt-1">
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }} 
                               class="rounded text-blue-950 focus:ring-blue-950">
                        <span class="text-xs font-semibold text-slate-700">Pin Notice to Top of Homepage List</span>
                    </label>

                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="opens_in_new_tab" value="1" {{ old('opens_in_new_tab', '1') ? 'checked' : '' }} 
                               class="rounded text-blue-950 focus:ring-blue-950">
                        <span class="text-xs font-semibold text-slate-700">Open External Links in New Tab</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.notifications.index') }}" 
                   class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                    Publish &amp; Save Notice
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
