@extends('layouts.app')

@section('title', 'About Us — Mission, Leadership & Defence Training Pedagogy | Nirmaan Academy')

@section('content')
<div class="py-10 sm:py-16 text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16 sm:space-y-24">
        
        <!-- 1. Hero Introduction -->
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-black tracking-wider uppercase bg-blue-50 text-blue-950 border border-blue-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ $aboutConfig['hero_badge'] ?? 'DEDICATED TO NATIONAL DEFENCE' }}</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-slate-950 tracking-tight leading-tight">
                {{ $aboutConfig['hero_title'] ?? 'Shaping the Guardians of Tomorrow' }}
            </h1>
            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl mx-auto">
                {{ $aboutConfig['hero_subtitle'] ?? 'Nirmaan was founded with one resolute mission: to provide world-class, structured, and affordable guidance to every passionate young Indian striving to serve in the armed and paramilitary forces.' }}
            </p>
        </div>

        <!-- 2. Dynamic Impact Statistics Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-2xs text-center space-y-1">
                <span class="text-2xl sm:text-4xl font-black text-blue-950 font-mono">
                    {{ $aboutConfig['stats_selections'] ?? '12,500+' }}
                </span>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">
                    {{ $aboutConfig['stats_selections_label'] ?? 'Cadet Recommendations' }}
                </span>
                <span class="block text-[11px] text-emerald-600 font-semibold">Across Army, Navy &amp; Air Force</span>
            </div>

            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-2xs text-center space-y-1">
                <span class="text-2xl sm:text-4xl font-black text-blue-950 font-mono">
                    {{ $aboutConfig['stats_faculty'] ?? '45+' }}
                </span>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">
                    {{ $aboutConfig['stats_faculty_label'] ?? 'Veteran Mentors' }}
                </span>
                <span class="block text-[11px] text-slate-500 font-medium">Ex-SSB Board Officers</span>
            </div>

            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-2xs text-center space-y-1">
                <span class="text-2xl sm:text-4xl font-black text-blue-950 font-mono">
                    {{ $aboutConfig['stats_centers'] ?? '18+' }}
                </span>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">
                    {{ $aboutConfig['stats_centers_label'] ?? 'Drill Grounds' }}
                </span>
                <span class="block text-[11px] text-slate-500 font-medium">Obstacle &amp; Running Tracks</span>
            </div>

            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-2xs text-center space-y-1">
                <span class="text-2xl sm:text-4xl font-black text-emerald-600 font-mono">
                    {{ $aboutConfig['stats_success_rate'] ?? '94.2%' }}
                </span>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">
                    {{ $aboutConfig['stats_success_rate_label'] ?? 'Written Clear Rate' }}
                </span>
                <span class="block text-[11px] text-emerald-600 font-semibold">In Verified CBT Simulations</span>
            </div>
        </div>

        <!-- 3. Director's Message & Leadership Panel -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12">
                <!-- Left: Leadership Badge & Profile -->
                <div class="lg:col-span-4 bg-gradient-to-br from-blue-950 via-slate-900 to-blue-900 p-8 sm:p-10 text-white flex flex-col justify-between space-y-6">
                    <div class="space-y-3">
                        <span class="inline-block px-2.5 py-1 rounded bg-blue-800 text-blue-100 text-[10px] font-black tracking-widest uppercase">
                            DIRECTORATE COMMUNIQUE
                        </span>
                        <div class="w-20 h-20 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-3xl font-black text-white shadow-inner">
                            🎖️
                        </div>
                        <div>
                            <h3 class="text-xl font-black tracking-tight text-white">
                                {{ $aboutConfig['director_name'] ?? 'Col. R. K. Verma (Retd.)' }}
                            </h3>
                            <p class="text-xs font-bold text-blue-200 mt-0.5">
                                {{ $aboutConfig['director_role'] ?? 'Director & Chief Academic Mentor' }}
                            </p>
                        </div>
                    </div>

                    <p class="text-xs text-blue-100/90 leading-relaxed border-t border-white/10 pt-4">
                        {{ $aboutConfig['director_bio'] ?? 'Decorated Indian Army veteran with over 28 years of active command, staff appointments, and extensive tenure at Services Selection Board (SSB) evaluation boards.' }}
                    </p>
                </div>

                <!-- Right: Full Director's Message -->
                <div class="lg:col-span-8 p-8 sm:p-12 space-y-6 flex flex-col justify-center">
                    <div class="flex items-center space-x-2 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <svg class="w-4 h-4 text-blue-950" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        <span>A Call to Honor &amp; Discipline</span>
                    </div>

                    <blockquote class="text-base sm:text-lg text-slate-800 font-serif italic leading-relaxed">
                        "{{ $aboutConfig['director_message'] ?? 'At Nirmaan, we do not merely teach for a competitive exam; we build the character, fortitude, and officer-like qualities (OLQs) needed to lead soldiers in the face of adversity. Our cadets are trained to think decisively, act with honor, and serve with relentless commitment.' }}"
                    </blockquote>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100 text-xs">
                        <div class="space-y-1">
                            <span class="font-bold text-slate-900 block">Mission Statement:</span>
                            <p class="text-slate-600 leading-relaxed">{{ $aboutConfig['mission_statement'] }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="font-bold text-slate-900 block">Vision Statement:</span>
                            <p class="text-slate-600 leading-relaxed">{{ $aboutConfig['vision_statement'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. The Four Pillars of Nirmaan Pedagogy -->
        <div class="space-y-8">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-950 block">THE NIRMAAN METHODOLOGY</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-950 tracking-tight">
                    Four Pillars of Officer-Grade Excellence
                </h2>
                <p class="text-xs sm:text-sm text-slate-500">
                    Engineered to transition school and college candidates into disciplined military cadets.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Pillar 1 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-2xs space-y-3 hover:border-blue-950 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-950 border border-blue-200 flex items-center justify-center font-black text-sm">
                        01
                    </div>
                    <h3 class="text-base font-bold text-slate-900">
                        {{ $aboutConfig['pillar_1_title'] }}
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $aboutConfig['pillar_1_desc'] }}
                    </p>
                </div>

                <!-- Pillar 2 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-2xs space-y-3 hover:border-blue-950 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center justify-center font-black text-sm">
                        02
                    </div>
                    <h3 class="text-base font-bold text-slate-900">
                        {{ $aboutConfig['pillar_2_title'] }}
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $aboutConfig['pillar_2_desc'] }}
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-2xs space-y-3 hover:border-blue-950 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-800 border border-amber-200 flex items-center justify-center font-black text-sm">
                        03
                    </div>
                    <h3 class="text-base font-bold text-slate-900">
                        {{ $aboutConfig['pillar_3_title'] }}
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $aboutConfig['pillar_3_desc'] }}
                    </p>
                </div>

                <!-- Pillar 4 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-2xs space-y-3 hover:border-blue-950 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 flex items-center justify-center font-black text-sm">
                        04
                    </div>
                    <h3 class="text-base font-bold text-slate-900">
                        {{ $aboutConfig['pillar_4_title'] }}
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $aboutConfig['pillar_4_desc'] }}
                    </p>
                </div>
            </div>
        </div>

        <!-- 5. Exam Wings Trained -->
        <div class="bg-slate-50 rounded-2xl p-6 sm:p-10 border border-slate-200/80 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-black text-slate-950">Defence &amp; Paramilitary Wings Covered</h3>
                    <p class="text-xs text-slate-500">Structured courses mapped to individual recruitment board patterns</p>
                </div>
                <a href="{{ route('courses.index') }}" class="text-xs font-bold text-blue-950 hover:underline">
                    View All Batches →
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach($categories as $cat)
                    <a href="{{ route('courses.index', ['category' => $cat->slug]) }}" 
                       class="bg-white p-4 rounded-xl border border-slate-200 text-center space-y-2 hover:border-blue-950 hover:shadow-2xs transition-all">
                        <span class="text-2xl block">🎖️</span>
                        <span class="block text-xs font-bold text-slate-900">{{ $cat->name }}</span>
                        <span class="block text-[10px] text-slate-400 font-mono">{{ $cat->code }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- 6. Transparency & Institutional Notice -->
        <div class="bg-white rounded-2xl p-6 sm:p-10 border border-slate-200 shadow-2xs space-y-4">
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                <svg class="w-4 h-4 text-blue-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Statutory Disclaimer &amp; Non-Affiliation Notice</span>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
                Nirmaan Defence Prep Institute is a private, premier autonomous preparatory coaching establishment registered in India. We do not claim any official affiliation with, or endorsement by, the Ministry of Defence (MoD), Indian Armed Forces recruitment directorates, or UPSC. Official recruitment notices, exam schedules, and results are published exclusively on official government portals (joinindianarmy.nic.in, joinindiannavy.gov.in, careerairforce.nic.in, and upsc.gov.in).
            </p>
        </div>

        <!-- 7. Bottom CTA -->
        <div class="bg-gradient-to-r from-blue-950 via-slate-900 to-blue-900 rounded-2xl p-8 sm:p-12 text-center text-white space-y-4">
            <h3 class="text-2xl sm:text-3xl font-black tracking-tight">Ready to Begin Your Cadet Journey?</h3>
            <p class="text-xs sm:text-sm text-blue-200 max-w-xl mx-auto leading-relaxed">
                Join thousands of driven aspirants preparing daily with structured curricula, live simulation testing, and guidance from former officers.
            </p>
            <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('courses.index') }}" class="px-6 py-3 rounded-xl bg-white text-blue-950 font-bold text-xs hover:bg-blue-50 transition-colors shadow-xs">
                    Browse All Batches
                </a>
                <a href="{{ route('contact') }}" class="px-6 py-3 rounded-xl bg-blue-900/80 hover:bg-blue-900 text-white font-bold text-xs border border-white/20 transition-colors">
                    Talk to an Academic Counsellor
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
