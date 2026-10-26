@extends('layouts.admin')

@section('title', 'Course Management — Admin')
@section('header_title', 'Course & Curriculum Management')

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">All Published Courses</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage batch prices, syllabus modules, video lessons, and preview access.</p>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="px-5 py-2.5 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
            + Create New Course
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Course Title</th>
                        <th class="px-6 py-4">Branch Category</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Modules</th>
                        <th class="px-6 py-4">Students Enrolled</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($courses as $c)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-bold text-gray-900">
                                <a href="{{ route('admin.courses.edit', $c->id) }}" class="hover:text-blue-700">
                                    {{ $c->title }}
                                </a>
                                <span class="text-[10px] text-gray-400 font-normal block">{{ $c->subtitle }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800">
                                    {{ $c->examCategory ? $c->examCategory->name : 'Defence' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-black text-gray-900">{{ $c->formatted_price }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-700">{{ $c->modules_count }} Modules</td>
                            <td class="px-6 py-4 font-semibold text-gray-700">{{ $c->enrollments_count }} Students</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $c->is_published ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $c->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.courses.edit', $c->id) }}" class="text-blue-700 hover:underline font-bold">
                                    Edit &amp; Modules
                                </a>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('courses.show', $c->slug) }}" target="_blank" class="text-gray-500 hover:text-gray-800">
                                    Preview ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-xs text-gray-400">No courses available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $courses->links() }}
        </div>
    </div>

</div>
@endsection
