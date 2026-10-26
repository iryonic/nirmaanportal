@extends('layouts.app')

@section('title', 'Set New Password — Nirmaan Defence Platform')

@section('content')
<div class="py-12 sm:py-20 flex items-center justify-center px-4 bg-gray-50/50 min-h-[calc(100vh-160px)]"
     x-data="{
        password: '',
        confirmPassword: '',
        showPassword: false,
        showConfirm: false,
        isSubmitting: false,
        
        get passwordScore() {
            let score = 0;
            if (this.password.length >= 6) score += 1;
            if (this.password.length >= 10) score += 1;
            if (/[0-9]/.test(this.password)) score += 1;
            if (/[A-Z]/.test(this.password) || /[^A-Za-z0-9]/.test(this.password)) score += 1;
            return score;
        },
        
        get strengthLabel() {
            if (!this.password) return '';
            if (this.passwordScore <= 1) return 'Weak';
            if (this.passwordScore <= 2) return 'Fair';
            if (this.passwordScore <= 3) return 'Good';
            return 'Strong';
        },
        
        get strengthColor() {
            if (this.passwordScore <= 1) return 'bg-red-500';
            if (this.passwordScore <= 2) return 'bg-amber-500';
            if (this.passwordScore <= 3) return 'bg-blue-600';
            return 'bg-emerald-600';
        },
        
        get passwordsMatch() {
            if (!this.confirmPassword) return null;
            return this.password === this.confirmPassword;
        }
     }">
    <div class="max-w-md w-full">
        
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center space-x-2.5 mb-3 group">
                <span class="w-10 h-10 rounded-xl bg-blue-700 text-white font-black text-lg flex items-center justify-center shadow-xs">
                    N
                </span>
                <span class="text-2xl font-bold tracking-tight text-gray-900">NIRMAAN</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Set New Password</h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Create a new secure password for your defence portal account</p>
        </div>

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-xs">
            
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700 space-y-1">
                    <div class="font-bold flex items-center space-x-1.5">
                        <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Please fix the following:</span>
                    </div>
                    <ul class="list-disc list-inside pl-1 text-[11px] leading-relaxed">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-4" @submit="isSubmitting = true">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Confirm Account Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required 
                           value="{{ old('email', $email) }}" 
                           placeholder="cadet@nirmaan.com" 
                           class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white focus:border-transparent transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 mb-1.5">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               id="password" 
                               name="password" 
                               x-model="password"
                               required 
                               placeholder="Minimum 6 characters" 
                               class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 pr-10 outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white focus:border-transparent transition">
                        
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showPassword" style="display:none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>

                    <!-- Dynamic Strength Meter Bar -->
                    <div x-show="password.length > 0" class="mt-2 space-y-1">
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-gray-500">Security Strength:</span>
                            <span class="font-bold font-mono" :class="passwordScore >= 3 ? 'text-emerald-700' : (passwordScore >= 2 ? 'text-blue-700' : 'text-amber-700')" x-text="strengthLabel"></span>
                        </div>
                        <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden flex gap-0.5">
                            <div class="h-full transition-all flex-1" :class="passwordScore >= 1 ? strengthColor : 'bg-transparent'"></div>
                            <div class="h-full transition-all flex-1" :class="passwordScore >= 2 ? strengthColor : 'bg-transparent'"></div>
                            <div class="h-full transition-all flex-1" :class="passwordScore >= 3 ? strengthColor : 'bg-transparent'"></div>
                            <div class="h-full transition-all flex-1" :class="passwordScore >= 4 ? strengthColor : 'bg-transparent'"></div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Confirm New Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               x-model="confirmPassword"
                               required 
                               placeholder="Re-enter password" 
                               class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 pr-10 outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white focus:border-transparent transition">
                        
                        <button type="button" 
                                @click="showConfirm = !showConfirm" 
                                class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showConfirm" style="display:none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>

                    <div x-show="confirmPassword.length > 0" class="mt-1.5 text-[11px]">
                        <span x-show="passwordsMatch === true" class="text-emerald-700 font-medium flex items-center space-x-1">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span>Passwords match</span>
                        </span>
                        <span x-show="passwordsMatch === false" style="display:none;" class="text-red-600 font-medium flex items-center space-x-1">
                            <svg class="w-3.5 h-3.5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            <span>Passwords do not match yet</span>
                        </span>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            :disabled="isSubmitting"
                            class="w-full py-3 px-4 rounded-xl text-xs sm:text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 disabled:opacity-60 transition shadow-xs flex items-center justify-center space-x-2">
                        <span x-show="!isSubmitting">Update Password &amp; Sign In</span>
                        <span x-show="isSubmitting" style="display:none;" class="flex items-center space-x-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Updating Security Token...</span>
                        </span>
                    </button>
                </div>
            </form>

        </div>

    </div>
</div>
@endsection
