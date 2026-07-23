<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Produk</h2>
            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                + Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider w-8">#</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50 {{ $product->is_active ? '' : 'opacity-60' }}">
                                <td class="px-4 py-3 text-gray-400">
                                    {{ $loop->iteration + ($products->firstItem() - 1) }}
                                </td>
                                <td class="px-4 py-3">
                                    <img src="{{ $product->imageUrl() }}"
                                         alt="{{ $product->name }}"
                                         class="w-12 h-12 object-cover rounded-md border border-gray-200">
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-400 font-mono">{{ $product->slug }}</div>
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    {{ $product->category?->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-800 font-medium">
                                    {{ $product->formattedPrice() }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($product->is_active)
                                        <span class="{{ $product->stock > 0 ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' }} px-2 py-0.5 rounded-full text-xs font-medium">
                                            {{ $product->stock }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Switch ON/OFF --}}
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('admin.products.toggle', $product) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                title="{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none
                                                       {{ $product->is_active ? 'bg-indigo-600' : 'bg-gray-300' }}">
                                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                                         {{ $product->is_active ? 'translate-x-6' : 'translate-x-1' }}">
                                            </span>
                                        </button>
                                    </form>
                                </td>

                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>

                                    {{-- tombol hapus --}}
                                    <!-- <form action="{{ route('admin.products.destroy', $product) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Hapus produk \'{{ addslashes($product->name) }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                            Hapus
                                        </button>
                                    </form> -->
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                                    Belum ada produk.
                                    <a href="{{ route('admin.products.create') }}" class="text-indigo-600 hover:underline">Tambah sekarang</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($products->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
