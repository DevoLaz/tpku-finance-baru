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
            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50 border-b">
                <td class="py-3 px-4 text-sm"><?php echo e(\Carbon\Carbon::parse($item->tanggal)->format('d M Y')); ?></td>
                <td class="py-3 px-4"><?php echo e($item->deskripsi); ?></td>
                <td class="py-3 px-4 text-right font-semibold <?php echo e($item->tipe == 'masuk' ? 'text-green-600' : 'text-red-600'); ?>">
                    <?php echo e($item->tipe == 'masuk' ? '+' : '-'); ?>Rp <?php echo e(number_format(abs($item->jumlah), 0, ',', '.')); ?>

                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="3" class="py-6 text-center text-gray-500">Tidak ada data untuk aktivitas ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
        <?php if(count($items) > 0): ?>
        <tfoot class="bg-gray-100 font-bold">
            <tr>
                <td colspan="2" class="py-3 px-4 text-right">Subtotal</td>
                <?php if($tipe === 'masuk'): ?>
                    <td class="py-3 px-4 text-right text-green-700">+Rp <?php echo e(number_format($items->sum('jumlah'), 0, ',', '.')); ?></td>
                <?php elseif($tipe === 'keluar'): ?>
                    <td class="py-3 px-4 text-right text-red-700">-Rp <?php echo e(number_format(abs($items->sum('jumlah')), 0, ',', '.')); ?></td>
                <?php else: ?>
                    
                    <td class="py-3 px-4 text-right"></td> 
                <?php endif; ?>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
</div><?php /**PATH C:\tpku-finance-baru\resources\views/laporan/_arus_kas_tabel.blade.php ENDPATH**/ ?>