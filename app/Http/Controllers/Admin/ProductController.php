<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $showTrashed = $request->boolean('trashed');

        $query = $showTrashed
            ? Product::onlyTrashed()->with('category')->oldest()
            : Product::with('category')->oldest();

        $products     = $query->paginate(10)->withQueryString();
        $trashedCount = Product::onlyTrashed()->count();

        return view('admin.products.index', compact('products', 'trashedCount', 'showTrashed'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id'  => ['required', 'exists:categories,id'],
            'name'         => ['required', 'string', 'max:255', 'unique:products,name'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'price'        => ['required', 'integer', 'min:0'],
            'stock'        => ['required', 'integer', 'min:0'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $data['slug']        = Str::slug($data['name']);
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        if ($request->server('CONTENT_LENGTH') > 0 && empty($_POST) && empty($_FILES)) {
            return back()->withErrors(['image' => 'Ukuran file terlalu besar.'])->withInput();
        }

        $data = $request->validate([
            'category_id'  => ['required', 'exists:categories,id'],
            'name'         => ['required', 'string', 'max:255', 'unique:products,name,' . $product->id],
            'description'  => ['nullable', 'string', 'max:2000'],
            'price'        => ['required', 'integer', 'min:0'],
            'stock'        => ['required', 'integer', 'min:0'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $data['slug']        = Str::slug($data['name']);
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($data['image']);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Soft delete — produk ditandai dihapus tapi data order_items tetap aman.
     * Foto TIDAK dihapus agar bisa dipulihkan kembali.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Pulihkan produk yang di-soft delete.
     */
    public function restore(int $id): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil dipulihkan.');
    }

    /**
     * Toggle is_active ON/OFF.
     */
    public function toggle(Product $product): RedirectResponse
    {
        $product->update(['is_active' => ! $product->is_active]);

        $label = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Produk \"{$product->name}\" berhasil {$label}.");
    }
}
