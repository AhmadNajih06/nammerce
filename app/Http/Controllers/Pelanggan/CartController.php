<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang.
     */
    public function index(): View
    {
        $cart  = session()->get('cart', []);
        $items = $this->resolveCartItems($cart);
        $total = array_sum(array_map(fn ($i) => $i['price'] * $i['quantity'], $items));

        return view('pelanggan.cart.index', compact('items', 'total'));
    }

    /**
     * Tambah produk ke keranjang.
     */
    public function add(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($product->stock < 1) {
            return back()->with('error', 'Produk ini sudah habis.');
        }

        if (! $product->is_active) {
            return back()->with('error', 'Produk ini sedang tidak tersedia untuk dipesan.');
        }

        $qty  = (int) $request->quantity;
        $cart = session()->get('cart', []);
        $key  = (string) $product->id;

        if (isset($cart[$key])) {
            $newQty          = $cart[$key]['quantity'] + $qty;
            $cart[$key]['quantity'] = min($newQty, $product->stock);
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'image'      => $product->image,
                'stock'      => $product->stock,
                'quantity'   => min($qty, $product->stock),
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', '"' . $product->name . '" ditambahkan ke keranjang.');
    }

    /**
     * Update jumlah item di keranjang.
     */
    public function update(Request $request, int $productId): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = session()->get('cart', []);
        $key  = (string) $productId;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = min((int) $request->quantity, $cart[$key]['stock']);
            session()->put('cart', $cart);
        }

        return redirect()->route('pelanggan.cart.index');
    }

    /**
     * Hapus satu item dari keranjang.
     */
    public function remove(int $productId): RedirectResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[(string) $productId]);
        session()->put('cart', $cart);

        return redirect()->route('pelanggan.cart.index')
            ->with('success', 'Item dihapus dari keranjang.');
    }

    /**
     * Kosongkan seluruh keranjang.
     */
    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return redirect()->route('pelanggan.cart.index')
            ->with('success', 'Keranjang berhasil dikosongkan.');
    }

    // -------------------------------------------------------------------------

    /**
     * Ambil data item cart dengan harga & stok terkini dari DB.
     */
    private function resolveCartItems(array $cart): array
    {
        if (empty($cart)) {
            return [];
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = [];
        foreach ($cart as $key => $data) {
            $product = $products->get((int) $key);
            if (! $product) {
                continue; // produk sudah dihapus admin
            }
            $items[$key] = array_merge($data, [
                'price' => $product->price,
                'stock' => $product->stock,
                'image' => $product->image,
            ]);
        }

        return $items;
    }
}
