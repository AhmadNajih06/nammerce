<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-gray-600">&larr;</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Produk</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('admin.products.store') }}" method="POST"
                      enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-400 @enderror"
                               placeholder="Contoh: Laptop ASUS VivoBook" autofocus>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="category_id" name="category_id"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('category_id') border-red-400 @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga & Stok --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                Harga (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="price" name="price" value="{{ old('price', 0) }}"
                                   min="0" step="100"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('price') border-red-400 @enderror">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">
                                Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}"
                                   min="0"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('stock') border-red-400 @enderror">
                            @error('stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-400 @enderror"
                                  placeholder="Deskripsi produk...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Foto --}}
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                            Foto Produk <span class="text-gray-400 font-normal">(jpg/png/jpeg)</span>
                        </label>
                        <input type="file" id="image" name="image" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('image') border border-red-400 rounded @enderror">
                        {{-- Preview --}}
                        <div id="imagePreview" class="mt-2 hidden">
                            <img id="previewImg" src="" alt="Preview"
                                 class="w-32 h-32 object-cover rounded-md border border-gray-200">
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Produk Unggulan --}}
                    <div class="flex items-center gap-3 pt-1">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1"
                               {{ old('is_featured') ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_featured" class="text-sm font-medium text-gray-700">
                            Tandai sebagai <span class="text-yellow-500 font-semibold">⭐ Produk Unggulan</span>
                            <span class="text-gray-400 font-normal text-xs">(tampil di halaman utama)</span>
                        </label>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <a href="{{ route('admin.products.index') }}"
                           class="px-5 py-2 text-gray-600 hover:text-gray-800">
                            Batal
                        </a>
                         <button type="submit"
                                class="px-5 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition">
                            Simpan Produk
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (ev) {
                document.getElementById('previewImg').src = ev.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });
    </script>
</x-app-layout>
