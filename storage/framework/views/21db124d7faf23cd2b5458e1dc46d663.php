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
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Laporan Neraca</h1>
                    <p class="text-indigo-100">Potret posisi keuangan perusahaan per tanggal <?php echo e(\Carbon\Carbon::parse($tanggalLaporan)->format('d F Y')); ?></p>
                </div>
                
                <a href="<?php echo e(route('laporan.neraca.exportPdf', request()->query())); ?>" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    <span>Ekspor PDF</span>
                </a>
                <a href="<?php echo e(route('laporan.neraca.exportExcel', request()->query())); ?>" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Ekspor Excel</a>

            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="<?php echo e(route('laporan.neraca')); ?>" class="flex items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal Laporan</label>
                    <input type="date" name="tanggal" value="<?php echo e($tanggalLaporan); ?>" class="w-full px-4 py-2 rounded-lg border-gray-300">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg">Tampilkan</button>
            </form>
        </div>

        <!-- Konten Neraca -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- SISI ASET -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 border-b-2 pb-2 mb-4">ASET</h2>
                <table class="w-full text-sm">
                    <tr class="font-semibold"><td class="py-2" colspan="2">Aset Lancar</td></tr>
                    <tr class="border-b"><td class="py-2 pl-4">Kas & Setara Kas</td><td class="py-2 text-right">Rp <?php echo e(number_format($kas, 0, ',', '.')); ?></td></tr>
                    <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Aset Lancar</td><td class="py-2 text-right">Rp <?php echo e(number_format($kas, 0, ',', '.')); ?></td></tr>

                    <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                    <tr class="font-semibold"><td class="py-2" colspan="2">Aset Tetap</td></tr>
                    <?php $__currentLoopData = $asetFisikItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-b"><td class="py-2 pl-4"><?php echo e($item->nama_aset); ?></td><td class="py-2 text-right">Rp <?php echo e(number_format($item->harga_perolehan, 0, ',', '.')); ?></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-b"><td class="py-2 pl-4 text-red-600">Akumulasi Penyusutan</td><td class="py-2 text-right text-red-600">(Rp <?php echo e(number_format($totalAkumulasiPenyusutan, 0, ',', '.')); ?>)</td></tr>
                    <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Aset Tetap (Nilai Buku)</td><td class="py-2 text-right">Rp <?php echo e(number_format($asetFisikItems->sum('harga_perolehan') - $totalAkumulasiPenyusutan, 0, ',', '.')); ?></td></tr>

                    <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                    <tr class="font-bold text-lg bg-indigo-100 border-t-2 border-indigo-300">
                        <td class="py-4">TOTAL ASET</td>
                        <td class="py-4 text-right">Rp <?php echo e(number_format($totalAset, 0, ',', '.')); ?></td>
                    </tr>
                </table>
            </div>

            <!-- SISI LIABILITAS & EKUITAS -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 border-b-2 pb-2 mb-4">LIABILITAS & EKUITAS</h2>
                <table class="w-full text-sm">
                    <tr class="font-semibold"><td class="py-2" colspan="2">Liabilitas</td></tr>
                    <tr class="border-b"><td class="py-2 pl-4">Utang Usaha</td><td class="py-2 text-right">Rp 0</td></tr>
                    <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Liabilitas</td><td class="py-2 text-right">Rp <?php echo e(number_format($totalLiabilitas, 0, ',', '.')); ?></td></tr>

                    <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                    <tr class="font-semibold"><td class="py-2" colspan="2">Ekuitas</td></tr>
                    <tr class="border-b"><td class="py-2 pl-4">Modal Disetor</td><td class="py-2 text-right">Rp <?php echo e(number_format($modalDisetor, 0, ',', '.')); ?></td></tr>
                    <tr class="border-b"><td class="py-2 pl-4">Laba Ditahan</td><td class="py-2 text-right">Rp <?php echo e(number_format($labaDitahan, 0, ',', '.')); ?></td></tr>
                    <tr class="font-semibold bg-gray-50"><td class="py-2 pl-4">Total Ekuitas</td><td class="py-2 text-right">Rp <?php echo e(number_format($totalEkuitas, 0, ',', '.')); ?></td></tr>

                    <tr><td colspan="2" class="py-3">&nbsp;</td></tr>

                    <tr class="font-bold text-lg bg-indigo-100 border-t-2 border-indigo-300">
                        <td class="py-4">TOTAL LIABILITAS & EKUITAS</td>
                        <td class="py-4 text-right">Rp <?php echo e(number_format($totalLiabilitasEkuitas, 0, ',', '.')); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Check Balance -->
        <div class="mt-8 text-center">
            <?php if($totalAset > 0 || $totalLiabilitasEkuitas > 0): ?>
                <?php if(round($totalAset) == round($totalLiabilitasEkuitas)): ?>
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 font-semibold rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Neraca Seimbang (Balanced)
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-800 font-semibold rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="m21.73 18-8-14a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                        Neraca Tidak Seimbang! Selisih: Rp <?php echo e(number_format(abs($totalAset - $totalLiabilitasEkuitas))); ?>

                    </span>
                <?php endif; ?>
            <?php endif; ?>
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
<?php /**PATH C:\tpku-finance-baru\resources\views/laporan/neraca.blade.php ENDPATH**/ ?>