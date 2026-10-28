<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Command Center — Nirmaan Defence Platform')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 min-h-full flex font-sans antialiased selection:bg-blue-950 selection:text-white" 
      x-data="{ 
          sidebarOpen: false, 
          sidebarCollapsed: false,
          userMenuOpen: false, 
          quickCreateOpen: false 
      }"
      @click.away="userMenuOpen = false; quickCreateOpen = false">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-xs lg:hidden" 
         style="display:none;"></div>

    <!-- Sidebar: Linear-inspired Blue-950 Primary Brand Canvas -->
    <aside :class="{
                'translate-x-0': sidebarOpen,
                '-translate-x-full lg:translate-x-0': !sidebarOpen,
                'lg:w-18': sidebarCollapsed,
                'lg:w-64': !sidebarCollapsed
           }" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-blue-950 text-slate-200 flex flex-col transition-all duration-200 ease-in-out border-r border-blue-900/60 shadow-xl lg:static lg:shadow-none">
        
        <!-- Sidebar Brand Header -->
        <div class="h-16 flex items-center px-4 bg-white border-b border-blue-900/40 justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 group min-w-0 w-full">
                <div class="w-full h-12 rounded-lg bg-white flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform shrink-0">
                    <img src="{{ asset('images/logo.svg') }}" alt="Nirmaan" class="h-8 w-auto">
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-500 hover:text-slate-900 p-1 rounded-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-grow px-2.5 py-4 space-y-4 overflow-y-auto text-xs font-medium scrollbar-thin scrollbar-thumb-blue-900">
            
            <!-- Group: Overview -->
            <div class="space-y-0.5">
                <div x-show="!sidebarCollapsed" class="px-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    Overview
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   title="Command Dashboard"
                   class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg transition-colors group {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white font-semibold shadow-2xs' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <span x-show="!sidebarCollapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                    @endif
                </a>
            </div>

            <!-- Group: Academic & Curriculum -->
            <div class="space-y-0.5">
                <div x-show="!sidebarCollapsed" class="px-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    Academics
                </div>

                <a href="{{ route('admin.courses.index') }}" 
                   title="Courses & Curriculum"
                   class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg transition-colors group {{ request()->routeIs('admin.courses.*') ? 'bg-white/10 text-white font-semibold shadow-2xs' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.courses.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Courses &amp; Syllabus</span>
                    @if(request()->routeIs('admin.courses.*'))
                        <span x-show="!sidebarCollapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                    @endif
                </a>
            </div>

            <!-- Group: Examination Engine -->
            <div class="space-y-0.5">
                <div x-show="!sidebarCollapsed" class="px-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    Testing
                </div>

                <a href="{{ route('admin.questions.index') }}" 
                   title="Question Bank Repository"
                   class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg transition-colors group {{ request()->routeIs('admin.questions.*') ? 'bg-white/10 text-white font-semibold shadow-2xs' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.questions.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Question Bank</span>
                    @if(request()->routeIs('admin.questions.*'))
                        <span x-show="!sidebarCollapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                    @endif
                </a>

                <a href="{{ route('admin.tests.index') }}" 
                   title="CBT Mock Tests"
                   class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg transition-colors group {{ request()->routeIs('admin.tests.*') ? 'bg-white/10 text-white font-semibold shadow-2xs' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.tests.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">CBT Mock Tests</span>
                    @if(request()->routeIs('admin.tests.*'))
                        <span x-show="!sidebarCollapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                    @endif
                </a>
            </div>

            <!-- Group: Cadets & Finance -->
            <div class="space-y-0.5">
                <div x-show="!sidebarCollapsed" class="px-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    Cadets &amp; Finance
                </div>

                <a href="{{ route('admin.students.index') }}" 
                   title="Registered Cadets"
                   class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg transition-colors group {{ request()->routeIs('admin.students.*') ? 'bg-white/10 text-white font-semibold shadow-2xs' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.students.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Cadets Roster</span>
                    @if(request()->routeIs('admin.students.*'))
                        <span x-show="!sidebarCollapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                    @endif
                </a>

                <a href="{{ route('admin.orders.index') }}" 
                   title="Orders & Receipts"
                   class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg transition-colors group {{ request()->routeIs('admin.orders.*') ? 'bg-white/10 text-white font-semibold shadow-2xs' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Orders &amp; Audits</span>
                    @if(request()->routeIs('admin.orders.*'))
                        <span x-show="!sidebarCollapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                    @endif
                </a>

                <a href="{{ route('admin.settings.payment') }}" 
                   title="Razorpay Payment Gateway Settings"
                   class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg transition-colors group {{ request()->routeIs('admin.settings.payment*') ? 'bg-white/10 text-white font-semibold shadow-2xs' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.settings.payment*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Payment Gateway</span>
                    @if(request()->routeIs('admin.settings.payment*'))
                        <span x-show="!sidebarCollapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                    @endif
                </a>
            </div>

            <!-- Group: Site Management -->
            <div class="space-y-0.5">
                <div x-show="!sidebarCollapsed" class="px-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    Portal &amp; Comms
                </div>

                <a href="{{ route('admin.notifications.index') }}" 
                   title="Notice Board & Notifications"
                   class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg transition-colors group {{ request()->routeIs('admin.notifications.*') ? 'bg-white/10 text-white font-semibold shadow-2xs' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.notifications.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Notice Board &amp; Alerts</span>
                    @if(request()->routeIs('admin.notifications.*'))
                        <span x-show="!sidebarCollapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                    @endif
                </a>

                <a href="{{ route('admin.content.index') }}" 
                   title="Site Content & FAQs"
                   class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg transition-colors group {{ request()->routeIs('admin.content.*') ? 'bg-white/10 text-white font-semibold shadow-2xs' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.content.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Content &amp; Settings</span>
                    @if(request()->routeIs('admin.content.*'))
                        <span x-show="!sidebarCollapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                    @endif
                </a>
            </div>

        </nav>

        <!-- Sidebar Footer Action Box -->
        <div class="p-3 border-t border-blue-900/60 bg-blue-950 space-y-2">
            <a href="{{ route('home') }}" target="_blank" 
               class="flex items-center justify-center space-x-2 w-full py-2 px-3 rounded-lg text-xs font-semibold text-slate-200 bg-white/5 hover:bg-white/10 hover:text-white transition-colors border border-white/10 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span x-show="!sidebarCollapsed">Live Website ↗</span>
            </a>

            <div x-show="!sidebarCollapsed" class="flex items-center justify-between px-2 pt-1 text-[11px] text-slate-400">
                <span class="flex items-center space-x-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span class="text-[10px] uppercase tracking-wider font-semibold">Operational</span>
                </span>
                <span class="text-[10px] font-mono text-slate-400">v2.5</span>
            </div>
        </div>
    </aside>

    <!-- Main Content Layout Area -->
    <div class="flex-grow flex flex-col min-w-0 overflow-y-auto">
        
        <!-- Linear-style Clean Top Navigation Header -->
        <header class="h-16 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-2xs">
            
            <!-- Left: Sidebar Toggles & Clean Breadcrumbs -->
            <div class="flex items-center space-x-3">
                <!-- Mobile Drawer Trigger -->
                <button @click="sidebarOpen = true" 
                        class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <!-- Desktop Sidebar Collapse Toggle -->
                <button @click="sidebarCollapsed = !sidebarCollapsed" 
                        class="hidden lg:flex p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-colors focus:outline-none"
                        title="Toggle Sidebar Width">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path>
                    </svg>
                </button>

                <!-- Breadcrumb Hierarchy -->
                <div class="flex items-center space-x-2 text-xs">
                    <a href="{{ route('admin.dashboard') }}" class="text-slate-500 hover:text-blue-950 font-medium hidden sm:inline-block">Admin</a>
                    <span class="text-slate-300 hidden sm:inline-block">/</span>
                    <h1 class="text-xs sm:text-sm font-semibold text-slate-900 truncate">
                        @yield('header_title', 'HQ Command Center')
                    </h1>
                </div>
            </div>

            <!-- Right Controls: Quick Actions, Live Site, Officer Dropdown -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                
                <!-- Quick Create Dropdown Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            type="button" 
                            class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="hidden sm:inline">New</span>
                        <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" 
                         @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-1.5 w-52 bg-white rounded-xl shadow-lg border border-slate-200 py-1.5 z-50 text-xs font-medium" 
                         style="display:none;">
                        <div class="px-3 py-1 text-[10px] uppercase font-bold text-slate-400 tracking-wider">Quick Actions</div>
                        <a href="{{ route('admin.courses.create') }}" class="flex items-center px-3 py-2 hover:bg-slate-50 text-slate-700 hover:text-slate-900">
                            <span class="w-2 h-2 rounded-full bg-amber-500 mr-2.5"></span>
                            New Course Batch
                        </a>
                        <a href="{{ route('admin.questions.create') }}" class="flex items-center px-3 py-2 hover:bg-slate-50 text-slate-700 hover:text-slate-900">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2.5"></span>
                            New Question
                        </a>
                        <a href="{{ route('admin.tests.create') }}" class="flex items-center px-3 py-2 hover:bg-slate-50 text-slate-700 hover:text-slate-900">
                            <span class="w-2 h-2 rounded-full bg-sky-500 mr-2.5"></span>
                            New CBT Mock Test
                        </a>
                        <a href="{{ route('admin.notifications.create') }}" class="flex items-center px-3 py-2 hover:bg-slate-50 text-slate-700 hover:text-slate-900">
                            <span class="w-2 h-2 rounded-full bg-blue-600 mr-2.5"></span>
                            Post Notice Bulletin
                        </a>
                    </div>
                </div>

                <!-- Preview Live Website Button -->
                <a href="{{ route('home') }}" target="_blank" 
                   class="hidden md:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs transition-colors"
                   title="Preview Live Portal">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span>Website</span>
                </a>

                <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>

                <!-- Officer Account Dropdown -->
                <div class="relative" x-data="{ userMenu: false }">
                    <button @click="userMenu = !userMenu" 
                            type="button" 
                            class="flex items-center space-x-2.5 p-1 rounded-lg hover:bg-slate-100 transition-colors focus:outline-none">
                        <div class="w-8 h-8 rounded-lg bg-blue-950 text-white font-bold text-xs flex items-center justify-center shadow-2xs">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden md:flex flex-col text-left">
                            <span class="text-xs font-semibold text-slate-900 leading-tight">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] text-slate-500 font-medium">Super Admin</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
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
                         class="absolute right-0 mt-1.5 w-60 bg-white rounded-xl shadow-lg border border-slate-200 p-1.5 z-50 text-xs" 
                         style="display:none;">
                        
                        <div class="px-3 py-2 border-b border-slate-100 bg-slate-50/70 rounded-lg mb-1">
                            <p class="font-semibold text-slate-900 text-xs truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            <span class="inline-block mt-1 px-1.5 py-0.2 rounded text-[9px] font-bold bg-blue-50 text-blue-950 border border-blue-200 uppercase">
                                Administrator Privilege
                            </span>
                        </div>

                        <a href="{{ route('student.dashboard') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-slate-50 text-slate-700 font-medium">
                            <svg class="w-6 h-6 text-slate-500 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            Switch to Student Portal
                        </a>

                        <a href="{{ route('admin.content.index') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-slate-50 text-slate-700 font-medium">
                            <svg class="w-6 h-6 text-slate-500 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                            Platform Settings
                        </a>

                        <div class="border-t border-slate-100 my-1"></div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-3 py-2 rounded-md hover:bg-red-50 text-red-600 font-semibold transition-colors">
                                <svg class="w-6 h-6 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        <!-- Flash Alert Feedback Notifications -->
        @include('partials.flash')

        <!-- Main Body Page Canvas -->
        <main class="p-4 flex-grow w-full max-w-full mx-auto space-y-6">
            @yield('content')
        </main>

        <!-- Synchronized Admin Footer -->
        <footer class="bg-white border-t border-slate-200/80 py-3.5 px-4 sm:px-6 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-3 mt-auto">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('logo.png') }}" alt="" class="h-6">
                <span>&bull; Defence Prep Institute</span>
            </div>
            <div class="flex items-center space-x-3 text-[11px]">
                <a href="{{ route('home') }}" class="hover:text-blue-950 transition-colors">Discovery Home</a>
                <span>&bull;</span>
                <a href="{{ route('courses.index') }}" class="hover:text-blue-950 transition-colors">Courses</a>
                <span>&bull;</span>
                <a href="{{ route('student.tests.index') }}" class="hover:text-blue-950 transition-colors">CBT Engine</a>
            </div>
        </footer>

    </div>

    @stack('scripts')
</body>
</html>
