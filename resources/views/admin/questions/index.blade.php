@extends('layouts.admin')

@section('title', 'Question Bank — Admin')
@section('header_title', 'Question Bank Repository')

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">National Question Bank</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage single-correct multiple-choice questions organized by subject and difficulty.</p>
        </div>
        <a href="{{ route('admin.questions.create') }}" class="px-5 py-2.5 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
            + Add New Question
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs">
        <form action="{{ route('admin.questions.index') }}" method="GET" class="flex flex-wrap gap-4 items-center">
            <select name="subject" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 outline-none focus:border-blue-500">
                <option value="">All Subjects</option>
                <option value="General Knowledge" {{ request('subject') === 'General Knowledge' ? 'selected' : '' }}>General Knowledge</option>
                <option value="General Science" {{ request('subject') === 'General Science' ? 'selected' : '' }}>General Science</option>
                <option value="Mathematics" {{ request('subject') === 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                <option value="Reasoning" {{ request('subject') === 'Reasoning' ? 'selected' : '' }}>Reasoning</option>
                <option value="English" {{ request('subject') === 'English' ? 'selected' : '' }}>English</option>
            </select>

            <select name="difficulty" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 outline-none focus:border-blue-500">
                <option value="">All Difficulties</option>
                <option value="easy" {{ request('difficulty') === 'easy' ? 'selected' : '' }}>Easy</option>
                <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="hard" {{ request('difficulty') === 'hard' ? 'selected' : '' }}>Hard</option>
            </select>

            @if(request()->hasAny(['subject', 'difficulty']))
                <a href="{{ route('admin.questions.index') }}" class="text-xs text-gray-500 hover:text-gray-800">Clear Filters</a>
            @endif
        </form>
    </div>

    <!-- Questions Table -->
    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Question Text</th>
                        <th class="px-6 py-4">Correct</th>
                        <th class="px-6 py-4">Marks</th>
                        <th class="px-6 py-4">Difficulty</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($questions as $q)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800">
                                    {{ $q->subject }}
                                </span>
                                @if($q->topic)<span class="text-[10px] text-gray-400 block mt-0.5">{{ $q->topic }}</span>@endif
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900 max-w-md">
                                <p class="line-clamp-2 leading-relaxed">{{ $q->question_text }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 font-extrabold flex items-center justify-center text-xs">
                                    {{ $q->correct_option }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-700">+{{ $q->marks }} / -{{ $q->negative_marks }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $q->difficulty === 'easy' ? 'bg-emerald-50 text-emerald-700' : ($q->difficulty === 'medium' ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                                    {{ $q->difficulty }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.questions.edit', $q->id) }}" class="text-blue-700 hover:underline font-bold">Edit</a>
                                <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this question?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-xs text-gray-400">No questions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $questions->links() }}
        </div>
    </div>

</div>
@endsection
