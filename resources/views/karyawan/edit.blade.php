<x-app-layout>
    <div class="p-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white mb-2">Edit Data Karyawan</h1>
            <p class="text-green-100">Perbarui data untuk karyawan: <strong>{{ $karyawan->nama }}</strong></p>
        </div>

        {{-- Menampilkan Error Validasi --}}
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md">
                <p class="font-bold">Oops! Ada yang salah:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Section -->
        <div class="bg-white rounded-2xl shadow-md p-8">
            <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST">
                @method('PUT')
                @include('karyawan._form')
            </form>
        </div>
    </div>
    
    @push('scripts')
        <script>
            lucide.createIcons();
        </script>
    @endpush
</x-app-layout>