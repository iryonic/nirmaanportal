@extends('layouts.admin')

@section('title', 'Attempt Audit Log — ' . $test->title)
@section('header_title', 'Student Submissions: ' . $test->title)

@section('content')
<div class="space-y-5">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs">
        <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.tests.index') }}" class="hover:text-blue-950">Mock Tests</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold truncate max-w-xs">{{ $test->title }} Attempts</span>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-950 font-medium text-xs border border-blue-200/60">
                Total: {{ $attempts->total() }} Submissions
            </span>
            <a href="{{ route('admin.tests.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                &larr; Back
            </a>
        </div>
    </div>

    <!-- Attempts Table -->
    <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 py-2.5 whitespace-nowrap">Cadet Candidate</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Score</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Correct / Incorrect</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Accuracy</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Time</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Date</th>
                        <th class="px-4 py-2.5 whitespace-nowrap text-right">Review</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attempts as $att)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap font-semibold text-slate-900">
                                {{ $att->user ? $att->user->name : 'Cadet' }}
                                <span class="text-[10px] text-slate-400 font-normal block font-mono">{{ $att->user ? $att->user->email : '' }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-semibold text-slate-900 font-mono">
                                {{ $att->score }} <span class="text-slate-400 font-normal text-xs">/ {{ number_format($test->total_marks, 0) }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-mono">
                                <span class="text-emerald-700 font-semibold">+{{ $att->correct_count }}</span> / 
                                <span class="text-red-600 font-semibold">-{{ $att->incorrect_count }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-semibold text-slate-800 font-mono">
                                {{ $att->accuracy_percentage }}%
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-700 font-mono">
                                {{ gmdate('i:s', $att->time_taken_seconds) }} mins
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-400 font-medium">
                                {{ $att->submitted_at ? $att->submitted_at->format('d M, Y h:i A') : 'In Progress' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right font-medium">
                                <a href="{{ route('student.tests.result', $att->id) }}" target="_blank" class="px-2.5 py-1 rounded-md text-xs font-medium text-blue-950 hover:bg-blue-100 bg-blue-50 border border-blue-200/60 transition-colors inline-block shadow-2xs">
                                    Scorecard ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-xs text-slate-400">No attempts recorded for this test yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attempts->hasPages())
            <div class="p-3 border-t border-slate-200/80 bg-slate-50/50">
                {{ $attempts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
