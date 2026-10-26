@extends('layouts.admin')

@section('title', 'Cadet Dossier: ' . $student->name . ' — Admin Command')
@section('header_title', 'Cadet Dossier: ' . $student->name)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-2xl p-4 sm:p-5 border border-gray-200 shadow-2xs">
        <div class="flex items-center space-x-2 text-xs font-semibold text-gray-500">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
            <span>&bull;</span>
            <a href="{{ route('admin.students.index') }}" class="hover:text-blue-700">Cadets Roster</a>
            <span>&bull;</span>
            <span class="text-blue-700 font-bold truncate max-w-xs">{{ $student->name }}</span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-gray-600 hover:text-gray-900 transition">
                ← Back to Cadets
            </a>
            <form action="{{ route('admin.students.toggle', $student->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $student->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">
                    {{ $student->is_active ? 'Suspend Account' : 'Reactivate Account' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Cadet Profile Info Card -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-800 font-bold flex items-center justify-center text-2xl border border-blue-200">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $student->name }}</h1>
                <p class="text-xs text-gray-500 font-mono">{{ $student->email }} • {{ $student->phone ?? 'No Phone' }}</p>
                <div class="mt-2 flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                        Target: {{ $student->target_exam ?? 'Defence Forces' }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium">Joined {{ $student->created_at->format('d M, Y') }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $student->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                        {{ $student->is_active ? '● Active' : '● Suspended' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Manual Enrollment Grant Form -->
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 sm:w-80">
            <h4 class="text-xs font-bold text-gray-800 mb-2">+ Grant Manual Course Access</h4>
            <form action="{{ route('admin.students.enroll', $student->id) }}" method="POST" class="space-y-2">
                @csrf
                <select name="course_id" required class="w-full text-xs bg-white border border-gray-300 rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Select Course Batch</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}">{{ $c->title }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition shadow-2xs">
                    Enroll Candidate
                </button>
            </form>
        </div>
    </div>

    <!-- Enrolled Courses Table with whitespace-nowrap -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900">Active Course Enrollments ({{ $student->enrollments->count() }})</h3>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 whitespace-nowrap">Course Batch Title</th>
                        <th class="px-6 py-3.5 whitespace-nowrap">Enrollment Date</th>
                        <th class="px-6 py-3.5 whitespace-nowrap">Access Expiration</th>
                        <th class="px-6 py-3.5 whitespace-nowrap">Status</th>
                        <th class="px-6 py-3.5 whitespace-nowrap text-right">LMS Link</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($student->enrollments as $enr)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-6 py-3.5 whitespace-nowrap font-bold text-gray-900">
                                {{ $enr->course ? $enr->course->title : 'Course' }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap font-medium text-gray-500">
                                {{ $enr->enrolled_at ? $enr->enrolled_at->format('d M, Y') : '-' }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap font-medium text-gray-500">
                                {{ $enr->expires_at ? $enr->expires_at->format('d M, Y') : 'Ongoing (6 Mos)' }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    {{ $enr->status }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap text-right">
                                @if($enr->course)
                                    <a href="{{ route('courses.show', $enr->course->slug) }}" target="_blank" class="text-blue-700 font-bold hover:underline">
                                        View Batch ↗
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-xs text-gray-400 italic">No courses currently enrolled.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- CBT Mock Attempts Table with whitespace-nowrap -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900">CBT Mock Test Attempts ({{ $student->testAttempts->count() }})</h3>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 whitespace-nowrap">Mock Examination</th>
                        <th class="px-6 py-3.5 whitespace-nowrap">Marks Scored</th>
                        <th class="px-6 py-3.5 whitespace-nowrap">Accuracy Rate</th>
                        <th class="px-6 py-3.5 whitespace-nowrap">Qualification</th>
                        <th class="px-6 py-3.5 whitespace-nowrap">Submission Date</th>
                        <th class="px-6 py-3.5 whitespace-nowrap text-right">Scorecard</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($student->testAttempts as $att)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-6 py-3.5 whitespace-nowrap font-bold text-gray-900">
                                {{ $att->mockTest ? $att->mockTest->title : 'CBT Test' }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap font-mono font-bold text-emerald-700">
                                {{ $att->score }} <span class="text-gray-400 font-normal text-xs">/ {{ $att->mockTest ? $att->mockTest->total_marks : 0 }}</span>
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap font-mono font-bold text-gray-800">
                                {{ $att->accuracy_percentage }}%
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $att->is_passed ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                                    {{ $att->is_passed ? 'QUALIFIED' : 'NOT QUALIFIED' }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap font-medium text-gray-400">
                                {{ $att->submitted_at ? $att->submitted_at->format('d M, Y h:i A') : 'In Progress' }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap text-right">
                                <a href="{{ route('student.tests.result', $att->id) }}" target="_blank" class="px-2.5 py-1 rounded-lg text-xs font-bold text-blue-700 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 transition inline-block">
                                    Sheet ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-xs text-gray-400 italic">No mock tests attempted yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
