<x-app-layout>
    <div class="p-8">
        <div class="bg-gradient-to-r from-red-500 to-orange-500 rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white">Catat Beban Operasional</h1>
            <p class="text-orange-100">Input pengeluaran lain-lain seperti listrik, internet, sewa, dll.</p>
        </div>

        {{-- Blok untuk menampilkan semua jenis error --}}
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
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Terjadi Kesalahan Sistem:</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('beban.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label for="tanggal" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Beban *</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                </div>
                <div>
                    {{-- Nama input ini 'nama_beban' agar cocok dengan validasi di controller --}}
                    <label for="nama_beban" class="block text-sm font-bold text-gray-700 mb-2">Nama Beban *</label>
                    <input type="text" name="nama_beban" value="{{ old('nama_beban') }}" placeholder="Contoh: Biaya Listrik & Air, Sewa Kantor, Biaya Promosi" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label for="jumlah" class="block text-sm font-bold text-gray-700 mb-2">Jumlah Pengeluaran (Rp) *</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label for="keterangan" class="block text-sm font-bold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg">{{ old('keterangan') }}</textarea>
                </div>
                <div>
                    <label for="bukti" class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti (Struk/Nota)</label>
                    <input type="file" name="bukti" id="bukti" class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                </div>
                <div class="flex justify-end gap-4 pt-4 border-t">
                    <a href="{{ route('beban.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md font-semibold">Simpan Beban</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
