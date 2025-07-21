<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#F9FAF9] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-[#173720]">Buat Password Baru</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Silakan masukkan password baru Anda di bawah ini.
                </p>
            </div>

            <form class="!mt-8 space-y-6" method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-[#173720]">Email</label>
                    {{-- Menambahkan atribut readonly dan style untuk menonaktifkan input --}}
                    <input id="email" name="email" type="email" required value="{{ old('email', $request->email) }}"
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:z-10 sm:text-sm bg-gray-100 cursor-not-allowed"
                           placeholder="you@example.com" autocomplete="username" readonly>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- New Password -->
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-[#173720]">Password Baru</label>
                    <input id="password" name="password" type="password" required
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:z-10 sm:text-sm bg-white"
                           placeholder="Password Baru" autocomplete="new-password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm New Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-[#173720]">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:z-10 sm:text-sm bg-white"
                           placeholder="Konfirmasi Password" autocomplete="new-password">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#173720] hover:bg-[#155c30] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
