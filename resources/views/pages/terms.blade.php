@extends('layouts.app')

@section('title', 'Terms of Service & Cadet Enrollment Agreement | Nirmaan Academy')

@section('content')
<div class="py-10 sm:py-16 text-slate-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Header -->
        <div class="border-b border-slate-200 pb-8 space-y-3">
            <div class="flex items-center space-x-2 text-xs font-bold text-blue-950 uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                <span>CADET ENROLLMENT CHARTER &amp; CONDITIONS</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-950 tracking-tight">
                Terms of Service &amp; Code of Discipline
            </h1>
            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 pt-1">
                <span>Effective Date: <strong class="text-slate-900 font-semibold">{{ $termsConfig['effective_date'] ?? 'November 1, 2026' }}</strong></span>
                <span>•</span>
                <span>Jurisdiction: <strong class="text-slate-900 font-semibold">{{ $termsConfig['jurisdiction'] ?? 'Courts of New Delhi, India' }}</strong></span>
                <span>•</span>
                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 font-bold border border-slate-200 text-[10px]">
                    Legally Binding Agreement
                </span>
            </div>
        </div>

        <!-- Document Body Container -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            
            <!-- Left Sticky Table of Contents (4 cols) -->
            <div class="hidden md:block md:col-span-4 sticky top-24 space-y-3 bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs text-xs">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Contract Sections</span>
                <nav class="space-y-1.5 font-medium text-slate-600">
                    <a href="#term-1" class="block hover:text-blue-950 py-1 transition-colors">1. Agreement to Terms</a>
                    <a href="#term-2" class="block hover:text-blue-950 py-1 transition-colors">2. Cadet Code of Conduct</a>
                    <a href="#term-3" class="block hover:text-blue-950 py-1 transition-colors">3. Anti-Piracy &amp; IP Protection</a>
                    <a href="#term-4" class="block hover:text-blue-950 py-1 transition-colors">4. Enrollment &amp; Batch Access</a>
                    <a href="#term-5" class="block hover:text-blue-950 py-1 transition-colors">5. Pricing &amp; GST Invoicing</a>
                    <a href="#term-6" class="block hover:text-blue-950 py-1 transition-colors">6. Refund &amp; Cancellation Policy</a>
                    <a href="#term-7" class="block hover:text-blue-950 py-1 transition-colors">7. CBT Simulation Engine Rules</a>
                    <a href="#term-8" class="block hover:text-blue-950 py-1 transition-colors">8. Non-Affiliation Disclaimer</a>
                    <a href="#term-9" class="block hover:text-blue-950 py-1 transition-colors">9. Physical Drills Risk Waiver</a>
                    <a href="#term-10" class="block hover:text-blue-950 py-1 transition-colors">10. Governing Jurisdiction</a>
                </nav>

                <div class="pt-3 border-t border-slate-100">
                    <button type="button" onclick="window.print()" class="text-[11px] font-bold text-blue-950 flex items-center space-x-1 hover:underline">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span>Print Terms Agreement</span>
                    </button>
                </div>
            </div>

            <!-- Right: Terms Content (8 cols) -->
            <div class="md:col-span-8 bg-white rounded-2xl p-6 sm:p-10 border border-slate-200 shadow-2xs space-y-8 text-xs sm:text-sm text-slate-700 leading-relaxed">
                
                <!-- Term 1 -->
                <div id="term-1" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">1</span>
                        <span>Agreement to Terms &amp; Eligibility</span>
                    </h2>
                    <p>
                        By creating an account, enrolling in a course, subscribing to CBT mock tests, or entering any Nirmaan Defence Prep Institute campus, you ("Cadet" or "User") agree to be bound unconditionally by these Terms of Service. If you are under 18 years of age, your enrollment signifies consent from your parent or lawful legal guardian.
                    </p>
                </div>

                <!-- Term 2 -->
                <div id="term-2" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">2</span>
                        <span>Cadet Code of Conduct &amp; Military Decorum</span>
                    </h2>
                    <p>
                        As an aspirant aiming for uniform service in the Indian Armed Forces, you are expected to exhibit the highest standards of integrity, punctuality, and mutual respect. The following behaviors warrant immediate expulsion without fee reimbursement:
                    </p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Foul, abusive, derogatory, or defamatory language directed toward faculty, mentors, or peer cadets.</li>
                        <li>Engaging in academic dishonesty, answer leakage, or impersonation during online mock tests.</li>
                        <li>Political agitation, discrimination on basis of caste, creed, religion, or gender within classrooms or online forums.</li>
                    </ul>
                </div>

                <!-- Term 3 -->
                <div id="term-3" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">3</span>
                        <span>Intellectual Property, Copyright &amp; Anti-Piracy Notice</span>
                    </h2>
                    <div class="p-4 rounded-xl bg-rose-50/70 border border-rose-200 text-rose-950 space-y-2">
                        <p class="font-bold">Strict Anti-Piracy Warning</p>
                        <p class="text-xs text-rose-900 leading-relaxed">
                            All video lectures, PDF dossiers, mock test questions, answer explanations, and proprietary algorithmic diagnostic scores are protected under the <em>Indian Copyright Act, 1957</em>. Any screen recording, unauthorized redistribution, Telegram group scraping, or re-sale of Nirmaan materials will invite civil damages and criminal prosecution under Sections 63 and 65 of the Copyright Act and the IT Act 2000.
                        </p>
                    </div>
                </div>

                <!-- Term 4 -->
                <div id="term-4" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">4</span>
                        <span>Enrollment, Account Security &amp; Batch Access</span>
                    </h2>
                    <p>
                        Each enrollment license is individual and non-transferable. Sharing credentials with friends or third parties is prohibited. Our security engine flags concurrent IP sessions and will temporarily lock accounts exhibiting multi-device anomalies.
                    </p>
                </div>

                <!-- Term 5 -->
                <div id="term-5" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">5</span>
                        <span>Fee Structure, Razorpay Processing &amp; GST Invoicing</span>
                    </h2>
                    <p>
                        All payments are processed securely via <strong>Razorpay</strong>. Upon successful order authorization, an electronic Tax Invoice compliant with GST rules is generated instantly under Service Accounting Code (SAC) 999293 (Commercial training &amp; coaching services) and accessible inside the cadet's Orders dashboard.
                    </p>
                </div>

                <!-- Term 6 -->
                <div id="term-6" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">6</span>
                        <span>Refund &amp; Cancellation Policy</span>
                    </h2>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                        <p class="font-bold text-slate-900">{{ $termsConfig['refund_period_days'] ?? '7' }}-Day Admission Refund Window</p>
                        <p class="text-xs text-slate-600">
                            A cadet may request a refund within {{ $termsConfig['refund_period_days'] ?? '7' }} calendar days of initial course batch purchase, provided less than 20% of video curriculum has been viewed and zero official full-length CBT mock tests have been submitted. Standalone mock test purchases and downloadable study notes are non-refundable once unlocked. Approved refunds are credited to the original source account via Razorpay within 5–7 business days.
                        </p>
                    </div>
                </div>

                <!-- Term 7 -->
                <div id="term-7" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">7</span>
                        <span>CBT Simulation Engine Rules</span>
                    </h2>
                    <p>
                        Our mock test engine enforces standardized negative marking (-0.33 to -1.33 depending on official UPSC/SSB criteria). Once a timed test starts, the timer cannot be paused. Abandoning a test will auto-submit answers at the expiry of the allocated minutes.
                    </p>
                </div>

                <!-- Term 8 -->
                <div id="term-8" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">8</span>
                        <span>Statutory Non-Affiliation Disclaimer</span>
                    </h2>
                    <p class="p-3.5 rounded-xl bg-slate-100 text-slate-800 font-medium">
                        <strong>Nirmaan Defence Prep Institute is an independent educational training provider.</strong> We do not issue government military commissions, nor do we influence recruitment board outcomes. Selection in the armed forces is determined exclusively on candidate performance before official military assessment authorities.
                    </p>
                </div>

                <!-- Term 9 -->
                <div id="term-9" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">9</span>
                        <span>Physical Obstacle Drills Assumption of Risk</span>
                    </h2>
                    <p>
                        Cadets participating in optional physical drills, ground obstacle training, and cross-country runs do so voluntarily. Cadets must ensure they possess medical fitness before undertaking high-intensity physical exercises.
                    </p>
                </div>

                <!-- Term 10 -->
                <div id="term-10" class="space-y-3 pt-4 border-t border-slate-200">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">10</span>
                        <span>Governing Law &amp; Dispute Resolution</span>
                    </h2>
                    <p>
                        These Terms are governed by and construed in accordance with the laws of the Republic of India. Any dispute arising out of or in connection with enrollment, fee payments, or disciplinary actions shall be subject to the exclusive jurisdiction of the competent courts in <strong class="text-slate-900 font-semibold">{{ $termsConfig['jurisdiction'] ?? 'New Delhi, India' }}</strong>.
                    </p>

                    <div class="text-xs text-slate-500 pt-2">
                        Legal inquiries: <a href="mailto:{{ $termsConfig['contact_email'] ?? 'legal@nirmaan.com' }}" class="text-blue-950 font-bold hover:underline">{{ $termsConfig['contact_email'] ?? 'legal@nirmaan.com' }}</a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
