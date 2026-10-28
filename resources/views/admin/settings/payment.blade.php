@extends('layouts.admin')

@section('title', 'Razorpay Payment Gateway Settings — Admin Command')
@section('header_title', 'Payment Gateway ')

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
                <span class="text-slate-900 font-semibold">Razorpay Integration</span>
            </div>
            <h1 class=" flex flex-wrap mb-[10px] gap-1 text-lg sm:text-xl font-bold text-slate-900 tracking-tight flex items-center space-x-2.5">
                <span>Razorpay Gateway Configuration</span>
                @if($settings['razorpay_enabled'])
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                        Active
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                        Disabled
                    </span>
                @endif
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $settings['razorpay_mode'] === 'live' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                    {{ strtoupper($settings['razorpay_mode']) }} MODE
                </span>
            </h1>
        </div>

        <div class="flex items-center space-x-2 self-start sm:self-auto">
            <button type="button" @click="testConnection()" :disabled="testing"
                    class="inline-flex items-center space-x-1.5 px-3 py-2 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 hover:text-blue-950 transition-colors shadow-2xs disabled:opacity-60">
                <svg x-show="!testing" class="w-3.5 h-3.5 text-blue-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <svg x-show="testing" class="w-3.5 h-3.5 animate-spin text-blue-950" fill="none" viewBox="0 0 24 24" style="display: none;"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span x-text="testing ? 'Verifying...' : 'Test API Connection'">Test API Connection</span>
            </button>
            <a href="https://dashboard.razorpay.com/" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center space-x-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-blue-950 text-white hover:bg-blue-900 transition-colors shadow-2xs">
                <span>Razorpay Dashboard ↗</span>
            </a>
        </div>
    </div>

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

    <!-- Main Configuration Form -->
    <form action="{{ route('admin.settings.payment.update') }}" method="POST" class="space-y-6">
        @csrf

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
                <div class="text-[11px] text-slate-500">
                    💡 In Test mode, you can use any valid Razorpay test cards (e.g. Card No: <code class="bg-slate-100 px-1 py-0.5 rounded text-slate-800">4111 1111 1111 1111</code>, CVV: <code class="bg-slate-100 px-1 py-0.5 rounded text-slate-800">123</code>, OTP: <code class="bg-slate-100 px-1 py-0.5 rounded text-slate-800">123456</code>).
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
                <div class="text-[11px] text-amber-700 bg-amber-50 p-3 rounded-lg border border-amber-200 flex items-center space-x-2">
                    <svg class="w-4 h-4 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span><strong>Live Production Mode:</strong> Real rupee payments will be debited from candidate bank accounts/UPI. Ensure webhooks are configured to auto-confirm enrollments.</span>
                </div>
            </div>
        </div>

        <!-- 3. Webhook Setup & Instant Settlement -->
        <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-semibold text-slate-900">3. Razorpay Webhook Endpoint &amp; HMAC Signature</h2>
                <p class="text-[11px] text-slate-500">Configure asynchronous notification capture in Razorpay Dashboard to activate candidate enrollments instantly even if the student closes their browser window.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-1">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Webhook Callback URL <span class="text-slate-400 font-normal">(Copy into Razorpay Dashboard)</span>
                    </label>
                    <div class="flex items-center space-x-2">
                        <input type="text" readonly value="{{ $webhookUrl }}" id="webhookUrlInput"
                               class="w-full text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 font-mono text-slate-700 outline-none select-all">
                        <button type="button" @click="copyWebhookUrl()"
                                class="px-3 py-2 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 shadow-2xs shrink-0">
                            <span x-text="copiedWebhook ? 'Copied!' : 'Copy'">Copy</span>
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">Recommended events: <code class="text-blue-950 font-semibold">order.paid</code>, <code class="text-blue-950 font-semibold">payment.captured</code>, <code class="text-blue-950 font-semibold">payment.failed</code>.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Webhook Secret <span class="text-slate-400 font-normal">(HMAC-SHA256 signature verification)</span>
                    </label>
                    <input type="text" name="razorpay_webhook_secret"
                           value="{{ !empty($settings['razorpay_webhook_secret']) ? '••••••••••••••••' . substr($settings['razorpay_webhook_secret'], -4) : '' }}"
                           placeholder="Enter webhook secret configured in Razorpay"
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3 py-2 font-mono text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs">
                    <p class="text-[11px] text-slate-500 mt-1">This secret is used to cryptographically guarantee webhook payloads were sent by Razorpay.</p>
                </div>
            </div>
        </div>

        <!-- 4. Checkout Presentation & Branding -->
        <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-semibold text-slate-900">4. Checkout Branding &amp; Presentation</h2>
                <p class="text-[11px] text-slate-500">Configure how the Razorpay checkout overlay looks to cadets purchasing courses or mock test passes.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-1">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Merchant / Brand Title</label>
                    <input type="text" name="razorpay_merchant_name" value="{{ $settings['razorpay_merchant_name'] }}"
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

        <!-- Submit Button Row -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('admin.dashboard') }}"
               class="px-4 py-2 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs">
                Discard Changes
            </a>
            <button type="submit"
                    class="px-5 py-2 text-xs font-semibold rounded-lg bg-blue-950 text-white hover:bg-blue-900 transition-colors shadow-2xs flex items-center space-x-2">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Save Razorpay Settings</span>
            </button>
        </div>
    </form>
</div>

<script>
function paymentSettingsPage() {
    return {
        mode: '{{ $settings['razorpay_mode'] }}',
        activeKeyTab: '{{ $settings['razorpay_mode'] === 'live' ? 'live' : 'test' }}',
        showTestSecret: false,
        showLiveSecret: false,
        copiedWebhook: false,
        testing: false,
        testResult: null,

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
            
            fetch('{{ route('admin.settings.payment.test') }}', {
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
