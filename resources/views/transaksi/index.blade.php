<x-app-layout>
    <div class="p-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Riwayat Transaksi Penjualan</h1>
                    <p class="text-green-100">Daftar semua rekap pemasukan dari penjualan.</p>
                </div>
                <a href="{{ route('transaksi.create') }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
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

                    <div id="filter-bulanan" class="{{ $periode == 'bulanan' ? '' : 'hidden' }} grid grid-cols-2 gap-2 md:col-span-2">
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
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
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase">Bukti</th>
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi ?? $transaction->created_at)->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $transaction->keterangan ?: '-' }}</td>
                                <td class="py-3 px-4 text-right font-bold text-green-600">Rp {{ number_format($transaction->total_penjualan ?? $transaction->total, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-center">
                                    {{-- Tombol Lihat Bukti --}}
                                    @if ($transaction->bukti)
                                        <button 
                                            type="button"
                                            class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-md"
                                            data-img-url="{{ Storage::url($transaction->bukti) }}">
                                            Lihat
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    {{-- PERBAIKAN FINAL: Menggunakan $transaction->id secara eksplisit --}}
                                    <form action="{{ route('transaksi.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rekap ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-gray-500">
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
            if (periodeSelect) {
                togglePeriodeFilter(periodeSelect.value);
            }
        });
    </script>
    @endpush
</x-app-layout>
