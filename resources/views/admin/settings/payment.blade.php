@extends('layouts.admin')

@section('title', 'Payment Methods & Gateway Settings — Admin Command')
@section('header_title', 'Payment Settings')

@section('content')
<div class="space-y-6" x-data="paymentSettingsPage()">

    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs">
        <div>
            <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.orders.index') }}" class="hover:text-blue-950">Finance</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Payment Settings</span>
            </div>
            <h1 class="flex flex-wrap gap-2 text-lg sm:text-xl font-bold text-slate-900 tracking-tight items-center">
                <span>Razorpay Gateway Configuration &amp; Payment Methods</span>
                @if($settings['razorpay_enabled'])
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                        Razorpay Active
                    </span>
                @endif
                @if($settings['upi_enabled'])
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                        UPI Direct
                    </span>
                @endif
                @if($settings['bank_transfer_enabled'])
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        IMPS/NEFT
                    </span>
                @endif
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Control which payment channels, VPAs, bank accounts, and merchant details appear on cadet checkout.</p>
        </div>

        <div class="flex items-center space-x-2 self-start sm:self-auto">
            <button type="button" @click="testConnection()" :disabled="testing"
                    class="inline-flex items-center space-x-1.5 px-3 py-2 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 hover:text-blue-950 transition-colors shadow-2xs disabled:opacity-60">
                <svg x-show="!testing" class="w-3.5 h-3.5 text-blue-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <svg x-show="testing" class="w-3.5 h-3.5 animate-spin text-blue-950" fill="none" viewBox="0 0 24 24" style="display: none;"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span x-text="testing ? 'Verifying...' : 'Test Razorpay API'">Test Razorpay API</span>
            </button>
            <a href="https://dashboard.razorpay.com/" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center space-x-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-blue-950 text-white hover:bg-blue-900 transition-colors shadow-2xs">
                <span>Razorpay Dashboard ↗</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-xs font-medium flex items-center space-x-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-xs text-red-700">
            <div class="font-bold mb-1">Please fix the following validation errors:</div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Connection Test Result Banner -->
    <div x-show="testResult !== null" x-cloak
         :class="testResult && testResult.success ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-rose-50 border-rose-200 text-rose-800'"
         class="rounded-xl p-4 border text-xs font-medium flex items-start justify-between">
        <div class="flex items-center space-x-2">
            <template x-if="testResult && testResult.success">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </template>
            <template x-if="testResult && !testResult.success">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </template>
            <span x-text="testResult ? testResult.message : ''"></span>
        </div>
        <button type="button" @click="testResult = null" class="text-slate-400 hover:text-slate-600">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Navigation Tabs for Payment Sections -->
    <div class="flex items-center space-x-2 border-b border-slate-200 pb-2 overflow-x-auto text-xs font-semibold">
        <button type="button" @click="activeSection = 'razorpay'"
                :class="activeSection === 'razorpay' ? 'bg-blue-950 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200'"
                class="px-3.5 py-2 rounded-lg transition-colors flex items-center space-x-1.5 shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            <span>Razorpay Gateway</span>
        </button>

        <button type="button" @click="activeSection = 'upi'"
                :class="activeSection === 'upi' ? 'bg-blue-950 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200'"
                class="px-3.5 py-2 rounded-lg transition-colors flex items-center space-x-1.5 shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            <span>Direct UPI &amp; QR Code</span>
        </button>

        <button type="button" @click="activeSection = 'bank'"
                :class="activeSection === 'bank' ? 'bg-blue-950 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200'"
                class="px-3.5 py-2 rounded-lg transition-colors flex items-center space-x-1.5 shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            <span>Bank Transfer (NEFT / IMPS)</span>
        </button>

        <button type="button" @click="activeSection = 'methods'"
                :class="activeSection === 'methods' ? 'bg-blue-950 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200'"
                class="px-3.5 py-2 rounded-lg transition-colors flex items-center space-x-1.5 shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <span>Alternate Channels &amp; Desk</span>
        </button>
    </div>

    <!-- Main Configuration Form -->
    <form action="{{ route('admin.settings.payment.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- ================= TAB 1: RAZORPAY GATEWAY ================= -->
        <div x-show="activeSection === 'razorpay'" class="space-y-6">
            <!-- 1. Gateway Status & Environment -->
            <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">1. Engine Status &amp; Gateway Environment</h2>
                        <p class="text-[11px] text-slate-500">Toggle whether cadets pay through Razorpay and select Sandbox (Test) or Production (Live).</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-1">
                    <!-- Enabled Toggle Card -->
                    <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/60 flex items-start justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-800">Razorpay Gateway</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Enable checkout processing across website</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-3">
                            <input type="checkbox" name="razorpay_enabled" value="1" {{ $settings['razorpay_enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-950"></div>
                        </label>
                    </div>

                    <!-- Environment Selector -->
                    <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/60">
                        <label class="block text-xs font-semibold text-slate-800 mb-2">Environment Mode</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center justify-center p-2 rounded-md border text-xs font-semibold cursor-pointer transition-colors"
                                   :class="mode === 'test' ? 'bg-white border-blue-950 text-blue-950 shadow-2xs ring-1 ring-blue-950' : 'bg-transparent border-slate-200 text-slate-600 hover:bg-white'">
                                <input type="radio" name="razorpay_mode" value="test" x-model="mode" class="sr-only">
                                <span>Sandbox (Test)</span>
                            </label>
                            <label class="flex items-center justify-center p-2 rounded-md border text-xs font-semibold cursor-pointer transition-colors"
                                   :class="mode === 'live' ? 'bg-white border-amber-600 text-amber-700 shadow-2xs ring-1 ring-amber-600' : 'bg-transparent border-slate-200 text-slate-600 hover:bg-white'">
                                <input type="radio" name="razorpay_mode" value="live" x-model="mode" class="sr-only">
                                <span>Production (Live)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Fallback Simulation Toggle -->
                    <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/60 flex items-start justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-800">Sandbox Simulation Fallback</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Allows mock testing when live keys or internet are unavailable</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-3">
                            <input type="checkbox" name="razorpay_fallback_simulation" value="1" {{ $settings['razorpay_fallback_simulation'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-950"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- 2. API Credentials Configuration -->
            <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">2. Razorpay API Keys</h2>
                        <p class="text-[11px] text-slate-500">Provide credentials from your Razorpay Dashboard &gt; Settings &gt; API Keys.</p>
                    </div>
                    <div class="flex items-center p-1 bg-slate-100 rounded-lg text-xs font-medium border border-slate-200">
                        <button type="button" @click="activeKeyTab = 'test'"
                                :class="activeKeyTab === 'test' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                                class="px-2.5 py-1 rounded transition-colors">
                            Test Keys
                        </button>
                        <button type="button" @click="activeKeyTab = 'live'"
                                :class="activeKeyTab === 'live' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                                class="px-2.5 py-1 rounded transition-colors">
                            Live Keys
                        </button>
                    </div>
                </div>

                <!-- Test Keys Section -->
                <div x-show="activeKeyTab === 'test'" class="space-y-4 pt-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Test Key ID <span class="text-slate-400 font-normal">(starts with rzp_test_)</span>
                            </label>
                            <input type="text" name="razorpay_key_id" value="{{ $settings['razorpay_key_id'] }}"
                                   placeholder="rzp_test_xxxxxxxxxxxxxxxx"
                                   class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 font-mono text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Test Key Secret
                            </label>
                            <div class="relative">
                                <input :type="showTestSecret ? 'text' : 'password'" name="razorpay_key_secret"
                                       value="{{ !empty($settings['razorpay_key_secret']) ? '••••••••••••••••' . substr($settings['razorpay_key_secret'], -4) : '' }}"
                                       placeholder="Enter test key secret"
                                       class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg pl-3 pr-10 py-2 font-mono text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                                <button type="button" @click="showTestSecret = !showTestSecret"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                    <svg x-show="!showTestSecret" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="showTestSecret" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Live Keys Section -->
                <div x-show="activeKeyTab === 'live'" class="space-y-4 pt-1" style="display: none;">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Live Key ID <span class="text-amber-600 font-normal">(starts with rzp_live_)</span>
                            </label>
                            <input type="text" name="razorpay_live_key_id" value="{{ $settings['razorpay_live_key_id'] }}"
                                   placeholder="rzp_live_xxxxxxxxxxxxxxxx"
                                   class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 font-mono text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Live Key Secret
                            </label>
                            <div class="relative">
                                <input :type="showLiveSecret ? 'text' : 'password'" name="razorpay_live_key_secret"
                                       value="{{ !empty($settings['razorpay_live_key_secret']) ? '••••••••••••••••' . substr($settings['razorpay_live_key_secret'], -4) : '' }}"
                                       placeholder="Enter live key secret"
                                       class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg pl-3 pr-10 py-2 font-mono text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                                <button type="button" @click="showLiveSecret = !showLiveSecret"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                    <svg x-show="!showLiveSecret" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="showLiveSecret" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Webhook Setup & Instant Settlement -->
            <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-sm font-semibold text-slate-900">3. Razorpay Webhook Endpoint</h2>
                    <p class="text-[11px] text-slate-500">Configure asynchronous notification capture in Razorpay Dashboard to activate candidate enrollments instantly.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Webhook Callback URL
                        </label>
                        <div class="flex items-center space-x-2">
                            <input type="text" readonly value="{{ $webhookUrl }}" id="webhookUrlInput"
                                   class="w-full text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 font-mono text-slate-700 outline-none select-all">
                            <button type="button" @click="copyWebhookUrl()"
                                    class="px-3 py-2 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 shadow-2xs shrink-0">
                                <span x-text="copiedWebhook ? 'Copied!' : 'Copy'">Copy</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Webhook Secret (HMAC-SHA256)
                        </label>
                        <input type="text" name="razorpay_webhook_secret"
                               value="{{ !empty($settings['razorpay_webhook_secret']) ? '••••••••••••••••' . substr($settings['razorpay_webhook_secret'], -4) : '' }}"
                               placeholder="Enter webhook secret"
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 font-mono text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                    </div>
                </div>
            </div>

            <!-- 4. Checkout Presentation & Branding -->
            <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-sm font-semibold text-slate-900">4. Branding &amp; Presentation</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Merchant / Brand Title</label>
                        <input type="text" name="razorpay_merchant_name" value="{{ $settings['razorpay_merchant_name'] }}" required
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Theme Brand Color</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" name="razorpay_theme_color" value="{{ $settings['razorpay_theme_color'] }}"
                                   class="w-10 h-9 p-0.5 border border-slate-200 rounded-lg cursor-pointer bg-white">
                            <input type="text" readonly value="{{ $settings['razorpay_theme_color'] }}"
                                   class="w-full text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 font-mono text-slate-700">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TAB 2: DIRECT UPI & QR CODE ================= -->
        <div x-show="activeSection === 'upi'" class="space-y-6" style="display: none;">
            <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Direct UPI Gateway &amp; Official QR Code</h2>
                        <p class="text-[11px] text-slate-500">Configure your academy UPI Virtual Payment Address (VPA) and upload an official QR code for direct cadet scans.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="upi_enabled" value="1" {{ $settings['upi_enabled'] ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-950"></div>
                        <span class="ml-2 text-xs font-semibold text-slate-700">Enable Direct UPI</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pt-1">
                    <!-- UPI Details Form (2 Cols) -->
                    <div class="lg:col-span-2 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                UPI Virtual Payment Address (VPA) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="upi_vpa" value="{{ $settings['upi_vpa'] }}" required
                                       placeholder="nirmaanacademy@icici"
                                       class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 font-mono text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">This VPA is displayed on checkout and copied to the student clipboard upon clicking "Copy UPI ID".</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                UPI Beneficiary Merchant Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="upi_merchant_name" value="{{ $settings['upi_merchant_name'] }}" required
                                   placeholder="Nirmaan Defence Academy"
                                   class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Cadet Payment Guidance / Instructions
                            </label>
                            <textarea name="upi_instructions" rows="3"
                                      class="w-full text-xs bg-white border border-slate-200 rounded-lg p-3 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">{{ $settings['upi_instructions'] }}</textarea>
                            <p class="text-[11px] text-slate-400 mt-1">Guidelines displayed to candidates inside the UPI payment box.</p>
                        </div>

                        <!-- QR Code Upload -->
                        <div class="pt-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Custom Branded UPI QR Code Image (Optional)
                            </label>
                            <input type="file" name="upi_qr_file" accept="image/*"
                                   @change="handleQrUpload($event)"
                                   class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-950 hover:file:bg-blue-100 cursor-pointer">
                            <p class="text-[11px] text-slate-400 mt-1">Upload a high-resolution PNG, JPG, or SVG of your academy BharatPe/PhonePe/GPay QR code (Max 4MB). If none is uploaded, the checkout system auto-generates a dynamic vector QR code from your VPA.</p>
                            
                            @if(!empty($settings['upi_qr_url']))
                                <div class="mt-2 flex items-center space-x-2">
                                    <label class="flex items-center space-x-1.5 text-xs text-red-600 cursor-pointer">
                                        <input type="checkbox" name="remove_upi_qr" value="1" class="rounded text-red-600 focus:ring-red-500">
                                        <span>Remove uploaded QR image and revert to auto-generated vector QR</span>
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- UPI Preview Box (1 Col) -->
                    <div class="space-y-3">
                        <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">Live Checkout Preview</div>
                        <div class="border rounded-xl p-4 bg-slate-50/70 border-slate-200 text-center space-y-3">
                            <div class="w-36 h-36 mx-auto bg-white p-2 rounded-xl border border-slate-200 shadow-2xs flex items-center justify-center overflow-hidden">
                                <template x-if="qrPreviewUrl">
                                    <img :src="qrPreviewUrl" alt="QR Preview" class="w-full h-full object-contain">
                                </template>
                                <template x-if="!qrPreviewUrl">
                                    @if(!empty($settings['upi_qr_url']))
                                        <img src="{{ $settings['upi_qr_url'] }}" alt="Active QR" class="w-full h-full object-contain">
                                    @else
                                        <div class="text-center p-2">
                                            <svg class="w-10 h-10 text-slate-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                            <span class="text-[10px] text-slate-400 block font-mono">Auto-generated Vector QR</span>
                                        </div>
                                    @endif
                                </template>
                            </div>

                            <div>
                                <div class="text-xs font-bold text-slate-900 font-mono" x-text="upiVpa || '{{ $settings['upi_vpa'] }}'"></div>
                                <div class="text-[11px] text-slate-500" x-text="upiMerchant || '{{ $settings['upi_merchant_name'] }}'"></div>
                            </div>

                            <div class="inline-flex items-center space-x-1 px-3 py-1 bg-white border border-slate-200 rounded-lg text-slate-700 text-xs font-semibold shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-blue-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                <span>Copy UPI ID</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TAB 3: BANK TRANSFER / NEFT / IMPS ================= -->
        <div x-show="activeSection === 'bank'" class="space-y-6" style="display: none;">
            <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Direct Bank Transfer / NEFT / IMPS / RTGS</h2>
                        <p class="text-[11px] text-slate-500">Provide official academy current account banking credentials for direct wire transfers.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="bank_transfer_enabled" value="1" {{ $settings['bank_transfer_enabled'] ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-950"></div>
                        <span class="ml-2 text-xs font-semibold text-slate-700">Enable Bank Transfer Tab</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Bank Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="bank_name" value="{{ $settings['bank_name'] }}" required
                               placeholder="State Bank of India"
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Account Holder / Beneficiary Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="bank_account_name" value="{{ $settings['bank_account_name'] }}" required
                               placeholder="Nirmaan Academy Educational Foundation"
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Account Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="bank_account_number" value="{{ $settings['bank_account_number'] }}" required
                               placeholder="4092101004521"
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 font-mono text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            IFSC Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="bank_ifsc_code" value="{{ $settings['bank_ifsc_code'] }}" required
                               placeholder="SBIN0001234"
                               class="w-full text-xs sm:text-sm uppercase bg-white border border-slate-200 rounded-lg px-3 py-2 font-mono font-bold text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Bank Branch Name &amp; Address
                        </label>
                        <input type="text" name="bank_branch" value="{{ $settings['bank_branch'] }}"
                               placeholder="Defence Cantt Branch, Jaipur"
                               class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Bank Transfer Instructions &amp; Verification Note
                        </label>
                        <textarea name="bank_instructions" rows="2"
                                  class="w-full text-xs bg-white border border-slate-200 rounded-lg p-3 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">{{ $settings['bank_instructions'] }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TAB 4: ALTERNATE CHANNELS & DESK ================= -->
        <div x-show="activeSection === 'methods'" class="space-y-6" style="display: none;">
            <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-sm font-semibold text-slate-900">Alternate Payment Channels &amp; Academy Campus Desk</h2>
                    <p class="text-[11px] text-slate-500">Enable or disable specific payment options shown on the checkout page.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                    <!-- Cards Toggle -->
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 flex items-start justify-between">
                        <div>
                            <div class="text-xs font-bold text-slate-900">Debit &amp; Credit Cards</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Visa, Mastercard, RuPay</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-2">
                            <input type="checkbox" name="method_card_enabled" value="1" {{ $settings['method_card_enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-950"></div>
                        </label>
                    </div>

                    <!-- Netbanking Toggle -->
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 flex items-start justify-between">
                        <div>
                            <div class="text-xs font-bold text-slate-900">Net Banking</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">50+ Major Indian Banks</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-2">
                            <input type="checkbox" name="method_netbanking_enabled" value="1" {{ $settings['method_netbanking_enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-950"></div>
                        </label>
                    </div>

                    <!-- EMI Toggle -->
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 flex items-start justify-between">
                        <div>
                            <div class="text-xs font-bold text-slate-900">0% Easy EMI</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Installment assistance</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-2">
                            <input type="checkbox" name="method_emi_enabled" value="1" {{ $settings['method_emi_enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-950"></div>
                        </label>
                    </div>
                </div>

                <!-- Offline Campus Desk Settings -->
                <div class="border-t border-slate-100 pt-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Academy Admissions Desk / In-Person Payment</h3>
                            <p class="text-[11px] text-slate-500">Allow candidates to reserve their seat and pay directly at the academy campus via cash or demand draft.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="method_offline_enabled" value="1" {{ $settings['method_offline_enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-950"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Campus Desk Address</label>
                            <input type="text" name="offline_campus_address" value="{{ $settings['offline_campus_address'] }}"
                                   class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Admissions Helpdesk Phone</label>
                            <input type="text" name="offline_contact_phone" value="{{ $settings['offline_contact_phone'] }}"
                                   class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Desk Instructions &amp; Timings</label>
                            <textarea name="offline_instructions" rows="2"
                                      class="w-full text-xs bg-white border border-slate-200 rounded-lg p-3 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">{{ $settings['offline_instructions'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button Row -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('admin.dashboard') }}"
               class="px-4 py-2 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs">
                Discard Changes
            </a>
            <button type="submit"
                    class="px-5 py-2 text-xs font-semibold rounded-lg bg-blue-950 text-white hover:bg-blue-900 transition-colors shadow-2xs flex items-center space-x-2">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Save All Payment Settings</span>
            </button>
        </div>
    </form>
</div>

<script>
function paymentSettingsPage() {
    return {
        activeSection: 'razorpay',
        mode: '{{ $settings['razorpay_mode'] }}',
        activeKeyTab: '{{ $settings['razorpay_mode'] === 'live' ? 'live' : 'test' }}',
        showTestSecret: false,
        showLiveSecret: false,
        copiedWebhook: false,
        testing: false,
        testResult: null,
        qrPreviewUrl: null,
        upiVpa: '{{ $settings['upi_vpa'] }}',
        upiMerchant: '{{ $settings['upi_merchant_name'] }}',

        handleQrUpload(e) {
            const file = e.target.files[0];
            if (file) {
                this.qrPreviewUrl = URL.createObjectURL(file);
            }
        },

        copyWebhookUrl() {
            const input = document.getElementById('webhookUrlInput');
            if (navigator.clipboard) {
                navigator.clipboard.writeText(input.value).then(() => {
                    this.copiedWebhook = true;
                    setTimeout(() => this.copiedWebhook = false, 2500);
                });
            } else {
                input.select();
                document.execCommand('copy');
                this.copiedWebhook = true;
                setTimeout(() => this.copiedWebhook = false, 2500);
            }
        },

        testConnection() {
            this.testing = true;
            this.testResult = null;
            
            fetch('{{ route('admin.settings.payment.test', [], false) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.testing = false;
                this.testResult = data;
            })
            .catch(err => {
                this.testing = false;
                this.testResult = {
                    success: false,
                    message: 'Network request failed: ' + err.message
                };
            });
        }
    }
}
</script>
@endsection
