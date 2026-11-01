@extends('layouts.admin')

@section('title', 'Site Content, Legal Pages, FAQs & Grievances — Admin Command')
@section('header_title', 'CMS')

@section('content')
<div class="space-y-5" x-data="{ activeTab: '{{ request('tab', 'settings') }}' }">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs">
        <div>
            <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Portal Content &amp; CMS</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Website Content, Pages, FAQs &amp; Grievances</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage About, Contact, Privacy, Terms, Grievance Cell, homepage copy, and candidate inquiries.</p>
        </div>

        <!-- Linear Tab Segmented Control -->
        <div class="flex items-center flex-wrap gap-1 p-1 bg-slate-100 rounded-lg text-xs font-medium border border-slate-200/80">
            <button @click="activeTab = 'settings'" 
                    :class="activeTab === 'settings' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-2.5 py-1.5 rounded-md transition-colors">
                General &amp; Social
            </button>
            <button @click="activeTab = 'about'" 
                    :class="activeTab === 'about' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-2.5 py-1.5 rounded-md transition-colors">
                About Page
            </button>
            <button @click="activeTab = 'contact'" 
                    :class="activeTab === 'contact' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-2.5 py-1.5 rounded-md transition-colors">
                Contact &amp; Map
            </button>
            <button @click="activeTab = 'legal'" 
                    :class="activeTab === 'legal' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-2.5 py-1.5 rounded-md transition-colors">
                Privacy &amp; Terms
            </button>
            <button @click="activeTab = 'grievance'" 
                    :class="activeTab === 'grievance' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-2.5 py-1.5 rounded-md transition-colors">
                Grievance Cell
            </button>
            <button @click="activeTab = 'video'" 
                    :class="activeTab === 'video' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-2.5 py-1.5 rounded-md transition-colors flex items-center space-x-1">
                <svg class="w-3.5 h-3.5 text-rose-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                <span>Video</span>
            </button>
            <button @click="activeTab = 'faqs'" 
                    :class="activeTab === 'faqs' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-2.5 py-1.5 rounded-md transition-colors">
                FAQs ({{ $faqs->count() }})
            </button>
            <button @click="activeTab = 'inquiries'" 
                    :class="activeTab === 'inquiries' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-2.5 py-1.5 rounded-md transition-colors">
                Inquiries &amp; Dockets ({{ $inquiries->total() }})
            </button>
        </div>
    </div>

    <!-- TAB 1: General & Homepage Copy -->
    <div x-show="activeTab === 'settings'" class="bg-white rounded-xl p-5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-5">
        <div>
            <h2 class="text-sm font-semibold text-slate-900">Homepage Copy &amp; Social Community Channels</h2>
            <p class="text-[11px] text-slate-500">Configure global headlines and social handles shown in discovery pages and footer.</p>
        </div>

        <form action="{{ route('admin.content.settings') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Hero Section Main Headline</label>
                    <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? 'Build a Strong Future with Nirmaan' }}" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Academy Contact Helpline</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+91 98765 43210' }}" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Hero Supporting Subtitle</label>
                <textarea name="hero_subtitle" rows="2" 
                          class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ $settings['hero_subtitle'] ?? 'Nirmaan provides curated study material, mock tests and online classes for aspirants preparing for security forces entrance examinations.' }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Official Inquiries Email</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'support@nirmaan.com' }}" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Academy Headquarters Address</label>
                    <input type="text" name="contact_address" value="{{ $settings['contact_address'] ?? 'Sector 12, Defence Colony, New Delhi - 110024' }}" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
            </div>

            <!-- Social Links -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Official Social Media Community Handles</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">YouTube Channel URL</label>
                        <input type="url" name="youtube_url" value="{{ $settings['youtube_url'] ?? 'https://youtube.com/@nirmaandefence' }}" 
                               class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Telegram Community URL</label>
                        <input type="url" name="telegram_url" value="{{ $settings['telegram_url'] ?? 'https://t.me/nirmaandefence' }}" 
                               class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Instagram Handle URL</label>
                        <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? 'https://instagram.com/nirmaandefence' }}" 
                               class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Facebook Page URL</label>
                        <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? 'https://facebook.com/nirmaandefence' }}" 
                               class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-5 py-2.5 rounded-lg text-xs font-bold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Save General Settings
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 2: About Us Page Configuration -->
    <div x-show="activeTab === 'about'" class="bg-white rounded-xl p-5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-6" style="display:none;">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">About Us Page Customizer</h2>
                <p class="text-[11px] text-slate-500">Edit hero headline, leadership communique, milestone statistics, and core pillars.</p>
            </div>
            <a href="{{ route('about') }}" target="_blank" class="text-xs font-bold text-blue-950 hover:underline inline-flex items-center space-x-1">
                <span>View Live Page</span>
                <span>↗</span>
            </a>
        </div>

        <form action="{{ route('admin.content.settings') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Hero Section -->
            <div class="space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">1. Hero Section</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Top Badge Text</label>
                        <input type="text" name="about_hero_badge" value="{{ $aboutConfig['hero_badge'] }}" 
                               class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Main Heading</label>
                        <input type="text" name="about_hero_title" value="{{ $aboutConfig['hero_title'] }}" 
                               class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Hero Subtitle</label>
                    <textarea name="about_hero_subtitle" rows="2" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">{{ $aboutConfig['hero_subtitle'] }}</textarea>
                </div>
            </div>

            <!-- Director's Message & Leadership -->
            <div class="pt-4 border-t border-slate-100 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">2. Directorate Communique &amp; Leadership</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Director Full Name</label>
                        <input type="text" name="about_director_name" value="{{ $aboutConfig['director_name'] }}" 
                               class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Director Official Role</label>
                        <input type="text" name="about_director_role" value="{{ $aboutConfig['director_role'] }}" 
                               class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Director Military Background / Bio</label>
                    <input type="text" name="about_director_bio" value="{{ $aboutConfig['director_bio'] }}" 
                           class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Director's Official Message to Cadets</label>
                    <textarea name="about_director_message" rows="3" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">{{ $aboutConfig['director_message'] }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Mission Statement</label>
                        <textarea name="about_mission_statement" rows="2" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">{{ $aboutConfig['mission_statement'] }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Vision Statement</label>
                        <textarea name="about_vision_statement" rows="2" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">{{ $aboutConfig['vision_statement'] }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Statistics Counters -->
            <div class="pt-4 border-t border-slate-100 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">3. Milestone Statistics</span>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Stat 1 Value</label>
                        <input type="text" name="about_stats_selections" value="{{ $aboutConfig['stats_selections'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5">
                        <label class="block text-[11px] font-semibold text-slate-600 mt-2 mb-1">Stat 1 Label</label>
                        <input type="text" name="about_stats_selections_label" value="{{ $aboutConfig['stats_selections_label'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Stat 2 Value</label>
                        <input type="text" name="about_stats_faculty" value="{{ $aboutConfig['stats_faculty'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5">
                        <label class="block text-[11px] font-semibold text-slate-600 mt-2 mb-1">Stat 2 Label</label>
                        <input type="text" name="about_stats_faculty_label" value="{{ $aboutConfig['stats_faculty_label'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Stat 3 Value</label>
                        <input type="text" name="about_stats_centers" value="{{ $aboutConfig['stats_centers'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5">
                        <label class="block text-[11px] font-semibold text-slate-600 mt-2 mb-1">Stat 3 Label</label>
                        <input type="text" name="about_stats_centers_label" value="{{ $aboutConfig['stats_centers_label'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Stat 4 Value</label>
                        <input type="text" name="about_stats_success_rate" value="{{ $aboutConfig['stats_success_rate'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5">
                        <label class="block text-[11px] font-semibold text-slate-600 mt-2 mb-1">Stat 4 Label</label>
                        <input type="text" name="about_stats_success_rate_label" value="{{ $aboutConfig['stats_success_rate_label'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5">
                    </div>
                </div>
            </div>

            <!-- Core Pillars -->
            <div class="pt-4 border-t border-slate-100 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">4. Four Core Pillars of Training</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">Pillar 1 Title &amp; Description</label>
                        <input type="text" name="about_pillar_1_title" value="{{ $aboutConfig['pillar_1_title'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-md px-2.5 py-1.5">
                        <textarea name="about_pillar_1_desc" rows="2" class="w-full text-xs bg-white border border-slate-200 rounded-md px-2.5 py-1.5">{{ $aboutConfig['pillar_1_desc'] }}</textarea>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">Pillar 2 Title &amp; Description</label>
                        <input type="text" name="about_pillar_2_title" value="{{ $aboutConfig['pillar_2_title'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-md px-2.5 py-1.5">
                        <textarea name="about_pillar_2_desc" rows="2" class="w-full text-xs bg-white border border-slate-200 rounded-md px-2.5 py-1.5">{{ $aboutConfig['pillar_2_desc'] }}</textarea>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">Pillar 3 Title &amp; Description</label>
                        <input type="text" name="about_pillar_3_title" value="{{ $aboutConfig['pillar_3_title'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-md px-2.5 py-1.5">
                        <textarea name="about_pillar_3_desc" rows="2" class="w-full text-xs bg-white border border-slate-200 rounded-md px-2.5 py-1.5">{{ $aboutConfig['pillar_3_desc'] }}</textarea>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">Pillar 4 Title &amp; Description</label>
                        <input type="text" name="about_pillar_4_title" value="{{ $aboutConfig['pillar_4_title'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-md px-2.5 py-1.5">
                        <textarea name="about_pillar_4_desc" rows="2" class="w-full text-xs bg-white border border-slate-200 rounded-md px-2.5 py-1.5">{{ $aboutConfig['pillar_4_desc'] }}</textarea>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="px-5 py-2.5 rounded-lg text-xs font-bold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Save About Us Settings
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 3: Contact & Map Page Configuration -->
    <div x-show="activeTab === 'contact'" class="bg-white rounded-xl p-5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-6" style="display:none;">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Contact Us &amp; Campus Settings</h2>
                <p class="text-[11px] text-slate-500">Configure public phone helplines, WhatsApp desk, admissions emails, and interactive map embed.</p>
            </div>
            <a href="{{ route('contact') }}" target="_blank" class="text-xs font-bold text-blue-950 hover:underline inline-flex items-center space-x-1">
                <span>View Live Page</span>
                <span>↗</span>
            </a>
        </div>

        <form action="{{ route('admin.content.settings') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Student Helpline Phone</label>
                    <input type="text" name="contact_phone" value="{{ $contactConfig['helpline_phone'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Admissions Cell Phone</label>
                    <input type="text" name="contact_admission_phone" value="{{ $contactConfig['admission_phone'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">WhatsApp Cadet Desk</label>
                    <input type="text" name="contact_whatsapp_phone" value="{{ $contactConfig['whatsapp_phone'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Official Support Email</label>
                    <input type="email" name="contact_email" value="{{ $contactConfig['support_email'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Admissions Desk Email</label>
                    <input type="email" name="contact_admissions_email" value="{{ $contactConfig['admissions_email'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Physical Campus Headquarters Address</label>
                <input type="text" name="contact_address" value="{{ $contactConfig['headquarters_address'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Office &amp; Counselling Working Hours</label>
                <input type="text" name="contact_working_hours" value="{{ $contactConfig['working_hours'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Google Maps Embed URL (iframe src)</label>
                <input type="text" name="contact_map_embed_url" value="{{ $contactConfig['map_embed_url'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950 font-mono text-[11px]">
            </div>

            <div class="pt-2">
                <button type="submit" class="px-5 py-2.5 rounded-lg text-xs font-bold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Save Contact &amp; Map Settings
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 4: Privacy & Terms Legal Settings -->
    <div x-show="activeTab === 'legal'" class="bg-white rounded-xl p-5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-6" style="display:none;">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Privacy Policy &amp; Terms of Service Controls</h2>
                <p class="text-[11px] text-slate-500">Configure legal dates, Data Protection Officer appointments, jurisdiction, and refund periods.</p>
            </div>
            <div class="flex items-center space-x-3 text-xs font-bold">
                <a href="{{ route('privacy') }}" target="_blank" class="text-blue-950 hover:underline">Privacy Policy ↗</a>
                <span>•</span>
                <a href="{{ route('terms') }}" target="_blank" class="text-blue-950 hover:underline">Terms of Service ↗</a>
            </div>
        </div>

        <form action="{{ route('admin.content.settings') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Privacy Policy Config -->
            <div class="space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Privacy Policy (DPDP Act 2023)</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Policy Effective Date</label>
                        <input type="text" name="privacy_effective_date" value="{{ $privacyConfig['effective_date'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Appointed DPO Name</label>
                        <input type="text" name="privacy_dpo_name" value="{{ $privacyConfig['dpo_name'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">DPO Email</label>
                        <input type="email" name="privacy_dpo_email" value="{{ $privacyConfig['dpo_email'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">DPO Direct Phone</label>
                        <input type="text" name="privacy_dpo_phone" value="{{ $privacyConfig['dpo_phone'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">DPO Postal Address</label>
                    <input type="text" name="privacy_dpo_address" value="{{ $privacyConfig['dpo_address'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                </div>
            </div>

            <!-- Terms of Service Config -->
            <div class="pt-4 border-t border-slate-100 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Terms of Service Parameters</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Terms Effective Date</label>
                        <input type="text" name="terms_effective_date" value="{{ $termsConfig['effective_date'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Court Jurisdiction</label>
                        <input type="text" name="terms_jurisdiction" value="{{ $termsConfig['jurisdiction'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Refund Eligibility Window (Days)</label>
                        <input type="number" name="terms_refund_period_days" value="{{ $termsConfig['refund_period_days'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Legal Notice Contact Email</label>
                    <input type="email" name="terms_contact_email" value="{{ $termsConfig['contact_email'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-5 py-2.5 rounded-lg text-xs font-bold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Save Legal Settings
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 5: Grievance Redressal Cell Settings -->
    <div x-show="activeTab === 'grievance'" class="bg-white rounded-xl p-5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-6" style="display:none;">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Statutory Grievance Redressal Cell Configuration</h2>
                <p class="text-[11px] text-slate-500">Maintain Grievance Officer credentials and statutory resolution SLA timelines under IT Rules 2021.</p>
            </div>
            <a href="{{ route('grievances') }}" target="_blank" class="text-xs font-bold text-blue-950 hover:underline inline-flex items-center space-x-1">
                <span>View Live Grievance Cell</span>
                <span>↗</span>
            </a>
        </div>

        <form action="{{ route('admin.content.settings') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Chief Grievance Officer Name</label>
                    <input type="text" name="grievance_officer_name" value="{{ $grievanceConfig['officer_name'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Official Designation</label>
                    <input type="text" name="grievance_officer_designation" value="{{ $grievanceConfig['officer_designation'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Grievance Direct Email</label>
                    <input type="email" name="grievance_officer_email" value="{{ $grievanceConfig['officer_email'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Grievance Helpline Phone</label>
                    <input type="text" name="grievance_officer_phone" value="{{ $grievanceConfig['officer_phone'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Grievance Cell Physical Address</label>
                    <input type="text" name="grievance_officer_address" value="{{ $grievanceConfig['officer_address'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Grievance Cell Working Hours</label>
                    <input type="text" name="grievance_officer_timings" value="{{ $grievanceConfig['officer_timings'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900">
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Service Level Agreement (SLA) Targets</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Level 1 (Frontline Desk)</label>
                        <input type="text" name="grievance_sla_level_1" value="{{ $grievanceConfig['sla_level_1'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Level 2 (Grievance Officer)</label>
                        <input type="text" name="grievance_sla_level_2" value="{{ $grievanceConfig['sla_level_2'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Level 3 (Directorate Review)</label>
                        <input type="text" name="grievance_sla_level_3" value="{{ $grievanceConfig['sla_level_3'] }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-5 py-2.5 rounded-lg text-xs font-bold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Save Grievance Settings
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 6: Featured YouTube Video Manager -->
    <div x-show="activeTab === 'video'" 
         x-data="{
            videoUrl: '{{ $featuredVideo['url'] ?? '' }}',
            customThumbnail: '{{ $featuredVideo['custom_thumbnail'] ?? '' }}',
            extractId(url) {
                if (!url) return '';
                const match = url.match(/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([^\"&?\/\s]{11})/i);
                if (match && match[1]) return match[1];
                if (/^[a-zA-Z0-9_-]{11}$/.test(url.trim())) return url.trim();
                return '';
            },
            get previewThumbnail() {
                if (this.customThumbnail) return this.customThumbnail;
                const id = this.extractId(this.videoUrl);
                return id ? `https://img.youtube.com/vi/${id}/hqdefault.jpg` : '{{ asset('images/cadets_parade.jpg') }}';
            }
         }"
         class="space-y-6" style="display:none;">

        <div class="bg-white rounded-xl p-5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        <span>Featured YouTube Video Spotlight</span>
                    </h2>
                    <p class="text-[11px] text-slate-500">Controls the interactive academy video modal featured on the home discovery portal.</p>
                </div>

                <a href="{{ route('home') }}#featured-video" target="_blank" class="text-xs font-semibold text-blue-950 hover:underline">
                    View on Homepage ↗
                </a>
            </div>

            <form action="{{ route('admin.content.video') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Enable / Disable Toggle -->
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Show Video Spotlight on Homepage</span>
                        <span class="text-[11px] text-slate-500">Toggle whether the featured video card is visible to aspirants.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="home_video_enabled" value="1" {{ ($featuredVideo['enabled'] ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-950"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">YouTube Video URL <span class="text-red-500">*</span></label>
                        <input type="url" name="home_video_url" x-model="videoUrl" required
                               placeholder="https://www.youtube.com/watch?v=..."
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Badge Tagline</label>
                        <input type="text" name="home_video_badge" value="{{ $featuredVideo['badge'] ?? 'INSIDE NIRMAAN ACADEMY' }}" 
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Headline Title</label>
                    <input type="text" name="home_video_title" value="{{ $featuredVideo['title'] ?? 'Experience Training, Drills & Classroom Life at Nirmaan' }}" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Supporting Subtitle</label>
                    <textarea name="home_video_subtitle" rows="2" class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950">{{ $featuredVideo['subtitle'] ?? '' }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Highlight 1</label>
                        <input type="text" name="home_video_highlight_1" value="{{ $featuredVideo['highlight_1'] ?? '' }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Highlight 2</label>
                        <input type="text" name="home_video_highlight_2" value="{{ $featuredVideo['highlight_2'] ?? '' }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Highlight 3</label>
                        <input type="text" name="home_video_highlight_3" value="{{ $featuredVideo['highlight_3'] ?? '' }}" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5">
                    </div>
                </div>

                <button type="submit" class="px-5 py-2.5 rounded-lg text-xs font-bold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Save Featured Video Settings
                </button>
            </form>
        </div>
    </div>

    <!-- TAB 7: FAQ Accordion Manager -->
    <div x-show="activeTab === 'faqs'" class="space-y-6" style="display:none;">
        <div class="bg-white rounded-xl p-5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-4">
            <h2 class="text-sm font-semibold text-slate-900">Add New FAQ Accordion Question</h2>
            <form action="{{ route('admin.content.faqs.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Question Title <span class="text-red-500">*</span></label>
                        <input type="text" name="question" required placeholder="e.g. Can 12th appearing candidates register for the NDA batch?" 
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Target Category</label>
                        <select name="category" class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                            <option value="general">General Admissions</option>
                            <option value="courses">Course Curriculum</option>
                            <option value="tests">CBT Tests &amp; Scoring</option>
                            <option value="payment">Fee &amp; Receipts</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Official Solution / Explanation <span class="text-red-500">*</span></label>
                    <textarea name="answer" rows="2" required placeholder="Enter the official answer displayed to defence aspirants..." 
                              class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors"></textarea>
                </div>
                <button type="submit" class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Publish FAQ
                </button>
            </form>
        </div>

        <!-- FAQs List -->
        <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-2xs divide-y divide-slate-100">
            @forelse($faqs as $faq)
                <div class="p-4 sm:p-5 flex items-start justify-between gap-4 hover:bg-slate-50/70 transition-colors">
                    <div class="space-y-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium uppercase bg-blue-50 text-blue-950 border border-blue-200/60">
                            {{ $faq->category }}
                        </span>
                        <h4 class="text-xs sm:text-sm font-semibold text-slate-900 mt-1">{{ $faq->question }}</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $faq->answer }}</p>
                    </div>
                    <form action="{{ route('admin.content.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Delete this FAQ permanently?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800 px-2.5 py-1 rounded-md border border-red-200 hover:bg-red-50 transition-colors shrink-0 shadow-2xs">
                            Delete
                        </button>
                    </form>
                </div>
            @empty
                <div class="p-8 text-center text-xs text-slate-400">No FAQs created yet.</div>
            @endforelse
        </div>
    </div>

    <!-- TAB 8: Inquiries & Grievances Dockets -->
    <div x-show="activeTab === 'inquiries'" class="space-y-4" style="display:none;">
        
        <!-- Filter Bar -->
        <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <div class="flex items-center space-x-2 text-xs">
                <span class="text-slate-400 font-bold uppercase text-[10px]">Filter Type:</span>
                <a href="{{ route('admin.content.index', ['tab' => 'inquiries']) }}" 
                   class="px-2.5 py-1 rounded-md font-bold {{ !request('type') ? 'bg-blue-950 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    All Messages
                </a>
                <a href="{{ route('admin.content.index', ['tab' => 'inquiries', 'type' => 'inquiry']) }}" 
                   class="px-2.5 py-1 rounded-md font-bold {{ request('type') === 'inquiry' ? 'bg-blue-950 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    General Inquiries
                </a>
                <a href="{{ route('admin.content.index', ['tab' => 'inquiries', 'type' => 'grievance']) }}" 
                   class="px-2.5 py-1 rounded-md font-bold {{ request('type') === 'grievance' ? 'bg-amber-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    ⚖️ Formal Grievances
                </a>
            </div>

            <span class="text-xs text-slate-400 font-mono">Total: {{ $inquiries->total() }}</span>
        </div>

        <!-- Inquiries / Grievances Table -->
        <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-2xs">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                        <tr>
                            <th class="px-4 py-2.5 whitespace-nowrap">Ticket / Type</th>
                            <th class="px-4 py-2.5 whitespace-nowrap">Sender Cadet</th>
                            <th class="px-4 py-2.5 whitespace-nowrap">Subject &amp; Message</th>
                            <th class="px-4 py-2.5 whitespace-nowrap">Phone</th>
                            <th class="px-4 py-2.5 whitespace-nowrap">Date</th>
                            <th class="px-4 py-2.5 whitespace-nowrap">Status</th>
                            <th class="px-4 py-2.5 whitespace-nowrap text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($inquiries as $inq)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="font-mono text-xs font-bold text-slate-900 block">
                                        #{{ $inq->ticket_number ?? 'INQ-' . $inq->id }}
                                    </span>
                                    <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ ($inq->type ?? 'inquiry') === 'grievance' ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-blue-50 text-blue-900 border border-blue-200' }}">
                                        {{ ($inq->type ?? 'inquiry') === 'grievance' ? '⚖️ Grievance' : '📩 Inquiry' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">
                                    {{ $inq->name }}
                                    <span class="text-[10px] text-slate-400 font-normal block">{{ $inq->email }}</span>
                                </td>
                                <td class="px-4 py-3 max-w-sm">
                                    <strong class="text-slate-900 block font-semibold">{{ $inq->subject }}</strong>
                                    <span class="text-slate-500 line-clamp-2 mt-0.5">{{ $inq->message }}</span>
                                </td>
                                <td class="px-4 py-3 font-mono font-medium text-slate-700 whitespace-nowrap">{{ $inq->phone ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-slate-400 whitespace-nowrap text-[11px]">{{ $inq->created_at->format('d M, Y H:i') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase {{ $inq->status === 'new' ? 'bg-amber-50 text-amber-700 border border-amber-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $inq->status === 'new' ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                                        <span>{{ $inq->status === 'new' ? 'Pending' : 'Resolved' }}</span>
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <form action="{{ route('admin.content.inquiries.toggle', $inq->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-md text-xs font-medium text-slate-700 hover:text-blue-950 bg-white hover:bg-slate-50 border border-slate-200 transition-colors shadow-2xs">
                                            {{ $inq->status === 'new' ? 'Mark Resolved' : 'Mark Pending' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-xs text-slate-400 whitespace-nowrap">No messages recorded in this category.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($inquiries->hasPages())
                <div class="p-3 border-t border-slate-200/80 bg-slate-50/50">
                    {{ $inquiries->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
