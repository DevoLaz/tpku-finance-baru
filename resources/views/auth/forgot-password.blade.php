<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#F9FAF9] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-[#173720]">Reset Password Anda</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Lupa password Anda? Tidak masalah. Beri tahu kami alamat email Anda dan kami akan mengirimkan link untuk mengatur ulang password Anda.
                </p>
            </div>

            <!-- Session Status -->
            <div class="!mt-4">
                <x-auth-session-status class="mb-4" :status="session('status')" />
            </div>


            <form class="!mt-6 space-y-6" method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    {{-- Menggunakan <label> biasa --}}
                    <label for="email" class="block text-sm font-medium text-[#173720]">Email</label>
                    
                    {{-- Menggunakan <input> biasa untuk kontrol penuh atas style --}}
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:z-10 sm:text-sm bg-white"
                           placeholder="you@example.com">
                           
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    {{-- Menggunakan <button> biasa untuk kontrol penuh atas style --}}
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#173720] hover:bg-[#155c30] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                        Kirim Link Reset
                    </button>
                </div>
            </form>

            <p class="!mt-4 text-center text-sm text-gray-600">
                <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-500">
                    Kembali ke Login
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
