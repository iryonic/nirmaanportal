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
