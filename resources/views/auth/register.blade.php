<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#F9FAF9] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-[#173720]">Buat Akun Baru</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Isi data di bawah ini untuk mendaftar.
                </p>
            </div>

            <form class="!mt-8 space-y-6" method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-[#173720]">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:z-10 sm:text-sm bg-white"
                           placeholder="Nama Anda">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-[#173720]">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:z-10 sm:text-sm bg-white"
                           placeholder="you@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-[#173720]">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:z-10 sm:text-sm bg-white"
                           placeholder="Password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-[#173720]">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:z-10 sm:text-sm bg-white"
                           placeholder="Konfirmasi Password">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>
                </div>

                 <div class="!mt-6">
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#173720] hover:bg-[#155c30] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                        Daftar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
