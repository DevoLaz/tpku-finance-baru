<x-app-layout>
    <div class="p-8">
        <div class="bg-gradient-to-r from-red-500 to-orange-500 rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Riwayat Beban Operasional</h1>
                    <p class="text-orange-100">Daftar semua pengeluaran di luar pengadaan dan gaji.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('beban.create') }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        <span>Catat Beban</span>
                    </a>
                     {{-- TOMBOL EKSPOR PDF --}}
                    <a href="{{ route('beban.exportPdf', request()->query()) }}" class="px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white rounded-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        <span>Ekspor PDF</span>
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- FORM FILTER --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('beban.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                    <input type="date" name="dari" value="{{ request('dari') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('beban.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="py-3 px-4 text-left">Tanggal</th>
                            <th class="py-3 px-4 text-left">Nama Beban</th>
                            <th class="py-3 px-4 text-left">Kategori</th>
                            <th class="py-3 px-4 text-right">Jumlah</th>
                            <th class="py-3 px-4 text-center">Bukti</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bebans as $beban)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($beban->tanggal)->format('d M Y') }}</td>
                                <td class="py-3 px-4">{{ $beban->nama }}</td>
                                <td class="py-3 px-4"><span class="px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-800 rounded-full">{{ $beban->kategori->nama_kategori ?? 'N/A' }}</span></td>
                                <td class="py-3 px-4 text-right font-medium text-red-600">Rp {{ number_format($beban->jumlah, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-center">
                                    @if ($beban->bukti)
                                        <button 
                                            type="button"
                                            class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-md"
                                            data-img-url="{{ Storage::url($beban->bukti) }}">
                                            Lihat
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <form action="{{ route('beban.destroy', $beban) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus beban ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-12 text-gray-500">Belum ada data beban yang sesuai dengan filter.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bebans->hasPages())
                <div class="p-6 border-t">{{ $bebans->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
