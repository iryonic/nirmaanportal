@extends('layouts.app')

@section('title', '419 — Session Expired | Nirmaan Defence Academy')

@section('content')
<div class="py-16 sm:py-24 flex items-center justify-center px-4 bg-gray-50/50 min-h-[calc(100vh-200px)]">
    <div class="max-w-xl w-full text-center space-y-6">
        
        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-200 text-blue-800 text-xs font-mono font-bold">
            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
            <span>HTTP 419 • SECURITY TOKEN REFRESH REQUIRED</span>
        </div>

        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Portal Session Expired</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-2 max-w-md mx-auto leading-relaxed">
                For cadet data privacy and CSRF protection, inactive submission tokens automatically expire. Simply refresh to establish a renewed security handshake.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-2xs max-w-md mx-auto space-y-3">
            <button type="button" 
                    onclick="window.location.reload()" 
                    class="w-full py-3 px-4 rounded-xl text-xs sm:text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 transition shadow-2xs flex items-center justify-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span>Refresh &amp; Continue Session</span>
            </button>

            <a href="{{ route('login') }}" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 transition block text-center">
                Return to Sign In Screen
            </a>
        </div>

    </div>
</div>
@endsection
