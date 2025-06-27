<x-app-layout>
    <div class="p-8">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Laporan Penggajian</h1>
                    <p class="text-green-100">Rekapitulasi pembayaran gaji karyawan.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('karyawan.index') }}" class="px-6 py-3 bg-white/10 text-white rounded-lg flex items-center gap-2">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span>Kelola Karyawan</span>
                    </a>
                    <a href="{{ route('laporan.penggajian.create') }}" class="px-6 py-3 bg-white/20 text-white rounded-lg flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        <span>Input Gaji Baru</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <p class="text-sm font-medium text-gray-600 mb-1">Total Gaji Kotor</p>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalGajiKotor ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <p class="text-sm font-medium text-gray-600 mb-1">Total Potongan</p>
                <p class="text-2xl font-bold text-yellow-600">Rp {{ number_format($totalPotongan ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm font-medium text-gray-600 mb-1">Total Gaji Bersih</p>
                <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($totalGajiBersih ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-green-50 border-b">
                                <th class="py-3 px-4 text-left text-sm font-semibold text-[#173720]">Karyawan</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-[#173720]">Periode</th>
                                <th class="py-3 px-4 text-right text-sm font-semibold text-[#173720]">Gaji Pokok</th>
                                <th class="py-3 px-4 text-right text-sm font-semibold text-[#173720]">Tunjangan</th>
                                <th class="py-3 px-4 text-right text-sm font-semibold text-[#173720]">Potongan</th>
                                <th class="py-3 px-4 text-right text-sm font-semibold text-[#173720]">Gaji Bersih</th>
                                <th class="py-3 px-4 text-center text-sm font-semibold text-[#173720]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penggajian ?? [] as $gaji)
                                <tr class="hover:bg-green-50 border-b">
                                    <td class="py-3 px-4">
                                        <p class="font-medium">{{ $gaji->karyawan->nama_lengkap ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-500">{{ $gaji->karyawan->jabatan ?? '' }}</p>
                                    </td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($gaji->periode)->isoFormat('MMMM YYYY') }}</td>
                                    <td class="py-3 px-4 text-right">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right text-green-600">+Rp {{ number_format($gaji->total_pendapatan - $gaji->gaji_pokok, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right text-red-600">-Rp {{ number_format($gaji->total_potongan, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right font-bold">Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('laporan.slip_gaji', $gaji->id) }}" class="text-blue-600 hover:underline text-sm font-medium">Lihat Slip</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-gray-500">
                                        <p>Belum ada data penggajian untuk periode ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>lucide.createIcons();</script>
    @endpush
</x-app-layout>