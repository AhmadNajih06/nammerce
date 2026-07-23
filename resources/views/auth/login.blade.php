<x-guest-layout>

    {{-- Judul --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Selamat datang kembali</h1>
        <p class="text-sm text-gray-500 mt-1">Masuk ke akun Anda untuk mulai belanja</p>
    </div>

    {{-- Session status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </span>
                <x-text-input id="email" type="email" name="email"
                              :value="old('email')"
                              class="block w-full pl-10"
                              required autofocus autocomplete="username"
                              placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                        Lupa password?
                    </a>
                @endif
            </div>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <x-text-input id="password" type="password" name="password"
                              class="block w-full pl-10"
                              required autocomplete="current-password"
                              placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        {{-- Remember me --}}
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                   class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <label for="remember_me" class="ml-2 text-sm text-gray-600">Ingat saya</label>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full flex justify-center py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Masuk
        </button>

        {{-- Divider --}}
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="px-3 bg-white text-gray-400">Belum punya akun?</span>
            </div>
        </div>

        {{-- Link register --}}
        <a href="{{ route('register') }}"
           class="w-full flex justify-center py-2.5 px-4 border border-indigo-600 text-indigo-600 text-sm font-semibold rounded-lg hover:bg-indigo-50 transition">
            Buat Akun Baru
        </a>

    </form>

    {{-- Back to home --}}
    <p class="mt-6 text-center text-xs text-gray-400">
        <a href="{{ url('/') }}" class="hover:text-indigo-600 transition">&larr; Kembali ke Beranda</a>
    </p>

</x-guest-layout>
