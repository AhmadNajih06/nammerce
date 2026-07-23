<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Foto Profil --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header class="mb-4">
                        <h2 class="text-lg font-medium text-gray-900">Foto Profil</h2>
                        <p class="mt-1 text-sm text-gray-600">Upload atau hapus foto profil Anda.</p>
                    </header>

                    <div class="flex items-start gap-6">
                        <img src="{{ auth()->user()->avatarUrl() }}"
                             alt="{{ auth()->user()->name }}"
                             class="w-24 h-24 object-cover rounded-full border-2 border-gray-200">
                        <div class="flex-1 space-y-3">
                            @if (session('status') === 'avatar-updated')
                                <p class="text-sm text-green-600">Foto profil berhasil diperbarui.</p>
                            @endif
                            @if (session('status') === 'avatar-removed')
                                <p class="text-sm text-green-600">Foto profil berhasil dihapus.</p>
                            @endif

                            <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                                @csrf
                                <input type="file" name="avatar" accept="image/*"
                                       class="text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                    Upload
                                </button>
                            </form>
                            @error('avatar')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @if (auth()->user()->avatar)
                                <form action="{{ route('profile.avatar.destroy') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-sm text-red-600 hover:text-red-800">
                                        Hapus Foto
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
