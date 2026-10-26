@extends('layouts.admin')

@section('title', 'Registered Students — Admin')
@section('header_title', 'Student Directory & Accounts')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Registered Defence Aspirants</h1>
            <p class="text-xs text-gray-500 mt-0.5">Audit student profiles, active enrollments, and access status.</p>
        </div>

        <!-- Search Form -->
        <form action="{{ route('admin.students.index') }}" method="GET" class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone..." class="text-xs bg-white border border-gray-200 rounded-full px-4 py-2 w-64 outline-none focus:border-blue-500 shadow-2xs">
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Cadet Name</th>
                        <th class="px-6 py-4">Contact Details</th>
                        <th class="px-6 py-4">Target Examination</th>
                        <th class="px-6 py-4">Batches</th>
                        <th class="px-6 py-4">Test Submissions</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $st)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 block">{{ $st->name }}</span>
                                <span class="text-[10px] text-gray-400">Joined {{ $st->created_at->format('M Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-800 block">{{ $st->email }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">{{ $st->phone ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-blue-800">
                                {{ $st->target_exam ?? 'General' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $st->enrollments_count }} Courses</td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $st->test_attempts_count }} Mocks</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $st->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                                    {{ $st->is_active ? 'Active' : 'Suspended' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.students.show', $st->id) }}" class="text-xs font-bold text-blue-700 hover:underline">
                                    Profile
                                </a>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('admin.students.toggle', $st->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold {{ $st->is_active ? 'text-amber-600 hover:underline' : 'text-emerald-600 hover:underline' }}">
                                        {{ $st->is_active ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-xs text-gray-400">No students registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $students->links() }}
        </div>
    </div>

</div>
@endsection
