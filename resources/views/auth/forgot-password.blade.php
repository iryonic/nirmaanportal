@extends('layouts.app')

@section('title', 'Password Recovery — Nirmaan Defence Platform')

@section('content')
<div class="py-12 sm:py-20 flex items-center justify-center px-4 bg-gray-50/50 min-h-[calc(100vh-160px)]"
     x-data="{ isSubmitting: false }">
    <div class="max-w-md w-full">
        
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center space-x-2.5 mb-3 group">
                <span class="w-10 h-10 rounded-xl bg-blue-700 text-white font-black text-lg flex items-center justify-center shadow-xs">
                    N
                </span>
                <span class="text-2xl font-bold tracking-tight text-gray-900">NIRMAAN</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Recover Account Access</h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Verify your email address to receive password recovery instructions</p>
        </div>

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-xs">
            
            @if (session('status'))
                <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 space-y-2">
                    <div class="flex items-center space-x-2 font-bold">
                        <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ session('status') }}</span>
                    </div>

                    @if(session('simulation_token') && session('simulation_email'))
                        <div class="mt-3 pt-3 border-t border-emerald-200 text-[11px] text-emerald-900 bg-white p-3 rounded-lg border border-emerald-100">
                            <span class="font-bold block mb-1 text-emerald-950">⚡ Local Simulator Recovery Link:</span>
                            <a href="{{ route('password.reset', ['token' => session('simulation_token'), 'email' => session('simulation_email')]) }}" 
                               class="text-blue-700 font-mono font-bold hover:underline break-all block">
                                Click here to proceed with password reset →
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                    <div class="font-bold flex items-center space-x-1.5 mb-1">
                        <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Recovery Error:</span>
                    </div>
                    <p class="text-[11px]">{{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4" @submit="isSubmitting = true">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Registered Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required 
                           value="{{ old('email') }}" 
                           placeholder="Enter your registered email (e.g. cadet@nirmaan.com)" 
                           class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white focus:border-transparent transition">
                    <span class="text-[10px] text-gray-400 mt-1 block">A secure password authorization token will be dispatched.</span>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            :disabled="isSubmitting"
                            class="w-full py-3 px-4 rounded-xl text-xs sm:text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 disabled:opacity-60 transition shadow-xs flex items-center justify-center space-x-2">
                        <span x-show="!isSubmitting">Send Recovery Instructions</span>
                        <span x-show="isSubmitting" style="display:none;" class="flex items-center space-x-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Generating Token...</span>
                        </span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 text-center text-xs text-gray-500">
                Remembered your password? 
                <a href="{{ route('login') }}" class="font-bold text-blue-700 hover:underline ml-1">
                    ← Back to Sign In
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
