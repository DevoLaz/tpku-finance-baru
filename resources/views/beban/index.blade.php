<x-app-layout>
    <div class="p-8">
        <div class="bg-gradient-to-r from-red-500 to-orange-500 rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Riwayat Beban Operasional</h1>
                    <p class="text-orange-100">Daftar semua pengeluaran di luar pengadaan dan gaji.</p>
                </div>
                <a href="{{ route('beban.create') }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bebans as $beban)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($beban->tanggal)->format('d M Y') }}</td>
                                <td class="py-3 px-4">{{ $beban->nama_beban }}</td>
                                <td class="py-3 px-4 text-right font-medium text-red-600">Rp {{ number_format($beban->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-12 text-gray-500">Belum ada data beban.</td></tr>
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
