@extends('layouts.app')

@section('title', 'Privacy Policy & Data Protection Charter | Nirmaan Academy')

@section('content')
<div class="py-10 sm:py-16 text-slate-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Header -->
        <div class="border-b border-slate-200 pb-8 space-y-3">
            <div class="flex items-center space-x-2 text-xs font-bold text-blue-950 uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>LEGAL CHARTER &amp; PRIVACY COMPLIANCE</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-950 tracking-tight">
                Privacy Policy &amp; Cadet Data Protection
            </h1>
            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 pt-1">
                <span>Effective Date: <strong class="text-slate-900 font-semibold">{{ $privacyConfig['effective_date'] ?? 'November 1, 2026' }}</strong></span>
                <span>•</span>
                <span>Jurisdiction: <strong class="text-slate-900 font-semibold">Republic of India</strong></span>
                <span>•</span>
                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-950 font-bold border border-blue-200 text-[10px]">
                    DPDP Act 2023 Compliant
                </span>
            </div>
        </div>

        <!-- Document Body Container -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            
            <!-- Left Sticky Table of Contents (4 cols) -->
            <div class="hidden md:block md:col-span-4 sticky top-24 space-y-3 bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs text-xs">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Policy Sections</span>
                <nav class="space-y-1.5 font-medium text-slate-600">
                    <a href="#section-1" class="block hover:text-blue-950 py-1 transition-colors">1. Scope &amp; Commitment</a>
                    <a href="#section-2" class="block hover:text-blue-950 py-1 transition-colors">2. Information We Collect</a>
                    <a href="#section-3" class="block hover:text-blue-950 py-1 transition-colors">3. How Your Data Is Used</a>
                    <a href="#section-4" class="block hover:text-blue-950 py-1 transition-colors">4. Razorpay &amp; Payment Security</a>
                    <a href="#section-5" class="block hover:text-blue-950 py-1 transition-colors">5. PWA Caching &amp; Storage</a>
                    <a href="#section-6" class="block hover:text-blue-950 py-1 transition-colors">6. Data Retention &amp; Integrity</a>
                    <a href="#section-7" class="block hover:text-blue-950 py-1 transition-colors">7. Third-Party Disclosures</a>
                    <a href="#section-8" class="block hover:text-blue-950 py-1 transition-colors">8. Your Rights as a Cadet</a>
                    <a href="#section-9" class="block hover:text-blue-950 py-1 transition-colors">9. Data Protection Officer</a>
                </nav>

                <div class="pt-3 border-t border-slate-100">
                    <button type="button" onclick="window.print()" class="text-[11px] font-bold text-blue-950 flex items-center space-x-1 hover:underline">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span>Print Legal Charter</span>
                    </button>
                </div>
            </div>

            <!-- Right: Policy Content (8 cols) -->
            <div class="md:col-span-8 bg-white rounded-2xl p-6 sm:p-10 border border-slate-200 shadow-2xs space-y-8 text-xs sm:text-sm text-slate-700 leading-relaxed">
                
                <!-- Section 1 -->
                <div id="section-1" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">1</span>
                        <span>Scope &amp; Institutional Commitment</span>
                    </h2>
                    <p>
                        Nirmaan Defence Prep Institute ("Nirmaan", "we", "us", or "our") respects the privacy of every candidate, cadet, and visitor accessing our digital portal (<strong class="text-slate-900">nirmaan.com</strong>) and offline training campuses. This Privacy Policy outlines our transparent protocols governing the collection, storage, processing, and protection of personal data in strict conformity with the <em>Digital Personal Data Protection (DPDP) Act, 2023</em> and the <em>Information Technology Act, 2000</em>.
                    </p>
                </div>

                <!-- Section 2 -->
                <div id="section-2" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">2</span>
                        <span>Categories of Cadet Information Collected</span>
                    </h2>
                    <p>To provide high-fidelity testing simulations, authenticated study access, and administrative support, we collect:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Candidate Profile Data:</strong> Full Name, Email Address, Verified Mobile Number, State of Domicile, Date of Birth, and Target Examination Stream (e.g. NDA, CDS, Agniveer).</li>
                        <li><strong>Academic &amp; Test Telemetry:</strong> Mock test submissions, answer choices, response time stamps per question, percentile rank computations, and practice drill history.</li>
                        <li><strong>Device &amp; Session Logs:</strong> IP address, browser user-agent, operating system, and login session identifiers strictly for fraud prevention and concurrent session security.</li>
                    </ul>
                </div>

                <!-- Section 3 -->
                <div id="section-3" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">3</span>
                        <span>Lawful Purpose &amp; Usage of Student Data</span>
                    </h2>
                    <p>Candidate information is utilized solely for lawful educational purposes:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Provision of purchased course video modules, PDF study dossiers, and live test execution.</li>
                        <li>Generation of authentic tax invoices, fee receipts, and roll numbers compliant with statutory accounting regulations.</li>
                        <li>SMS / WhatsApp / Email delivery of critical exam schedule alerts, batch timing revisions, and admission updates.</li>
                        <li>Benchmarking national peer percentiles without disclosing individual identities to third parties.</li>
                    </ul>
                </div>

                <!-- Section 4 -->
                <div id="section-4" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">4</span>
                        <span>Razorpay &amp; Financial Transaction Security</span>
                    </h2>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                        <p class="font-bold text-slate-900">PCI-DSS Level 1 Encrypted Processing</p>
                        <p class="text-xs text-slate-600">
                            Nirmaan never receives, processes, or stores your Credit/Debit Card numbers, CVV, Netbanking passwords, or UPI MPINs on our servers. All financial transactions are processed through <strong>Razorpay Software Private Limited</strong>, an RBI-authorized Payment Aggregator complying with the highest PCI-DSS Level 1 security standards and 128-bit SSL end-to-end cryptographic encryption.
                        </p>
                    </div>
                </div>

                <!-- Section 5 -->
                <div id="section-5" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">5</span>
                        <span>Progressive Web App (PWA) &amp; Local Storage</span>
                    </h2>
                    <p>
                        Our platform employs secure browser local storage mechanisms (e.g. <code>localStorage</code>) to preserve client-side practice session timers, question bookmark lists, and offline cached syllabus files. No sensitive financial or credential data is ever stored within local storage.
                    </p>
                </div>

                <!-- Section 6 -->
                <div id="section-6" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">6</span>
                        <span>Data Retention &amp; Account Integrity</span>
                    </h2>
                    <p>
                        Candidate academic records and test attempt results are preserved during the active validity of the subscribed course and for a statutory period required by Indian taxation authorities. Candidates may request permanent deletion of their account upon completion of their recruitment lifecycle.
                    </p>
                </div>

                <!-- Section 7 -->
                <div id="section-7" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">7</span>
                        <span>Zero Commercial Sale Commitment</span>
                    </h2>
                    <p class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-200 text-blue-950 font-medium">
                        <strong>Nirmaan strictly prohibits the commercial sale, rental, or trade of candidate phone numbers or emails to third-party telemarketers or external entities.</strong> Your details remain exclusively within the academy's secure perimeter.
                    </p>
                </div>

                <!-- Section 8 -->
                <div id="section-8" class="space-y-2">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">8</span>
                        <span>Your Statutory Rights as a Cadet</span>
                    </h2>
                    <p>Under the DPDP Act 2023, you retain the right to:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Access a summary of personal data held by Nirmaan.</li>
                        <li>Request correction of inaccurate biographical or contact entries.</li>
                        <li>Withdraw consent for marketing communications at any time.</li>
                        <li>File a complaint before the Data Protection Board of India if unresolved by our DPO.</li>
                    </ul>
                </div>

                <!-- Section 9 -->
                <div id="section-9" class="space-y-3 pt-4 border-t border-slate-200">
                    <h2 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center text-xs font-mono font-bold">9</span>
                        <span>Designated Data Protection Officer (DPO)</span>
                    </h2>
                    <p>For data inquiries, access requests, or privacy concerns, please contact our appointed officer:</p>
                    
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-1 text-xs">
                        <div class="font-bold text-slate-900">{{ $privacyConfig['dpo_name'] }}</div>
                        <div class="text-slate-600">Designation: Data Protection &amp; Privacy Officer</div>
                        <div class="text-slate-600">Email: <a href="mailto:{{ $privacyConfig['dpo_email'] }}" class="text-blue-950 font-bold hover:underline">{{ $privacyConfig['dpo_email'] }}</a></div>
                        <div class="text-slate-600">Helpline: {{ $privacyConfig['dpo_phone'] }}</div>
                        <div class="text-slate-600">Postal Address: {{ $privacyConfig['dpo_address'] }}</div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
