<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CouponManagementController extends Controller
{
    /**
     * Display a listing of coupons with KPI analytics and filters.
     */
    public function index(Request $request): View
    {
        $query = Coupon::query()->with('creator');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type') && in_array($request->input('type'), [Coupon::TYPE_PERCENT, Coupon::TYPE_FIXED])) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true)
                      ->where(function ($q) {
                          $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                      });
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'expired') {
                $query->whereNotNull('expires_at')->where('expires_at', '<=', now());
            }
        }

        $coupons = $query->latest()->paginate(12)->withQueryString();

        // Metrics for top cards
        $metrics = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })->count(),
            'total_used' => Coupon::sum('used_count'),
            'expired' => Coupon::whereNotNull('expires_at')->where('expires_at', '<=', now())->count(),
        ];

        return view('admin.coupons.index', compact('coupons', 'metrics'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create(): View
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->has('code')) {
            $request->merge(['code' => strtoupper(trim((string) $request->input('code')))]);
        }

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:coupons,code'],
            'name' => ['nullable', 'string', 'max:150'],
            'type' => ['required', Rule::in([Coupon::TYPE_PERCENT, Coupon::TYPE_FIXED])],
            'value' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('type') === Coupon::TYPE_PERCENT && $value > 100) {
                        $fail('Percentage discount value cannot exceed 100%.');
                    }
                },
            ],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['min_order_amount'] = $validated['min_order_amount'] ?? 0;
        $validated['created_by'] = auth()->id();

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', "Coupon '{$validated['code']}' created successfully.");
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        if ($request->has('code')) {
            $request->merge(['code' => strtoupper(trim((string) $request->input('code')))]);
        }

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('coupons', 'code')->ignore($coupon->id)],
            'name' => ['nullable', 'string', 'max:150'],
            'type' => ['required', Rule::in([Coupon::TYPE_PERCENT, Coupon::TYPE_FIXED])],
            'value' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('type') === Coupon::TYPE_PERCENT && $value > 100) {
                        $fail('Percentage discount value cannot exceed 100%.');
                    }
                },
            ],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active');
        $validated['min_order_amount'] = $validated['min_order_amount'] ?? 0;

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', "Coupon '{$coupon->code}' updated successfully.");
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        $code = $coupon->code;
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', "Coupon '{$code}' deleted successfully.");
    }

    /**
     * Toggle the active state of a coupon.
     */
    public function toggleStatus(Request $request, Coupon $coupon): JsonResponse|RedirectResponse
    {
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        $statusText = $coupon->is_active ? 'activated' : 'deactivated';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $coupon->is_active,
                'message' => "Coupon '{$coupon->code}' has been {$statusText}.",
            ]);
        }

        return back()->with('success', "Coupon '{$coupon->code}' has been {$statusText}.");
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'exists:coupons,id'],
            'action' => ['required', 'string', 'in:activate,deactivate,delete,export'],
        ]);

        $ids = $validated['ids'];
        $count = count($ids);

        switch ($validated['action']) {
            case 'activate':
                Coupon::whereIn('id', $ids)->update(['is_active' => true]);
                return back()->with('success', "{$count} coupons were activated.");

            case 'deactivate':
                Coupon::whereIn('id', $ids)->update(['is_active' => false]);
                return back()->with('success', "{$count} coupons were deactivated.");

            case 'delete':
                Coupon::whereIn('id', $ids)->delete();
                return back()->with('success', "{$count} coupons were deleted successfully.");

            case 'export':
                $coupons = Coupon::whereIn('id', $ids)->get();
                $filename = 'coupons_export_' . now()->format('Ymd_His') . '.csv';

                return response()->streamDownload(function () use ($coupons) {
                    $handle = fopen('php://output', 'w');
                    fputcsv($handle, ['ID', 'Code', 'Discount Type', 'Value', 'Min Order', 'Redemptions', 'Max Uses', 'Status', 'Expiry Date']);

                    foreach ($coupons as $c) {
                        fputcsv($handle, [
                            $c->id,
                            $c->code,
                            $c->type,
                            $c->value,
                            $c->min_order_amount ?? 0,
                            $c->used_count ?? 0,
                            $c->max_uses ?? 'Unlimited',
                            $c->is_active ? 'Active' : 'Inactive',
                            $c->expires_at ? $c->expires_at->format('Y-m-d H:i:s') : 'Never',
                        ]);
                    }
                    fclose($handle);
                }, $filename, ['Content-Type' => 'text/csv']);

            default:
                return back()->with('error', 'Unknown bulk action.');
        }
    }
}
