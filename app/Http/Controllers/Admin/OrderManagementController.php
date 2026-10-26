<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'course']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('payment_id', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($uq) use ($s) {
                      $uq->where('name', 'like', "%{$s}%")
                         ->orWhere('email', 'like', "%{$s}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $totalRevenue = Order::where('status', 'paid')->sum('amount');

        return view('admin.orders.index', compact('orders', 'totalRevenue'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'course', 'enrollment']);
        return view('admin.orders.show', compact('order'));
    }
}
