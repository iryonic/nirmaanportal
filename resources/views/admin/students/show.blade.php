@extends('layouts.admin')

@section('title', 'Student Profile: ' . $student->name . ' — Admin')
@section('header_title', 'Cadet Record: ' . $student->name)

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
            ← Back to Student Directory
        </a>
        <form action="{{ route('admin.students.toggle', $student->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 rounded-full text-xs font-bold {{ $student->is_active ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                {{ $student->is_active ? 'Suspend Account' : 'Reactivate Account' }}
            </button>
        </form>
    </div>

    <!-- Student Info Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-800 font-extrabold flex items-center justify-center text-xl">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $student->name }}</h1>
                <p class="text-xs text-gray-500">{{ $student->email }} • {{ $student->phone ?? 'No Phone' }}</p>
                <div class="mt-2 flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-100">
                        Target: {{ $student->target_exam ?? 'Defence Forces' }}
                    </span>
                    <span class="text-xs text-gray-400">Joined {{ $student->created_at->format('d M, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Manual Enrollment Grant Form -->
        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200/60 sm:w-80">
            <h4 class="text-xs font-bold text-gray-700 mb-2">+ Grant Manual Course Access</h4>
            <form action="{{ route('admin.students.enroll', $student->id) }}" method="POST" class="space-y-2">
                @csrf
                <select name="course_id" required class="w-full text-xs bg-white border border-gray-200 rounded-xl px-3 py-2 outline-none">
                    <option value="">Select Course</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}">{{ $c->title }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition">
                    Enroll Student
                </button>
            </form>
        </div>
    </div>

    <!-- Enrolled Courses -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xs">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Active Course Enrollments ({{ $student->enrollments->count() }})</h3>
        <div class="divide-y divide-gray-100">
            @forelse($student->enrollments as $enr)
                <div class="py-3.5 flex items-center justify-between text-xs">
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $enr->course->title }}</h4>
                        <span class="text-[10px] text-gray-400">Enrolled: {{ $enr->enrolled_at ? $enr->enrolled_at->format('d M, Y') : '-' }} • Expires: {{ $enr->expires_at ? $enr->expires_at->format('d M, Y') : 'Ongoing' }}</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-800">
                        {{ $enr->status }}
                    </span>
                </div>
            @empty
                <p class="py-4 text-xs text-gray-400 italic">No courses currently enrolled.</p>
            @endforelse
        </div>
    </div>

    <!-- Past Mock Attempts -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xs">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Mock Test Attempts ({{ $student->testAttempts->count() }})</h3>
        <div class="divide-y divide-gray-100">
            @forelse($student->testAttempts as $att)
                <div class="py-3.5 flex items-center justify-between text-xs">
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $att->mockTest->title }}</h4>
                        <span class="text-[10px] text-gray-400">Score: <strong class="text-emerald-700">{{ $att->score }}/{{ number_format($att->mockTest->total_marks, 0) }}</strong> • Accuracy: {{ $att->accuracy_percentage }}% • {{ $att->submitted_at ? $att->submitted_at->format('d M, Y') : '' }}</span>
                    </div>
                    <a href="{{ route('student.tests.result', $att->id) }}" target="_blank" class="text-xs font-bold text-blue-700 hover:underline">
                        View Sheet ↗
                    </a>
                </div>
            @empty
                <p class="py-4 text-xs text-gray-400 italic">No mock tests attempted yet.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
