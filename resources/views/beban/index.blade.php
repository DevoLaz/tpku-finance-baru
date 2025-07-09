<x-app-layout>
    <div class="p-8">
        <div class="bg-gradient-to-r from-red-500 to-orange-500 rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Riwayat Beban Operasional</h1>
                    <p class="text-orange-100">Daftar semua pengeluaran di luar pengadaan dan gaji.</p>
                </div>
                <a href="{{ route('beban.create') }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    <span>Catat Beban</span>
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="py-3 px-4 text-left">Tanggal</th>
                            <th class="py-3 px-4 text-left">Nama Beban</th>
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
                            <tr><td colspan="5" class="text-center py-12 text-gray-500">Belum ada data beban.</td></tr>
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
