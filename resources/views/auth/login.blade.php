@extends('layouts.app')

@section('title', 'Sign In — Nirmaan Defence Portal')

@section('content')
<div class="py-12 sm:py-20 flex items-center justify-center px-4">
    <div class="max-w-md w-full" x-data="{ roleTab: 'student' }">
        
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block mb-3">
                <img src="{{ asset('images/logo.svg') }}" alt="Nirmaan" class="h-10 w-auto mx-auto">
            </a>
            <h1 class="text-2xl font-extrabold text-gray-900">Welcome to Nirmaan</h1>
            <p class="text-xs text-gray-500 mt-1">Sign in to your learning account or administrative workspace</p>
        </div>

        <!-- Quick Demo Switcher Pills -->
        <div class="bg-gray-200/70 p-1 rounded-2xl flex mb-6 text-xs font-bold text-gray-600">
            <button type="button" @click="roleTab = 'student'; document.getElementById('email').value='student@nirmaan.com'; document.getElementById('password').value='password123';" :class="roleTab === 'student' ? 'bg-white text-blue-700 shadow-xs' : 'hover:text-gray-900'" class="flex-1 py-2 rounded-xl transition text-center">
                Student Account
            </button>
            <button type="button" @click="roleTab = 'admin'; document.getElementById('email').value='admin@nirmaan.com'; document.getElementById('password').value='password123';" :class="roleTab === 'admin' ? 'bg-white text-slate-900 shadow-xs' : 'hover:text-gray-900'" class="flex-1 py-2 rounded-xl transition text-center">
                Admin Console
            </button>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xl shadow-gray-200/50">
            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" required value="{{ old('email', 'student@nirmaan.com') }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                    @error('email')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-bold text-gray-700">Password</label>
                        <span class="text-[11px] text-blue-600">Default: password123</span>
                    </div>
                    <input type="password" id="password" name="password" required value="password123" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                    @error('password')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center space-x-2 text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-blue-600 focus:ring-blue-500">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-full text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-md transition transform hover:-translate-y-0.5">
                    Sign In
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 text-center text-xs text-gray-500">
                Don’t have an account yet? 
                <a href="{{ route('register') }}" class="font-bold text-blue-700 hover:underline">
                    Register as Student
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
