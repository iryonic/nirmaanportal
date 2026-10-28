@extends('layouts.app')

@section('title', 'Sign In — Nirmaan Defence Portal')

@section('content')
<div class="py-12 sm:py-20 flex items-center justify-center px-4 bg-slate-50 min-h-[calc(100vh-160px)]"
     x-data="{
        roleTab: 'student',
        showPassword: false,
        isSubmitting: false,
        setRole(role) {
            this.roleTab = role;
            if (role === 'student') {
                $refs.loginInput.value = 'student@nirmaan.com';
                $refs.passwordInput.value = 'password123';
            } else {
                $refs.loginInput.value = 'admin@nirmaan.com';
                $refs.passwordInput.value = 'password123';
            }
        }
     }">
    <div class="max-w-md w-full">
        
        <!-- Academy Brand Header -->
        <div class="text-center mb-8">
             <a href="{{ route('home') }}" class="inline-flex items-center space-x-2.5 mb-3 group">
                <img src="{{ asset('images/logo.svg') }}" alt="" class="w-44">
            </a>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Welcome to Nirmaan</h1>
            <p class="text-xs text-slate-500 mt-1 font-medium">Access your defence learning portal or administrative workspace</p>
        </div>

        <!-- Quick Demo Switcher Tabs -->
        <div class="bg-slate-100 p-1 rounded-lg flex mb-6 text-xs font-semibold text-slate-600 border border-slate-200 shadow-2xs">
            <button type="button" 
                    @click="setRole('student')" 
                    :class="roleTab === 'student' ? 'bg-white text-blue-950 shadow-2xs border border-slate-200/80 font-semibold' : 'hover:text-slate-900 font-medium'" 
                    class="flex-1 py-1.5 rounded-md transition text-center">
                Cadet Student
            </button>
            <button type="button" 
                    @click="setRole('admin')" 
                    :class="roleTab === 'admin' ? 'bg-white text-blue-950 shadow-2xs border border-slate-200/80 font-semibold' : 'hover:text-slate-900 font-medium'" 
                    class="flex-1 py-1.5 rounded-md transition text-center">
                Staff &amp; Admin
            </button>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200 shadow-2xs">
            
            <!-- Global Error Banner if Multiple Errors or Rate Limit -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-xs text-rose-800 space-y-1">
                    <div class="font-semibold flex items-center space-x-1.5">
                        <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Unable to sign in:</span>
                    </div>
                    <ul class="list-disc list-inside pl-1 text-[11px] leading-relaxed">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-5 p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center space-x-2">
                    <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4" @submit="isSubmitting = true">
                @csrf

                <!-- Email or Mobile Number Input -->
                <div>
                    <label for="login" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Email Address or Mobile Number <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="login" 
                           name="email" 
                           x-ref="loginInput"
                           required 
                           autocomplete="username"
                           value="{{ old('email', 'student@nirmaan.com') }}" 
                           placeholder="e.g. cadet@nirmaan.com or 9876543210" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition">
                    <span class="text-[10px] text-slate-400 mt-1 block">Enter your registered email address or 10-digit Indian mobile number.</span>
                </div>

                <!-- Password Input with Show/Hide Toggle -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-700">
                            Account Password <span class="text-rose-500">*</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-[11px] text-blue-950 hover:underline font-semibold">
                            Forgot Password?
                        </a>
                    </div>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               id="password" 
                               name="password" 
                               x-ref="passwordInput"
                               required 
                               autocomplete="current-password"
                               value="password123" 
                               placeholder="Enter your security password" 
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 pr-10 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition">
                        
                        <!-- Show / Hide Password Button -->
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute right-2.5 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none"
                                :title="showPassword ? 'Hide password' : 'Show password'">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showPassword" style="display:none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Security Meta -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center space-x-2 text-slate-600 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-950 focus:ring-blue-950">
                        <span class="font-medium text-slate-700">Remember this device</span>
                    </label>
                    <span class="text-[10px] text-slate-400 font-mono">256-bit SSL</span>
                </div>

                <!-- Submit CTA -->
                <div class="pt-2">
                    <button type="submit" 
                            :disabled="isSubmitting"
                            class="w-full py-2.5 px-4 rounded-lg text-xs sm:text-sm font-semibold text-white bg-blue-950 hover:bg-blue-900 disabled:opacity-60 transition shadow-2xs flex items-center justify-center space-x-2">
                        <span x-show="!isSubmitting">Secure Sign In</span>
                        <span x-show="isSubmitting" style="display:none;" class="flex items-center space-x-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Authenticating...</span>
                        </span>
                    </button>
                </div>
            </form>

            <div class=" flex  flex-col mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500">
                New candidate preparing for defence exams? 
                <a href="{{ route('register') }}" class="font-semibold text-blue-950 hover:underline ml-1">
                    Register as Student
                </a>
            </div>
        </div>

        <!-- Defence Helpdesk Notice -->
        <div class="mt-6 text-center text-[11px] text-slate-400">
            For portal login issues or account reactivations, contact <a href="mailto:support@nirmaan.edu.in" class="text-slate-600 font-medium underline">support@nirmaan.edu.in</a>
        </div>

    </div>
</div>
@endsection
