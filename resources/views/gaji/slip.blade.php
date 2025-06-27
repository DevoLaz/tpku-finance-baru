<x-app-layout>
    <div class="p-8">
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Slip Gaji Karyawan</h1>
                    <p class="text-green-100">Detail gaji untuk periode {{ \Carbon\Carbon::parse($gaji->periode)->isoFormat('MMMM Y') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('laporan.penggajian.index') }}" class="px-6 py-3 bg-white/10 text-white rounded-lg flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        <span>Kembali</span>
                    </a>
                    <button onclick="window.print()" class="px-6 py-3 bg-white/20 text-white rounded-lg flex items-center gap-2">
                        <i data-lucide="printer" class="w-5 h-5"></i>
                        <span>Cetak</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-md p-10" id="slip-gaji">
            <div class="text-center border-b-2 border-gray-200 pb-6 mb-6">
                <h2 class="text-2xl font-bold text-[#173720]">TPKU FINANCE</h2>
                <p class="text-gray-500">Jl. Raya Keuangan No. 123, Kota Sejahtera</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div>
                    <h3 class="font-bold text-gray-800">Nama Karyawan</h3>
                    <p class="text-gray-600">{{ $gaji->karyawan->nama_lengkap }}</p>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Jabatan</h3>
                    <p class="text-gray-600">{{ $gaji->karyawan->jabatan }}</p>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Status</h3>
                    <p class="text-gray-600">{{ ucfirst($gaji->karyawan->status_karyawan) }}</p>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Periode Gaji</h3>
                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($gaji->periode)->isoFormat('MMMM Y') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-bold text-green-700 mb-4 border-b pb-2">Pendapatan</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between"><span class="text-gray-600">Gaji Pokok</span><span class="font-medium">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Tunjangan Jabatan</span><span class="font-medium">Rp {{ number_format($gaji->tunjangan_jabatan, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Tunjangan Transport</span><span class="font-medium">Rp {{ number_format($gaji->tunjangan_transport, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Bonus / Lembur</span><span class="font-medium">Rp {{ number_format($gaji->bonus, 0, ',', '.') }}</span></div>
                        <div class="border-t mt-3 pt-3 flex justify-between">
                            <span class="font-bold text-gray-800">Total Pendapatan</span>
                            <span class="font-bold text-green-600">Rp {{ number_format($gaji->total_pendapatan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-red-700 mb-4 border-b pb-2">Potongan</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between"><span class="text-gray-600">Pajak PPh 21</span><span class="font-medium">Rp {{ number_format($gaji->pph21, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Iuran BPJS</span><span class="font-medium">Rp {{ number_format($gaji->bpjs, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Potongan Lainnya</span><span class="font-medium">Rp {{ number_format($gaji->potongan_lain, 0, ',', '.') }}</span></div>
                        <div class="border-t mt-3 pt-3 flex justify-between">
                            <span class="font-bold text-gray-800">Total Potongan</span>
                            <span class="font-bold text-red-600">Rp {{ number_format($gaji->total_potongan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-10 pt-6 border-t-4 border-double border-gray-300">
                <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold text-gray-800">GAJI BERSIH DITERIMA</h3>
                    <p class="text-2xl font-extrabold text-[#173720]">Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-8 mt-12 text-center">
                <div>
                    <p class="mb-16">Penerima,</p>
                    <p class="font-bold border-t border-gray-400 pt-2">{{ $gaji->karyawan->nama_lengkap }}</p>
                </div>
                <div>
                    <p class="mb-16">Bagian Keuangan,</p>
                    <p class="font-bold border-t border-gray-400 pt-2">(.....................)</p>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <style>
            @media print {
                body * { visibility: hidden; }
                #slip-gaji, #slip-gaji * { visibility: visible; }
                #slip-gaji { position: absolute; left: 0; top: 0; width: 100%; }
            }
        </style>
        <script>lucide.createIcons();</script>
    @endpush
</x-app-layout>