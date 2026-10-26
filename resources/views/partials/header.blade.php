<header x-data="{ mobileMenuOpen: false, searchOpen: false }" class="bg-white/95 backdrop-blur-md border-b border-gray-100 sticky top-0 z-40 transition-all duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 focus:outline-none">
                    <img src="{{ asset('images/logo.svg') }}" alt="Nirmaan" class="h-10 w-auto">
                </a>
            </div>

            <!-- Desktop Navigation Links (matching design image) -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('courses.index') }}" class="text-sm font-semibold transition-colors {{ request()->routeIs('courses.*') ? 'text-blue-700 font-bold' : 'text-gray-600 hover:text-blue-700' }}">
                    Courses
                </a>
                <a href="{{ route('student.tests.index') }}" class="text-sm font-semibold transition-colors {{ request()->routeIs('*.tests.*') ? 'text-blue-700 font-bold' : 'text-gray-600 hover:text-blue-700' }}">
                    Tests
                </a>
                <a href="{{ route('student.orders') }}" class="text-sm font-semibold transition-colors {{ request()->routeIs('student.orders') ? 'text-blue-700 font-bold' : 'text-gray-600 hover:text-blue-700' }}">
                    Payments
                </a>
                <a href="{{ route('announcements') }}" class="text-sm font-semibold transition-colors {{ request()->routeIs('announcements') ? 'text-blue-700 font-bold' : 'text-gray-600 hover:text-blue-700' }}">
                    Announcements
                </a>
            </nav>

            <!-- Search Bar & Actions -->
            <div class="hidden lg:flex items-center space-x-5">
                <!-- Search input pill -->
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <div class="relative flex items-center">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" name="q" placeholder="Search courses, tests..." value="{{ request('q') }}" class="pl-9 pr-4 py-1.5 text-xs bg-gray-50/80 border border-gray-200 rounded-full w-48 focus:w-64 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all outline-none">
                    </div>
                </form>

                <div class="h-4 w-px bg-gray-200"></div>

                <!-- Auth Controls -->
                @auth
                    <div class="relative" x-data="{ menuOpen: false }">
                        <button @click="menuOpen = !menuOpen" 
                                type="button" 
                                class="flex items-center space-x-2.5 px-3 py-1.5 rounded-full border border-gray-200 bg-gray-50/80 hover:bg-gray-100 transition focus:outline-none">
                            <div class="w-7 h-7 rounded-full {{ Auth::user()->isAdmin() ? 'bg-slate-900' : 'bg-blue-700' }} text-white font-bold text-xs flex items-center justify-center">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-xs font-bold text-gray-800 max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase {{ Auth::user()->isAdmin() ? 'bg-slate-800 text-white' : 'bg-blue-100 text-blue-800' }}">
                                {{ Auth::user()->isAdmin() ? 'Officer' : 'Cadet' }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="menuOpen" 
                             @click.away="menuOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 z-50 text-xs" 
                             style="display:none;">
                            
                            <div class="px-3 py-2 border-b border-gray-100 bg-gray-50/60 rounded-xl mb-1">
                                <p class="font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-blue-50 text-blue-700 font-bold">
                                    <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    Command Dashboard
                                </a>
                                <a href="{{ route('student.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                                    <svg class="w-4 h-4 text-gray-400 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                                    Student View
                                </a>
                            @else
                                <a href="{{ route('student.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-blue-50 text-blue-700 font-bold">
                                    <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    Cadet Dashboard
                                </a>
                                <a href="{{ route('student.practice') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                                    <svg class="w-4 h-4 text-gray-400 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    Practice Drill Bank
                                </a>
                                <a href="{{ route('student.tests.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                                    <svg class="w-4 h-4 text-gray-400 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    CBT Mock Tests
                                </a>
                                <a href="{{ route('student.orders') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                                    <svg class="w-4 h-4 text-gray-400 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    My Receipts
                                </a>
                            @endif

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
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2 text-sm font-semibold rounded-full text-blue-700 bg-blue-50/60 border border-blue-200 hover:bg-blue-100 hover:border-blue-300 transition-all shadow-xs">
                        Sign In
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Toggle Button -->
            <div class="flex items-center space-x-2 md:hidden">
                <a href="{{ route('login') }}" class="px-3.5 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 rounded-full border border-blue-200">
                    {{ Auth::check() ? 'Portal' : 'Sign In' }}
                </a>
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 focus:outline-none" aria-label="Toggle Navigation">
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0 -translate-y-2" 
         x-transition:enter-end="opacity-100 translate-y-0" 
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100 translate-y-0" 
         x-transition:leave-end="opacity-0 -translate-y-2" 
         class="md:hidden border-b border-gray-200 bg-white px-4 pt-3 pb-6 shadow-xl"
         style="display:none;">
        
        <!-- Mobile Search -->
        <form action="{{ route('search') }}" method="GET" class="mb-4">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="q" placeholder="Search courses, mock tests..." class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-blue-500">
            </div>
        </form>

        <div class="space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                Home
            </a>
            <a href="{{ route('courses.index') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('courses.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                Courses
            </a>
            <a href="{{ route('student.tests.index') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('*.tests.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                Mock Tests
            </a>
            <a href="{{ route('resources.index') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('resources.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                Free Resources
            </a>
            <a href="{{ route('student.orders') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('student.orders') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                Payments & Receipts
            </a>
            <a href="{{ route('announcements') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold {{ request()->routeIs('announcements') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                Announcements
            </a>
            <a href="{{ route('about') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-gray-700 hover:bg-gray-50">
                About Nirmaan
            </a>
            <a href="{{ route('contact') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-gray-700 hover:bg-gray-50">
                Contact & Support
            </a>
        </div>

        <div class="mt-5 pt-5 border-t border-gray-100 flex flex-col space-y-2">
            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="w-full text-center py-2.5 rounded-xl bg-slate-900 text-white font-semibold text-sm">
                        Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="w-full text-center py-2.5 rounded-xl bg-blue-700 text-white font-semibold text-sm shadow-sm">
                        Go to Student Portal
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-center py-2 text-sm text-red-600 font-medium">
                        Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-xl bg-blue-700 text-white font-semibold text-sm shadow-sm">
                    Sign In
                </a>
                <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold text-sm">
                    Create New Account
                </a>
            @endauth
        </div>
    </div>
</header>
