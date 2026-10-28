@extends('layouts.student')

@section('title', 'My Profile & Account Settings — Nirmaan')

@section('content')
<div class="py-8 sm:py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Account Settings</h1>
                <p class="text-xs text-slate-500 mt-0.5">Manage your cadet profile, contact details, and account credentials.</p>
            </div>
            <div class="flex items-center space-x-2 text-xs">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-mono font-medium bg-slate-100 text-slate-700 border border-slate-200">
                    Cadet ID: #NIR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                </span>
            </div>
        </div>

        <form action="{{ route('student.profile.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: Profile & Identity -->
            <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 shadow-2xs space-y-6">
                <div>
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Cadet Identity</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Your official registration details recorded in the Nirmaan academy roster.</p>
                </div>

                <div class="flex items-center space-x-4 pt-1">
                    <div class="w-14 h-14 rounded-xl bg-blue-950 text-white font-bold flex items-center justify-center text-lg shadow-2xs border border-blue-900">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-slate-900">{{ $user->name }}</div>
                        <div class="text-xs text-slate-500 font-mono">{{ $user->email }}</div>
                        <div class="mt-1 flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Verified Cadet
                            </span>
                            <span class="text-[11px] text-slate-400 font-medium">Joined {{ $user->created_at ? $user->created_at->format('M Y') : 'Active' }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               required 
                               value="{{ old('name', $user->name) }}" 
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition shadow-2xs">
                        @error('name')<span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Mobile Number <span class="text-rose-500">*</span>
                        </label>
                        <input type="tel" 
                               name="phone" 
                               required 
                               value="{{ old('phone', $user->phone) }}" 
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition shadow-2xs">
                        @error('phone')<span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Registered Email (Immutable)</label>
                    <input type="email" 
                           disabled 
                           value="{{ $user->email }}" 
                           class="w-full text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-slate-500 cursor-not-allowed">
                    <span class="text-[11px] text-slate-400 mt-1 block">To update your registered email address, please submit a grievance ticket to the admissions officer.</span>
                </div>
            </div>

            <!-- Section 2: Examination Target -->
            <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 shadow-2xs space-y-4">
                <div>
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Target Armed Forces Examination</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Customizes your practice question bank recommendations and CBT mock test alerts.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Primary Target Exam Branch <span class="text-rose-500">*</span></label>
                    <select name="target_exam" required class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-slate-900 focus:outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition shadow-2xs">
                        <option value="Indian Army Agniveer" {{ $user->target_exam === 'Indian Army Agniveer' ? 'selected' : '' }}>Indian Army Agniveer (GD / Tech / Clerk / Tradesman)</option>
                        <option value="Indian Navy SSR/AA/MR" {{ $user->target_exam === 'Indian Navy SSR/AA/MR' ? 'selected' : '' }}>Indian Navy (SSR / AA / MR / Sailor Cadre)</option>
                        <option value="Indian Air Force Group X/Y" {{ $user->target_exam === 'Indian Air Force Group X/Y' ? 'selected' : '' }}>Indian Air Force Agniveer Vayu (Group X / Y)</option>
                        <option value="BSF Constable/ASI" {{ $user->target_exam === 'BSF Constable/ASI' ? 'selected' : '' }}>BSF (Border Security Force — Constable / Tradesman / ASI)</option>
                        <option value="CISF Constable/ASI" {{ $user->target_exam === 'CISF Constable/ASI' ? 'selected' : '' }}>CISF (Central Industrial Security Force — Constable / HC Min)</option>
                        <option value="SSB / CAPF AC" {{ $user->target_exam === 'SSB / CAPF AC' ? 'selected' : '' }}>SSB / CAPF Assistant Commandant (Officer Entry)</option>
                        <option value="NDA / CDS" {{ $user->target_exam === 'NDA / CDS' ? 'selected' : '' }}>NDA / CDS (UPSC Commissioned Officer Cadre)</option>
                    </select>
                </div>
            </div>

            <!-- Section 3: Security & Credentials -->
            <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 shadow-2xs space-y-4">
                <div>
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Security &amp; Password</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Leave password fields blank if you do not wish to update your login credentials.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Current Password</label>
                        <input type="password" 
                               name="current_password" 
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition shadow-2xs">
                        @error('current_password')<span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">New Password</label>
                        <input type="password" 
                               name="new_password" 
                               autocomplete="new-password"
                               placeholder="Minimum 8 characters"
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition shadow-2xs">
                        @error('new_password')<span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('student.dashboard') }}" class="text-xs font-medium text-slate-600 hover:text-slate-900 transition">
                    ← Discard &amp; Return to Dashboard
                </a>
                <button type="submit" class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-lg text-xs font-medium text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Save Profile Changes</span>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
