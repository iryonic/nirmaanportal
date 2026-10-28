@extends('layouts.app')

@section('title', 'Password Recovery — Nirmaan Defence Platform')

@section('content')
<div class="py-12 sm:py-20 flex items-center justify-center px-4 bg-slate-50 min-h-[calc(100vh-160px)]"
     x-data="{ isSubmitting: false }">
    <div class="max-w-md w-full">
        
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center space-x-2.5 mb-3 group">
                <img src="{{ asset('images/logo.svg') }}" alt="" class="w-44">
            </a>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Recover Account Access</h1>
            <p class="text-xs text-slate-500 mt-1 font-medium">Verify your email address to receive password recovery instructions</p>
        </div>

        <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200 shadow-2xs">
            
            @if (session('status'))
                <div class="mb-5 p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 space-y-2">
                    <div class="flex items-center space-x-2 font-semibold">
                        <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ session('status') }}</span>
                    </div>

                    @if(session('simulation_token') && session('simulation_email'))
                        <div class="mt-2 pt-2 border-t border-emerald-200 text-[11px] text-emerald-900 bg-white p-2.5 rounded-md border border-emerald-100">
                            <span class="font-bold block mb-1 text-emerald-950">⚡ Local Simulator Recovery Link:</span>
                            <a href="{{ route('password.reset', ['token' => session('simulation_token'), 'email' => session('simulation_email')]) }}" 
                               class="text-blue-950 font-mono font-semibold hover:underline break-all block">
                                Click here to proceed with password reset →
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-xs text-rose-800">
                    <div class="font-semibold flex items-center space-x-1.5 mb-1">
                        <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Recovery Error:</span>
                    </div>
                    <p class="text-[11px]">{{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4" @submit="isSubmitting = true">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Registered Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required 
                           value="{{ old('email') }}" 
                           placeholder="Enter your registered email (e.g. cadet@nirmaan.com)" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition">
                    <span class="text-[10px] text-slate-400 mt-1 block">A secure password authorization token will be dispatched.</span>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            :disabled="isSubmitting"
                            class="w-full py-2.5 px-4 rounded-lg text-xs sm:text-sm font-semibold text-white bg-blue-950 hover:bg-blue-900 disabled:opacity-60 transition shadow-2xs flex items-center justify-center space-x-2">
                        <span x-show="!isSubmitting">Send Recovery Instructions</span>
                        <span x-show="isSubmitting" style="display:none;" class="flex items-center space-x-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Processing...</span>
                        </span>
                    </button>
                </div>
            </form>

            <div class=" flex  flex-col mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500">
                Remembered your password? 
                <a href="{{ route('login') }}" class="font-semibold text-blue-950 hover:underline ml-1">
                    Back to Sign In
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
