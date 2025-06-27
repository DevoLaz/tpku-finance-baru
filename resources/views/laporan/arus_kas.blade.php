<x-app-layout>
    <div class="p-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white mb-2">Laporan Arus Kas</h1>
            <p class="text-green-100">Analisis pergerakan kas masuk dan keluar perusahaan</p>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-6 bg-[#173720] rounded"></div>
                <h3 class="text-lg font-semibold text-gray-800">Filter Periode</h3>
            </div>
            
            <form method="GET" action="{{ route('laporan.arus_kas') }}" class="flex flex-wrap gap-4 items-end">
                <!-- Filter Tahun -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i>
                        Tahun
                    </label>
                    <select name="tahun" class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#173720] transition-all bg-white shadow-sm">
                        @forelse($daftarTahun as $thn)
                            <option value="{{ $thn }}" {{ request('tahun', $tahun) == $thn ? 'selected' : '' }}>
                                {{ $thn }}
                            </option>
                        @empty
                             <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                        @endforelse
                    </select>
                </div>

                <!-- Filter Bulan -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="calendar-days" class="w-4 h-4 inline mr-1"></i>
                        Bulan
                    </label>
                    <select name="bulan" class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#173720] transition-all bg-white shadow-sm">
                        <option value="">Semua Bulan (Tahunan)</option>
                        @php
                            $namaBulan = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        @foreach($namaBulan as $num => $nama)
                            <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Submit & Reset -->
                <div class="flex gap-2 items-end">
                    <button type="submit" class="px-6 py-2.5 bg-[#173720] hover:bg-[#2a5a37] text-white rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>Tampilkan</span>
                    </button>
                    <a href="{{ route('laporan.arus_kas') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
                <p class="text-sm font-medium text-gray-600 mb-1">Saldo Awal</p>
                <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
                <p class="text-sm font-medium text-gray-600 mb-1">Total Kas Masuk</p>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition-shadow">
                <p class="text-sm font-medium text-gray-600 mb-1">Total Kas Keluar</p>
                <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 {{ $saldoAkhir >= 0 ? 'border-purple-500' : 'border-orange-500' }} hover:shadow-lg transition-shadow">
                 <p class="text-sm font-medium text-gray-600 mb-1">Saldo Akhir</p>
                 <p class="text-2xl font-bold {{ $saldoAkhir >= 0 ? 'text-purple-600' : 'text-orange-600' }}">
                     Rp {{ number_format(abs($saldoAkhir), 0, ',', '.') }}
                 </p>
            </div>
        </div>

        <!-- Cash Flow Chart -->
        <div class="mt-6 bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-lg font-semibold text-[#173720] mb-4">
                <i data-lucide="bar-chart-3" class="w-5 h-5 inline mr-2"></i>
                Grafik Arus Kas Tahun {{ $tahun }}
            </h3>
            <div class="h-80">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>

        <!-- Detail Arus Kas dengan Tabs -->
        <div class="mt-6 bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Tab Headers -->
            <div class="flex border-b">
                <button onclick="switchTab('operasional')" id="tab-operasional" class="flex-1 px-6 py-4 font-semibold text-[#173720] border-b-2 border-[#173720] bg-green-50 transition-all">
                    Aktivitas Operasional
                </button>
                <button onclick="switchTab('investasi')" id="tab-investasi" class="flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-[#173720] transition-all">
                    Aktivitas Investasi
                </button>
                <button onclick="switchTab('pendanaan')" id="tab-pendanaan" class="flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-[#173720] transition-all">
                    Aktivitas Pendanaan
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Aktivitas Operasional Tab -->
                <div id="content-operasional" class="space-y-4">
                    <h3 class="text-lg font-semibold text-[#173720] mb-4">Arus Kas dari Aktivitas Operasional</h3>
                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-green-700 mb-3">Kas Masuk</h4>
                        @include('laporan._arus_kas_tabel', ['items' => $operasionalMasuk, 'tipe' => 'masuk'])
                    </div>
                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-red-700 mb-3">Kas Keluar</h4>
                        @include('laporan._arus_kas_tabel', ['items' => $operasionalKeluar, 'tipe' => 'keluar'])
                    </div>
                </div>

                <!-- Aktivitas Investasi Tab -->
                <div id="content-investasi" class="space-y-4 hidden">
                    <h3 class="text-lg font-semibold text-[#173720] mb-4">Arus Kas dari Aktivitas Investasi</h3>
                    @include('laporan._arus_kas_tabel', ['items' => $investasi, 'tipe' => 'semua'])
                </div>

                <!-- Aktivitas Pendanaan Tab -->
                <div id="content-pendanaan" class="space-y-4 hidden">
                    <h3 class="text-lg font-semibold text-[#173720] mb-4">Arus Kas dari Aktivitas Pendanaan</h3>
                    @include('laporan._arus_kas_tabel', ['items' => $pendanaan, 'tipe' => 'semua'])
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- Library Chart.js dari CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            
            // Tab switching
            const switchTab = (tab) => {
                const tabs = ['operasional', 'investasi', 'pendanaan'];
                tabs.forEach(t => {
                    document.getElementById('tab-' + t).classList.remove('border-b-2', 'border-[#173720]', 'text-[#173720]', 'bg-green-50');
                    document.getElementById('content-' + t).classList.add('hidden');
                });
                
                const tabElement = document.getElementById('tab-' + tab);
                tabElement.classList.add('border-b-2', 'border-[#173720]', 'text-[#173720]', 'bg-green-50');
                document.getElementById('content-' + tab).classList.remove('hidden');
            };

            // Tambahkan event listener ke tombol
            document.getElementById('tab-operasional').addEventListener('click', () => switchTab('operasional'));
            document.getElementById('tab-investasi').addEventListener('click', () => switchTab('investasi'));
            document.getElementById('tab-pendanaan').addEventListener('click', () => switchTab('pendanaan'));

            // Script Grafik Chart.js
            const cashFlowCtx = document.getElementById('cashFlowChart');
            if (cashFlowCtx) {
                const cashFlowData = JSON.parse('{!! $dataGrafikJson !!}');
                
                new Chart(cashFlowCtx, {
                    type: 'bar',
                    data: {
                        labels: cashFlowData.map(d => d.bulan),
                        datasets: [
                            { type: 'line', label: 'Arus Kas Bersih', data: cashFlowData.map(d => d.arus_kas_bersih), borderColor: '#8b5cf6', fill: true, yAxisID: 'y', order: 0 },
                            { type: 'bar', label: 'Kas Masuk', data: cashFlowData.map(d => d.kas_masuk), backgroundColor: 'rgba(34, 197, 94, 0.7)', yAxisID: 'y', order: 1 },
                            { type: 'bar', label: 'Kas Keluar', data: cashFlowData.map(d => d.kas_keluar), backgroundColor: 'rgba(239, 68, 68, 0.7)', yAxisID: 'y', order: 2 }
                        ]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>