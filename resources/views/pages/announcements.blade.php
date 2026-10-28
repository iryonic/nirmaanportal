@extends('layouts.app')

@section('title', 'Exam Notifications & Announcements — Nirmaan')
@section('meta_description', 'Latest defence exams notifications, recruitment alerts, CBT mock schedules, admit card updates, and official guidelines from Nirmaan.')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Banner -->
        <div class="text-center space-y-2.5 max-w-2xl mx-auto">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 text-blue-950 border border-blue-200 text-xs font-bold">
                <span class="w-2 h-2 rounded-full bg-blue-950 animate-pulse"></span>
                <span class="uppercase tracking-wider">OFFICIAL NOTICE REPOSITORY</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Announcements &amp; Exam Alerts
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                Stay updated on active applications, admit card releases, rally schedules, and examination date notifications across Indian Armed Forces.
            </p>
        </div>

        <!-- Filter & Search Card -->
        <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-2xs space-y-4">
            <form action="{{ route('announcements') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-grow w-full">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="q" placeholder="Search notices by keyword, post, or wing..." value="{{ request('q') }}" 
                           class="w-full pl-9 pr-4 py-2 text-xs sm:text-sm bg-white border border-slate-200 rounded-lg outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 text-slate-900 placeholder:text-slate-400 transition font-medium">
                </div>

                <div class="flex items-center space-x-2 w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition shadow-2xs">
                        Search Notices
                    </button>
                    @if(request()->hasAny(['q', 'category']))
                        <a href="{{ route('announcements') }}" class="px-3 py-2 rounded-lg text-xs font-semibold text-slate-500 hover:text-rose-600 transition whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <!-- Category Pills Bar -->
            @if(isset($categories) && $categories->count() > 0)
                <div class="flex items-center space-x-2 overflow-x-auto pb-1 no-scrollbar text-xs">
                    <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider shrink-0 mr-1">Categories:</span>
                    <a href="{{ route('announcements', request()->except('category')) }}" 
                       class="px-3 py-1 rounded-lg font-semibold transition shrink-0 border {{ !request('category') || request('category') === 'all' ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                        All ({{ $notifications->total() }})
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('announcements', array_merge(request()->query(), ['category' => $category])) }}" 
                           class="px-3 py-1 rounded-lg font-semibold transition shrink-0 border {{ request('category') === $category ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                            {{ $category }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Notices Listing -->
        <div class="space-y-4">
            @if(isset($notifications) && $notifications->count() > 0)
                @foreach($notifications as $notice)
                    <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200 shadow-2xs hover:border-slate-300 transition group">
                        
                        <!-- Top Metadata Row: NEW Badge, Category, Date -->
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2.5">
                            <div class="flex flex-wrap items-center gap-2">
                                @if($notice->is_new)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-rose-600 text-white shadow-2xs animate-pulse">
                                        NEW
                                    </span>
                                @endif

                                @if($notice->is_pinned)
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                        <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                        <span>PINNED</span>
                                    </span>
                                @endif

                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-blue-950 border border-blue-100">
                                    {{ $notice->category }}
                                </span>
                            </div>

                            <span class="inline-flex items-center space-x-1 text-xs text-slate-400 font-medium">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Published {{ $notice->formatted_date }}</span>
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-blue-950 transition leading-snug mb-2">
                            <a href="{{ $notice->action_url }}" 
                               @if($notice->opens_in_new_tab) target="_blank" rel="noopener noreferrer" @endif
                               class="focus:underline">
                                {{ $notice->title }}
                            </a>
                        </h3>

                        <!-- Description -->
                        @if($notice->description)
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                                {{ $notice->description }}
                            </p>
                        @endif

                        <!-- Detailed Bulletin Body if present -->
                        @if($notice->content)
                            <div class="my-3 p-3.5 bg-slate-50 rounded-lg text-xs text-slate-700 leading-relaxed border border-slate-100">
                                {!! nl2br(e($notice->content)) !!}
                            </div>
                        @endif

                        <!-- Footer Action Bar -->
                        <div class="pt-3.5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                            <div class="text-[11px] text-slate-400 flex items-center space-x-2">
                                <span>Official Bulletin #{{ $notice->id }}</span>
                                @if($notice->views_count > 0)
                                    <span>&bull;</span>
                                    <span>{{ number_format($notice->views_count) }} Views</span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-3">
                                @if($notice->attachment_path)
                                    <a href="{{ route('notifications.download', $notice->id) }}" 
                                       target="_blank"
                                       class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        <span>Download Document</span>
                                        @if($notice->attachment_size)
                                            <span class="text-[10px] text-rose-500">({{ $notice->attachment_size }})</span>
                                        @endif
                                    </a>
                                @endif

                                @if($notice->external_url)
                                    <a href="{{ $notice->external_url }}" 
                                       @if($notice->opens_in_new_tab) target="_blank" rel="noopener noreferrer" @endif
                                       class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg font-semibold text-blue-950 bg-blue-50 hover:bg-blue-100 border border-blue-200 transition">
                                        <span>Open Portal ↗</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach

                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="pt-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl p-12 text-center border border-slate-200 shadow-2xs">
                    <div class="w-12 h-12 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">No Announcements Found</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">There are no notices matching your query at this moment. Please check back soon or clear search filters.</p>
                    <div class="mt-4">
                        <a href="{{ route('announcements') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold text-blue-950 bg-blue-50 hover:bg-blue-100 transition border border-blue-200">
                            View All Notices
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
