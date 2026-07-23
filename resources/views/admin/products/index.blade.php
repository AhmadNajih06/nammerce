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

            {{-- Tab: Aktif / Terhapus --}}
            <div class="flex gap-2">
                <a href="{{ route('admin.products.index') }}"
                   class="px-4 py-1.5 text-sm rounded-md border transition
                          {{ ! $showTrashed ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-300 hover:border-indigo-400' }}">
                    Aktif
                </a>
                <a href="{{ route('admin.products.index', ['trashed' => 1]) }}"
                   class="px-4 py-1.5 text-sm rounded-md border transition flex items-center gap-1.5
                          {{ $showTrashed ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-gray-300 hover:border-red-400' }}">
                    Dihapus
                    @if ($trashedCount > 0)
                        <span class="inline-flex items-center justify-center w-4 h-4 text-xs font-bold rounded-full
                                     {{ $showTrashed ? 'bg-white text-red-600' : 'bg-red-500 text-white' }}">
                            {{ $trashedCount }}
                        </span>
                    @endif
                </a>
            </div>

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
                            <tr class="hover:bg-gray-50 {{ ! $product->is_active || $showTrashed ? 'opacity-60' : '' }}">
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
                                    @if ($showTrashed)
                                        <div class="text-xs text-red-500 mt-0.5">
                                            Dihapus {{ $product->deleted_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    {{ $product->category?->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-800 font-medium">
                                    {{ $product->formattedPrice() }}
                                </td>
                                <td class="px-4 py-3">
                                    @if (! $showTrashed && $product->is_active)
                                        <span class="{{ $product->stock > 0 ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' }} px-2 py-0.5 rounded-full text-xs font-medium">
                                            {{ $product->stock }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Switch ON/OFF (hanya untuk produk aktif, bukan yang dihapus) --}}
                                <td class="px-4 py-3 text-center">
                                    @if (! $showTrashed)
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
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-3 text-right space-x-2">
                                    @if ($showTrashed)
                                        {{-- Pulihkan --}}
                                        <form action="{{ route('admin.products.restore', $product->id) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-green-600 hover:text-green-800 font-medium">
                                                Pulihkan
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>

                                        <form action="{{ route('admin.products.destroy', $product) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Hapus produk \'{{ addslashes($product->name) }}\'?\n\nProduk tidak akan benar-benar dihapus dari database dan bisa dipulihkan kembali.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                                    @if ($showTrashed)
                                        Tidak ada produk yang dihapus.
                                    @else
                                        Belum ada produk.
                                        <a href="{{ route('admin.products.create') }}" class="text-indigo-600 hover:underline">Tambah sekarang</a>.
                                    @endif
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
