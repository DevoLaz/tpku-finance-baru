<x-app-layout>
    <div class="p-8">
        
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <div class="flex">
                    <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                    <div>
                        <p class="font-bold">Sukses!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Riwayat Pengadaan</h1>
                    <p class="text-green-100">Histori semua pembelian dan pengadaan barang</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pengadaan.create') }}" 
                       class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/></svg>
                        <span>Tambah Pengadaan</span>
                    </a>
                </div>
            </div>
        </div>

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
                                {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('pengadaan.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

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
                    <tbody>
                        @forelse ($pengadaansByInvoice as $invoiceNumber => $items)
                            <tbody x-data="{ open: false }">
                                <tr class="border-b hover:bg-gray-50 cursor-pointer" @click="open = !open">
                                    <td class="py-4 px-4">{{ $items->first()->tanggal_pengadaan->format('d M Y') }}</td>
                                    <td class="py-4 px-4 font-mono">{{ $invoiceNumber }}</td>
                                    <td class="py-4 px-4">{{ $items->first()->supplier->nama_supplier ?? 'N/A' }}</td>
                                    <td class="py-4 px-4 text-right font-bold text-green-600">Rp {{ number_format($items->sum('total_harga'), 0, ',', '.') }}</td>
                                    <td class="py-4 px-4 text-center">
                                        <button class="text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr x-show="open" x-transition class="bg-gray-50">
                                    <td colspan="5" class="p-0">
                                        <div class="p-4">
                                            {{-- Tombol Aksi (Lihat Bukti & Hapus) --}}
                                            <div class="flex justify-end items-center gap-3 mb-4">
                                                @if($items->first()->bukti)
                                                <button 
                                                    type="button"
                                                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg flex items-center gap-2 transition"
                                                    data-img-url="{{ Storage::url($items->first()->bukti) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    <span>Lihat Bukti</span>
                                                </button>
                                                @endif
                                                <form action="{{ route('pengadaan.destroy', $invoiceNumber) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh data untuk invoice #{{ $invoiceNumber }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg flex items-center gap-2 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                        <span>Hapus Invoice</span>
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
                                                            <td class="py-3 px-3">{{ $item->barang->nama_barang ?? 'N/A' }}</td>
                                                            <td class="py-3 px-3 text-center">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                                            <td class="py-3 px-3 text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
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
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <p class="text-gray-500">Belum ada riwayat pengadaan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
