<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // --- Stat cards ---
        $totalOrders   = Order::count();
        $totalRevenue  = Order::whereNotIn('status', ['cancelled'])->sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalProducts = Product::count();

        // --- Produk terlaris (top 5 berdasarkan total qty terjual) ---
        $topProducts = OrderItem::with('product.category')
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(price * quantity) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // --- Order terbaru (10 terakhir) ---
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'totalProducts',
            'topProducts',
            'recentOrders',
        ));
    }
}
