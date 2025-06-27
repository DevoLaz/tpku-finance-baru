<x-app-layout>
    {{-- Kita tidak butuh @extends atau @section('content') lagi --}}
    {{-- Semua konten halaman langsung ditaruh di sini --}}

    <div class="p-8"> {{-- Kita tambahkan padding di sini yang tadinya ada di <main> --}}
        
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <div class="flex">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2 mt-0.5"></i>
                    <div>
                        <p class="font-bold">Sukses!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Riwayat Pengadaan</h1>
                    <p class="text-green-100">Histori semua pembelian dan pengadaan barang</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pengadaan.create') }}" 
                       class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        <span>Tambah Pengadaan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
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
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('pengadaan.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
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

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#173720] text-white">
                        <tr>
                            <th class="py-4 px-4 text-left text-sm font-bold uppercase">Tanggal</th>
                            <th class="py-4 px-4 text-left text-sm font-bold uppercase">No Invoice</th>
                            <th class="py-4 px-4 text-left text-sm font-bold uppercase">Barang</th>
                            <th class="py-4 px-4 text-left text-sm font-bold uppercase">Supplier</th>
                            <th class="py-4 px-4 text-center text-sm font-bold uppercase">Jumlah</th>
                            <th class="py-4 px-4 text-right text-sm font-bold uppercase">Harga Beli</th>
                            <th class="py-4 px-4 text-right text-sm font-bold uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengadaans as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-4 px-4">{{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d M Y') }}</td>
                                <td class="py-4 px-4">{{ $item->no_invoice }}</td>
                                <td class="py-4 px-4">{{ $item->barang->nama ?? 'N/A' }}</td>
                                <td class="py-4 px-4">{{ $item->supplier->nama_supplier ?? 'N/A' }}</td>
                                <td class="py-4 px-4 text-center">{{ number_format($item->jumlah_masuk, 0, ',', '.') }}</td>
                                <td class="py-4 px-4 text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td class="py-4 px-4 text-right font-bold text-green-600">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <p class="text-gray-500">Belum ada riwayat pengadaan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pengadaans->hasPages())
                <div class="p-6 border-t border-gray-200">
                    {{ $pengadaans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
    @endpush

</x-app-layout>