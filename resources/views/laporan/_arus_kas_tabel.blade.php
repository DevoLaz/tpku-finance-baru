<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Keterangan</th>
                <th class="py-2 px-4 text-right text-sm font-semibold text-gray-700">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
            <tr class="hover:bg-gray-50 border-b">
                <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                <td class="py-3 px-4">{{ $item->deskripsi }}</td>
                <td class="py-3 px-4 text-right font-semibold {{ $item->tipe == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $item->tipe == 'masuk' ? '+' : '-' }}Rp {{ number_format(abs($item->jumlah), 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="py-6 text-center text-gray-500">Tidak ada data untuk aktivitas ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if(count($items) > 0)
        <tfoot class="bg-gray-100 font-bold">
            <tr>
                <td colspan="2" class="py-3 px-4 text-right">Subtotal</td>
                @if($tipe === 'masuk')
                    <td class="py-3 px-4 text-right text-green-700">+Rp {{ number_format($items->sum('jumlah'), 0, ',', '.') }}</td>
                @elseif($tipe === 'keluar')
                    <td class="py-3 px-4 text-right text-red-700">-Rp {{ number_format(abs($items->sum('jumlah')), 0, ',', '.') }}</td>
                @else
                    {{-- Untuk tipe 'semua', kita tidak menampilkan subtotal karena gabungan --}}
                    <td class="py-3 px-4 text-right"></td> 
                @endif
            </tr>
        </tfoot>
        @endif
    </table>
</div>