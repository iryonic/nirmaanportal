@extends('layouts.admin')

@section('title', 'Site Content, FAQs & Inquiries — Admin')
@section('header_title', 'Website Content & Candidate Inquiries')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'settings' }">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-3xl p-6 border border-gray-100 shadow-xs">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-400 font-semibold mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
                <span>&bull;</span>
                <span class="text-blue-700">Platform Content &amp; CMS</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Website Content, FAQs &amp; Inquiries</h1>
            <p class="text-xs text-gray-500 mt-0.5">Maintain academy contact information, homepage copy, FAQ accordion questions, and candidate inquiries.</p>
        </div>

        <!-- Tab Pills -->
        <div class="flex items-center p-1.5 bg-gray-100 rounded-2xl text-xs font-semibold self-start sm:self-auto">
            <button @click="activeTab = 'settings'" 
                    :class="activeTab === 'settings' ? 'bg-white text-blue-700 shadow-xs' : 'text-gray-500 hover:text-gray-800'"
                    class="px-4 py-2 rounded-xl transition">
                Settings &amp; Copy
            </button>
            <button @click="activeTab = 'faqs'" 
                    :class="activeTab === 'faqs' ? 'bg-white text-blue-700 shadow-xs' : 'text-gray-500 hover:text-gray-800'"
                    class="px-4 py-2 rounded-xl transition">
                FAQs ({{ $faqs->count() }})
            </button>
            <button @click="activeTab = 'inquiries'" 
                    :class="activeTab === 'inquiries' ? 'bg-white text-blue-700 shadow-xs' : 'text-gray-500 hover:text-gray-800'"
                    class="px-4 py-2 rounded-xl transition">
                Inquiries ({{ $inquiries->total() }})
            </button>
        </div>
    </div>

    <!-- Tab 1: General Settings & Social Links -->
    <div x-show="activeTab === 'settings'" class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xs space-y-6">
        <div>
            <h2 class="text-base font-extrabold text-gray-900">Homepage Copy &amp; Academy Contact Desks</h2>
            <p class="text-xs text-gray-500 mt-0.5">Configure live headlines and contact information shown on the discovery portal.</p>
        </div>

        <form action="{{ route('admin.content.settings') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Hero Section Main Headline</label>
                    <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? 'Build a Strong Future with Nirmaan' }}" 
                           class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Academy Contact Helpline</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+91 98765 43210' }}" 
                           class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500 focus:bg-white transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Hero Supporting Subtitle</label>
                <textarea name="hero_subtitle" rows="2" 
                          class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500 focus:bg-white transition">{{ $settings['hero_subtitle'] ?? 'Nirmaan provides curated study material, mock tests and online classes for aspirants preparing for security forces entrance examinations.' }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Official Inquiries Email</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'support@nirmaan.com' }}" 
                           class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Academy Headquarters Address</label>
                    <input type="text" name="contact_address" value="{{ $settings['contact_address'] ?? 'Sector 12, Defence Colony, New Delhi - 110024' }}" 
                           class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500 focus:bg-white transition">
                </div>
            </div>

            <!-- Social Links -->
            <div class="pt-6 border-t border-gray-100">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-3">Official Social Media Community Handles</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">YouTube Channel URL</label>
                        <input type="url" name="youtube_url" value="{{ $settings['youtube_url'] ?? 'https://youtube.com/@nirmaandefence' }}" 
                               class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 outline-none focus:border-blue-500 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Telegram Community URL</label>
                        <input type="url" name="telegram_url" value="{{ $settings['telegram_url'] ?? 'https://t.me/nirmaandefence' }}" 
                               class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 outline-none focus:border-blue-500 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Instagram Handle URL</label>
                        <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? 'https://instagram.com/nirmaandefence' }}" 
                               class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 outline-none focus:border-blue-500 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Facebook Page URL</label>
                        <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? 'https://facebook.com/nirmaandefence' }}" 
                               class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 outline-none focus:border-blue-500 focus:bg-white transition">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-500/20 transition">
                    Save Platform Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Tab 2: FAQs Manager -->
    <div x-show="activeTab === 'faqs'" class="space-y-6" style="display:none;">
        <!-- Add FAQ Form -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xs">
            <h3 class="text-sm font-extrabold text-gray-900 mb-3">+ Create New FAQ Question</h3>
            <form action="{{ route('admin.content.faqs.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Question Prompt *</label>
                        <input type="text" name="question" required placeholder="e.g. Can I take mock tests without an internet connection?" 
                               class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Target Category</label>
                        <select name="category" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                            <option value="general">General Admissions</option>
                            <option value="courses">Course Curriculum</option>
                            <option value="tests">CBT Tests &amp; Scoring</option>
                            <option value="payment">Fee &amp; Receipts</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Official Solution / Explanation *</label>
                    <textarea name="answer" rows="2" required placeholder="Enter the official answer displayed to defence aspirants..." 
                              class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500"></textarea>
                </div>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                    Publish FAQ
                </button>
            </form>
        </div>

        <!-- FAQs List -->
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs divide-y divide-gray-100">
            @forelse($faqs as $faq)
                <div class="p-5 flex items-start justify-between gap-4 hover:bg-gray-50/60 transition">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
                            {{ $faq->category }}
                        </span>
                        <h4 class="text-sm font-bold text-gray-900 mt-2">{{ $faq->question }}</h4>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ $faq->answer }}</p>
                    </div>
                    <form action="{{ route('admin.content.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Delete this FAQ permanently?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 hover:underline px-2 py-1">
                            Delete
                        </button>
                    </form>
                </div>
            @empty
                <div class="p-8 text-center text-xs text-gray-400">No FAQs created yet.</div>
            @endforelse
        </div>
    </div>

    <!-- Tab 3: Contact Inquiries -->
    <div x-show="activeTab === 'inquiries'" class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs" style="display:none;">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">Sender Cadet</th>
                        <th class="px-6 py-4 whitespace-nowrap">Inquiry Subject &amp; Note</th>
                        <th class="px-6 py-4 whitespace-nowrap">Contact Phone</th>
                        <th class="px-6 py-4 whitespace-nowrap">Received Date</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($inquiries as $inq)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">
                                {{ $inq->name }}
                                <span class="text-[10px] text-gray-400 font-normal block">{{ $inq->email }}</span>
                            </td>
                            <td class="px-6 py-4 max-w-sm">
                                <strong class="text-gray-900 block font-semibold">{{ $inq->subject }}</strong>
                                <span class="text-gray-500 line-clamp-2 mt-0.5">{{ $inq->message }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold text-gray-700 whitespace-nowrap">{{ $inq->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $inq->created_at->format('d M, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $inq->status === 'new' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $inq->status === 'new' ? '● New' : '● Handled' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <form action="{{ route('admin.content.inquiries.toggle', $inq->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 transition">
                                        {{ $inq->status === 'new' ? 'Mark Handled' : 'Mark New' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-xs text-gray-400 whitespace-nowrap">No candidate contact messages recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($inquiries->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $inquiries->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
