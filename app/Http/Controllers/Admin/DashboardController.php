<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // ── Stat cards ──────────────────────────────────────────────────────
        $totalOrders   = Order::count();
        $totalRevenue  = Order::where('status', 'completed')->sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalProducts = Product::count();

        // ── Produk terlaris top 5 ────────────────────────────────────────────
        $topProducts = OrderItem::with('product.category')
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(price * quantity) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // ── Order terbaru 10 ─────────────────────────────────────────────────
        $recentOrders = Order::with('user')->oldest()->limit(5)->get();

        // ── Grafik pendapatan bulanan (12 bulan terakhir) ────────────────────
        $revenueChart = $this->getMonthlyRevenue();

        // ── Grafik persentase penjualan per produk ───────────────────────────
        $productSalesChart = $this->getProductSalesChart();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'totalProducts',
            'topProducts',
            'recentOrders',
            'revenueChart',
            'productSalesChart',
        ));
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function getMonthlyRevenue(): array
    {
        $months = collect(range(11, 0))->map(fn ($i) => Carbon::now()->startOfMonth()->subMonths($i));

        $rows = Order::where('status', 'completed')
            ->where('order_date', '>=', Carbon::now()->startOfMonth()->subMonths(11))
            ->selectRaw("DATE_FORMAT(order_date, '%Y-%m') as month, SUM(total_amount) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        return [
            'labels' => $months->map(fn ($m) => $m->format('M Y'))->values()->toArray(),
            'data'   => $months->map(fn ($m) => (int) ($rows[$m->format('Y-m')] ?? 0))->values()->toArray(),
        ];
    }

    private function getProductSalesChart(): array
    {
        $items = OrderItem::selectRaw('product_id, SUM(quantity) as total_qty')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product:id,name')
            ->limit(8)
            ->get();

        if ($items->isEmpty()) {
            return ['labels' => [], 'data' => [], 'raw' => []];
        }

        $totalQty = $items->sum('total_qty');

        return [
            'labels' => $items->map(fn ($i) => $i->product?->name ?? '(dihapus)')->toArray(),
            'data'   => $items->map(fn ($i) => round(($i->total_qty / $totalQty) * 100, 1))->toArray(),
            'raw'    => $items->map(fn ($i) => (int) $i->total_qty)->toArray(),
        ];
    }
}
