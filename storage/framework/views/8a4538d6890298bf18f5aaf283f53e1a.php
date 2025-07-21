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
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md">
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Daftar Karyawan</h1>
                    <p class="text-green-100">Manajemen semua data karyawan perusahaan.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('laporan.penggajian.index')); ?>" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg font-semibold flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        <span>Kembali ke Penggajian</span>
                    </a>
                    <a href="<?php echo e(route('karyawan.create')); ?>" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                        <span>Tambah Karyawan</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Nama Lengkap</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Posisi</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Tgl. Masuk</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $karyawans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karyawan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 font-medium"><?php echo e($karyawan->nama_lengkap); ?></td>
                                <td class="py-3 px-4 text-gray-600"><?php echo e($karyawan->jabatan); ?></td>
                                <td class="py-3 px-4 text-gray-600"><?php echo e(\Carbon\Carbon::parse($karyawan->tanggal_bergabung)->isoFormat('DD MMMM YYYY')); ?></td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="<?php echo e(route('karyawan.edit', $karyawan->id)); ?>" class="text-blue-500 hover:text-blue-700 p-2 rounded-full hover:bg-blue-100">
                                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                                        </a>
                                        <form action="<?php echo e(route('karyawan.destroy', $karyawan->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-100">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-500">
                                    <p>Belum ada data karyawan.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                <?php echo e($karyawans->links()); ?>

            </div>
        </div>
    </div>
    
    <?php $__env->startPush('scripts'); ?>
        <script>
            lucide.createIcons();
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
<?php endif; ?><?php /**PATH C:\tpku-finance-baru\resources\views/karyawan/index.blade.php ENDPATH**/ ?>