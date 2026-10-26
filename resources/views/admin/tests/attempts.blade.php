@extends('layouts.admin')

@section('title', 'Attempt Audit Log — ' . $test->title)
@section('header_title', 'Student Submissions: ' . $test->title)

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between bg-white rounded-2xl p-4 border border-gray-100 shadow-2xs">
        <div class="flex items-center space-x-2 text-xs font-semibold text-gray-500">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
            <span>&bull;</span>
            <a href="{{ route('admin.tests.index') }}" class="hover:text-blue-700">Mock Tests</a>
            <span>&bull;</span>
            <span class="text-blue-700 font-bold truncate max-w-xs">{{ $test->title }} Attempts</span>
        </div>
        <div class="flex items-center space-x-3">
            <span class="text-xs text-gray-500 font-medium">Total: {{ $attempts->total() }} Submissions</span>
            <a href="{{ route('admin.tests.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800">
                ← Back to Tests
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Score</th>
                        <th class="px-6 py-4">Correct / Incorrect</th>
                        <th class="px-6 py-4">Accuracy</th>
                        <th class="px-6 py-4">Time Taken</th>
                        <th class="px-6 py-4">Submission Date</th>
                        <th class="px-6 py-4 text-right">Review</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attempts as $att)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ $att->user ? $att->user->name : 'Anonymous Cadet' }}
                                <span class="text-[10px] text-gray-400 font-normal block">{{ $att->user ? $att->user->email : '' }}</span>
                            </td>
                            <td class="px-6 py-4 font-black text-emerald-700 text-sm">
                                {{ $att->score }} / {{ number_format($test->total_marks, 0) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-emerald-700 font-bold">+{{ $att->correct_count }}</span> / 
                                <span class="text-red-600 font-bold">-{{ $att->incorrect_count }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $att->accuracy_percentage }}%</td>
                            <td class="px-6 py-4">{{ gmdate('i:s', $att->time_taken_seconds) }} mins</td>
                            <td class="px-6 py-4 text-gray-400">{{ $att->submitted_at ? $att->submitted_at->format('d M, Y h:i A') : 'In Progress' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('student.tests.result', $att->id) }}" target="_blank" class="text-xs font-bold text-blue-700 hover:underline">
                                    Full Sheet ↗
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
        <div class="p-4 border-t border-gray-100">
            {{ $attempts->links() }}
        </div>
    </div>

</div>
@endsection
