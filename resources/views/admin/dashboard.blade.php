<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                        Selamat datang, {{ auth()->user()->name }}!
                    </h3>
                    <p class="text-sm text-gray-500">Anda login sebagai <span class="font-medium text-indigo-600">Admin</span>.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold text-gray-800 mb-2">Kelola Produk</h4>
                    <p class="text-sm text-gray-500">Tambah, ubah, dan hapus produk toko.</p>
                    {{-- Route::get('/admin/products', ...) akan ditambahkan nanti --}}
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold text-gray-800 mb-2">Kelola Order</h4>
                    <p class="text-sm text-gray-500">Lihat dan proses pesanan pelanggan.</p>
                    {{-- Route::get('/admin/orders', ...) akan ditambahkan nanti --}}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
