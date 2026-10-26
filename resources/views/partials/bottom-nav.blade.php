<!-- Unified Dynamic Mobile Bottom Navigation Bar -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200 px-1.5 py-1 shadow-lg select-none print:hidden">
    <div class="grid grid-cols-5 items-center text-center">
        
        <!-- 1. Home Tab (Always points to website homepage) -->
        @php
            $isHomeActive = request()->routeIs('home');
        @endphp
        <a href="{{ route('home') }}" 
           class="flex flex-col items-center justify-center py-1 rounded-lg transition-colors {{ $isHomeActive ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-gray-900 font-medium' }}">
            <div class="relative">
                <svg class="w-5 h-5" fill="{{ $isHomeActive ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isHomeActive ? '1' : '2' }}" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                @if($isHomeActive)
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-blue-700"></span>
                @endif
            </div>
            <span class="text-[10px] mt-1 leading-none">Home</span>
        </a>

        <!-- 2. Courses Tab -->
        @php
            $isCoursesActive = request()->routeIs('courses.*');
        @endphp
        <a href="{{ route('courses.index') }}" 
           class="flex flex-col items-center justify-center py-1 rounded-lg transition-colors {{ $isCoursesActive ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-gray-900 font-medium' }}">
            <div class="relative">
                <svg class="w-5 h-5" fill="{{ $isCoursesActive ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isCoursesActive ? '1' : '2' }}" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                @if($isCoursesActive)
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-blue-700"></span>
                @endif
            </div>
            <span class="text-[10px] mt-1 leading-none">Courses</span>
        </a>

        <!-- 3. Practice Drills Tab -->
        @php
            $practiceRoute = auth()->check() ? route('student.practice') : route('login');
            $isPracticeActive = request()->routeIs('student.practice');
        @endphp
        <a href="{{ $practiceRoute }}" 
           class="flex flex-col items-center justify-center py-1 rounded-lg transition-colors {{ $isPracticeActive ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-gray-900 font-medium' }}">
            <div class="relative">
                <svg class="w-5 h-5" fill="{{ $isPracticeActive ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isPracticeActive ? '1' : '2' }}" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                @if($isPracticeActive)
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-blue-700"></span>
                @endif
            </div>
            <span class="text-[10px] mt-1 leading-none">Practice</span>
        </a>

        <!-- 4. CBT Mock Tests Tab -->
        @php
            $testsRoute = auth()->check() ? route('student.tests.index') : route('login');
            $isTestsActive = request()->routeIs('*.tests.*');
        @endphp
        <a href="{{ $testsRoute }}" 
           class="flex flex-col items-center justify-center py-1 rounded-lg transition-colors {{ $isTestsActive ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-gray-900 font-medium' }}">
            <div class="relative">
                <svg class="w-5 h-5" fill="{{ $isTestsActive ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isTestsActive ? '1' : '2' }}" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @if($isTestsActive)
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-blue-700"></span>
                @endif
            </div>
            <span class="text-[10px] mt-1 leading-none">Tests</span>
        </a>

        <!-- 5. Dashboard / Account Tab -->
        @auth
            @php
                $accountRoute = Auth::user()->isAdmin() ? route('admin.dashboard') : route('student.dashboard');
                $isAccountActive = request()->routeIs('student.dashboard') || request()->routeIs('student.profile') || request()->routeIs('student.orders*') || request()->routeIs('admin.dashboard');
            @endphp
            <a href="{{ $accountRoute }}" 
               class="flex flex-col items-center justify-center py-1 rounded-lg transition-colors {{ $isAccountActive ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-gray-900 font-medium' }}">
                <div class="relative">
                    <div class="w-5 h-5 rounded-full {{ $isAccountActive ? 'bg-blue-700 text-white' : 'bg-gray-200 text-gray-700' }} flex items-center justify-center text-[10px] font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    @if($isAccountActive)
                        <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-blue-700"></span>
                    @endif
                </div>
                <span class="text-[10px] mt-1 leading-none truncate max-w-[50px]">
                    {{ Auth::user()->isAdmin() ? 'Command' : 'Dashboard' }}
                </span>
            </a>
        @else
            @php
                $isAuthActive = request()->routeIs('login') || request()->routeIs('register');
            @endphp
            <a href="{{ route('login') }}" 
               class="flex flex-col items-center justify-center py-1 rounded-lg transition-colors {{ $isAuthActive ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-gray-900 font-medium' }}">
                <div class="relative">
                    <svg class="w-5 h-5" fill="{{ $isAuthActive ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isAuthActive ? '1' : '2' }}" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    @if($isAuthActive)
                        <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-blue-700"></span>
                    @endif
                </div>
                <span class="text-[10px] mt-1 leading-none">Sign In</span>
            </a>
        @endauth

    </div>
</nav>
