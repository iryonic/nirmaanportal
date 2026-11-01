<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'course', 'mockTest']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gateway')) {
            $query->where('payment_gateway', $request->gateway);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('payment_id', 'like', "%{$s}%")
                  ->orWhere('notes', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($uq) use ($s) {
                      $uq->where('name', 'like', "%{$s}%")
                         ->orWhere('email', 'like', "%{$s}%")
                         ->orWhere('phone', 'like', "%{$s}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $totalRevenue = Order::where('status', 'paid')->sum('amount');
        $paidCount = Order::where('status', 'paid')->count();
        $todayRevenue = Order::where('status', 'paid')->whereDate('paid_at', today())->sum('amount');

        return view('admin.orders.index', compact('orders', 'totalRevenue', 'paidCount', 'todayRevenue'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'course', 'enrollment']);
        return view('admin.orders.show', compact('order'));
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'exists:orders,id'],
            'action' => ['required', 'string', 'in:mark_paid,mark_pending,delete,export'],
        ]);

        $ids = $validated['ids'];
        $count = count($ids);

        switch ($validated['action']) {
            case 'mark_paid':
                Order::whereIn('id', $ids)->update(['status' => 'paid', 'paid_at' => now()]);
                return back()->with('success', "{$count} orders were marked as paid & verified.");

            case 'mark_pending':
                Order::whereIn('id', $ids)->update(['status' => 'pending']);
                return back()->with('success', "{$count} orders were marked as pending.");

            case 'delete':
                Order::whereIn('id', $ids)->delete();
                return back()->with('success', "{$count} orders were deleted successfully.");

            case 'export':
                $orders = Order::with(['user', 'course', 'mockTest'])->whereIn('id', $ids)->get();
                $filename = 'orders_export_' . now()->format('Ymd_His') . '.csv';

                return response()->streamDownload(function () use ($orders) {
                    $handle = fopen('php://output', 'w');
                    fputcsv($handle, ['ID', 'Order Number', 'Cadet Name', 'Cadet Email', 'Item', 'Gateway', 'Amount', 'Status', 'Paid At', 'Created Date']);

                    foreach ($orders as $o) {
                        $itemTitle = $o->course ? $o->course->title : ($o->mockTest ? $o->mockTest->title : ($o->notes ?? 'Service'));
                        fputcsv($handle, [
                            $o->id,
                            $o->order_number,
                            $o->user ? $o->user->name : 'N/A',
                            $o->user ? $o->user->email : 'N/A',
                            $itemTitle,
                            strtoupper($o->payment_gateway ?? 'N/A'),
                            $o->amount,
                            strtoupper($o->status),
                            $o->paid_at ? $o->paid_at->format('Y-m-d H:i:s') : 'N/A',
                            $o->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                    fclose($handle);
                }, $filename, ['Content-Type' => 'text/csv']);

            default:
                return back()->with('error', 'Unknown bulk action.');
        }
    }
}
