@extends('layouts.app')

@section('title', 'About Nirmaan — Mission, Faculty & Defence Preparation Pedagogy')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Hero Introduction -->
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-950 block">DEDICATED TO NATIONAL DEFENCE</span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 leading-tight">
                Shaping the Guardians of Tomorrow
            </h1>
            <p class="text-base sm:text-lg text-slate-600 leading-relaxed">
                Nirmaan was founded with one resolute mission: to provide world-class, structured, and affordable guidance to every passionate young Indian striving to serve in the armed and paramilitary forces.
            </p>
        </div>

        <!-- Pillars -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-8 border border-slate-200 shadow-2xs">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center justify-center font-bold text-sm mb-4">
                    01
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Rigorous Academic Caliber</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Our curricula strictly mirror the official recruitment boards' latest blueprints, ensuring zero time wasted on outdated or peripheral topics.
                </p>
            </div>

            <div class="bg-white rounded-xl p-8 border border-slate-200 shadow-2xs">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-950 border border-blue-200 flex items-center justify-center font-bold text-sm mb-4">
                    02
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Veteran Mentorship</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Courses and SSB guidance are prepared with direct inputs from retired officers who understand officer-like qualities (OLQs) inside out.
                </p>
            </div>

            <div class="bg-white rounded-xl p-8 border border-slate-200 shadow-2xs">
                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-800 border border-slate-200 flex items-center justify-center font-bold text-sm mb-4">
                    03
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Modern Technology</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    High-speed mobile simulation test engines with real-time scoring and instant step-by-step diagnostic feedback.
                </p>
            </div>
        </div>

        <!-- Integrity & Transparency Statement -->
        <div class="bg-white rounded-xl p-8 sm:p-12 border border-slate-200 shadow-2xs">
            <div class="max-w-3xl">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-4">Our Commitment to Transparency</h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                    Nirmaan is an independent educational training institute. We do not claim any official government affiliation or endorsement. We prepare candidates purely through merit, discipline, and uncompromising study habits.
                </p>
                <div class="pt-4 flex flex-wrap gap-4">
                    <a href="{{ route('courses.index') }}" class="px-6 py-2.5 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition shadow-2xs">
                        Explore Batches
                    </a>
                    <a href="{{ route('contact') }}" class="px-6 py-2.5 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition">
                        Connect with Mentors
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
