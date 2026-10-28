@extends('layouts.admin')

@section('title', 'Cadet Dossier: ' . $student->name . ' — Admin Command')
@section('header_title', 'Student View')

@section('content')
<div class="space-y-5 max-w-5xl mx-auto">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs">
        <div class="flex  flex-wrap items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.students.index') }}" class="hover:text-blue-950">Cadets</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold truncate max-w-xs">{{ $student->name }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                 Back
            </a>
            <form action="{{ route('admin.students.toggle', $student->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-2.5 py-1 rounded-md text-xs font-medium transition-colors shadow-2xs {{ $student->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">
                    {{ $student->is_active ? 'Suspend Account' : 'Reactivate Account' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Cadet Profile Info Card -->
    <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-xl bg-blue-950 text-white font-bold flex items-center justify-center text-xl shadow-2xs shrink-0">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-900">{{ $student->name }}</h1>
                <p class="text-xs text-slate-500 font-mono">{{ $student->email }} • {{ $student->phone ?? 'No Phone' }}</p>
                <div class="mt-2 flex flex-wrap items-center gap-2">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-blue-50 text-blue-950 border border-blue-200/60">
                        Target: {{ $student->target_exam ?? 'Defence Forces' }}
                    </span>
                    <span class="text-xs text-slate-400">Joined {{ $student->created_at->format('d M, Y') }}</span>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase {{ $student->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-red-50 text-red-700 border border-red-200/60' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $student->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                        <span>{{ $student->is_active ? 'Active' : 'Suspended' }}</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Manual Enrollment Grant Form -->
        <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-200/80 md:w-80 shrink-0">
            <h4 class="text-xs font-semibold text-slate-800 mb-2">+ Grant Manual Course Access</h4>
            <form action="{{ route('admin.students.enroll', $student->id) }}" method="POST" class="space-y-2">
                @csrf
                <select name="course_id" required class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                    <option value="">Select Course Batch</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}">{{ $c->title }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                    Enroll Cadet
                </button>
            </form>
        </div>
    </div>

    <!-- Enrolled Courses Table -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200/80 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Active Course Enrollments ({{ $student->enrollments->count() }})</h3>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 py-2.5 whitespace-nowrap">Course Batch Title</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Enrollment Date</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Access Expiration</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Status</th>
                        <th class="px-4 py-2.5 whitespace-nowrap text-right">LMS Link</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($student->enrollments as $enr)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-900">
                                {{ $enr->course ? $enr->course->title : 'Course' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-500">
                                {{ $enr->enrolled_at ? $enr->enrolled_at->format('d M, Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-500">
                                {{ $enr->expires_at ? $enr->expires_at->format('d M, Y') : 'Ongoing (6 Mos)' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>{{ $enr->status }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                @if($enr->course)
                                    <a href="{{ route('courses.show', $enr->course->slug) }}" target="_blank" class="text-blue-950 font-medium text-xs hover:underline">
                                        View Batch ↗
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-xs text-slate-400 italic">No courses currently enrolled.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- CBT Mock Attempts Table -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200/80 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">CBT Mock Test Attempts ({{ $student->testAttempts->count() }})</h3>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 py-2.5 whitespace-nowrap">Mock Examination</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Marks Scored</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Accuracy</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Qualification</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Date</th>
                        <th class="px-4 py-2.5 whitespace-nowrap text-right">Scorecard</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($student->testAttempts as $att)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-900">
                                {{ $att->mockTest ? $att->mockTest->title : 'Mock Test' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-mono font-medium text-slate-900">
                                {{ $att->score }} <span class="text-slate-400 text-xs">/ {{ $att->mockTest ? $att->mockTest->total_marks : 0 }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-mono font-medium text-slate-800">
                                {{ $att->accuracy_percentage }}%
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase {{ $att->is_passed ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-red-50 text-red-700 border border-red-200/60' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $att->is_passed ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    <span>{{ $att->is_passed ? 'Qualified' : 'Not Qualified' }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-400 text-[11px]">
                                {{ $att->submitted_at ? $att->submitted_at->format('d M, Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <a href="{{ route('student.tests.result', $att->id) }}" target="_blank" class="px-2.5 py-1 rounded-md text-xs font-medium text-blue-950 hover:bg-blue-100 bg-blue-50 border border-blue-200/60 transition-colors inline-block shadow-2xs">
                                    Scorecard ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-xs text-slate-400 italic">No CBT tests attempted yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
