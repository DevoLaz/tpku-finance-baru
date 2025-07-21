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
                    <h1 class="text-3xl font-bold text-white mb-2">Laporan Laba Rugi</h1>
                    <p class="text-green-100">Analisis pendapatan dan pengeluaran perusahaan</p>
                </div>
                
                <a href="<?php echo e(route('laporan.laba_rugi.exportPdf', request()->query())); ?>" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    <span>Ekspor PDF</span>
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="<?php echo e(route('laporan.laba_rugi')); ?>" class="flex flex-wrap gap-4 items-end">
                <?php echo csrf_field(); ?>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select name="tahun" class="w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-300">
                        <?php $__empty_1 = true; $__currentLoopData = $daftarTahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($thn); ?>" <?php echo e(request('tahun', $tahun) == $thn ? 'selected' : ''); ?>><?php echo e($thn); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <option value="<?php echo e(date('Y')); ?>"><?php echo e(date('Y')); ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="bulan" class="w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-300">
                        <?php
                            $namaBulan = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                        ?>
                        <?php $__currentLoopData = $namaBulan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $nama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($num); ?>" <?php echo e(request('bulan', $bulan) == $num ? 'selected' : ''); ?>><?php echo e($nama); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex gap-2 items-end">
                    <button type="submit" class="px-6 py-2.5 bg-[#173720] text-white rounded-lg">Tampilkan</button>
                    <a href="<?php echo e(route('laporan.laba_rugi')); ?>" class="px-6 py-2.5 bg-gray-500 text-white rounded-lg">Reset</a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                <p class="text-2xl font-bold text-green-600">Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-500">
                <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                <p class="text-2xl font-bold text-red-600">Rp <?php echo e(number_format($totalPengeluaran, 0, ',', '.')); ?></p>
            </div>
            <?php $isProfit = $labaBersih >= 0; ?>
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 <?php echo e($isProfit ? 'border-blue-500' : 'border-orange-500'); ?>">
                <p class="text-sm font-medium text-gray-600"><?php echo e($isProfit ? 'Laba Bersih' : 'Rugi Bersih'); ?></p>
                <p class="text-2xl font-bold <?php echo e($isProfit ? 'text-blue-600' : 'text-orange-600'); ?>">Rp <?php echo e(number_format(abs($labaBersih), 0, ',', '.')); ?></p>
            </div>
        </div>

        <!-- Detail Laporan -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <table class="w-full">
                
                <tr class="font-bold text-lg"><td colspan="2" class="py-2">Pendapatan</td></tr>
                <?php $__empty_1 = true; $__currentLoopData = $pendapatanItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b"><td class="py-2 pl-4">Penjualan</td><td class="py-2 text-right">Rp <?php echo e(number_format($item->total_penjualan, 0, ',', '.')); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr class="border-b"><td class="py-2 pl-4">Penjualan</td><td class="py-2 text-right">Rp 0</td></tr>
                <?php endif; ?>
                <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Pendapatan</td><td class="py-2 text-right">Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></td></tr>

                
                <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                
                <tr class="font-bold text-lg"><td colspan="2" class="py-2">Beban-Beban</td></tr>
                <?php $__empty_1 = true; $__currentLoopData = $pengeluaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b"><td class="py-2 pl-4"><?php echo e($item['keterangan']); ?></td><td class="py-2 text-right text-red-600">(Rp <?php echo e(number_format($item['jumlah'], 0, ',', '.')); ?>)</td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr class="border-b"><td class="py-2 pl-4">Tidak ada beban</td><td class="py-2 text-right text-red-600">(Rp 0)</td></tr>
                <?php endif; ?>
                <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Pengeluaran</td><td class="py-2 text-right text-red-600">(Rp <?php echo e(number_format($totalPengeluaran, 0, ',', '.')); ?>)</td></tr>

                
                <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                
                <tr class="font-bold text-xl bg-gray-100 border-t-2 border-gray-300">
                    <td class="py-4"><?php echo e($isProfit ? 'LABA BERSIH' : 'RUGI BERSIH'); ?></td>
                    <td class="py-4 text-right <?php echo e($isProfit ? 'text-blue-600' : 'text-orange-600'); ?>">Rp <?php echo e(number_format(abs($labaBersih), 0, ',', '.')); ?></td>
                </tr>
            </table>
        </div>
    </div>
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
<?php /**PATH C:\tpku-finance-baru\resources\views/laporan/laba_rugi.blade.php ENDPATH**/ ?>