<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Daftar semua produk dengan filter kategori & pencarian.
     */
    public function index(Request $request): View
    {
        $query = Product::with('category')->oldest();

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Pencarian nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('pelanggan.products.index', compact('products', 'categories'));
    }

    /**
     * Detail satu produk.
     */
    public function show(Product $product): View
    {
        return view('pelanggan.products.show', compact('product'));
    }
}
