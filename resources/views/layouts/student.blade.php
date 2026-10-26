<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Learning Workspace — Nirmaan')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-gray-800 min-h-full flex flex-col font-sans pb-20 md:pb-0 selection:bg-blue-100 selection:text-blue-900">

    <!-- Top Navigation Bar -->
    <header class="bg-white/95 backdrop-blur-md border-b border-gray-200/90 sticky top-0 z-30 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Left: Brand & Portal Badge -->
                <div class="flex items-center space-x-3.5">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/logo.svg') }}" alt="Nirmaan" class="h-9 w-auto">
                    </a>
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-800 border border-blue-200 uppercase tracking-wider">
                        CADET PORTAL
                    </span>
                </div>

                <!-- Desktop Center Navigation -->
                <nav class="hidden md:flex items-center space-x-6 text-xs font-bold">
                    <a href="{{ route('student.dashboard') }}" class="transition py-2 border-b-2 {{ request()->routeIs('student.dashboard') ? 'text-blue-700 border-blue-700' : 'text-gray-600 hover:text-blue-700 border-transparent' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('courses.index') }}" class="transition py-2 border-b-2 {{ request()->routeIs('courses.*') ? 'text-blue-700 border-blue-700' : 'text-gray-600 hover:text-blue-700 border-transparent' }}">
                        Courses
                    </a>
                    <a href="{{ route('student.practice') }}" class="transition py-2 border-b-2 {{ request()->routeIs('student.practice') ? 'text-blue-700 border-blue-700' : 'text-gray-600 hover:text-blue-700 border-transparent' }}">
                        Practice Bank
                    </a>
                    <a href="{{ route('student.tests.index') }}" class="transition py-2 border-b-2 {{ request()->routeIs('student.tests.*') ? 'text-blue-700 border-blue-700' : 'text-gray-600 hover:text-blue-700 border-transparent' }}">
                        Mock Tests
                    </a>
                    <a href="{{ route('student.orders') }}" class="transition py-2 border-b-2 {{ request()->routeIs('student.orders') ? 'text-blue-700 border-blue-700' : 'text-gray-600 hover:text-blue-700 border-transparent' }}">
                        Receipts
                    </a>
                </nav>

                <!-- Right: Profile Dropdown & Quick Actions -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" target="_blank" class="hidden lg:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-full text-xs font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200 transition">
                        <span>Public Site ↗</span>
                    </a>

                    <!-- Profile Avatar Dropdown -->
                    <div class="relative" x-data="{ menuOpen: false }">
                        <button @click="menuOpen = !menuOpen" 
                                type="button" 
                                class="flex items-center space-x-2.5 p-1 rounded-full border border-gray-200 bg-gray-50 hover:bg-gray-100 transition focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-blue-700 text-white font-bold flex items-center justify-center text-xs shadow-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:inline-block text-xs font-bold text-gray-800 max-w-[120px] truncate pr-1">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-gray-400 pr-1 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="menuOpen" 
                             @click.away="menuOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 z-50 text-xs" 
                             style="display:none;">
                            
                            <div class="px-3 py-2.5 border-b border-gray-100 bg-gray-50/70 rounded-xl mb-1">
                                <p class="font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                <div class="mt-1 flex items-center space-x-2">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-blue-100 text-blue-800 uppercase">
                                        {{ Auth::user()->target_exam ?? 'General Defence' }}
                                    </span>
                                </div>
                            </div>

                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-slate-900 hover:text-white text-slate-800 font-bold mb-1 transition">
                                    <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    Officer Command Console
                                </a>
                            @endif

                            <a href="{{ route('student.profile') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                                <svg class="w-4 h-4 text-gray-400 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Edit Cadet Profile
                            </a>

                            <a href="{{ route('student.orders') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                                <svg class="w-4 h-4 text-gray-400 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Fee Receipts &amp; Invoices
                            </a>

                            <div class="border-t border-gray-100 my-1"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-3 py-2 rounded-lg hover:bg-red-50 text-red-600 font-bold transition">
                                    <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- Flash notifications -->
    @include('partials.flash')

    <!-- Main Content Slot -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation (Matching Prompt Requirements: 1. Home 2. My Courses 3. Practice 4. Tests 5. Profile) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200 px-2 py-1 shadow-lg">
        <div class="grid grid-cols-5 items-center">
            
            <!-- 1. Home -->
            <a href="{{ route('student.dashboard') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('student.dashboard') ? 'text-blue-700' : 'text-gray-400 hover:text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-[10px] font-semibold mt-0.5">Home</span>
            </a>

            <!-- 2. My Courses -->
            <a href="{{ route('courses.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('courses.*') ? 'text-blue-700' : 'text-gray-400 hover:text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="text-[10px] font-semibold mt-0.5">Courses</span>
            </a>

            <!-- 3. Practice -->
            <a href="{{ route('student.practice') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('student.practice') ? 'text-blue-700' : 'text-gray-400 hover:text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <span class="text-[10px] font-semibold mt-0.5">Practice</span>
            </a>

            <!-- 4. Tests -->
            <a href="{{ route('student.tests.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('student.tests.*') ? 'text-blue-700' : 'text-gray-400 hover:text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-[10px] font-semibold mt-0.5">Tests</span>
            </a>

            <!-- 5. Profile -->
            <a href="{{ route('student.profile') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('student.profile') ? 'text-blue-700' : 'text-gray-400 hover:text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="text-[10px] font-semibold mt-0.5">Profile</span>
            </a>

        </div>
    </nav>

    @stack('scripts')
</body>
</html>
