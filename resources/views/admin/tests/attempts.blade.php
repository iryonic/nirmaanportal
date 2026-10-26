@extends('layouts.admin')

@section('title', 'Attempt Audit Log — ' . $test->title)
@section('header_title', 'Student Submissions: ' . $test->title)

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between bg-white rounded-2xl p-4 sm:p-5 border border-gray-200 shadow-2xs">
        <div class="flex items-center space-x-2 text-xs font-semibold text-gray-500">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
            <span>&bull;</span>
            <a href="{{ route('admin.tests.index') }}" class="hover:text-blue-700">Mock Tests</a>
            <span>&bull;</span>
            <span class="text-blue-700 font-bold truncate max-w-xs">{{ $test->title }} Attempts</span>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs border border-blue-200">
                Total: {{ $attempts->total() }} Submissions
            </span>
            <a href="{{ route('admin.tests.index') }}" class="text-xs font-bold text-gray-600 hover:text-gray-900 transition">
                ← Back to Tests
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">Cadet Candidate</th>
                        <th class="px-6 py-4 whitespace-nowrap">Score Obtained</th>
                        <th class="px-6 py-4 whitespace-nowrap">Correct / Incorrect</th>
                        <th class="px-6 py-4 whitespace-nowrap">Accuracy Rate</th>
                        <th class="px-6 py-4 whitespace-nowrap">Time Consumed</th>
                        <th class="px-6 py-4 whitespace-nowrap">Submission Date</th>
                        <th class="px-6 py-4 whitespace-nowrap text-right">Review</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attempts as $att)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                {{ $att->user ? $att->user->name : 'Cadet' }}
                                <span class="text-[10px] text-gray-400 font-normal block font-mono">{{ $att->user ? $att->user->email : '' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-emerald-700 text-sm font-mono">
                                {{ $att->score }} <span class="text-gray-400 font-normal text-xs">/ {{ number_format($test->total_marks, 0) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-mono">
                                <span class="text-emerald-700 font-bold">+{{ $att->correct_count }}</span> / 
                                <span class="text-red-600 font-bold">-{{ $att->incorrect_count }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-800 font-mono">
                                {{ $att->accuracy_percentage }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-mono">
                                {{ gmdate('i:s', $att->time_taken_seconds) }} mins
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-400 font-medium">
                                {{ $att->submitted_at ? $att->submitted_at->format('d M, Y h:i A') : 'In Progress' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold">
                                <a href="{{ route('student.tests.result', $att->id) }}" target="_blank" class="px-2.5 py-1 rounded-lg text-xs font-bold text-blue-700 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 transition inline-block">
                                    Scorecard ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-xs text-gray-400">No attempts recorded for this test yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attempts->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                {{ $attempts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
