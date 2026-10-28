@extends('layouts.admin')

@section('title', 'Cadet Aspirants Directory — Admin Command')
@section('header_title', 'Students ')

@section('content')
<div class="space-y-5">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs">
        <div>
            <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Cadets Roster</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Registered Defence Aspirants</h1>
            <p class="text-xs text-slate-500 mt-0.5">Inspect candidate progression, course batch subscriptions, CBT examination scorecards, and account security.</p>
        </div>
        <div class="flex items-center space-x-2 shrink-0">
            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                Total: {{ $students->total() }}
            </span>
            <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-xs font-medium border border-emerald-200/60">
                Active: {{ $totalActive ?? $students->total() }}
            </span>
            @if(isset($totalSuspended) && $totalSuspended > 0)
                <span class="px-2.5 py-1 rounded-md bg-red-50 text-red-700 text-xs font-medium border border-red-200/60">
                    Suspended: {{ $totalSuspended }}
                </span>
            @endif
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
            
            <!-- Keyword Search -->
            <div class="relative flex-grow min-w-[220px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" placeholder="Search cadet name, email, phone number, or target branch..." value="{{ request('search') }}" 
                       class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50/70 border border-slate-200 rounded-lg outline-none focus:border-blue-950 focus:bg-white focus:ring-1 focus:ring-blue-950/20 transition-colors">
            </div>

            <!-- Target Exam Filter -->
            <select name="exam" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="">All Target Services</option>
                <option value="Indian Army Agniveer" {{ request('exam') === 'Indian Army Agniveer' ? 'selected' : '' }}>Indian Army Agniveer</option>
                <option value="Indian Navy SSR/AA/MR" {{ request('exam') === 'Indian Navy SSR/AA/MR' ? 'selected' : '' }}>Indian Navy SSR/AA</option>
                <option value="Indian Air Force Group X/Y" {{ request('exam') === 'Indian Air Force Group X/Y' ? 'selected' : '' }}>Air Force Group X/Y</option>
                <option value="NDA / CDS" {{ request('exam') === 'NDA / CDS' ? 'selected' : '' }}>NDA / CDS (Officer)</option>
                <option value="AFCAT" {{ request('exam') === 'AFCAT' ? 'selected' : '' }}>AFCAT</option>
                <option value="BSF Constable/ASI" {{ request('exam') === 'BSF Constable/ASI' ? 'selected' : '' }}>BSF Constable/ASI</option>
            </select>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="">All Account Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Cadets Only</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended Cadets</option>
            </select>

            <button type="submit" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-white transition-colors shadow-2xs">
                Filter
            </button>

            @if(request()->hasAny(['search', 'exam', 'status']))
                <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold text-slate-500 hover:text-red-600 transition-colors pl-1">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 py-2.5 whitespace-nowrap">Cadet Profile</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Contact Information</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Target Service</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Enrolled Batches</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">CBT Submissions</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Status</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Joined Date</th>
                        <th class="px-4 py-2.5 whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $st)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Cadet Profile -->
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-900">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-950 text-white font-semibold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($st->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.students.show', $st->id) }}" class="font-semibold text-slate-900 text-xs hover:text-blue-950 transition-colors block">
                                            {{ $st->name }}
                                        </a>
                                        <span class="text-[10px] text-slate-400 font-mono">UID #{{ $st->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Info -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-medium text-slate-800 block">{{ $st->email }}</span>
                                <span class="text-[11px] text-slate-400 block font-mono">{{ $st->phone ?? 'No phone' }}</span>
                            </td>

                            <!-- Target Service -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-blue-50 text-blue-950 border border-blue-200/60">
                                    {{ $st->target_exam ?? 'NDA / CDS' }}
                                </span>
                            </td>

                            <!-- Enrolled Batches -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $st->enrollments_count }} Batches
                                </span>
                            </td>

                            <!-- CBT Attempts -->
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-800">
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-sky-50 text-sky-900 border border-sky-200/60">
                                    {{ $st->test_attempts_count }} Tests
                                </span>
                            </td>

                            <!-- Account Status -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase {{ $st->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-red-50 text-red-700 border border-red-200/60' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $st->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    <span>{{ $st->is_active ? 'Active' : 'Suspended' }}</span>
                                </span>
                            </td>

                            <!-- Joined Date -->
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-400 text-[11px]">
                                {{ $st->created_at->format('M d, Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 whitespace-nowrap text-right space-x-1.5">
                                <a href="{{ route('admin.students.show', $st->id) }}" class="px-2.5 py-1 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-950 font-medium text-xs transition-colors border border-blue-200/60 shadow-2xs">
                                    Dossier
                                </a>
                                <form action="{{ route('admin.students.toggle', $st->id) }}" method="POST" class="inline" onsubmit="return confirm('Change status for this cadet account?');">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 rounded-md text-xs font-medium {{ $st->is_active ? 'text-amber-700 hover:bg-amber-50 border border-amber-200' : 'text-emerald-700 hover:bg-emerald-50 border border-emerald-200' }} transition-colors shadow-2xs">
                                        {{ $st->is_active ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-xs text-slate-400">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                No cadets found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="p-3 border-t border-slate-200/80 bg-slate-50/50">
                {{ $students->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
