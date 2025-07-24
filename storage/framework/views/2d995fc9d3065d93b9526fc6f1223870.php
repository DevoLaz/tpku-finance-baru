<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="p-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Laporan Arus Kas</h1>
                    <p class="text-green-100">Analisis aliran masuk dan keluar kas perusahaan.</p>
                </div>
                
                <!-- === PERBAIKAN DI SINI: Tombol dibungkus dalam satu div === -->
                <div class="flex items-center gap-4">
                    
                    <a href="<?php echo e(route('laporan.arus_kas.exportPdf', request()->query())); ?>" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold flex items-center gap-2 transition-transform transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        <span>Ekspor PDF</span>
                    </a>

                    
                    <a href="<?php echo e(route('laporan.arusKas.exportExcel', request()->query())); ?>" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold flex items-center gap-2 transition-transform transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="3" y1="15" x2="21" y2="15"></line><line x1="9" y1="3" x2="9" y2="21"></line><line x1="15" y1="3" x2="15" y2="21"></line></svg>
                        <span>Ekspor Excel</span>
                    </a>
                </div>
                <!-- === AKHIR PERBAIKAN === -->
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" action="<?php echo e(route('laporan.arus_kas')); ?>" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <?php $__currentLoopData = $daftarTahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($thn); ?>" <?php echo e($tahun == $thn ? 'selected' : ''); ?>><?php echo e($thn); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Bulan (Tahunan)</option>
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e($bulan == $i ? 'selected' : ''); ?>><?php echo e(\Carbon\Carbon::create()->month($i)->format('F')); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
                        <span>Tampilkan</span>
                    </button>
                    <a href="<?php echo e(route('laporan.arus_kas')); ?>" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-gray-400">
                <p class="text-sm text-gray-600">Saldo Awal Periode</p>
                <p class="text-2xl font-bold text-gray-800">Rp <?php echo e(number_format($saldoAwal, 0, ',', '.')); ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                <p class="text-sm text-gray-600">Total Kas Masuk</p>
                <p class="text-2xl font-bold text-green-600">+ Rp <?php echo e(number_format($totalKasMasuk, 0, ',', '.')); ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                <p class="text-sm text-gray-600">Total Kas Keluar</p>
                <p class="text-2xl font-bold text-red-600">- Rp <?php echo e(number_format($totalKasKeluar, 0, ',', '.')); ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">Saldo Akhir Periode</p>
                <p class="text-2xl font-bold text-blue-800">Rp <?php echo e(number_format($saldoAkhir, 0, ',', '.')); ?></p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Grafik Arus Kas Tahunan (<?php echo e($tahun); ?>)</h3>
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
                        <?php echo $__env->make('laporan._arus_kas_tabel', ['items' => $operasionalMasuk, 'tipe' => 'masuk'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                    <div>
                        <h4 class="font-semibold text-red-700 mb-2">Kas Keluar untuk Operasional</h4>
                        <?php echo $__env->make('laporan._arus_kas_tabel', ['items' => $operasionalKeluar, 'tipe' => 'keluar'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Investasi -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-4 border-b font-bold text-lg text-gray-800">Arus Kas dari Aktivitas Investasi</div>
                <div class="p-4">
                    <?php echo $__env->make('laporan._arus_kas_tabel', ['items' => $investasi, 'tipe' => 'semua'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>

            <!-- Aktivitas Pendanaan -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-4 border-b font-bold text-lg text-gray-800">Arus Kas dari Aktivitas Pendanaan</div>
                <div class="p-4">
                    <?php echo $__env->make('laporan._arus_kas_tabel', ['items' => $pendanaan, 'tipe' => 'semua'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>

    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                series: [{
                    name: 'Kas Masuk',
                    data: <?php echo json_encode(collect($dataGrafikFormatted)->pluck('kas_masuk')); ?>

                }, {
                    name: 'Kas Keluar',
                    data: <?php echo json_encode(collect($dataGrafikFormatted)->pluck('kas_keluar')); ?>

                }, {
                    name: 'Arus Kas Bersih',
                    data: <?php echo json_encode(collect($dataGrafikFormatted)->pluck('arus_kas_bersih')); ?>

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
                    categories: <?php echo json_encode(collect($dataGrafikFormatted)->pluck('bulan')); ?>,
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
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\tpku-finance-baru\resources\views/laporan/arus_kas.blade.php ENDPATH**/ ?>