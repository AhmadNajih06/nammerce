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
    public function index(): View
    {
        $products = Product::with('category')->oldest()->paginate(10);

        return view('admin.products.index', compact('products'));
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

        $data['slug'] = Str::slug($data['name']);

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
        // Jika POST payload melebihi post_max_size, PHP mengosongkan $_FILES & $_POST
        if ($request->server('CONTENT_LENGTH') > 0 && empty($_POST) && empty($_FILES)) {
            return back()->withErrors(['image' => 'Ukuran file terlalu besar. Pastikan ukuran file tidak melebihi batas server.'])->withInput();
        }

        $data = $request->validate([
            'category_id'  => ['required', 'exists:categories,id'],
            'name'         => ['required', 'string', 'max:255', 'unique:products,name,' . $product->id],
            'description'  => ['nullable', 'string', 'max:2000'],
            'price'        => ['required', 'integer', 'min:0'],
            'stock'        => ['required', 'integer', 'min:0'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('image')) {
            // Hapus foto lama
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            // Jangan overwrite image dengan null jika tidak ada file baru
            unset($data['image']);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
