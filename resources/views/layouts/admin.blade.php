<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Console — Nirmaan Defence Platform')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 min-h-full flex font-sans" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-black/60 lg:hidden" 
         style="display:none;"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white flex flex-col transition-transform duration-300 ease-in-out lg:static">
        
        <!-- Brand -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800 justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <span class="text-lg font-black tracking-wider text-white">NIRMAAN</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-600 text-white uppercase">ADMIN</span>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">✕</button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-grow px-4 py-6 space-y-1.5 overflow-y-auto text-xs font-semibold">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.courses.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.courses.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span>Courses &amp; Curriculum</span>
            </a>

            <a href="{{ route('admin.questions.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.questions.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <span>Question Bank</span>
            </a>

            <a href="{{ route('admin.tests.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.tests.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Mock Tests</span>
            </a>

            <a href="{{ route('admin.students.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.students.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span>Registered Students</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>Orders &amp; Payments</span>
            </a>

            <a href="{{ route('admin.content.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.content.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>Site Content &amp; FAQs</span>
            </a>
        </nav>

        <!-- Footer in sidebar -->
        <div class="p-4 border-t border-slate-800 space-y-2">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-center space-x-2 w-full py-2 rounded-xl text-xs font-bold text-slate-300 bg-slate-800 hover:bg-slate-700 transition">
                <span>View Public Site ↗</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-center py-2 text-xs font-semibold text-red-400 hover:text-red-300 transition">
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Layout -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Admin Header -->
        <header class="h-20 bg-white border-b border-gray-200 px-6 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center space-x-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h2 class="text-base font-extrabold text-gray-900 truncate">
                    @yield('header_title', 'Administrative Command')
                </h2>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-xs font-bold text-gray-700 hidden sm:inline-block">{{ Auth::user()->name }}</span>
                <div class="w-9 h-9 rounded-full bg-slate-900 text-white font-black text-xs flex items-center justify-center">
                    AD
                </div>
            </div>
        </header>

        <!-- Flash messages -->
        @include('partials.flash')

        <!-- Page body -->
        <main class="p-6 sm:p-8 flex-grow">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
