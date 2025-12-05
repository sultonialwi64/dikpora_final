<x-guest-layout>
    
<div class="text-center mt-8 mb-10">
    <h1 class="text-2xl font-extrabold tracking-wide" style="font-family: 'Poppins', sans-serif;">
        <!-- Warna Kontras dengan shadow -->
        <span class="text-blue-500 dark:text-yellow-500 text-shadow-lg">
            Dinas Pendidikan Pemuda dan Olahraga Kota Yogyakarta
        </span>
    </h1>
    <br>
</div>


    <!-- Tambahkan Logo -->
    <div class="flex justify-center mb-6">
        <img src="{{ asset('images/my-logo.webp') }}" alt="Logo" class="h-16 mx-auto">
    </div>

 
    <div class="text-center mt-8 mb-10">
        <h1 class="text-xl font-extrabold text-gray-700 dark:text-gray-100 tracking-wide">
           
            <span class="text-4xl">Sistem Peminjaman Ruang</span>
        </h1>
     
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Form Login -->
    <form method="POST" action="{{ route('login') }}" class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 max-w-md mx-auto">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
