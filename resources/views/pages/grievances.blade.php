@extends('layouts.app')

@section('title', 'Help & Grievance Redressal Cell | Nirmaan Defence Academy')

@section('content')
<div class="py-10 sm:py-16 text-slate-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-16">
        
        <!-- 1. Header Section -->
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <span class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-black tracking-wider uppercase bg-amber-50 text-amber-900 border border-amber-200">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>STATUTORY REDRESSAL MECHANISM</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-slate-950 tracking-tight leading-tight">
                Cadet Help &amp; Grievance Redressal Cell
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-2xl mx-auto">
                In compliance with the <em>Consumer Protection (E-Commerce) Rules, 2020</em> and the <em>Information Technology Rules, 2021</em>, Nirmaan provides a structured, transparent, and time-bound grievance escalation matrix for all cadets.
            </p>
        </div>

        <!-- 2. Escalation Hierarchy (3 Levels) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Level 1 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-950 border border-blue-200">
                            LEVEL 1 — DESK RESOLUTION
                        </span>
                        <span class="text-xs font-mono font-bold text-slate-400">SLA: 24h</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Frontline Academic &amp; Technical Support</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        For payment confirmations, login issues, app glitches, or video playback errors. Most operational queries are resolved at this level.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-xs">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Standard Resolution:</span>
                    <span class="font-bold text-blue-950">{{ $grievanceConfig['sla_level_1'] }}</span>
                </div>
            </div>

            <!-- Level 2 -->
            <div class="bg-white rounded-2xl p-6 border-2 border-amber-300/80 shadow-xs space-y-3 flex flex-col justify-between relative overflow-hidden">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-200">
                            LEVEL 2 — GRIEVANCE OFFICER
                        </span>
                        <span class="text-xs font-mono font-bold text-amber-700">SLA: 48h</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Statutory Grievance Redressal Cell</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        If your issue is unresolved within 24 hours by frontline support, or relates to fee refund disputes, test scoring audits, or disciplinary matters.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-xs">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Statutory Resolution:</span>
                    <span class="font-bold text-amber-900">{{ $grievanceConfig['sla_level_2'] }}</span>
                </div>
            </div>

            <!-- Level 3 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-800 border border-slate-200">
                            LEVEL 3 — DIRECTORATE APPELLATE
                        </span>
                        <span class="text-xs font-mono font-bold text-slate-400">SLA: 5 Days</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Appellate Review &amp; Director Hearing</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        For complex grievances requiring executive policy review. The Directorate provides final institutional adjudication.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-xs">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Executive Review:</span>
                    <span class="font-bold text-slate-900">{{ $grievanceConfig['sla_level_3'] }}</span>
                </div>
            </div>

        </div>

        <!-- 3. Form & Designated Officer Panel -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: Grievance Lodging Form (7 cols) -->
            <div class="lg:col-span-7 bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-2xs space-y-5">
                <div>
                    <h2 class="text-lg font-black text-slate-950 tracking-tight">File an Official Grievance Docket</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Please provide complete details so our Grievance Cell can investigate and respond within statutory SLA timelines.</p>
                </div>

                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-start space-x-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <div class="leading-relaxed font-medium">{{ session('success') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-300 text-rose-800 text-xs space-y-1">
                        @foreach($errors->all() as $err)
                            <div class="flex items-center space-x-1.5">
                                <span>•</span>
                                <span>{{ $err }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('grievances.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Cadet Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required value="{{ old('name', auth()->user()->name ?? '') }}" 
                                   placeholder="e.g. Aditya Pratap Singh"
                                   class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Registered Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" required value="{{ old('email', auth()->user()->email ?? '') }}" 
                                   placeholder="e.g. cadet@gmail.com"
                                   class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Mobile Helpline Number <span class="text-rose-500">*</span></label>
                            <input type="tel" name="phone" required value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                                   placeholder="+91 98765 43210"
                                   class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Roll / Order Number (if applicable)</label>
                            <input type="text" name="cadet_roll" value="{{ old('cadet_roll') }}" 
                                   placeholder="e.g. NIR-2026-0042 or ORD-10293"
                                   class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Grievance Nature / Classification <span class="text-rose-500">*</span></label>
                        <select name="category" required
                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">
                            <option value="Payment & Fee Refund Dispute">Payment &amp; Fee Refund Dispute</option>
                            <option value="Course Access & Lesson Playback Error">Course Access &amp; Lesson Playback Error</option>
                            <option value="Mock Test Engine & Scoring Discrepancy">Mock Test Engine &amp; Scoring Discrepancy</option>
                            <option value="Tax Invoice & Receipt Discrepancy">Tax Invoice &amp; Receipt Issue</option>
                            <option value="Faculty Guidance or Academic Conduct">Faculty Guidance / Classroom Decorum</option>
                            <option value="Other Statutory Grievance">Other Statutory Grievance</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Brief Subject Summary <span class="text-rose-500">*</span></label>
                        <input type="text" name="subject" required value="{{ old('subject') }}" 
                               placeholder="e.g. Transaction completed on Razorpay but course batch still locked"
                               class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Detailed Facts &amp; Redressal Sought <span class="text-rose-500">*</span></label>
                        <textarea name="message" required rows="4" 
                                  placeholder="Please provide full transaction references, dates, screenshots references, or specific remedy requested..."
                                  class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" 
                            class="w-full sm:w-auto px-7 py-3 rounded-xl bg-amber-900 hover:bg-amber-950 text-white font-bold text-xs transition-colors shadow-xs flex items-center justify-center space-x-2 cursor-pointer">
                        <span>Lodge Formal Grievance</span>
                        <span>→</span>
                    </button>
                </form>
            </div>

            <!-- Right: Designated Grievance Officer & Statutory Notice (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Officer Card -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-2xs space-y-4">
                    <span class="text-[10px] font-bold text-amber-900 tracking-wider uppercase block">APPOINTED STATUTORY OFFICER</span>
                    
                    <div class="flex items-center space-x-3.5">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-900 border border-amber-200 flex items-center justify-center text-xl font-bold">
                            ⚖️
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">{{ $grievanceConfig['officer_name'] }}</h3>
                            <p class="text-xs text-amber-800 font-semibold">{{ $grievanceConfig['officer_designation'] }}</p>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs text-slate-600 pt-2 border-t border-slate-100">
                        <div class="flex items-start space-x-2">
                            <span class="text-slate-400 shrink-0">✉️</span>
                            <span>Email: <a href="mailto:{{ $grievanceConfig['officer_email'] }}" class="text-blue-950 font-bold hover:underline">{{ $grievanceConfig['officer_email'] }}</a></span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <span class="text-slate-400 shrink-0">📞</span>
                            <span>Direct Line: <strong class="text-slate-900">{{ $grievanceConfig['officer_phone'] }}</strong></span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <span class="text-slate-400 shrink-0">🕒</span>
                            <span>Office Hours: {{ $grievanceConfig['officer_timings'] }}</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <span class="text-slate-400 shrink-0">📍</span>
                            <span>{{ $grievanceConfig['officer_address'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Statutory Charter Notice -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200/80 space-y-3 text-xs text-slate-600 leading-relaxed">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">TIME-BOUND COMMITMENT</span>
                    <p>
                        Every grievance submitted generates a timestamped digital tracking docket. An automated confirmation is provided immediately upon submission.
                    </p>
                    <p>
                        The Grievance Officer shall acknowledge the grievance within <strong>48 business hours</strong> and dispose of the grievance within a maximum of <strong>15 calendar days</strong> from the date of its receipt in compliance with the IT Rules 2021.
                    </p>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
