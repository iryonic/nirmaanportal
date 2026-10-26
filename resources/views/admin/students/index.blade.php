@extends('layouts.admin')

@section('title', 'Cadet Aspirants Directory — Admin Command')
@section('header_title', 'Cadets &amp; Aspirants Directory')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-2xl p-5 sm:p-6 border border-gray-200 shadow-2xs">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-400 font-semibold mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
                <span>&bull;</span>
                <span class="text-blue-700">Cadets Roster</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Registered Defence Aspirants</h1>
            <p class="text-xs text-gray-500 mt-0.5">Inspect candidate progression, course batch subscriptions, CBT examination scorecards, and account security.</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-800 text-xs font-bold border border-blue-200">
                Total: {{ $students->total() }}
            </span>
            <span class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                Active: {{ $totalActive ?? $students->total() }}
            </span>
            @if(isset($totalSuspended) && $totalSuspended > 0)
                <span class="px-3 py-1.5 rounded-xl bg-red-50 text-red-800 text-xs font-bold border border-red-200">
                    Suspended: {{ $totalSuspended }}
                </span>
            @endif
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-2xs">
        <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            
            <!-- Keyword Search -->
            <div class="relative flex-grow min-w-[240px]">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" placeholder="Search cadet name, email, phone number, or target branch..." value="{{ request('search') }}" 
                       class="w-full pl-9 pr-4 py-2.5 text-xs bg-gray-50 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white focus:border-transparent transition">
            </div>

            <!-- Target Exam Filter -->
            <select name="exam" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-600">
                <option value="">All Target Services</option>
                <option value="Indian Army Agniveer" {{ request('exam') === 'Indian Army Agniveer' ? 'selected' : '' }}>Indian Army Agniveer</option>
                <option value="Indian Navy SSR/AA/MR" {{ request('exam') === 'Indian Navy SSR/AA/MR' ? 'selected' : '' }}>Indian Navy SSR/AA</option>
                <option value="Indian Air Force Group X/Y" {{ request('exam') === 'Indian Air Force Group X/Y' ? 'selected' : '' }}>Air Force Group X/Y</option>
                <option value="NDA / CDS" {{ request('exam') === 'NDA / CDS' ? 'selected' : '' }}>NDA / CDS (Officer)</option>
                <option value="AFCAT" {{ request('exam') === 'AFCAT' ? 'selected' : '' }}>AFCAT</option>
                <option value="BSF Constable/ASI" {{ request('exam') === 'BSF Constable/ASI' ? 'selected' : '' }}>BSF Constable/ASI</option>
            </select>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-600">
                <option value="">All Account Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Cadets Only</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended Cadets</option>
            </select>

            <button type="submit" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white transition shadow-2xs">
                Filter Roster
            </button>

            @if(request()->hasAny(['search', 'exam', 'status']))
                <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-gray-500 hover:text-red-600 transition pl-1">
                    Clear Filters ✕
                </a>
            @endif
        </form>
    </div>

    <!-- Students Table with strict whitespace-nowrap -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">Cadet Profile</th>
                        <th class="px-6 py-4 whitespace-nowrap">Contact Information</th>
                        <th class="px-6 py-4 whitespace-nowrap">Target Service</th>
                        <th class="px-6 py-4 whitespace-nowrap">Enrolled Batches</th>
                        <th class="px-6 py-4 whitespace-nowrap">CBT Submissions</th>
                        <th class="px-6 py-4 whitespace-nowrap">Account Status</th>
                        <th class="px-6 py-4 whitespace-nowrap">Joined Date</th>
                        <th class="px-6 py-4 whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $st)
                        <tr class="hover:bg-gray-50/70 transition">
                            <!-- Cadet Profile -->
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 font-bold text-sm flex items-center justify-center shrink-0 border border-blue-200">
                                        {{ strtoupper(substr($st->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.students.show', $st->id) }}" class="font-bold text-gray-900 text-sm hover:text-blue-700 transition block">
                                            {{ $st->name }}
                                        </a>
                                        <span class="text-[10px] text-gray-400 font-mono">UID #{{ $st->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium text-gray-800 block">{{ $st->email }}</span>
                                <span class="text-[11px] text-gray-400 block font-mono">{{ $st->phone ?? 'No phone' }}</span>
                            </td>

                            <!-- Target Service -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                    {{ $st->target_exam ?? 'NDA / CDS' }}
                                </span>
                            </td>

                            <!-- Enrolled Batches -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 font-bold text-[11px]">
                                    {{ $st->enrollments_count }} Batches
                                </span>
                            </td>

                            <!-- CBT Attempts -->
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                                <span class="px-2.5 py-1 rounded-md bg-sky-50 text-sky-800 font-bold text-[11px] border border-sky-100">
                                    {{ $st->test_attempts_count }} Tests
                                </span>
                            </td>

                            <!-- Account Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $st->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                                    {{ $st->is_active ? '● Active' : '● Suspended' }}
                                </span>
                            </td>

                            <!-- Joined Date -->
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-400 text-[11px]">
                                {{ $st->created_at->format('M d, Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2 font-bold">
                                <a href="{{ route('admin.students.show', $st->id) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 transition">
                                    Dossier
                                </a>
                                <form action="{{ route('admin.students.toggle', $st->id) }}" method="POST" class="inline" onsubmit="return confirm('Change status for this cadet account?');">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold {{ $st->is_active ? 'text-amber-700 hover:bg-amber-50 border border-amber-200' : 'text-emerald-700 hover:bg-emerald-50 border border-emerald-200' }} transition">
                                        {{ $st->is_active ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-xs text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                No cadets found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                {{ $students->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
