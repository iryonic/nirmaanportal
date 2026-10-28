<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Workspace — Nirmaan')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- PWA Progressive Web App Meta -->
    @include('partials.pwa-head')

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 min-h-full flex flex-col font-sans pb-20 md:pb-0 selection:bg-blue-100 selection:text-blue-950 antialiased">

    <!-- Unified Top Header -->
    @include('partials.header')

    <!-- Student Workspace Sub-Navigation (Linear style) -->
    @auth
        <div class="bg-white border-b border-slate-200 sticky top-20 z-30 shadow-2xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-11 text-xs">
                    <div class="flex items-center space-x-1 sm:space-x-6 overflow-x-auto no-scrollbar py-1">
                        <a href="{{ route('student.dashboard') }}" 
                           class="inline-flex items-center space-x-1.5 px-2.5 py-1.5 rounded-md font-medium transition-colors whitespace-nowrap {{ request()->routeIs('student.dashboard') ? 'text-blue-950 font-semibold bg-slate-100' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('student.practice') }}" 
                           class="inline-flex items-center space-x-1.5 px-2.5 py-1.5 rounded-md font-medium transition-colors whitespace-nowrap {{ request()->routeIs('student.practice') ? 'text-blue-950 font-semibold bg-slate-100' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            <span>Practice Bank</span>
                        </a>

                        <a href="{{ route('student.tests.index') }}" 
                           class="inline-flex items-center space-x-1.5 px-2.5 py-1.5 rounded-md font-medium transition-colors whitespace-nowrap {{ request()->routeIs('student.tests.*') ? 'text-blue-950 font-semibold bg-slate-100' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>CBT Mock Tests</span>
                        </a>

                        <a href="{{ route('student.orders') }}" 
                           class="inline-flex items-center space-x-1.5 px-2.5 py-1.5 rounded-md font-medium transition-colors whitespace-nowrap {{ request()->routeIs('student.orders*') ? 'text-blue-950 font-semibold bg-slate-100' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>Invoices &amp; Orders</span>
                        </a>

                        <a href="{{ route('student.profile') }}" 
                           class="inline-flex items-center space-x-1.5 px-2.5 py-1.5 rounded-md font-medium transition-colors whitespace-nowrap {{ request()->routeIs('student.profile') ? 'text-blue-950 font-semibold bg-slate-100' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Profile &amp; Settings</span>
                        </a>
                    </div>

                    <div class="hidden sm:flex items-center space-x-2 text-slate-500 font-mono text-[11px]">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                            Cadet #{{ str_pad(auth()->id(), 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <!-- Flash notifications -->
    @include('partials.flash')

    <!-- Main Content Slot -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Unified Footer -->
    @include('partials.footer')

    <!-- Unified Dynamic Mobile Bottom Navigation Bar -->
    @include('partials.bottom-nav')

    <!-- PWA Service Worker Registration -->
    @include('partials.pwa-scripts')

    @stack('scripts')
</body>
</html>
