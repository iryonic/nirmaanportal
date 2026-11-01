@extends('layouts.admin')

@section('title', 'Scholarship Coupons & Promo Codes — Admin Command')
@section('header_title', 'Coupons & Discounts')

@section('content')
<div class="space-y-5">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs">
        <div>
            <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Coupons &amp; Concessions</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Coupons &amp; Scholarship Discount Codes</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage promotional vouchers, Agniveer scholarships, and fee concessions applied by cadets during checkout.</p>
        </div>
        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('admin.coupons.create') }}" 
               class="px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors flex items-center space-x-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Create New Coupon</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Total Coupons -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-950 flex items-center justify-center shrink-0 border border-blue-200/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block">Total Codes</span>
                <span class="text-xl font-bold text-slate-900 block font-mono">{{ $metrics['total'] }}</span>
            </div>
        </div>

        <!-- Active Coupons -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block">Active &amp; Valid</span>
                <span class="text-xl font-bold text-slate-900 block font-mono">{{ $metrics['active'] }}</span>
            </div>
        </div>

        <!-- Total Redemptions -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center shrink-0 border border-indigo-200/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block">Redemptions</span>
                <span class="text-xl font-bold text-slate-900 block font-mono">{{ number_format($metrics['total_used']) }}</span>
            </div>
        </div>

        <!-- Expired / Inactive -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-slate-50 text-slate-600 flex items-center justify-center shrink-0 border border-slate-200/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block">Expired Codes</span>
                <span class="text-xl font-bold text-slate-900 block font-mono">{{ $metrics['expired'] }}</span>
            </div>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            
            <!-- Search -->
            <div class="lg:col-span-5 relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search coupon code or campaign name..." 
                       class="w-full pl-9 pr-3.5 py-2 text-xs bg-slate-50/70 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-900 focus:bg-white transition-colors text-slate-900 placeholder:text-slate-400">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <!-- Type -->
            <div class="lg:col-span-3">
                <select name="type" class="w-full px-3 py-2 text-xs bg-slate-50/70 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-900 focus:bg-white text-slate-800">
                    <option value="">All Discount Types</option>
                    <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>Percentage Discount (%)</option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Fixed Flat Concession (₹)</option>
                </select>
            </div>

            <!-- Status -->
            <div class="lg:col-span-2">
                <select name="status" class="w-full px-3 py-2 text-xs bg-slate-50/70 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-900 focus:bg-white text-slate-800">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active &amp; Valid</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive (Paused)</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <!-- Submit / Reset Actions -->
            <div class="lg:col-span-2 flex items-center space-x-2">
                <button type="submit" class="flex-1 px-3 py-2 bg-slate-900 text-white text-xs font-semibold rounded-lg hover:bg-slate-800 transition-colors shadow-2xs">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'type', 'status']))
                    <a href="{{ route('admin.coupons.index') }}" class="px-2.5 py-2 text-xs text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors font-medium">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Coupons Data Table -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Coupon Code &amp; Campaign</th>
                        <th class="py-3 px-4">Concession Value</th>
                        <th class="py-3 px-4">Min. Order</th>
                        <th class="py-3 px-4">Redemptions</th>
                        <th class="py-3 px-4">Validity / Expiry</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <!-- Code & Campaign -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-md bg-blue-50 text-blue-950 border border-blue-200/80 font-mono font-bold text-xs tracking-wider">
                                        <span>{{ $coupon->code }}</span>
                                    </span>
                                </div>
                                @if($coupon->name)
                                    <div class="text-[11px] text-slate-600 font-medium mt-1 truncate max-w-xs">
                                        {{ $coupon->name }}
                                    </div>
                                @endif
                            </td>

                            <!-- Value -->
                            <td class="py-3.5 px-4 font-medium text-slate-900">
                                @if($coupon->type === 'percent')
                                    <span class="inline-flex items-center text-xs font-bold text-blue-950">
                                        {{ number_format($coupon->value, 0) }}% OFF
                                    </span>
                                    @if($coupon->max_discount)
                                        <span class="text-[11px] text-slate-500 block">Cap: ₹{{ number_format($coupon->max_discount, 2) }}</span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center text-xs font-bold text-emerald-800">
                                        ₹{{ number_format($coupon->value, 2) }} Flat OFF
                                    </span>
                                @endif
                            </td>

                            <!-- Min Order -->
                            <td class="py-3.5 px-4 text-slate-600 font-mono">
                                @if($coupon->min_order_amount > 0)
                                    ₹{{ number_format($coupon->min_order_amount, 2) }}
                                @else
                                    <span class="text-slate-400 font-sans text-[11px]">No min order</span>
                                @endif
                            </td>

                            <!-- Redemptions -->
                            <td class="py-3.5 px-4">
                                <div class="font-mono text-xs font-bold text-slate-800">
                                    {{ $coupon->used_count }}
                                    <span class="text-slate-400 font-normal">/ {{ $coupon->usage_limit ?? '∞' }}</span>
                                </div>
                                @if($coupon->usage_limit)
                                    @php $pct = min(100, round(($coupon->used_count / $coupon->usage_limit) * 100)); @endphp
                                    <div class="w-20 bg-slate-100 rounded-full h-1.5 mt-1 overflow-hidden">
                                        <div class="bg-blue-950 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                @endif
                            </td>

                            <!-- Validity / Expiry -->
                            <td class="py-3.5 px-4">
                                @if($coupon->isExpired())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                        Expired ({{ $coupon->expires_at->format('d M Y') }})
                                    </span>
                                @elseif($coupon->expires_at)
                                    <span class="text-slate-700 font-medium block">
                                        {{ $coupon->expires_at->format('d M Y') }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">
                                        {{ $coupon->expires_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">
                                        No Expiration
                                    </span>
                                @endif
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-3.5 px-4 text-center">
                                <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" 
                                            title="Toggle Status (Active / Inactive)"
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold transition-all shadow-2xs {{ $coupon->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-300 hover:bg-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $coupon->is_active ? 'bg-emerald-600' : 'bg-slate-400' }}"></span>
                                        {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                       title="Edit Coupon"
                                       class="p-1.5 text-slate-500 hover:text-blue-950 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" 
                                          onsubmit="return confirm('Are you sure you want to permanently delete coupon code {{ $coupon->code }}?');" 
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete Coupon"
                                                class="p-1.5 text-slate-400 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-800">No promo coupons found</p>
                                    <p class="text-[11px] text-slate-400">Try adjusting your search filters or create a new coupon voucher.</p>
                                    <a href="{{ route('admin.coupons.create') }}" class="inline-block mt-2 px-3 py-1.5 text-xs font-semibold text-white bg-blue-950 rounded-lg hover:bg-blue-900 transition-colors">
                                        Create Coupon
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="px-4 py-3 border-t border-slate-200/80 bg-slate-50">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
