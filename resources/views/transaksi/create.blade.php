<x-app-layout>
    <div class="p-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white">Catat Rekap Penjualan</h1>
            <p class="text-indigo-100">Input total pemasukan dari penjualan di sini.</p>
        </div>

        <!-- Validation Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <p class="font-bold">Terjadi Kesalahan:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Section -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            {{-- 1. Tambahkan enctype="multipart/form-data" di sini --}}
            <form action="{{ route('transaksi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_transaksi" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Transaksi *</label>
                        <input type="date" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label for="total_penjualan" class="block text-sm font-bold text-gray-700 mb-2">Total Pemasukan Penjualan (Rp) *</label>
                        <input type="number" name="total_penjualan" value="{{ old('total_penjualan') }}" min="0" placeholder="Contoh: 1500000" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                    </div>
                </div>
                <div>
                    <label for="keterangan" class="block text-sm font-bold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Contoh: Rekap penjualan shift pagi" class="w-full px-4 py-3 border border-gray-300 rounded-lg">{{ old('keterangan') }}</textarea>
                </div>
                
                {{-- 2. Tambahkan input untuk bukti di sini --}}
                <div>
                    <label for="bukti" class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti (Opsional)</label>
                    <input type="file" name="bukti" id="bukti" class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('bukti') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t">
                    <a href="{{ route('transaksi.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold">Simpan Pemasukan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
