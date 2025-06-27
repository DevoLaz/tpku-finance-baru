<x-app-layout>
    <div class="p-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white">Laporan Neraca</h1>
            <p class="text-indigo-100">Potret posisi keuangan perusahaan per tanggal {{ \Carbon\Carbon::parse($tanggalLaporan)->format('d F Y') }}</p>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('laporan.neraca') }}" class="flex items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal Laporan</label>
                    <input type="date" name="tanggal" value="{{ $tanggalLaporan }}" class="w-full px-4 py-2 rounded-lg border-gray-300">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg">Tampilkan</button>
            </form>
        </div>

        <!-- Konten Neraca -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- SISI ASET -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 border-b-2 pb-2 mb-4">ASET</h2>
                <table class="w-full text-sm">
                    <tr class="font-semibold"><td class="py-2" colspan="2">Aset Lancar</td></tr>
                    <tr class="border-b"><td class="py-2 pl-4">Kas & Setara Kas</td><td class="py-2 text-right">Rp {{ number_format($kas, 0, ',', '.') }}</td></tr>
                    <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Aset Lancar</td><td class="py-2 text-right">Rp {{ number_format($kas, 0, ',', '.') }}</td></tr>

                    <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                    <tr class="font-semibold"><td class="py-2" colspan="2">Aset Tetap</td></tr>
                    @foreach($asetFisikItems as $item)
                    <tr class="border-b"><td class="py-2 pl-4">{{ $item->nama_aset }}</td><td class="py-2 text-right">Rp {{ number_format($item->harga_perolehan, 0, ',', '.') }}</td></tr>
                    @endforeach
                    <tr class="border-b"><td class="py-2 pl-4 text-red-600">Akumulasi Penyusutan</td><td class="py-2 text-right text-red-600">(Rp {{ number_format($totalAkumulasiPenyusutan, 0, ',', '.') }})</td></tr>
                    <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Aset Tetap (Nilai Buku)</td><td class="py-2 text-right">Rp {{ number_format($kas + $totalAset - $totalAkumulasiPenyusutan - $kas, 0, ',', '.') }}</td></tr>

                    <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                    <tr class="font-bold text-lg bg-indigo-100 border-t-2 border-indigo-300">
                        <td class="py-4">TOTAL ASET</td>
                        <td class="py-4 text-right">Rp {{ number_format($totalAset, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <!-- SISI LIABILITAS & EKUITAS -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 border-b-2 pb-2 mb-4">LIABILITAS & EKUITAS</h2>
                <table class="w-full text-sm">
                    <tr class="font-semibold"><td class="py-2" colspan="2">Liabilitas</td></tr>
                    <tr class="border-b"><td class="py-2 pl-4">Utang Usaha</td><td class="py-2 text-right">Rp 0</td></tr>
                    <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Liabilitas</td><td class="py-2 text-right">Rp {{ number_format($totalLiabilitas, 0, ',', '.') }}</td></tr>

                    <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                    <tr class="font-semibold"><td class="py-2" colspan="2">Ekuitas</td></tr>
                    <tr class="border-b"><td class="py-2 pl-4">Modal Disetor</td><td class="py-2 text-right">Rp {{ number_format($modalDisetor, 0, ',', '.') }}</td></tr>
                    <tr class="border-b"><td class="py-2 pl-4">Laba Ditahan</td><td class="py-2 text-right">Rp {{ number_format($labaDitahan, 0, ',', '.') }}</td></tr>
                    <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Ekuitas</td><td class="py-2 text-right">Rp {{ number_format($totalEkuitas, 0, ',', '.') }}</td></tr>

                    <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                    <tr class="font-bold text-lg bg-indigo-100 border-t-2 border-indigo-300">
                        <td class="py-4">TOTAL LIABILITAS & EKUITAS</td>
                        <td class="py-4 text-right">Rp {{ number_format($totalLiabilitasEkuitas, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

         {{-- INI PERBAIKANNYA --}}
         <!-- Check Balance -->
        <div class="mt-8 text-center">
            {{-- Hanya tampilkan jika ada nilai (bukan 0 vs 0) --}}
            @if($totalAset > 0 || $totalLiabilitasEkuitas > 0)
                @if(round($totalAset) == round($totalLiabilitasEkuitas))
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 font-semibold rounded-full">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        Neraca Seimbang (Balanced)
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-800 font-semibold rounded-full">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                        Neraca Tidak Seimbang! Selisih: Rp {{ number_format(abs($totalAset - $totalLiabilitasEkuitas)) }}
                    </span>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
