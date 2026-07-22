<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Daftar semua order milik pelanggan yang sedang login.
     */
    public function index(): View
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('pelanggan.orders.index', compact('orders'));
    }

    /**
     * Detail satu order — hanya boleh dilihat oleh pemiliknya.
     */
    public function show(Order $order): View
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load('items.product.category');

        return view('pelanggan.orders.show', compact('order'));
    }
}
