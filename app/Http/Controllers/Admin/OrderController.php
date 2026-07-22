<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Daftar semua order.
     */
    public function index(Request $request): View
    {
        $query = Order::with('user')->latest();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Detail satu order beserta item-itemnya.
     */
    public function show(Order $order): View
    {
        $order->load('user', 'items.product.category');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update status order.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }
}
