<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pelanggan.products.index') }}" class="text-gray-400 hover:text-gray-600">&larr;</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Produk</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="flex flex-col sm:flex-row">

                    {{-- Foto --}}
                    <div class="sm:w-80 flex-shrink-0 bg-gray-100">
                        <img src="{{ $product->imageUrl() }}"
                             alt="{{ $product->name }}"
                             class="w-full h-80 sm:h-full object-cover">
                    </div>

                    {{-- Detail --}}
                    <div class="p-6 flex flex-col gap-3 flex-1">
                        <p class="text-sm text-indigo-500 font-medium">{{ $product->category->name }}</p>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                        <p class="text-2xl font-bold text-gray-800">{{ $product->formattedPrice() }}</p>

                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">Stok:</span>
                            @if ($product->stock > 0)
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    {{ $product->stock }} tersedia
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    Habis
                                </span>
                            @endif
                        </div>

                        @if ($product->description)
                            <div class="mt-2">
                                <p class="text-sm font-medium text-gray-700 mb-1">Deskripsi</p>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $product->description }}</p>
                            </div>
                        @endif

                        <div class="mt-auto pt-4 space-y-3">

                            @if (session('success'))
                                <div class="bg-green-100 border border-green-300 text-green-800 px-3 py-2 rounded text-sm">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="bg-red-100 border border-red-300 text-red-800 px-3 py-2 rounded text-sm">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if ($product->stock > 0)
                                <form action="{{ route('pelanggan.cart.add', $product) }}" method="POST"
                                      class="flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="quantity" value="1"
                                           min="1" max="{{ $product->stock }}"
                                           class="w-20 border-gray-300 rounded-md text-sm text-center">
                                    <button type="submit"
                                            class="flex-1 px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                        + Keranjang
                                    </button>
                                </form>
                            @else
                                <button disabled
                                        class="w-full px-5 py-2 bg-gray-200 text-gray-400 text-sm font-medium rounded-md cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @endif

                            <a href="{{ route('pelanggan.products.index') }}"
                               class="inline-block px-5 py-2 border border-gray-300 text-sm text-gray-700 rounded-md hover:bg-gray-50 transition">
                                &larr; Kembali ke Produk
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
