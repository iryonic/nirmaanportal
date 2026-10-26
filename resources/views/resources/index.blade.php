@extends('layouts.app')

@section('title', 'Free Defence Study Resources & Downloads — Nirmaan')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex text-xs text-gray-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800 font-semibold">Free Resources</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-gray-900">Free Defence Preparation Resources</h1>
            <p class="text-sm text-gray-600 mt-1 max-w-2xl">
                Download verified official syllabus copies, high-yield revision sheets, previous year papers, and attempt free simulated mock tests.
            </p>
        </div>

        <!-- Free Mock Tests Highlight -->
        @if($freeMockTests->count() > 0)
            <div class="mb-12">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Free Interactive Mock Tests</span>
                    </h2>
                    <a href="{{ route('student.tests.index') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-900">
                        View All Tests →
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($freeMockTests as $test)
                        <div class="bg-white rounded-xl p-5 border border-gray-200 flex items-center justify-between shadow-2xs">
                            <div>
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-100 uppercase">
                                    {{ $test->examCategory ? $test->examCategory->name : 'Defence Mock' }}
                                </span>
                                <h3 class="text-sm font-bold text-gray-900 mt-2">{{ $test->title }}</h3>
                                <div class="flex items-center space-x-4 text-xs text-gray-500 mt-1">
                                    <span>⏱ {{ $test->duration_minutes }} Mins</span>
                                    <span>•</span>
                                    <span>🎯 {{ number_format($test->total_marks, 0) }} Marks</span>
                                </div>
                            </div>
                            <a href="{{ route('student.tests.instructions', $test->id) }}" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition flex-shrink-0">
                                Start Test
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Filter bar -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('resources.index') }}" class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ !request('type') ? 'bg-blue-700 text-white' : 'bg-white text-gray-600 border border-gray-200' }}">
                All Materials
            </a>
            <a href="{{ route('resources.index', ['type' => 'syllabus']) }}" class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ request('type') === 'syllabus' ? 'bg-blue-700 text-white' : 'bg-white text-gray-600 border border-gray-200' }}">
                Official Syllabi
            </a>
            <a href="{{ route('resources.index', ['type' => 'pdf']) }}" class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ request('type') === 'pdf' ? 'bg-blue-700 text-white' : 'bg-white text-gray-600 border border-gray-200' }}">
                Revision Notes
            </a>
            <a href="{{ route('resources.index', ['type' => 'previous_paper']) }}" class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ request('type') === 'previous_paper' ? 'bg-blue-700 text-white' : 'bg-white text-gray-600 border border-gray-200' }}">
                Previous Papers
            </a>
        </div>

        <!-- Materials List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($materials as $material)
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-xs hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full">
                                {{ strtoupper($material->type) }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium">{{ $material->file_size ?? '2.5 MB' }}</span>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 leading-snug mb-2">
                            {{ $material->title }}
                        </h3>
                        <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed mb-4">
                            {{ $material->description }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[11px] text-gray-400">
                            📥 {{ number_format($material->downloads_count) }} downloads
                        </span>
                        <a href="{{ route('resources.download', $material->id) }}" class="inline-flex items-center space-x-1.5 px-4 py-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-full transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Download</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-500">
                    No resources matching the selected filter.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $materials->links() }}
        </div>

    </div>
</div>
@endsection
