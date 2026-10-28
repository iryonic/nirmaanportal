@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 print:hidden">
        <div class="bg-white border border-emerald-200 p-3 sm:p-3.5 rounded-lg shadow-2xs flex items-center justify-between text-xs">
            <div class="flex items-center space-x-2.5">
                <span class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </span>
                <p class="font-medium text-slate-800">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
@endif

@if(session('status'))
    <div x-data="{ show: true }" x-show="show" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 print:hidden">
        <div class="bg-white border border-emerald-200 p-3 sm:p-3.5 rounded-lg shadow-2xs flex items-center justify-between text-xs">
            <div class="flex items-center space-x-2.5">
                <span class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
                <p class="font-medium text-slate-800">{{ session('status') }}</p>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" x-show="show" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 print:hidden">
        <div class="bg-white border border-red-200 p-3 sm:p-3.5 rounded-lg shadow-2xs flex items-center justify-between text-xs">
            <div class="flex items-center space-x-2.5">
                <span class="w-6 h-6 rounded-md bg-red-50 text-red-600 flex items-center justify-center shrink-0 border border-red-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
                <p class="font-medium text-red-900">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
@endif

@if(session('warning'))
    <div x-data="{ show: true }" x-show="show" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 print:hidden">
        <div class="bg-white border border-amber-200 p-3 sm:p-3.5 rounded-lg shadow-2xs flex items-center justify-between text-xs">
            <div class="flex items-center space-x-2.5">
                <span class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </span>
                <p class="font-medium text-amber-900">{{ session('warning') }}</p>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
@endif

@if(session('info'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 print:hidden">
        <div class="bg-white border border-blue-200 p-3 sm:p-3.5 rounded-lg shadow-2xs flex items-center justify-between text-xs">
            <div class="flex items-center space-x-2.5">
                <span class="w-6 h-6 rounded-md bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 border border-blue-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
                <p class="font-medium text-slate-800">{{ session('info') }}</p>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
@endif
