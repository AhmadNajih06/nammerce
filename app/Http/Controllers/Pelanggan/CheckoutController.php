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
     * Halaman checkout — tampilkan ringkasan + form informasi pengiriman.
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
     * Proses checkout: validasi form, buat Order + OrderItems, kurangi stok.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'shipping_name'    => ['required', 'string', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'shipping_phone'   => ['required', 'string', 'max:20'],
            'payment_method'   => ['required', 'in:transfer,cod,ewallet'],
        ], [
            'shipping_name.required'    => 'Nama penerima wajib diisi.',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'shipping_phone.required'   => 'Nomor telepon wajib diisi.',
            'payment_method.required'   => 'Metode pembayaran wajib dipilih.',
            'payment_method.in'         => 'Metode pembayaran tidak valid.',
        ]);

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

        DB::transaction(function () use ($items, $request, &$orderId) {
            $total = array_sum(array_map(fn ($i) => $i['price'] * $i['quantity'], $items));

            $order = Order::create([
                'user_id'          => auth()->id(),
                'total_amount'     => $total,
                'status'           => 'pending',
                'order_date'       => now(),
                'shipping_name'    => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_phone'   => $request->shipping_phone,
                'payment_method'   => $request->payment_method,
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);

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
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('pelanggan.checkout.success', compact('order'));
    }

    // ── Helper ────────────────────────────────────────────────────────────────

    private function resolveCartItems(array $cart): array
    {
        if (empty($cart)) {
            return [];
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = [];
        foreach ($cart as $key => $data) {
            $product = $products->get((int) $key);
            if (! $product || $product->stock < 1 || ! $product->is_active) {
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
