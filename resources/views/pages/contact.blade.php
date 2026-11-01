@extends('layouts.app')

@section('title', 'Contact Us & Student Helpdesk | Nirmaan Defence Academy')

@section('content')
<div class="py-10 sm:py-16 text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-16">
        
        <!-- 1. Header Section -->
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <span class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-black tracking-wider uppercase bg-blue-50 text-blue-950 border border-blue-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ $contactConfig['hero_badge'] ?? 'WE ARE HERE TO HELP YOU' }}</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-slate-950 tracking-tight leading-tight">
                {{ $contactConfig['hero_title'] ?? 'Get in Touch with Nirmaan Academy' }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-2xl mx-auto">
                {{ $contactConfig['hero_subtitle'] ?? 'Whether you have questions regarding upcoming batches, CBT mock test subscriptions, or need guidance on SSB interview preparation, our academic counsellors are available 6 days a week.' }}
            </p>
        </div>

        <!-- 2. Interactive Direct Contact Channels -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Student Helpline -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-950 border border-blue-200 flex items-center justify-center text-lg mb-3">
                        📞
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Student Helpline</span>
                    <h3 class="text-sm font-black text-slate-900 mt-0.5">{{ $contactConfig['helpline_phone'] }}</h3>
                    <p class="text-[11px] text-slate-500 mt-1">General inquiries &amp; exam notification queries.</p>
                </div>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactConfig['helpline_phone']) }}" 
                   class="inline-flex items-center space-x-1.5 text-xs font-bold text-blue-950 hover:underline pt-2 border-t border-slate-100">
                    <span>Call Helpline</span>
                    <span>→</span>
                </a>
            </div>

            <!-- Admission Cell -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center justify-center text-lg mb-3">
                        🎓
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Admission Cell</span>
                    <h3 class="text-sm font-black text-slate-900 mt-0.5">{{ $contactConfig['admission_phone'] }}</h3>
                    <p class="text-[11px] text-slate-500 mt-1">Batch enrollment, fee waivers &amp; guidance.</p>
                </div>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactConfig['admission_phone']) }}" 
                   class="inline-flex items-center space-x-1.5 text-xs font-bold text-emerald-700 hover:underline pt-2 border-t border-slate-100">
                    <span>Call Admissions</span>
                    <span>→</span>
                </a>
            </div>

            <!-- WhatsApp Cadet Desk -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-green-50 text-green-700 border border-green-200 flex items-center justify-center text-lg mb-3">
                        💬
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">WhatsApp Desk</span>
                    <h3 class="text-sm font-black text-slate-900 mt-0.5">{{ $contactConfig['whatsapp_phone'] }}</h3>
                    <p class="text-[11px] text-slate-500 mt-1">Instant syllabus PDF &amp; timetable updates.</p>
                </div>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactConfig['whatsapp_phone']) }}" target="_blank" rel="noopener noreferrer" 
                   class="inline-flex items-center space-x-1.5 text-xs font-bold text-green-700 hover:underline pt-2 border-t border-slate-100">
                    <span>Chat on WhatsApp</span>
                    <span>→</span>
                </a>
            </div>

            <!-- Official Email -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-800 border border-purple-200 flex items-center justify-center text-lg mb-3">
                        ✉️
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Official Support</span>
                    <h3 class="text-xs font-black text-slate-900 mt-0.5 truncate">{{ $contactConfig['support_email'] }}</h3>
                    <p class="text-[11px] text-slate-500 mt-1">Written queries &amp; document submissions.</p>
                </div>
                <a href="mailto:{{ $contactConfig['support_email'] }}" 
                   class="inline-flex items-center space-x-1.5 text-xs font-bold text-purple-800 hover:underline pt-2 border-t border-slate-100">
                    <span>Send Email</span>
                    <span>→</span>
                </a>
            </div>

        </div>

        <!-- 3. Form & Location Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: Contact Inquiry Form (7 cols) -->
            <div class="lg:col-span-7 bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-2xs space-y-5">
                <div>
                    <h2 class="text-lg font-black text-slate-950 tracking-tight">Send a Message to Our Academic Team</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Fill out the form below. An academic mentor will review your details and respond within 24 hours.</p>
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

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required value="{{ old('name', auth()->user()->name ?? '') }}" 
                                   placeholder="e.g. Vikram Batra"
                                   class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" required value="{{ old('email', auth()->user()->email ?? '') }}" 
                                   placeholder="e.g. cadet@gmail.com"
                                   class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Phone / WhatsApp Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                                   placeholder="+91 98765 43210"
                                   class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nature of Inquiry</label>
                            <select name="inquiry_type" 
                                    class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">
                                <option value="General Enquiry">General Inquiry</option>
                                <option value="Admissions & Batches">Admission &amp; Batches</option>
                                <option value="Mock Test & CBT Support">Mock Test &amp; CBT Portal</option>
                                <option value="Scholarship & Fee">Scholarship &amp; Fee Assistance</option>
                                <option value="SSB Mentorship Desk">SSB Guidance Cell</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Subject / Target Exam <span class="text-rose-500">*</span></label>
                        <input type="text" name="subject" required value="{{ old('subject') }}" 
                               placeholder="e.g. Query regarding NDA 2027 Comprehensive Batch"
                               class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Your Detailed Message <span class="text-rose-500">*</span></label>
                        <textarea name="message" required rows="4" 
                                  placeholder="Please provide details about your academic background, current preparation stage, or specific assistance required..."
                                  class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 outline-none focus:bg-white focus:border-blue-950 focus:ring-2 focus:ring-blue-950/20 transition-all">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" 
                            class="w-full sm:w-auto px-7 py-3 rounded-xl bg-blue-950 hover:bg-blue-900 text-white font-bold text-xs transition-colors shadow-xs flex items-center justify-center space-x-2">
                        <span>Submit Inquiry</span>
                        <span>→</span>
                    </button>
                </form>
            </div>

            <!-- Right: Campus Details & Google Map (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Campus & Address Card -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-2xs space-y-4">
                    <span class="text-[10px] font-bold text-blue-950 tracking-wider uppercase block">PHYSICAL HEADQUARTERS</span>
                    <h3 class="text-base font-black text-slate-900">Nirmaan Academy Central Campus</h3>
                    
                    <div class="space-y-3 text-xs text-slate-600">
                        <div class="flex items-start space-x-2.5">
                            <span class="text-base shrink-0">📍</span>
                            <span>{{ $contactConfig['headquarters_address'] }}</span>
                        </div>

                        <div class="flex items-start space-x-2.5">
                            <span class="text-base shrink-0">🕒</span>
                            <span>{{ $contactConfig['working_hours'] }}</span>
                        </div>

                        <div class="flex items-start space-x-2.5">
                            <span class="text-base shrink-0">✉️</span>
                            <span>Admissions Desk: <a href="mailto:{{ $contactConfig['admissions_email'] }}" class="text-blue-950 font-bold hover:underline">{{ $contactConfig['admissions_email'] }}</a></span>
                        </div>
                    </div>
                </div>

                <!-- Google Map Embed Card -->
                <div class="bg-white rounded-2xl p-2 border border-slate-200 shadow-2xs overflow-hidden">
                    <iframe src="{{ $contactConfig['map_embed_url'] }}" 
                            width="100%" 
                            height="280" 
                            style="border:0; border-radius: 0.75rem;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <!-- Quick Redressal Direct Link -->
                <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200/80 text-xs flex items-center justify-between">
                    <div>
                        <span class="font-bold text-amber-900 block">Have a Grievance or Payment Issue?</span>
                        <span class="text-[11px] text-amber-700">Access our official Statutory Grievance Redressal Cell.</span>
                    </div>
                    <a href="{{ route('grievances') }}" class="px-3 py-1.5 rounded-lg bg-amber-900 text-white font-bold text-[11px] shrink-0 ml-2">
                        Grievances →
                    </a>
                </div>

            </div>

        </div>

        <!-- 4. Quick FAQs Accordion Section -->
        @if(isset($faqs) && count($faqs) > 0)
            <div class="bg-white rounded-2xl p-6 sm:p-10 border border-slate-200 shadow-2xs space-y-6">
                <div>
                    <span class="text-[10px] font-bold text-blue-950 tracking-wider uppercase block">FREQUENTLY ASKED QUESTIONS</span>
                    <h3 class="text-xl font-black text-slate-900 mt-1">Common Inquiries Answered</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($faqs as $faq)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <h4 class="text-xs font-bold text-slate-900">{{ $faq->question }}</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $faq->answer }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
