<x-app-layout>
    <div class="p-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white mb-2">Laporan Laba Rugi</h1>
            <p class="text-green-100">Analisis pendapatan dan pengeluaran perusahaan</p>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('laporan.laba_rugi') }}" class="flex flex-wrap gap-4 items-end">
                @csrf
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select name="tahun" class="w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-300">
                        @forelse($daftarTahun as $thn)
                            <option value="{{ $thn }}" {{ request('tahun', $tahun) == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                        @empty
                            <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                        @endforelse
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="bulan" class="w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-300">
                        @php
                            $namaBulan = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                        @endphp
                        @foreach($namaBulan as $num => $nama)
                            <option value="{{ $num }}" {{ request('bulan', $bulan) == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 items-end">
                    <button type="submit" class="px-6 py-2.5 bg-[#173720] text-white rounded-lg">Tampilkan</button>
                    <a href="{{ route('laporan.laba_rugi') }}" class="px-6 py-2.5 bg-gray-500 text-white rounded-lg">Reset</a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-500">
                <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
            </div>
            @php $isProfit = $labaBersih >= 0; @endphp
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 {{ $isProfit ? 'border-blue-500' : 'border-orange-500' }}">
                <p class="text-sm font-medium text-gray-600">{{ $isProfit ? 'Laba Bersih' : 'Rugi Bersih' }}</p>
                <p class="text-2xl font-bold {{ $isProfit ? 'text-blue-600' : 'text-orange-600' }}">Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Detail Laporan -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <table class="w-full">
                {{-- Pendapatan --}}
                <tr class="font-bold text-lg"><td colspan="2" class="py-2">Pendapatan</td></tr>
                @forelse ($pendapatanItems as $item)
                <tr class="border-b"><td class="py-2 pl-4">Penjualan</td><td class="py-2 text-right">Rp {{ number_format($item->total_penjualan, 0, ',', '.') }}</td></tr>
                @empty
                <tr class="border-b"><td class="py-2 pl-4">Penjualan</td><td class="py-2 text-right">Rp 0</td></tr>
                @endforelse
                <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Pendapatan</td><td class="py-2 text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td></tr>

                {{-- Spacer --}}
                <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                {{-- Pengeluaran --}}
                <tr class="font-bold text-lg"><td colspan="2" class="py-2">Beban-Beban</td></tr>
                @forelse ($pengeluaran as $item)
                <tr class="border-b"><td class="py-2 pl-4">{{ $item['keterangan'] }}</td><td class="py-2 text-right text-red-600">(Rp {{ number_format($item['jumlah'], 0, ',', '.') }})</td></tr>
                @empty
                <tr class="border-b"><td class="py-2 pl-4">Tidak ada beban</td><td class="py-2 text-right text-red-600">(Rp 0)</td></tr>
                @endforelse
                <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Pengeluaran</td><td class="py-2 text-right text-red-600">(Rp {{ number_format($totalPengeluaran, 0, ',', '.') }})</td></tr>

                {{-- Spacer --}}
                <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                {{-- Laba Bersih --}}
                <tr class="font-bold text-xl bg-gray-100 border-t-2 border-gray-300">
                    <td class="py-4">{{ $isProfit ? 'LABA BERSIH' : 'RUGI BERSIH' }}</td>
                    <td class="py-4 text-right {{ $isProfit ? 'text-blue-600' : 'text-orange-600' }}">Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>
</x-app-layout>
