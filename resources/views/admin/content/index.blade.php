@extends('layouts.admin')

@section('title', 'Site Content & FAQ Management — Admin')
@section('header_title', 'Website Content & FAQs')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto" x-data="{ activeTab: 'settings' }">
    
    <!-- Tab navigation -->
    <div class="flex space-x-2 border-b border-gray-200 pb-3 text-xs font-bold">
        <button @click="activeTab = 'settings'" :class="activeTab === 'settings' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100'" class="px-5 py-2.5 rounded-full transition shadow-2xs">
            Site Settings &amp; Social Links
        </button>
        <button @click="activeTab = 'faqs'" :class="activeTab === 'faqs' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100'" class="px-5 py-2.5 rounded-full transition shadow-2xs">
            Frequently Asked Questions ({{ $faqs->count() }})
        </button>
        <button @click="activeTab = 'inquiries'" :class="activeTab === 'inquiries' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100'" class="px-5 py-2.5 rounded-full transition shadow-2xs">
            Contact Inquiries ({{ $inquiries->total() }})
        </button>
    </div>

    <!-- Tab 1: General Settings & Social Links -->
    <div x-show="activeTab === 'settings'" class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl space-y-6">
        <div>
            <h2 class="text-base font-bold text-gray-900">Homepage Copy &amp; Academy Contact Information</h2>
            <p class="text-xs text-gray-500">Easily update hero headlines and contact desk records without editing code.</p>
        </div>

        <form action="{{ route('admin.content.settings') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Hero Section Headline</label>
                    <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? 'Build a Strong Future with Nirmaan' }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Contact Phone</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+91 98765 43210' }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Hero Supporting Subtitle</label>
                <textarea name="hero_subtitle" rows="2" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">{{ $settings['hero_subtitle'] ?? 'Nirmaan provides curated study material, mock tests and online classes for aspirants preparing for security forces entrance examinations.' }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'support@nirmaan.com' }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Academy Headquarters Address</label>
                    <input type="text" name="contact_address" value="{{ $settings['contact_address'] ?? 'Sector 12, Defence Colony, New Delhi - 110024' }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>
            </div>

            <!-- Social Links -->
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Official Social Media URLs</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">YouTube Channel URL</label>
                        <input type="url" name="youtube_url" value="{{ $settings['youtube_url'] ?? 'https://youtube.com/@nirmaandefence' }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Telegram Community URL</label>
                        <input type="url" name="telegram_url" value="{{ $settings['telegram_url'] ?? 'https://t.me/nirmaandefence' }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Instagram URL</label>
                        <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? 'https://instagram.com/nirmaandefence' }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Facebook URL</label>
                        <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? 'https://facebook.com/nirmaandefence' }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 outline-none">
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-8 py-3 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md transition">
                    Save Site Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Tab 2: FAQs Manager -->
    <div x-show="activeTab === 'faqs'" class="space-y-6" style="display:none;">
        <!-- Add FAQ Form -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xs">
            <h3 class="text-sm font-bold text-gray-900 mb-4">+ Add New FAQ Question</h3>
            <form action="{{ route('admin.content.faqs.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Question *</label>
                        <input type="text" name="question" required placeholder="e.g. Can I take mock tests without an internet connection?" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                        <select name="category" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                            <option value="general">General</option>
                            <option value="courses">Courses</option>
                            <option value="tests">Tests</option>
                            <option value="payment">Payment</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Detailed Answer *</label>
                    <textarea name="answer" rows="2" required placeholder="Enter the official answer..." class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none"></textarea>
                </div>
                <button type="submit" class="px-6 py-2 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition">
                    Save FAQ
                </button>
            </form>
        </div>

        <!-- FAQs List -->
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs divide-y divide-gray-100">
            @foreach($faqs as $faq)
                <div class="p-5 flex items-start justify-between gap-4">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 bg-blue-50 px-2 py-0.5 rounded">
                            {{ $faq->category }}
                        </span>
                        <h4 class="text-sm font-bold text-gray-900 mt-1">{{ $faq->question }}</h4>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ $faq->answer }}</p>
                    </div>
                    <form action="{{ route('admin.content.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Delete this FAQ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700">Delete</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tab 3: Contact Inquiries -->
    <div x-show="activeTab === 'inquiries'" class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs" style="display:none;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Sender</th>
                        <th class="px-6 py-4">Subject &amp; Message</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Received</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($inquiries as $inq)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ $inq->name }}
                                <span class="text-[10px] text-gray-400 font-normal block">{{ $inq->email }}</span>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <strong class="text-gray-800 block">{{ $inq->subject }}</strong>
                                <span class="text-gray-500 line-clamp-2">{{ $inq->message }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono">{{ $inq->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $inq->created_at->format('d M, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $inq->status === 'new' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $inq->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.content.inquiries.toggle', $inq->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-blue-700 hover:underline">
                                        {{ $inq->status === 'new' ? 'Mark Replied' : 'Mark New' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-xs text-gray-400">No contact messages received.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $inquiries->links() }}
        </div>
    </div>

</div>
@endsection
