@extends('layouts.app')

@section('title', '429 — Rate Limit Exceeded | Nirmaan Defence Academy')

@section('content')
<div class="py-16 sm:py-24 flex items-center justify-center px-4 bg-gray-50/50 min-h-[calc(100vh-200px)]">
    <div class="max-w-xl w-full text-center space-y-6">
        
        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-red-50 border border-red-200 text-red-800 text-xs font-mono font-bold">
            <span class="w-2 h-2 rounded-full bg-red-600 animate-ping"></span>
            <span>HTTP 429 • RATE LIMITING / FIREWALL ACTIVE</span>
        </div>

        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Too Many Requests Received</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-2 max-w-md mx-auto leading-relaxed">
                Our infrastructure has detected a high frequency of consecutive attempts from your IP terminal. Automated rate limiting has been engaged to safeguard exam servers.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-2xs max-w-md mx-auto space-y-4">
            <div class="p-3 bg-gray-50 rounded-xl text-xs text-gray-600 space-y-1">
                <span class="font-bold text-gray-800 block">Recommended Action:</span>
                <p>Please wait <span class="font-bold text-blue-700 font-mono">60 seconds</span> before attempting your request or sign-in again.</p>
            </div>

            <div class="space-y-2">
                <button type="button" 
                        onclick="window.location.reload()" 
                        class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition shadow-2xs">
                    Retry Request Now
                </button>
                <a href="{{ route('home') }}" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 transition block text-center">
                    Return to Academy Homepage
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
