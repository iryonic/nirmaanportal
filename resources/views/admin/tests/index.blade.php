@extends('layouts.admin')

@section('title', 'Mock Tests Management — Admin')
@section('header_title', 'Mock Test Engine & Examination Series')

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Mock Examination Sets</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage simulated full-length CBT tests and monitor student attempt performance.</p>
        </div>
        <a href="{{ route('admin.tests.create') }}" class="px-5 py-2.5 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
            + Create New Mock Test
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Test Title</th>
                        <th class="px-6 py-4">Branch Category</th>
                        <th class="px-6 py-4">Duration &amp; Marks</th>
                        <th class="px-6 py-4">Questions</th>
                        <th class="px-6 py-4">Total Attempts</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tests as $t)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-bold text-gray-900">
                                <a href="{{ route('admin.tests.edit', $t->id) }}" class="hover:text-blue-700">
                                    {{ $t->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800">
                                    {{ $t->examCategory ? $t->examCategory->name : 'General' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ $t->duration_minutes }}m • {{ number_format($t->total_marks, 0) }} Marks
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-700">{{ $t->questions_count }} Questions</td>
                            <td class="px-6 py-4 font-extrabold text-blue-700">
                                <a href="{{ route('admin.tests.attempts', $t->id) }}" class="hover:underline">
                                    {{ $t->attempts_count }} Attempts ↗
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $t->is_published ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $t->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.tests.edit', $t->id) }}" class="text-blue-700 hover:underline font-bold">Edit</a>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('admin.tests.attempts', $t->id) }}" class="text-gray-600 hover:text-gray-900 font-semibold">Results</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-xs text-gray-400">No mock tests configured yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $tests->links() }}
        </div>
    </div>

</div>
@endsection
