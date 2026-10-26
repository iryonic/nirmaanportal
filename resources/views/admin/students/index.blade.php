@extends('layouts.admin')

@section('title', 'Cadet Aspirants Directory — Admin')
@section('header_title', 'Cadets & Aspirants Directory')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-3xl p-6 border border-gray-100 shadow-xs">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-400 font-semibold mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
                <span>&bull;</span>
                <span class="text-blue-700">Cadets Roster</span>
            </div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight">Registered Defence Aspirants</h1>
            <p class="text-xs text-gray-500 mt-0.5">Inspect student progression, course enrollments, CBT exam histories, and manage manual access grants.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-3.5 py-1.5 rounded-xl bg-blue-50 text-blue-800 text-xs font-bold border border-blue-200">
                Total Registered: {{ $students->total() }}
            </span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-2xs">
        <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            
            <!-- Keyword Search -->
            <div class="relative flex-grow min-w-[240px]">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" placeholder="Search cadet name, email, phone, or target exam..." value="{{ request('search') }}" 
                       class="w-full pl-9 pr-4 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-blue-500 focus:bg-white transition">
            </div>

            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white transition">
                Search Cadets
            </button>

            @if(request()->filled('search'))
                <a href="{{ route('admin.students.index') }}" class="text-xs text-gray-500 hover:text-red-600 transition pl-1">
                    Clear Search ✕
                </a>
            @endif
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Cadet Profile</th>
                        <th class="px-6 py-4">Contact Information</th>
                        <th class="px-6 py-4">Target Service</th>
                        <th class="px-6 py-4">Enrolled Batches</th>
                        <th class="px-6 py-4">CBT Attempts</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $st)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 font-extrabold text-sm flex items-center justify-center flex-shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($st->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.students.show', $st->id) }}" class="font-extrabold text-gray-900 text-sm hover:text-blue-700 transition block">
                                            {{ $st->name }}
                                        </a>
                                        <span class="text-[10px] text-gray-400">Joined {{ $st->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-800 block">{{ $st->email }}</span>
                                <span class="text-[11px] text-gray-400 block">{{ $st->phone ?? 'No phone provided' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-800 border border-blue-200">
                                    {{ $st->target_exam ?? 'NDA / CDS' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-md bg-gray-100 text-gray-700 font-bold text-[11px]">
                                    {{ $st->enrollments_count }} Courses
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ $st->test_attempts_count }} Tests
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $st->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                                    {{ $st->is_active ? '● Active' : '● Suspended' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 font-bold">
                                <a href="{{ route('admin.students.show', $st->id) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 transition">
                                    Manage Cadet
                                </a>
                                <form action="{{ route('admin.students.toggle', $st->id) }}" method="POST" class="inline" onsubmit="return confirm('Toggle status for this cadet?');">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold {{ $st->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition">
                                        {{ $st->is_active ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-xs text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                No cadets found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $students->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
