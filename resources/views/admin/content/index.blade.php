@extends('layouts.admin')

@section('title', 'Site Content, FAQs & Inquiries — Admin Command')
@section('header_title', 'CMS')

@section('content')
<div class="space-y-5" x-data="{ activeTab: 'settings' }">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs">
        <div>
            <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Portal Content &amp; CMS</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Website Content, FAQs &amp; Inquiries</h1>
            <p class="text-xs text-slate-500 mt-0.5">Maintain academy contact information, homepage copy, FAQ accordion questions, and candidate inquiries.</p>
        </div>

        <!-- Linear Tab Segmented Control -->
        <div class="flex items-center p-1 bg-slate-100 rounded-lg text-xs font-medium self-start sm:self-auto border border-slate-200/80">
            <button @click="activeTab = 'settings'" 
                    :class="activeTab === 'settings' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-3 py-1.5 rounded-md transition-colors">
                Settings &amp; Copy
            </button>
            <button @click="activeTab = 'video'" 
                    :class="activeTab === 'video' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-3 py-1.5 rounded-md transition-colors flex items-center space-x-1.5">
                <svg class="w-3.5 h-3.5 text-rose-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                <span>Featured Video</span>
            </button>
            <button @click="activeTab = 'faqs'" 
                    :class="activeTab === 'faqs' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-3 py-1.5 rounded-md transition-colors">
                FAQs ({{ $faqs->count() }})
            </button>
            <button @click="activeTab = 'inquiries'" 
                    :class="activeTab === 'inquiries' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-3 py-1.5 rounded-md transition-colors">
                Inquiries ({{ $inquiries->total() }})
            </button>
        </div>
    </div>

    <!-- Tab 1: General Settings & Social Links -->
    <div x-show="activeTab === 'settings'" class="bg-white rounded-xl p-5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-5">
        <div>
            <h2 class="text-sm font-semibold text-slate-900">Homepage Copy &amp; Academy Contact Desks</h2>
            <p class="text-[11px] text-slate-500">Configure live headlines and contact information shown on the discovery portal.</p>
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
                <button type="submit" class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Save Platform Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Tab: Featured YouTube Video Manager -->
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

        <!-- Video Settings Form -->
        <div class="bg-white rounded-xl p-5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-md bg-rose-50 text-rose-600 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        </span>
                        <span>Homepage Featured YouTube Video &amp; Academy Tour</span>
                    </h2>
                    <p class="text-[11px] text-slate-500 mt-0.5">Showcase a professional YouTube orientation, campus drill tour, or demo lecture on the homepage.</p>
                </div>

                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg {{ ($featuredVideo['enabled'] ?? true) ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/70' : 'bg-slate-100 text-slate-600 border border-slate-200' }} text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full {{ ($featuredVideo['enabled'] ?? true) ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                    <span>{{ ($featuredVideo['enabled'] ?? true) ? 'Section Active on Homepage' : 'Section Hidden' }}</span>
                </div>
            </div>

            <form action="{{ route('admin.content.video') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Section Visibility Toggle Switch -->
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Enable Featured Video Section</h4>
                        <p class="text-[11px] text-slate-500">Toggle whether this video section is displayed to students and visitors on the homepage.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="home_video_enabled" value="1" {{ ($featuredVideo['enabled'] ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-950"></div>
                    </label>
                </div>

                <!-- Video Link & YouTube ID Extraction -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                    <div class="lg:col-span-8 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                YouTube Video URL or Video ID <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                                </span>
                                <input type="text" 
                                       name="home_video_url" 
                                       x-model="videoUrl"
                                       placeholder="e.g. https://www.youtube.com/watch?v=ScMzIvxBSi4 or ScMzIvxBSi4" 
                                       class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg pl-9 pr-3.5 py-2.5 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Accepts standard YouTube links (<code class="text-slate-600 bg-slate-100 px-1 py-0.5 rounded">youtube.com/watch?v=...</code>, <code class="text-slate-600 bg-slate-100 px-1 py-0.5 rounded">youtu.be/...</code>, Shorts, or direct 11-char Video ID).</p>
                        </div>

                        <!-- Section Badge & Title -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Section Eyebrow Badge</label>
                                <input type="text" name="home_video_badge" value="{{ $featuredVideo['badge'] ?? 'INSIDE NIRMAAN ACADEMY' }}" 
                                       class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Primary CTA Button Label</label>
                                <input type="text" name="home_video_cta_text" value="{{ $featuredVideo['cta_text'] ?? 'Explore All Courses' }}" 
                                       class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Section Headline Title</label>
                            <input type="text" name="home_video_title" value="{{ $featuredVideo['title'] ?? 'Experience Training, Drills & Classroom Life at Nirmaan' }}" 
                                   class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 font-semibold outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Supporting Subtitle Description</label>
                            <textarea name="home_video_subtitle" rows="2" 
                                      class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ $featuredVideo['subtitle'] ?? 'Get an exclusive inside look at our smart digital classrooms, physical obstacle grounds, and SSB interview chambers guided by former defence officers.' }}</textarea>
                        </div>

                        <!-- 3 Feature Highlights -->
                        <div class="pt-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Key Highlights / Pillars (Shown below video or copy)</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <span class="text-[10px] text-slate-400 block mb-0.5">Highlight 1</span>
                                    <input type="text" name="home_video_highlight_1" value="{{ $featuredVideo['highlight_1'] ?? 'Ex-Officers & SSB Assessors Mentorship' }}" 
                                           class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-slate-900 outline-none focus:border-blue-950 shadow-2xs">
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 block mb-0.5">Highlight 2</span>
                                    <input type="text" name="home_video_highlight_2" value="{{ $featuredVideo['highlight_2'] ?? 'Complete Written & Physical Ground Prep' }}" 
                                           class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-slate-900 outline-none focus:border-blue-950 shadow-2xs">
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 block mb-0.5">Highlight 3</span>
                                    <input type="text" name="home_video_highlight_3" value="{{ $featuredVideo['highlight_3'] ?? 'Digital Live & Recorded HD Lectures' }}" 
                                           class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-slate-900 outline-none focus:border-blue-950 shadow-2xs">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">CTA Button Destination URL</label>
                                <input type="text" name="home_video_cta_url" value="{{ $featuredVideo['cta_url'] ?? '' }}" placeholder="Leave blank to default to /courses" 
                                       class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 shadow-2xs">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Custom Poster Image URL (Optional)</label>
                                <input type="text" name="home_video_thumbnail" x-model="customThumbnail" placeholder="Leave empty for auto YouTube thumbnail" 
                                       class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 shadow-2xs">
                            </div>
                        </div>
                    </div>

                    <!-- Live Dynamic Preview Column -->
                    <div class="lg:col-span-4 bg-slate-50 rounded-xl p-4 border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Live Thumbnail Preview</span>
                            
                            <!-- Video Card Preview Frame -->
                            <div class="relative rounded-xl overflow-hidden aspect-video bg-slate-900 border border-slate-200 shadow-sm group">
                                <img :src="previewThumbnail" alt="Video Preview" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-slate-950/40 flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-white/95 text-rose-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                                <div class="absolute bottom-2 left-2 right-2 flex items-center justify-between text-white text-[10px] bg-slate-950/70 backdrop-blur-xs px-2 py-1 rounded">
                                    <span class="font-mono">YouTube HD</span>
                                    <span class="font-mono" x-text="extractId(videoUrl) ? 'ID: ' + extractId(videoUrl) : 'Invalid URL'"></span>
                                </div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <span class="text-[10px] font-bold text-amber-600 uppercase tracking-widest block" x-text="'PREVIEW'"></span>
                                <p class="text-xs font-bold text-slate-800 line-clamp-2">{{ $featuredVideo['title'] ?? 'Training Life at Nirmaan' }}</p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-200/60 mt-4">
                            <p class="text-[10px] text-slate-400">The player uses responsive 16:9 aspect ratio and privacy-enhanced YouTube embed mode.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <button type="submit" class="px-6 py-2.5 rounded-lg text-xs font-bold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors flex items-center space-x-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Save Featured Video Settings</span>
                    </button>
                    <a href="{{ route('home') }}#featured-video" target="_blank" class="text-xs font-semibold text-blue-950 hover:underline inline-flex items-center space-x-1">
                        <span>View on Homepage</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab 2: FAQs Manager -->
    <div x-show="activeTab === 'faqs'" class="space-y-5" style="display:none;">
        <!-- Add FAQ Form -->
        <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs space-y-4">
            <h3 class="text-sm font-semibold text-slate-900">+ Create FAQ Question</h3>
            <form action="{{ route('admin.content.faqs.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Question Prompt <span class="text-red-500">*</span></label>
                        <input type="text" name="question" required placeholder="e.g. Can I take mock tests without an internet connection?" 
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

    <!-- Tab 3: Contact Inquiries -->
    <div x-show="activeTab === 'inquiries'" class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-2xs" style="display:none;">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 py-2.5 whitespace-nowrap">Sender Cadet</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Inquiry Subject &amp; Note</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Contact Phone</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Received Date</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Status</th>
                        <th class="px-4 py-2.5 whitespace-nowrap text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inquiries as $inq)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">
                                {{ $inq->name }}
                                <span class="text-[10px] text-slate-400 font-normal block">{{ $inq->email }}</span>
                            </td>
                            <td class="px-4 py-3 max-w-sm">
                                <strong class="text-slate-900 block font-semibold">{{ $inq->subject }}</strong>
                                <span class="text-slate-500 line-clamp-2 mt-0.5">{{ $inq->message }}</span>
                            </td>
                            <td class="px-4 py-3 font-mono font-medium text-slate-700 whitespace-nowrap">{{ $inq->phone ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-slate-400 whitespace-nowrap text-[11px]">{{ $inq->created_at->format('d M, Y') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase {{ $inq->status === 'new' ? 'bg-amber-50 text-amber-700 border border-amber-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $inq->status === 'new' ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                                    <span>{{ $inq->status === 'new' ? 'New' : 'Handled' }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <form action="{{ route('admin.content.inquiries.toggle', $inq->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-md text-xs font-medium text-slate-700 hover:text-blue-950 bg-white hover:bg-slate-50 border border-slate-200 transition-colors shadow-2xs">
                                        {{ $inq->status === 'new' ? 'Mark Handled' : 'Mark New' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-xs text-slate-400 whitespace-nowrap">No candidate contact messages recorded.</td>
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
@endsection
