@extends('layouts.student')

@section('title', 'My Profile & Account Settings — Nirmaan')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Student Profile &amp; Settings</h1>
            <p class="text-xs text-gray-500 mt-1">Manage your registered details, contact information, and security credentials.</p>
        </div>

        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl space-y-6">
            
            <div class="flex items-center space-x-4 pb-6 border-b border-gray-100">
                <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-800 font-extrabold flex items-center justify-center text-xl border-2 border-blue-200">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $user->name }}</h2>
                    <span class="text-xs text-gray-400 font-medium">{{ $user->email }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 block w-fit mt-1">
                        Cadet ID: #NIR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
            </div>

            <form action="{{ route('student.profile.update') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" required value="{{ old('name', $user->name) }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                        @error('name')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Mobile Number</label>
                        <input type="tel" name="phone" required value="{{ old('phone', $user->phone) }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                        @error('phone')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Primary Target Examination</label>
                    <select name="target_exam" required class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                        <option value="Indian Army Agniveer" {{ $user->target_exam === 'Indian Army Agniveer' ? 'selected' : '' }}>Indian Army Agniveer (GD / Tech / Clerk)</option>
                        <option value="Indian Navy SSR/AA/MR" {{ $user->target_exam === 'Indian Navy SSR/AA/MR' ? 'selected' : '' }}>Indian Navy (SSR / AA / MR)</option>
                        <option value="Indian Air Force Group X/Y" {{ $user->target_exam === 'Indian Air Force Group X/Y' ? 'selected' : '' }}>Indian Air Force (Group X / Y)</option>
                        <option value="BSF Constable/ASI" {{ $user->target_exam === 'BSF Constable/ASI' ? 'selected' : '' }}>BSF (Border Security Force)</option>
                        <option value="CISF Constable/ASI" {{ $user->target_exam === 'CISF Constable/ASI' ? 'selected' : '' }}>CISF (Central Industrial Security Force)</option>
                        <option value="SSB / CAPF AC" {{ $user->target_exam === 'SSB / CAPF AC' ? 'selected' : '' }}>SSB / CAPF Assistant Commandant</option>
                        <option value="NDA / CDS" {{ $user->target_exam === 'NDA / CDS' ? 'selected' : '' }}>NDA / CDS (Officer Cadre)</option>
                    </select>
                </div>

                <div class="pt-6 border-t border-gray-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Change Password (Leave blank to keep current)</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Current Password</label>
                            <input type="password" name="current_password" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                            @error('current_password')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">New Password</label>
                            <input type="password" name="new_password" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                            @error('new_password')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="px-8 py-3 rounded-full text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-md transition">
                        Save Profile Changes
                    </button>
                </div>
            </form>

        </div>

    </div>
</div>
@endsection
