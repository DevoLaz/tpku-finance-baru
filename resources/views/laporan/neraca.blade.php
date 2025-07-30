<x-app-layout>
    <div class="p-8">
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Laporan Neraca</h1>
                    {{-- DIUBAH: Header disesuaikan dengan periode --}}
                    <p class="text-indigo-100">Laporan untuk periode yang berakhir pada {{ \Carbon\Carbon::parse($tanggalLaporan)->format('d F Y') }}</p>
                </div>
                
                <div class="relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold text-sm rounded-lg transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                <span>Ekspor Laporan</span>
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('laporan.neraca.exportPdf', request()->query())">
                                <span>Ekspor PDF</span>
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('laporan.neraca.exportExcel', request()->query())">
                                <span>Ekspor Excel</span>
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>

        {{-- DIUBAH: Filter diubah menjadi Bulan dan Tahun --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('laporan.neraca') }}" class="flex items-end gap-4">
                <div>
                    <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">Pilih Periode Bulan</label>
                    <select name="bulan" id="bulan" class="w-full px-4 py-2 rounded-lg border-gray-300">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Pilih Periode Tahun</label>
                    <select name="tahun" id="tahun" class="w-full px-4 py-2 rounded-lg border-gray-300">
                        @for ($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">Tampilkan</button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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
                    <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Aset Tetap (Nilai Buku)</td><td class="py-2 text-right">Rp {{ number_format($asetFisikItems->sum('harga_perolehan') - $totalAkumulasiPenyusutan, 0, ',', '.') }}</td></tr>

                    <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                    <tr class="font-bold text-lg bg-indigo-100 border-t-2 border-indigo-300">
                        <td class="py-4">TOTAL ASET</td>
                        <td class="py-4 text-right">Rp {{ number_format($totalAset, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

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

        <div class="mt-8 text-center">
            @if($totalAset > 0 || $totalLiabilitasEkuitas > 0)
                @if(round($totalAset) == round($totalLiabilitasEkuitas))
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 font-semibold rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Neraca Seimbang (Balanced)
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-800 font-semibold rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="m21.73 18-8-14a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                        Neraca Tidak Seimbang! Selisih: Rp {{ number_format(abs($totalAset - $totalLiabilitasEkuitas), 0, ',', '.') }}
                    </span>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>