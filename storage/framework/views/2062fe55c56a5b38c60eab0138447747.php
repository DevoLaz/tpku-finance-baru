<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengadaan Barang</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 2px 0; font-size: 12px; }
        .invoice-section { margin-bottom: 20px; page-break-inside: avoid; }
        .invoice-header { background-color: #173720; color: white; padding: 8px; font-size: 12px; }
        .invoice-header-table { width: 100%; }
        .invoice-header-table td { padding: 0; border: none; vertical-align: middle; }
        .invoice-details-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .invoice-details-table th, .invoice-details-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .invoice-details-table th { background-color: #f2f2f2; font-size: 11px; }
        .text-right { text-align: right; }
        .summary { margin-top: 30px; padding-top: 10px; border-top: 2px solid #173720; text-align: right; font-size: 14px; }
        .summary .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pengadaan Barang</h1>
        <?php if($dari || $sampai): ?>
            <p>
                Periode: 
                <?php echo e($dari ? \Carbon\Carbon::parse($dari)->format('d M Y') : 'Awal'); ?> - 
                <?php echo e($sampai ? \Carbon\Carbon::parse($sampai)->format('d M Y') : 'Akhir'); ?>

            </p>
        <?php else: ?>
            <p>Periode: Semua Data</p>
        <?php endif; ?>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $pengadaansByInvoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoiceNumber => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="invoice-section">
            <div class="invoice-header">
                <table class="invoice-header-table">
                    <tr>
                        <td><strong>No. Invoice:</strong> <?php echo e($invoiceNumber); ?></td>
                        <td class="text-right"><strong>Tanggal:</strong> <?php echo e(\Carbon\Carbon::parse($items->first()->tanggal_pembelian)->format('d M Y')); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Supplier:</strong> <?php echo e($items->first()->supplier->nama_supplier ?? 'N/A'); ?></td>
                    </tr>
                </table>
            </div>
            <table class="invoice-details-table">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th class="text-right">Jumlah</th>
                        <th class="text-right">Harga Beli</th>
                        <th class="text-right">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($item->barang->nama ?? 'N/A'); ?></td>
                            <td class="text-right"><?php echo e(number_format($item->jumlah_masuk, 0, ',', '.')); ?></td>
                            <td class="text-right">Rp <?php echo e(number_format($item->harga_beli, 0, ',', '.')); ?></td>
                            <td class="text-right">Rp <?php echo e(number_format($item->total_harga, 0, ',', '.')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #f2f2f2; font-weight: bold;">
                        <td colspan="3" class="text-right">Total Invoice</td>
                        <td class="text-right">Rp <?php echo e(number_format($items->sum('total_harga'), 0, ',', '.')); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p style="text-align: center;">Tidak ada data pengadaan untuk periode yang dipilih.</p>
    <?php endif; ?>

    <div class="summary">
        <span class="total">Total Pengeluaran Keseluruhan: Rp <?php echo e(number_format($totalPengeluaran, 0, ',', '.')); ?></span>
    </div>

</body>
</html>
<?php /**PATH C:\tpku-finance-baru\resources\views/pengadaan/pdf.blade.php ENDPATH**/ ?>