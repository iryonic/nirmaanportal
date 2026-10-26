@extends('layouts.app')

@section('title', '500 — Server Incident | Nirmaan Defence Academy')

@section('content')
<div class="py-16 sm:py-24 flex items-center justify-center px-4 bg-gray-50/50 min-h-[calc(100vh-200px)]">
    <div class="max-w-xl w-full text-center space-y-6">
        
        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-red-50 border border-red-200 text-red-800 text-xs font-mono font-bold">
            <span class="w-2 h-2 rounded-full bg-red-600"></span>
            <span>HTTP 500 • INTERNAL SYSTEM EXCEPTION</span>
        </div>

        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Internal Platform Interruption</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-2 max-w-md mx-auto leading-relaxed">
                An unexpected technical exception occurred while processing this transaction. Our engineering division has received the error trace for diagnostics.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-2xs max-w-md mx-auto space-y-4 text-left">
            <div class="space-y-1 text-xs">
                <span class="text-[10px] uppercase font-bold text-gray-400 block tracking-wider">Automated Diagnostics Reference</span>
                <p class="font-mono font-bold text-gray-800 bg-gray-50 p-2.5 rounded-lg border border-gray-200 text-[11px]">
                    REF: NIR-SYS-{{ date('Ymd') }}-{{ strtoupper(substr(md5(microtime()), 0, 8)) }}
                </p>
            </div>

            <div class="space-y-2 pt-2">
                <button type="button" 
                        onclick="window.location.reload()" 
                        class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition shadow-2xs">
                    Retry Operation
                </button>
                <a href="{{ route('home') }}" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 transition block text-center">
                    Return to Safe Ground (Home)
                </a>
            </div>

            <div class="pt-2 border-t border-gray-100 text-[11px] text-gray-400 text-center">
                Need urgent assistance during a scheduled mock test? Contact <a href="mailto:support@nirmaan.edu.in" class="text-blue-700 underline font-semibold">support@nirmaan.edu.in</a>.
            </div>
        </div>

    </div>
</div>
@endsection
