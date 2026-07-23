<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Produk
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter & Search --}}
            <form method="GET" action="{{ route('pelanggan.products.index') }}"
                  class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari produk..."
                       class="flex-1 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">

                <select name="category"
                        class="border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                    Cari
                </button>

                @if (request('search') || request('category'))
                    <a href="{{ route('pelanggan.products.index') }}"
                       class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md text-center">
                        Reset
                    </a>
                @endif
            </form>

            {{-- Grid Produk --}}
            @if ($products->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-10 text-center text-gray-400">
                    Tidak ada produk ditemukan.
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach ($products as $product)
                        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden hover:shadow-md transition flex flex-col">
                            {{-- Foto — klik ke detail --}}
                            <a href="{{ route('pelanggan.products.show', $product) }}" class="block group">
                                <div class="aspect-square overflow-hidden bg-gray-100">
                                    <img src="{{ $product->imageUrl() }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                            </a>
                            {{-- Info --}}
                            <div class="p-3 flex flex-col flex-1">
                                <p class="text-xs text-indigo-500 font-medium mb-0.5">{{ $product->category->name }}</p>
                                <a href="{{ route('pelanggan.products.show', $product) }}"
                                   class="text-sm font-semibold text-gray-800 leading-snug line-clamp-2 hover:text-indigo-600">
                                    {{ $product->name }}
                                </a>
                                <p class="mt-1 text-sm font-bold text-gray-900">{{ $product->formattedPrice() }}</p>
                                {{-- Stok & tombol: hanya tampil jika produk aktif --}}
                            @if ($product->is_active)
                                <p class="text-xs mt-1 {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $product->stock > 0 ? 'Stok: ' . $product->stock : 'Habis' }}
                                </p>

                                <div class="mt-3">
                                    @if ($product->stock > 0)
                                        <a href="{{ route('pelanggan.products.show', $product) }}"
                                        class="w-full inline-block text-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-md hover:bg-indigo-700 transition">
                                            Lihat Detail
                                        </a>
                                    @else
                                        <button disabled
                                                class="w-full px-3 py-1.5 bg-gray-100 text-gray-400 text-xs font-medium rounded-md cursor-not-allowed">
                                            Stok Habis
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="mt-3">
                                    <span class="w-full inline-block text-center px-3 py-1.5 bg-gray-100 text-gray-400 text-xs font-medium rounded-md">
                                        Tidak Tersedia
                                    </span>
                                </div>
                            @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($products->hasPages())
                    <div>{{ $products->links() }}</div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
