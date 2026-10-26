@extends('layouts.app')

@section('title', '403 — Restricted Area | Nirmaan Defence Academy')

@section('content')
<div class="py-16 sm:py-24 flex items-center justify-center px-4 bg-gray-50/50 min-h-[calc(100vh-200px)]">
    <div class="max-w-xl w-full text-center space-y-6">
        
        <!-- Status Code Pill -->
        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-mono font-bold">
            <span class="w-2 h-2 rounded-full bg-amber-600"></span>
            <span>HTTP 403 • ACCESS RESTRICTED / CLEARANCE REQUIRED</span>
        </div>

        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Security Clearance Restricted</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-2 max-w-md mx-auto leading-relaxed">
                Your current account permissions do not permit entry into this restricted academy partition. This section is reserved for authorized faculty or administrative officers.
            </p>
        </div>

        <!-- Clearance Resolution Card -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-2xs text-left max-w-md mx-auto space-y-4">
            <div class="flex items-start space-x-3 pb-3 border-b border-gray-100">
                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 font-bold text-sm">
                    !
                </div>
                <div>
                    <h3 class="text-xs font-bold text-gray-900">Standard Operational Procedures</h3>
                    <p class="text-[11px] text-gray-500 mt-0.5">If you are a student, please return to your learning portal. If you are an administrator, verify your staff credentials.</p>
                </div>
            </div>

            <div class="space-y-2 pt-1 text-xs">
                @if(Auth::check())
                    @if(Auth::user()->isStudent())
                        <a href="{{ route('student.dashboard') }}" class="w-full py-2.5 px-4 rounded-xl text-center font-bold text-white bg-blue-700 hover:bg-blue-800 transition block shadow-2xs">
                            Switch to Cadet Learning Dashboard →
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="w-full py-2.5 px-4 rounded-xl text-center font-bold text-white bg-slate-900 hover:bg-black transition block shadow-2xs">
                            Go to Administrative Console →
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="w-full py-2.5 px-4 rounded-xl text-center font-bold text-white bg-blue-700 hover:bg-blue-800 transition block shadow-2xs">
                        Sign In with Authorized Credentials →
                    </a>
                @endif

                <a href="{{ route('home') }}" class="w-full py-2.5 px-4 rounded-xl text-center font-semibold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 transition block">
                    Return to Public Academy Homepage
                </a>
            </div>
        </div>

        <div class="text-[11px] text-gray-400">
            Security incident reference logged for audit trail. Questions? Contact <span class="font-mono text-gray-600">security@nirmaan.edu.in</span>.
        </div>

    </div>
</div>
@endsection
