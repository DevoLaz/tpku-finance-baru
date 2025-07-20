<x-app-layout>
    <div class="p-8">
        {{-- Bagian Header --}}
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Riwayat Transaksi Penjualan</h1>
                    <p class="text-green-100">Daftar semua rekap pemasukan dari penjualan.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('transaksi.fetchApi') }}" class="px-6 py-3 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/><path d="M16 12h5"/><path d="M12 7v5"/></svg>
                        <span>Sinkronkan API</span>
                    </a>
                    <a href="{{ route('transaksi.create') }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        <span>Catat Penjualan</span>
                    </a>
                    <a href="{{ route('transaksi.exportPdf', request()->query()) }}" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        <span>Ekspor PDF</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Filter --}}
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
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Ringkasan Total --}}
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

        {{-- Tabel Utama --}}
        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-bold uppercase">Tanggal</th>
                            <th class="py-3 px-4 text-left text-sm font-bold uppercase">Keterangan</th>
                            <th class="py-3 px-4 text-right text-sm font-bold uppercase">Total Pemasukan</th>
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase">Bukti</th>
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase">Aksi</th>
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase w-24">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tbody x-data="{ open: false }">
                                {{-- Baris Utama --}}
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y') }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $transaction->keterangan ?: '-' }}</td>
                                    <td class="py-3 px-4 text-right font-bold text-green-600">Rp {{ number_format($transaction->total_penjualan, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @if ($transaction->bukti)
                                            <button type="button" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-md" data-img-url="{{ asset($transaction->bukti) }}">Lihat</button>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <form action="{{ route('transaksi.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rekap ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-3 px-4 text-center cursor-pointer" @click="open = !open">
                                        @if(!empty($transaction->items_detail))
                                            <button class="text-blue-500 hover:text-blue-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Baris Detail Dropdown --}}
                                <tr x-show="open" x-transition class="bg-gray-50" style="display: none;">
                                    <td colspan="6" class="p-0">
                                        <div class="p-4">
                                            @if(!empty($transaction->items_detail) && is_array($transaction->items_detail))
                                                <h4 class="font-bold text-lg mb-2 text-gray-700">Detail Barang Terjual:</h4>
                                                
                                                <table class="w-full text-sm mt-2">
                                                    <thead class="bg-gray-200">
                                                        <tr>
                                                            <th class="py-2 px-3 text-left font-semibold text-gray-600">Nama Barang</th>
                                                            <th class="py-2 px-3 text-center font-semibold text-gray-600">Qty</th>
                                                            <th class="py-2 px-3 text-right font-semibold text-gray-600">Harga Satuan</th>
                                                            <th class="py-2 px-3 text-right font-semibold text-gray-600">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($transaction->items_detail as $item)
                                                            <tr class="border-b border-gray-200 last:border-b-0">
                                                                <td class="py-3 px-3">{{ $item['name'] }}</td>
                                                                <td class="py-3 px-3 text-center">{{ $item['qty'] }}</td>
                                                                <td class="py-3 px-3 text-right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                                                <td class="py-3 px-3 text-right font-medium">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            @else
                                                <p class="text-gray-500 italic p-4">Tidak ada detail barang untuk transaksi ini.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-500">
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
</x-app-layout>