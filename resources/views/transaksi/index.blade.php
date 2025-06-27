<x-app-layout>
    <div class="p-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Riwayat Transaksi Penjualan</h1>
                    <p class="text-indigo-100">Daftar semua rekap pemasukan dari penjualan.</p>
                </div>
                <a href="{{ route('transaksi.create') }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    <span>Catat Penjualan</span>
                </a>
            </div>
        </div>

        <!-- Session Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Filter Form -->
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('transaksi.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                        <select name="periode" onchange="togglePeriodeFilter(this.value)" class="w-full pl-4 pr-8 py-2 rounded border-gray-300">
                            <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="harian" {{ $periode == 'harian' ? 'selected' : '' }}>Harian</option>
                        </select>
                    </div>

                    <div id="filter-harian" class="{{ $periode == 'harian' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full px-4 py-2 rounded border-gray-300">
                    </div>

                    <div id="filter-bulanan" class="{{ $periode == 'bulanan' ? '' : 'hidden' }} grid grid-cols-2 gap-2 col-span-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="tahun" class="w-full pl-4 pr-8 py-2 rounded border-gray-300">
                                @forelse($daftarTahun as $thn)
                                    <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                                @empty
                                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                @endforelse
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                            <select name="bulan" class="w-full pl-4 pr-8 py-2 rounded border-gray-300">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">Tampilkan</button>
                        <a href="{{ route('transaksi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white p-2 rounded text-center transition">
                            <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-4">
                <p class="text-sm text-gray-600">Jumlah Rekap Penjualan</p>
                <p class="text-2xl font-bold text-blue-600">{{ $jumlahTransaksi }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <p class="text-sm text-gray-600">Total Pemasukan pada {{ $judulPeriode }}</p>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="py-3 px-4 text-left text-sm font-bold uppercase">Tanggal</th>
                            <th class="py-3 px-4 text-left text-sm font-bold uppercase">Keterangan</th>
                            <th class="py-3 px-4 text-right text-sm font-bold uppercase">Total Pemasukan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $transaction->keterangan ?: '-' }}</td>
                                <td class="py-3 px-4 text-right font-bold text-green-600">Rp {{ number_format($transaction->total_penjualan, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-12 text-gray-500">
                                    <p>Belum ada data transaksi penjualan pada periode ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="p-6 border-t">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function togglePeriodeFilter(value) {
            const filterHarian = document.getElementById('filter-harian');
            const filterBulanan = document.getElementById('filter-bulanan');
            if (value === 'harian') {
                filterHarian.classList.remove('hidden');
                filterBulanan.classList.add('hidden');
            } else {
                filterHarian.classList.add('hidden');
                filterBulanan.classList.remove('hidden');
            }
        }
        // Jalankan saat halaman load untuk memastikan filter yang benar ditampilkan
        document.addEventListener('DOMContentLoaded', function() {
            const periodeSelect = document.querySelector('select[name="periode"]');
            togglePeriodeFilter(periodeSelect.value);
        });
    </script>
    @endpush
</x-app-layout>
