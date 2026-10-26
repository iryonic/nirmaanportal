@extends('layouts.app')

@section('title', 'Student Registration — Nirmaan Defence Platform')

@section('content')
<div class="py-12 sm:py-16 flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block mb-3">
                <img src="{{ asset('images/logo.svg') }}" alt="Nirmaan" class="h-10 w-auto mx-auto">
            </a>
            <h1 class="text-2xl font-extrabold text-gray-900">Create Student Account</h1>
            <p class="text-xs text-gray-500 mt-1">Start your journey toward a proud career in the security forces</p>
        </div>

        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xl shadow-gray-200/50">
            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Full Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Vikram Singh" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                    @error('name')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Email Address *</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="vikram@example.com" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                    @error('email')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Mobile Number *</label>
                    <input type="tel" name="phone" required value="{{ old('phone') }}" placeholder="+91 98765 43210" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                    @error('phone')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Target Examination *</label>
                    <select name="target_exam" required class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                        <option value="Indian Army Agniveer" {{ old('target_exam') === 'Indian Army Agniveer' ? 'selected' : '' }}>Indian Army (Agniveer / GD / Tech)</option>
                        <option value="Indian Navy SSR/AA/MR" {{ old('target_exam') === 'Indian Navy SSR/AA/MR' ? 'selected' : '' }}>Indian Navy (SSR / AA / MR)</option>
                        <option value="Indian Air Force Group X/Y" {{ old('target_exam') === 'Indian Air Force Group X/Y' ? 'selected' : '' }}>Indian Air Force (Group X / Y)</option>
                        <option value="BSF Constable/ASI" {{ old('target_exam') === 'BSF Constable/ASI' ? 'selected' : '' }}>BSF (Border Security Force)</option>
                        <option value="CISF Constable/ASI" {{ old('target_exam') === 'CISF Constable/ASI' ? 'selected' : '' }}>CISF (Central Industrial Security Force)</option>
                        <option value="SSB / CAPF AC" {{ old('target_exam') === 'SSB / CAPF AC' ? 'selected' : '' }}>SSB / CAPF Assistant Commandant</option>
                        <option value="NDA / CDS" {{ old('target_exam') === 'NDA / CDS' ? 'selected' : '' }}>NDA / CDS (Officer Cadre)</option>
                    </select>
                    @error('target_exam')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Password *</label>
                    <input type="password" name="password" required placeholder="Minimum 6 characters" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                    @error('password')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required placeholder="Re-enter password" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-full text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-md transition transform hover:-translate-y-0.5">
                    Create Account
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 text-center text-xs text-gray-500">
                Already registered? 
                <a href="{{ route('login') }}" class="font-bold text-blue-700 hover:underline">
                    Sign In
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
