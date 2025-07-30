<x-app-layout>
    <div class="p-8">
        
        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Sukses!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Riwayat Pengadaan</h1>
                    <p class="text-green-100">Histori semua pembelian dan pengadaan barang</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pengadaan.create') }}" 
                        class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Tambah Pengadaan</span>
                    </a>
                    <a href="{{ route('pengadaan.exportPdf', request()->query()) }}" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold flex items-center gap-2 transition-all transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        <span>Ekspor PDF</span>
                    </a>
                    <a href="{{ route('pengadaan.exportExcel', request()->query()) }}" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold flex items-center gap-2 transition-all transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        <span>Ekspor Excel</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('pengadaan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                    <input type="date" name="dari" value="{{ request('dari') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barang</label>
                    <select name="barang_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Barang</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('pengadaan.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Statistik --}}
        @if($totalTransaksi > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                <p class="text-blue-100 text-sm">Total Transaksi</p>
                <p class="text-3xl font-bold">{{ $totalTransaksi }}</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <p class="text-green-100 text-sm">Total Pengeluaran</p>
                <p class="text-2xl font-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                <p class="text-purple-100 text-sm">Total Item Masuk</p>
                <p class="text-3xl font-bold">{{ number_format($totalItemMasuk, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                <p class="text-orange-100 text-sm">Rata-rata/Transaksi</p>
                <p class="text-xl font-bold">Rp {{ number_format($rataRataPerTransaksi, 0, ',', '.') }}</p>
            </div>
        </div>
        @endif

        {{-- Tabel Pengadaan --}}
        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#173720] text-white">
                        <tr>
                            <th class="py-4 px-4 text-left text-sm font-bold uppercase w-1/6">Tanggal</th>
                            <th class="py-4 px-4 text-left text-sm font-bold uppercase w-1/6">No Invoice</th>
                            <th class="py-4 px-4 text-left text-sm font-bold uppercase">Supplier</th>
                            <th class="py-4 px-4 text-right text-sm font-bold uppercase w-1/6">Total Pembelian</th>
                            <th class="py-4 px-4 text-center text-sm font-bold uppercase w-16">Detail</th>
                        </tr>
                    </thead>
                    
                    @forelse ($pengadaansByInvoice as $invoiceNumber => $items)
                        
                        <tbody x-data="{ open: false }">
                            
                            <tr @click="open = !open" class="border-b hover:bg-gray-50 cursor-pointer">
                                <td class="py-4 px-4">{{ \Carbon\Carbon::parse($items->first()->tanggal_pembelian)->format('d M Y') }}</td>
                                <td class="py-4 px-4 font-mono">{{ $invoiceNumber }}</td>
                                <td class="py-4 px-4">{{ $items->first()->supplier->nama ?? 'N/A' }}</td>
                                <td class="py-4 px-4 text-right font-bold text-green-600">Rp {{ number_format($items->sum('total_harga'), 0, ',', '.') }}</td>
                                <td class="py-4 px-4 text-center">
                                    <button class="text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                            </tr>
                            
                            <tr x-show="open" x-transition class="bg-gray-50">
                                <td colspan="5" class="p-0">
                                    <div class="p-4">
                                        <div class="flex justify-end items-center gap-3 mb-4">
                                            @if($items->first()->bukti)
                                                <a href="{{ asset('storage/' . $items->first()->bukti) }}" target="_blank"
                                                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg flex items-center gap-2 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                    Lihat Bukti
                                                </a>
                                            @endif
                                            <form action="{{ route('pengadaan.destroy', $invoiceNumber) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh data untuk invoice #{{ $invoiceNumber }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg flex items-center gap-2 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    Hapus Invoice
                                                </button>
                                            </form>
                                        </div>

                                        <table class="w-full text-sm">
                                            <thead class="bg-gray-200">
                                                <tr>
                                                    <th class="py-2 px-3 text-left font-semibold">Barang</th>
                                                    <th class="py-2 px-3 text-center font-semibold">Jumlah</th>
                                                    <th class="py-2 px-3 text-right font-semibold">Harga Beli</th>
                                                    <th class="py-2 px-3 text-right font-semibold">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $item)
                                                    <tr class="border-b border-gray-200 last:border-b-0">
                                                        <td class="py-3 px-3">{{ $item->barang->nama ?? 'N/A' }}</td>
                                                        <td class="py-3 px-3 text-center">{{ number_format($item->jumlah_masuk, 0, ',', '.') }}</td>
                                                        <td class="py-3 px-3 text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                                        <td class="py-3 px-3 text-right font-medium">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @empty
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <p class="text-gray-500">Belum ada riwayat pengadaan</p>
                                </td>
                            </tr>
                        </tbody>
                    @endforelse

                </table>
            </div>
        </div>

        {{-- Panel Daftar Request dari API --}}
        @if ($requestItems->count())
        <div class="bg-yellow-100 rounded-lg p-6 mt-10 shadow-inner">
            <h2 class="text-xl font-bold text-yellow-800 mb-4">Request Pengadaan dari Management Stok</h2>
            <div class="grid gap-4">
                @foreach ($requestItems as $item)
                    <div class="bg-white p-4 rounded-lg border shadow-sm flex justify-between items-center">
                        <div>
                            <p class="text-gray-800 font-semibold">{{ $item['barang']['nama_barang'] }} ({{ $item['jumlah'] }} {{ $item['barang']['unit_barang'] }})</p>
                            <p class="text-sm text-gray-500">Request oleh user #{{ $item['user_id'] }} pada {{ \Carbon\Carbon::parse($item['tanggal_pengadaan'])->format('d M Y') }}</p>
                        </div>
                        <form action="{{ route('pengadaan.create') }}" method="GET">
                            <input type="hidden" name="req_id" value="{{ $item['id'] }}">
                            <!-- <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">Gunakan</button> -->
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-app-layout>