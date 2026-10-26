@extends('layouts.app')

@section('title', '404 — Page Not Found | Nirmaan Defence Academy')

@section('content')
<div class="py-16 sm:py-24 flex items-center justify-center px-4 bg-gray-50/50 min-h-[calc(100vh-200px)]">
    <div class="max-w-xl w-full text-center space-y-6">
        
        <!-- Status Code Pill -->
        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-200 text-blue-800 text-xs font-mono font-bold">
            <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
            <span>HTTP 404 • TARGET RESOURCE UNLOCATED</span>
        </div>

        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Mission Objective Not Found</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-2 max-w-md mx-auto leading-relaxed">
                The defence curriculum module, examination drill, or document you requested may have been decommissioned, relocated, or temporarily restricted.
            </p>
        </div>

        <!-- Quick Search Bar -->
        <form action="{{ route('search') }}" method="GET" class="max-w-md mx-auto">
            <div class="flex gap-2">
                <input type="text" 
                       name="q" 
                       placeholder="Search courses, tests, syllabi (e.g. NDA, Agniveer)..." 
                       class="flex-1 text-xs bg-white border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent shadow-2xs">
                <button type="submit" class="px-5 py-3 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition shadow-2xs">
                    Search
                </button>
            </div>
        </form>

        <!-- Navigation Shortcut Actions -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-2xs text-left max-w-md mx-auto space-y-3">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block">Recommended Safe Routes</span>
            
            <div class="grid grid-cols-2 gap-2 text-xs">
                <a href="{{ route('home') }}" class="p-3 rounded-xl border border-gray-200 hover:border-blue-500 hover:bg-blue-50/30 transition block font-bold text-gray-900">
                    <span class="block text-blue-700 text-[11px] font-mono mb-0.5">01. BASE</span>
                    Home Portal →
                </a>

                <a href="{{ route('courses.index') }}" class="p-3 rounded-xl border border-gray-200 hover:border-blue-500 hover:bg-blue-50/30 transition block font-bold text-gray-900">
                    <span class="block text-blue-700 text-[11px] font-mono mb-0.5">02. BATCHES</span>
                    All Courses →
                </a>

                <a href="{{ route('student.tests.index') }}" class="p-3 rounded-xl border border-gray-200 hover:border-blue-500 hover:bg-blue-50/30 transition block font-bold text-gray-900">
                    <span class="block text-blue-700 text-[11px] font-mono mb-0.5">03. ENGINE</span>
                    CBT Mock Tests →
                </a>

                <a href="{{ route('contact') }}" class="p-3 rounded-xl border border-gray-200 hover:border-blue-500 hover:bg-blue-50/30 transition block font-bold text-gray-900">
                    <span class="block text-blue-700 text-[11px] font-mono mb-0.5">04. SUPPORT</span>
                    Help &amp; Grievance →
                </a>
            </div>
        </div>

        <!-- Back Button -->
        <div>
            <button type="button" onclick="window.history.back()" class="text-xs text-gray-500 hover:text-gray-900 font-semibold inline-flex items-center space-x-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Return to Previous Screen</span>
            </button>
        </div>

    </div>
</div>
@endsection
