@extends('layouts.app')

@section('title', 'Exam Notifications & Announcements — Nirmaan')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div class="text-center space-y-2">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 block">UPDATES &amp; RECRUITMENTS</span>
            <h1 class="text-3xl font-extrabold text-gray-900">Announcements &amp; Exam Alerts</h1>
            <p class="text-sm text-gray-600">Stay updated on active applications, admit card releases, rally schedules, and examination date notifications.</p>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-xs hover:shadow-md transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        INDIAN ARMY
                    </span>
                    <span class="text-xs text-gray-400 font-medium">Updated 2 days ago</span>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">
                    Agniveer Army Rally Notification &amp; Online CEE Dates Announced
                </h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Registration for Indian Army Agniveer GD, Technical, and Tradesmen for the upcoming recruitment cycle is now open on the official Join Indian Army portal. Candidates are advised to complete mock tests in timed conditions.
                </p>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-[11px] text-gray-400">Written Exam Stage: Computer-Based Test (CBT)</span>
                    <a href="{{ route('courses.show', 'indian-army-agniveer-comprehensive-course') }}" class="text-xs font-bold text-blue-700 hover:underline">
                        View Batch Preparation →
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-xs hover:shadow-md transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-sky-50 text-sky-800 border border-sky-200">
                        INDIAN AIR FORCE
                    </span>
                    <span class="text-xs text-gray-400 font-medium">Updated 5 days ago</span>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">
                    IAF Agniveervayu Science &amp; Non-Science Stream Mock Test Series Live
                </h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Phase-1 examination dates are fast approaching. The Nirmaan academic team has released 5 newly formulated full-length RAGA and Technical Mathematics mock sets.
                </p>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-[11px] text-gray-400">Phase: STAR Online Examination</span>
                    <a href="{{ route('student.tests.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
                        Take Airforce Mock Test →
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-xs hover:shadow-md transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                        INDIAN NAVY
                    </span>
                    <span class="text-xs text-gray-400 font-medium">Updated 1 week ago</span>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">
                    Navy SSR / MR Physical Fitness Test (PFT) Criteria &amp; Merit Cut-Offs
                </h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Official state-wise cutoffs for the recent INET examination have been published. Download the comprehensive PDF guide analyzing previous trendlines in our Free Resources library.
                </p>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-[11px] text-gray-400">Eligibility Guide &amp; Analysis</span>
                    <a href="{{ route('resources.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
                        Download PDF Free →
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
