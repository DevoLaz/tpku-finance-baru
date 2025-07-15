<x-app-layout>
    <div class="p-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Laporan Arus Kas</h1>
                    <p class="text-green-100">Analisis aliran masuk dan keluar kas perusahaan.</p>
                </div>
                <a href="{{ route('laporan.arus_kas.exportPdf', request()->query()) }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    <span>Ekspor PDF</span>
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('laporan.arus_kas') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        @foreach($daftarTahun as $thn)
                            <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Bulan (Tahunan)</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
                        <span>Tampilkan</span>
                    </button>
                    <a href="{{ route('laporan.arus_kas') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-gray-400">
                <p class="text-sm text-gray-600">Saldo Awal Periode</p>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                <p class="text-sm text-gray-600">Total Kas Masuk</p>
                <p class="text-2xl font-bold text-green-600">+ Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                <p class="text-sm text-gray-600">Total Kas Keluar</p>
                <p class="text-2xl font-bold text-red-600">- Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">Saldo Akhir Periode</p>
                <p class="text-2xl font-bold text-blue-800">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Grafik Arus Kas Tahunan ({{ $tahun }})</h3>
            <div id="arusKasChart"></div>
        </div>
        
        <!-- Detail Arus Kas -->
        <div class="space-y-6">
            <!-- Aktivitas Operasional -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-4 border-b font-bold text-lg text-gray-800">Arus Kas dari Aktivitas Operasional</div>
                <div class="p-4 space-y-4">
                    <div>
                        <h4 class="font-semibold text-green-700 mb-2">Kas Masuk dari Operasional</h4>
                        @include('laporan._arus_kas_tabel', ['items' => $operasionalMasuk, 'tipe' => 'masuk'])
                    </div>
                    <div>
                        <h4 class="font-semibold text-red-700 mb-2">Kas Keluar untuk Operasional</h4>
                        @include('laporan._arus_kas_tabel', ['items' => $operasionalKeluar, 'tipe' => 'keluar'])
                    </div>
                </div>
            </div>

            <!-- Aktivitas Investasi -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-4 border-b font-bold text-lg text-gray-800">Arus Kas dari Aktivitas Investasi</div>
                <div class="p-4">
                    @include('laporan._arus_kas_tabel', ['items' => $investasi, 'tipe' => 'semua'])
                </div>
            </div>

            <!-- Aktivitas Pendanaan -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-4 border-b font-bold text-lg text-gray-800">Arus Kas dari Aktivitas Pendanaan</div>
                <div class="p-4">
                    @include('laporan._arus_kas_tabel', ['items' => $pendanaan, 'tipe' => 'semua'])
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                series: [{
                    name: 'Kas Masuk',
                    // PERBAIKAN LOGIKA: Mengambil data dari array PHP
                    data: {!! json_encode(collect($dataGrafikFormatted)->pluck('kas_masuk')) !!}
                }, {
                    name: 'Kas Keluar',
                    data: {!! json_encode(collect($dataGrafikFormatted)->pluck('kas_keluar')) !!}
                }, {
                    name: 'Arus Kas Bersih',
                    data: {!! json_encode(collect($dataGrafikFormatted)->pluck('arus_kas_bersih')) !!}
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: true
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    // PERBAIKAN LOGIKA: Mengambil kategori dari array PHP
                    categories: {!! json_encode(collect($dataGrafikFormatted)->pluck('bulan')) !!},
                },
                yaxis: {
                    title: {
                        text: 'Rupiah (Rp)'
                    },
                    labels: {
                        formatter: function (value) {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                        }
                    }
                },
                colors: ['#16a34a', '#dc2626', '#2563eb'] // Hijau, Merah, Biru
            };

            var chart = new ApexCharts(document.querySelector("#arusKasChart"), options);
            chart.render();
        });
    </script>
    @endpush
</x-app-layout>
