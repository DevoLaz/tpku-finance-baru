<x-app-layout>
    <div class="p-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white">Tambah Aset Tetap Baru</h1>
            <p class="text-green-100">Isi detail aset di bawah ini. Untuk Modal, isi Masa Manfaat dengan 0.</p>
        </div>

        <!-- Validation & Error Messages -->
        {{-- Blok untuk menampilkan error validasi (jika ada) --}}
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Terjadi Kesalahan Validasi:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- PERBAIKAN: Blok untuk menampilkan error dari session (error tersembunyi) --}}
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Terjadi Kesalahan Sistem:</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif


        <!-- Form Section -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('aset-tetap.store') }}" method="POST" enctype="multipart/form-data">
                {{-- Form di-include dari partial --}}
                @include('aset-tetap._form')
            </form>
        </div>
    </div>
</x-app-layout>
