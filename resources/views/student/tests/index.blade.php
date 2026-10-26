@extends('layouts.student')

@section('title', 'Mock Tests Series — Nirmaan')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Official Pattern Mock Tests</h1>
            <p class="text-xs text-gray-500 mt-1">Simulated full-length computer-based tests (CBT) strictly adhering to negative marking and timing rules.</p>
        </div>

        <!-- Available Tests Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tests as $test)
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xs hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $test->examCategory ? $test->examCategory->name : 'Defence Forces' }}
                            </span>
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                                {{ $test->is_free ? 'Free Test' : 'Premium' }}
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-gray-900 leading-snug mb-2">
                            {{ $test->title }}
                        </h3>
                        <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed mb-4">
                            {{ $test->description }}
                        </p>

                        <div class="grid grid-cols-3 gap-2 py-3 bg-gray-50/70 rounded-2xl border border-gray-100 text-center mb-6">
                            <div>
                                <span class="text-[10px] text-gray-400 block font-medium">Questions</span>
                                <span class="text-xs font-bold text-gray-800">{{ $test->questions_count }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 block font-medium">Duration</span>
                                <span class="text-xs font-bold text-gray-800">{{ $test->duration_minutes }}m</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 block font-medium">Max Marks</span>
                                <span class="text-xs font-bold text-gray-800">{{ number_format($test->total_marks, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[11px] text-amber-600 font-medium">
                            -{{ $test->negative_marking_ratio }} Negative Marking
                        </span>
                        <a href="{{ route('student.tests.instructions', $test->id) }}" class="px-5 py-2 rounded-full text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-xs transition">
                            Start Exam →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Completed Attempts History -->
        @if($attempts->count() > 0)
            <div class="pt-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">My Past Test Submissions</h2>
                <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-gray-600">
                            <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Test Title</th>
                                    <th class="px-6 py-4">Score</th>
                                    <th class="px-6 py-4">Accuracy</th>
                                    <th class="px-6 py-4">Time Taken</th>
                                    <th class="px-6 py-4">Submitted On</th>
                                    <th class="px-6 py-4 text-right">Review</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($attempts as $att)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4 font-bold text-gray-900">{{ $att->mockTest->title }}</td>
                                        <td class="px-6 py-4 font-extrabold text-emerald-700">{{ $att->score }} / {{ number_format($att->mockTest->total_marks, 0) }}</td>
                                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $att->accuracy_percentage }}%</td>
                                        <td class="px-6 py-4">{{ gmdate('i:s', $att->time_taken_seconds) }} mins</td>
                                        <td class="px-6 py-4 text-gray-400">{{ $att->submitted_at ? $att->submitted_at->format('d M, Y h:i A') : '-' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('student.tests.result', $att->id) }}" class="text-xs font-bold text-blue-700 hover:underline">
                                                View Analysis →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
