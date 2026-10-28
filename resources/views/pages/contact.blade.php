@extends('layouts.app')

@section('title', 'Contact & Academic Helpdesk — Nirmaan')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-950 block mb-1">STUDENT ASSISTANCE</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Get in Touch with Nirmaan</h1>
            <p class="text-sm text-slate-600 mt-2">
                Have queries regarding batches, syllabus, age eligibility or mock tests? Our academic support desk is here to assist you.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Info Cards -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200 shadow-2xs space-y-6">
                    <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Contact Information</h2>

                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-950 border border-blue-100 flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 block">Phone Support</span>
                            <span class="text-sm font-bold text-slate-800">+91 98765 43210</span>
                            <p class="text-[11px] text-slate-500 mt-0.5">Mon–Sat: 9:00 AM – 7:00 PM IST</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-100 flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 block">Email Desk</span>
                            <span class="text-sm font-bold text-slate-800">support@nirmaan.com</span>
                            <p class="text-[11px] text-slate-500 mt-0.5">Typically replied within 4 hours</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-800 border border-slate-200 flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 block">Headquarters</span>
                            <span class="text-sm font-bold text-slate-800">Nirmaan Defence Academy</span>
                            <p class="text-[11px] text-slate-500 mt-0.5">Sector 12, Defence Colony, New Delhi - 110024</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Inquiry Form -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-xl p-6 sm:p-10 border border-slate-200 shadow-2xs">
                    <h2 class="text-xl font-bold text-slate-900 mb-1">Send a Message</h2>
                    <p class="text-xs text-slate-500 mb-6">Fill out the form below and an academic counsellor will connect with you.</p>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Your Full Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Ramesh Kumar" class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition font-medium">
                                @error('name')<span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" required value="{{ old('email') }}" placeholder="name@example.com" class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition font-medium">
                                @error('email')<span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210" class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition font-medium">
                                @error('phone')<span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Subject <span class="text-rose-500">*</span></label>
                                <input type="text" name="subject" required value="{{ old('subject') }}" placeholder="e.g. Batch Enquiry for Agniveer Army" class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition font-medium">
                                @error('subject')<span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Message / Question <span class="text-rose-500">*</span></label>
                            <textarea name="message" rows="4" required placeholder="Tell us how we can help you with your preparation..." class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition font-medium">{{ old('message') }}</textarea>
                            @error('message')<span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <button type="submit" class="w-full py-2.5 px-6 rounded-lg text-xs sm:text-sm font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition">
                            Submit Inquiry
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
