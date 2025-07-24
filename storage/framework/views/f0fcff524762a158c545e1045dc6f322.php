<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 2px 0; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; }
        .text-right { text-align: right; }
        .section-title { font-size: 14px; font-weight: bold; padding-top: 15px; }
        .item-row td { border-bottom: 1px solid #eee; }
        .total-row { font-weight: bold; border-top: 1px solid #333; }
        .final-result { font-size: 16px; font-weight: bold; padding-top: 10px; border-top: 2px double #333; }
        .negative { color: #d00; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Laba Rugi</h1>
        <p><?php echo e($judulPeriode); ?></p>
    </div>

    <table>
        <!-- Pendapatan -->
        <tr>
            <td colspan="2" class="section-title">Pendapatan</td>
        </tr>
        <?php $__empty_1 = true; $__currentLoopData = $pendapatanItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="item-row">
                <td>Penjualan</td>
                <td class="text-right">Rp <?php echo e(number_format($item->total_penjualan, 0, ',', '.')); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr class="item-row">
                <td>Penjualan</td>
                <td class="text-right">Rp 0</td>
            </tr>
        <?php endif; ?>
        <tr class="total-row">
            <td>Total Pendapatan</td>
            <td class="text-right">Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></td>
        </tr>

        <!-- Beban-Beban -->
        <tr>
            <td colspan="2" class="section-title">Beban-Beban</td>
        </tr>
        <?php $__empty_1 = true; $__currentLoopData = $pengeluaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="item-row">
                <td><?php echo e($item['keterangan']); ?></td>
                <td class="text-right negative">(Rp <?php echo e(number_format($item['jumlah'], 0, ',', '.')); ?>)</td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr class="item-row">
                <td>Tidak ada beban</td>
                <td class="text-right negative">(Rp 0)</td>
            </tr>
        <?php endif; ?>
        <tr class="total-row">
            <td>Total Beban</td>
            <td class="text-right negative">(Rp <?php echo e(number_format($totalPengeluaran, 0, ',', '.')); ?>)</td>
        </tr>

        <!-- Laba/Rugi Bersih -->
        <?php $isProfit = $labaBersih >= 0; ?>
        <tr class="final-result">
            <td><?php echo e($isProfit ? 'Laba Bersih' : 'Rugi Bersih'); ?></td>
            <td class="text-right <?php echo e(!$isProfit ? 'negative' : ''); ?>">
                Rp <?php echo e(number_format(abs($labaBersih), 0, ',', '.')); ?>

            </td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH C:\tpku-finance-baru\resources\views/laporan/laba_rugi_pdf.blade.php ENDPATH**/ ?>