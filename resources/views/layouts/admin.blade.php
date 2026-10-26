<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Command Center — Nirmaan Defence Platform')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 min-h-full flex font-sans antialiased selection:bg-blue-600 selection:text-white" 
      x-data="{ 
          sidebarOpen: false, 
          sidebarCollapsed: false,
          userMenuOpen: false, 
          quickCreateOpen: false 
      }"
      @click.away="userMenuOpen = false; quickCreateOpen = false">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-950/70 backdrop-blur-xs lg:hidden" 
         style="display:none;"></div>

    <!-- Sidebar -->
    <aside :class="{
                'translate-x-0': sidebarOpen,
                '-translate-x-full lg:translate-x-0': !sidebarOpen,
                'lg:w-20': sidebarCollapsed,
                'lg:w-72': !sidebarCollapsed
           }" 
           class="fixed inset-y-0 left-0 z-50 w-72 bg-[#0B132B] text-slate-200 flex flex-col transition-all duration-300 ease-in-out border-r border-slate-800/80 shadow-2xl lg:static lg:shadow-none">
        
        <!-- Sidebar Brand Header -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800/80 justify-between bg-[#080E21]">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-xl bg-white p-1.5 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform flex-shrink-0">
                    <img src="{{ asset('images/logo.svg') }}" alt="Nirmaan" class="h-7 w-auto">
                </div>
                <div x-show="!sidebarCollapsed" class="flex flex-col transition-opacity duration-200">
                    <div class="flex items-center space-x-1.5">
                        <span class="text-base font-black tracking-wider text-white">NIRMAAN</span>
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-blue-500/20 text-blue-400 border border-blue-500/30 uppercase tracking-wider">OFFICER</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-semibold tracking-tight">Defence Command HQ</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-grow px-3 py-5 space-y-6 overflow-y-auto text-xs font-semibold scrollbar-thin scrollbar-thumb-slate-800">
            
            <!-- Group: Main Control -->
            <div class="space-y-1">
                <div x-show="!sidebarCollapsed" class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                    Executive Control
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   title="Dashboard & KPIs"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-blue-400 group-hover:text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Command Dashboard</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <span x-show="!sidebarCollapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
            </div>

            <!-- Group: Academic & Curriculum -->
            <div class="space-y-1">
                <div x-show="!sidebarCollapsed" class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                    Academic &amp; Training
                </div>

                <a href="{{ route('admin.courses.index') }}" 
                   title="Courses & Curriculum"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.courses.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.courses.*') ? 'text-white' : 'text-amber-400 group-hover:text-amber-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Courses &amp; Syllabus</span>
                </a>
            </div>

            <!-- Group: Examination Engine -->
            <div class="space-y-1">
                <div x-show="!sidebarCollapsed" class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                    Testing &amp; Evaluation
                </div>

                <a href="{{ route('admin.questions.index') }}" 
                   title="Question Bank Repository"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.questions.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.questions.*') ? 'text-white' : 'text-emerald-400 group-hover:text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Question Bank</span>
                </a>

                <a href="{{ route('admin.tests.index') }}" 
                   title="CBT Mock Tests"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.tests.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.tests.*') ? 'text-white' : 'text-sky-400 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">CBT Mock Tests</span>
                </a>
            </div>

            <!-- Group: Cadets & Revenue -->
            <div class="space-y-1">
                <div x-show="!sidebarCollapsed" class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                    Cadets &amp; Finance
                </div>

                <a href="{{ route('admin.students.index') }}" 
                   title="Registered Cadets"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.students.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.students.*') ? 'text-white' : 'text-violet-400 group-hover:text-violet-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Registered Cadets</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" 
                   title="Orders & Receipts"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-emerald-400 group-hover:text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Orders &amp; Audits</span>
                </a>
            </div>

            <!-- Group: Site Management -->
            <div class="space-y-1">
                <div x-show="!sidebarCollapsed" class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                    Site &amp; Enquiries
                </div>

                <a href="{{ route('admin.content.index') }}" 
                   title="Site Content & FAQs"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.content.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.content.*') ? 'text-white' : 'text-rose-400 group-hover:text-rose-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Content, FAQs &amp; Inquiries</span>
                </a>
            </div>

        </nav>

        <!-- Sidebar Footer Action Box -->
        <div class="p-3 border-t border-slate-800/80 bg-[#080E21] space-y-2">
            <a href="{{ route('home') }}" target="_blank" 
               class="flex items-center justify-center space-x-2 w-full py-2.5 px-3 rounded-xl text-xs font-bold text-slate-300 bg-slate-800/90 hover:bg-slate-700 hover:text-white transition shadow-sm border border-slate-700/60">
                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span x-show="!sidebarCollapsed">View Live Website</span>
            </a>

            <div x-show="!sidebarCollapsed" class="flex items-center justify-between px-2 pt-1 text-[10px] text-slate-400 font-medium">
                <span class="flex items-center space-x-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>System Online</span>
                </span>
                <span>v2.4 LTS</span>
            </div>
        </div>
    </aside>

    <!-- Main Content Layout Area -->
    <div class="flex-grow flex flex-col min-w-0 overflow-y-auto">
        
        <!-- Synchronized Admin Top Header (h-20 matching Public/Student Rhythm) -->
        <header class="h-20 bg-white/95 backdrop-blur-md border-b border-gray-200/90 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-2xs">
            
            <!-- Left: Sidebar Toggles & Breadcrumb Hierarchy -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                <!-- Mobile Drawer Trigger -->
                <button @click="sidebarOpen = true" 
                        class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <!-- Desktop Sidebar Collapse Toggle -->
                <button @click="sidebarCollapsed = !sidebarCollapsed" 
                        class="hidden lg:flex p-2 rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition focus:outline-none"
                        title="Toggle Sidebar Width">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path>
                    </svg>
                </button>

                <!-- Breadcrumb Hierarchy / Title Synchronizer -->
                <div class="flex items-center space-x-2 text-xs">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-blue-700 font-medium hidden sm:inline-block">Admin</a>
                    <span class="text-gray-300 hidden sm:inline-block">/</span>
                    <h1 class="text-sm sm:text-base font-extrabold text-gray-900 truncate">
                        @yield('header_title', 'Administrative Command')
                    </h1>
                </div>
            </div>

            <!-- Right Controls: Quick Actions, Site Preview, Profile Dropdown -->
            <div class="flex items-center space-x-2.5 sm:space-x-4">
                
                <!-- Quick Create Dropdown Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            type="button" 
                            class="inline-flex items-center space-x-1.5 px-3 py-2 rounded-xl text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="hidden md:inline">Quick Create</span>
                        <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" 
                         @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 text-xs font-semibold" 
                         style="display:none;">
                        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-gray-400 tracking-wider">Add New Entity</div>
                        <a href="{{ route('admin.courses.create') }}" class="flex items-center px-3.5 py-2 hover:bg-gray-50 text-gray-700 hover:text-blue-700">
                            <span class="w-2 h-2 rounded-full bg-amber-500 mr-2.5"></span>
                            New Course Batch
                        </a>
                        <a href="{{ route('admin.questions.create') }}" class="flex items-center px-3.5 py-2 hover:bg-gray-50 text-gray-700 hover:text-blue-700">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2.5"></span>
                            New Question
                        </a>
                        <a href="{{ route('admin.tests.create') }}" class="flex items-center px-3.5 py-2 hover:bg-gray-50 text-gray-700 hover:text-blue-700">
                            <span class="w-2 h-2 rounded-full bg-sky-500 mr-2.5"></span>
                            New CBT Mock Test
                        </a>
                    </div>
                </div>

                <!-- Preview Public Website Button -->
                <a href="{{ route('home') }}" target="_blank" 
                   class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-2 rounded-xl text-xs font-bold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 transition"
                   title="Preview Live Discovery Portal">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span>Live Portal</span>
                </a>

                <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>

                <!-- Officer Account Dropdown -->
                <div class="relative" x-data="{ userMenu: false }">
                    <button @click="userMenu = !userMenu" 
                            type="button" 
                            class="flex items-center space-x-3 p-1.5 rounded-2xl hover:bg-gray-100/80 transition focus:outline-none">
                        <div class="w-9 h-9 rounded-lg bg-blue-700 text-white font-extrabold text-xs flex items-center justify-center">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden md:flex flex-col text-left">
                            <span class="text-xs font-bold text-gray-900 leading-tight">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] text-blue-700 font-extrabold uppercase tracking-tight">Super Officer</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="userMenu" 
                         @click.away="userMenu = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 z-50 text-xs" 
                         style="display:none;">
                        
                        <div class="px-3 py-2.5 border-b border-gray-100 bg-gray-50/50 rounded-xl mb-1">
                            <p class="font-bold text-gray-900 text-xs truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[9px] font-extrabold bg-blue-100 text-blue-800 uppercase">
                                Administrator Privilege
                            </span>
                        </div>

                        <a href="{{ route('student.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                            <svg class="w-4 h-4 text-blue-600 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            Switch to Student Portal
                        </a>

                        <a href="{{ route('admin.content.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                            <svg class="w-4 h-4 text-gray-500 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                            Platform Settings
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-3 py-2 rounded-lg hover:bg-red-50 text-red-600 font-bold transition">
                                <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Terminate Session
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        <!-- Flash Alert Feedback Notifications -->
        @include('partials.flash')

        <!-- Main Body Page Canvas -->
        <main class="p-4 sm:p-8 flex-grow  w-full mx-auto space-y-6">
            @yield('content')
        </main>

        <!-- Synchronized Admin Footer -->
        <footer class="bg-white border-t border-gray-200/80 py-4 px-6 sm:px-8 text-xs text-gray-500 flex flex-col sm:flex-row items-center justify-between gap-3 mt-auto">
            <div class="flex items-center space-x-2">
                <span class="font-bold text-gray-900">NIRMAAN</span>
                <span>&bull; Defence Examination Prep HQ</span>
            </div>
            <div class="flex items-center space-x-4 text-[11px]">
                <a href="{{ route('home') }}" class="hover:text-blue-700 transition">Discovery Home</a>
                <span>&bull;</span>
                <a href="{{ route('courses.index') }}" class="hover:text-blue-700 transition">Course Catalogue</a>
                <span>&bull;</span>
                <a href="{{ route('student.tests.index') }}" class="hover:text-blue-700 transition">CBT Testing Engine</a>
            </div>
        </footer>

    </div>

    @stack('scripts')
</body>
</html>
