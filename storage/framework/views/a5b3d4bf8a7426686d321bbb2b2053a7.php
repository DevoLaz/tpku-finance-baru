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
        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Laporan Penggajian</h1>
                    <p class="text-green-100">Rekapitulasi pembayaran gaji karyawan.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('karyawan.index')); ?>" class="px-6 py-3 bg-white/10 text-white rounded-lg flex items-center gap-2">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span>Kelola Karyawan</span>
                    </a>
                    <a href="<?php echo e(route('laporan.penggajian.create')); ?>" class="px-6 py-3 bg-white/20 text-white rounded-lg flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        <span>Input Gaji Baru</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <p class="text-sm font-medium text-gray-600 mb-1">Total Gaji Kotor</p>
                <p class="text-2xl font-bold text-green-600">Rp <?php echo e(number_format($totalGajiKotor ?? 0, 0, ',', '.')); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <p class="text-sm font-medium text-gray-600 mb-1">Total Potongan</p>
                <p class="text-2xl font-bold text-yellow-600">Rp <?php echo e(number_format($totalPotongan ?? 0, 0, ',', '.')); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm font-medium text-gray-600 mb-1">Total Gaji Bersih</p>
                <p class="text-2xl font-bold text-blue-600">Rp <?php echo e(number_format($totalGajiBersih ?? 0, 0, ',', '.')); ?></p>
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
                            <?php $__empty_1 = true; $__currentLoopData = $penggajian ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gaji): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-green-50 border-b">
                                    <td class="py-3 px-4">
                                        <p class="font-medium"><?php echo e($gaji->karyawan->nama_lengkap ?? 'N/A'); ?></p>
                                        <p class="text-sm text-gray-500"><?php echo e($gaji->karyawan->jabatan ?? ''); ?></p>
                                    </td>
                                    <td class="py-3 px-4"><?php echo e(\Carbon\Carbon::parse($gaji->periode)->isoFormat('MMMM YYYY')); ?></td>
                                    <td class="py-3 px-4 text-right">Rp <?php echo e(number_format($gaji->gaji_pokok, 0, ',', '.')); ?></td>
                                    <td class="py-3 px-4 text-right text-green-600">+Rp <?php echo e(number_format($gaji->total_pendapatan - $gaji->gaji_pokok, 0, ',', '.')); ?></td>
                                    <td class="py-3 px-4 text-right text-red-600">-Rp <?php echo e(number_format($gaji->total_potongan, 0, ',', '.')); ?></td>
                                    <td class="py-3 px-4 text-right font-bold">Rp <?php echo e(number_format($gaji->gaji_bersih, 0, ',', '.')); ?></td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="<?php echo e(route('laporan.slip_gaji', $gaji->id)); ?>" class="text-blue-600 hover:underline text-sm font-medium">Lihat Slip</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-gray-500">
                                        <p>Belum ada data penggajian untuk periode ini.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php $__env->startPush('scripts'); ?>
        <script>lucide.createIcons();</script>
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
<?php endif; ?><?php /**PATH C:\tpku-finance-baru\resources\views/gaji/index.blade.php ENDPATH**/ ?>