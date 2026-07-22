<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Halaman konfirmasi — tampilkan ringkasan sebelum bayar.
     */
    public function index(): View|RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('pelanggan.cart.index')
                ->with('error', 'Keranjang Anda kosong.');
        }

        $items = $this->resolveCartItems($cart);

        if (empty($items)) {
            session()->forget('cart');
            return redirect()->route('pelanggan.products.index')
                ->with('error', 'Produk di keranjang sudah tidak tersedia.');
        }

        $total = array_sum(array_map(fn ($i) => $i['price'] * $i['quantity'], $items));

        return view('pelanggan.checkout.index', compact('items', 'total'));
    }

    /**
     * Proses checkout: buat Order + OrderItems, kurangi stok.
     */
    public function store(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('pelanggan.cart.index')
                ->with('error', 'Keranjang Anda kosong.');
        }

        $items = $this->resolveCartItems($cart);

        if (empty($items)) {
            session()->forget('cart');
            return redirect()->route('pelanggan.products.index')
                ->with('error', 'Produk di keranjang sudah tidak tersedia.');
        }

        $orderId = null;

        DB::transaction(function () use ($items, &$orderId) {
            $total = array_sum(array_map(fn ($i) => $i['price'] * $i['quantity'], $items));

            $order = Order::create([
                'user_id'      => auth()->id(),
                'total_amount' => $total,
                'status'       => 'pending',
                'order_date'   => now(),
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);

                // Kurangi stok
                Product::where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);
            }

            session()->forget('cart');
            $orderId = $order->id;
        });

        return redirect()->route('pelanggan.checkout.success', ['order' => $orderId]);
    }

    /**
     * Halaman sukses — tampilkan ringkasan order.
     */
    public function success(Order $order): View|RedirectResponse
    {
        // Pastikan order milik user yang sedang login
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('pelanggan.checkout.success', compact('order'));
    }

    // -------------------------------------------------------------------------

    private function resolveCartItems(array $cart): array
    {
        if (empty($cart)) {
            return [];
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = [];
        foreach ($cart as $key => $data) {
            $product = $products->get((int) $key);
            if (! $product || $product->stock < 1) {
                continue;
            }
            $qty         = min($data['quantity'], $product->stock);
            $items[$key] = array_merge($data, [
                'product_id' => $product->id,
                'price'      => $product->price,
                'stock'      => $product->stock,
                'image'      => $product->image,
                'quantity'   => $qty,
            ]);
        }

        return $items;
    }
}
