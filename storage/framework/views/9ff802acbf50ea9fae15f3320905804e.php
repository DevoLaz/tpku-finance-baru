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
        
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Riwayat Transaksi Penjualan</h1>
                    <p class="text-green-100">Daftar semua rekap pemasukan dari penjualan.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('transaksi.fetchApi')); ?>" class="px-6 py-3 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/><path d="M16 12h5"/><path d="M12 7v5"/></svg>
                        <span>Sinkronkan API</span>
                    </a>
                    <a href="<?php echo e(route('transaksi.create')); ?>" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        <span>Catat Penjualan</span>
                    </a>
                    <a href="<?php echo e(route('transaksi.exportPdf', request()->query())); ?>" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        <span>Ekspor PDF</span>
                    </a>
                </div>
            </div>
        </div>

        
        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <p><?php echo e(session('error')); ?></p>
            </div>
        <?php endif; ?>

        
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <form method="GET" action="<?php echo e(route('transaksi.index')); ?>">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                        <select name="periode" onchange="togglePeriodeFilter(this.value)" class="w-full pl-4 pr-8 py-2 rounded border-gray-300">
                            <option value="bulanan" <?php echo e($periode == 'bulanan' ? 'selected' : ''); ?>>Bulanan</option>
                            <option value="harian" <?php echo e($periode == 'harian' ? 'selected' : ''); ?>>Harian</option>
                        </select>
                    </div>
                    <div id="filter-harian" class="<?php echo e($periode == 'harian' ? '' : 'hidden'); ?>">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="<?php echo e($tanggal); ?>" class="w-full px-4 py-2 rounded border-gray-300">
                    </div>
                    <div id="filter-bulanan" class="<?php echo e($periode == 'bulanan' ? '' : 'hidden'); ?> grid grid-cols-2 gap-2 md:col-span-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="tahun" class="w-full pl-4 pr-8 py-2 rounded border-gray-300">
                                <?php $__empty_1 = true; $__currentLoopData = $daftarTahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($thn); ?>" <?php echo e($tahun == $thn ? 'selected' : ''); ?>><?php echo e($thn); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <option value="<?php echo e(date('Y')); ?>"><?php echo e(date('Y')); ?></option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                            <select name="bulan" class="w-full pl-4 pr-8 py-2 rounded border-gray-300">
                                <?php for($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e($bulan == $i ? 'selected' : ''); ?>><?php echo e(\Carbon\Carbon::create()->month($i)->format('F')); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">Tampilkan</button>
                        <a href="<?php echo e(route('transaksi.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white p-2 rounded text-center transition">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-4">
                <p class="text-sm text-gray-600">Jumlah Rekap Penjualan</p>
                <p class="text-2xl font-bold text-blue-600"><?php echo e($jumlahTransaksi); ?></p>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <p class="text-sm text-gray-600">Total Pemasukan pada <?php echo e($judulPeriode); ?></p>
                <p class="text-2xl font-bold text-green-600">Rp <?php echo e(number_format($totalPemasukan, 0, ',', '.')); ?></p>
            </div>
        </div>

        
        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-bold uppercase">Tanggal</th>
                            <th class="py-3 px-4 text-left text-sm font-bold uppercase">Keterangan</th>
                            <th class="py-3 px-4 text-right text-sm font-bold uppercase">Total Pemasukan</th>
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase">Bukti</th>
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase">Aksi</th>
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase w-24">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tbody x-data="{ open: false }">
                                
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4"><?php echo e(\Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y')); ?></td>
                                    <td class="py-3 px-4 text-gray-600"><?php echo e($transaction->keterangan ?: '-'); ?></td>
                                    <td class="py-3 px-4 text-right font-bold text-green-600">Rp <?php echo e(number_format($transaction->total_penjualan, 0, ',', '.')); ?></td>
                                    <td class="py-3 px-4 text-center">
                                        <?php if($transaction->bukti): ?>
                                            <button type="button" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-md" data-img-url="<?php echo e(asset($transaction->bukti)); ?>">Lihat</button>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <form action="<?php echo e(route('transaksi.destroy', $transaction->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus rekap ini?');">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-3 px-4 text-center cursor-pointer" @click="open = !open">
                                        <?php if(!empty($transaction->items_detail)): ?>
                                            <button class="text-blue-500 hover:text-blue-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                
                                
                                

                                
                                <tr x-show="open" x-transition class="bg-gray-50" style="display: none;">
                                    <td colspan="6" class="p-0">
                                        <div class="p-4">
                                            <?php
                                                // Decode string JSON. Mungkin ada double encoding.
                                                $items = json_decode($transaction->items_detail);
                                                if (is_string($items)) {
                                                    // Jika masih string, decode lagi untuk dapat array.
                                                    $items = json_decode($items, true);
                                                } else {
                                                    // Jika decode pertama berhasil, jadikan array.
                                                    $items = (array) $items;
                                                }
                                            ?>

                                            
                                            <?php if(is_array($items) && !empty($items) && isset($items[0])): ?>
                                                <h4 class="font-bold text-lg mb-2 text-gray-700">Detail Barang Terjual:</h4>
                                                
                                                <table class="w-full text-sm mt-2">
                                                    <thead class="bg-gray-200">
                                                        <tr>
                                                            <th class="py-2 px-3 text-left font-semibold text-gray-600">Nama Barang</th>
                                                            <th class="py-2 px-3 text-center font-semibold text-gray-600">Qty</th>
                                                            <th class="py-2 px-3 text-right font-semibold text-gray-600">Harga Satuan</th>
                                                            <th class="py-2 px-3 text-right font-semibold text-gray-600">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            
                                                            <?php $item = (array) $item; ?>
                                                            <tr class="border-b border-gray-200 last:border-b-0">
                                                                <td class="py-3 px-3"><?php echo e($item['name'] ?? 'N/A'); ?></td>
                                                                <td class="py-3 px-3 text-center"><?php echo e($item['qty'] ?? 'N/A'); ?></td>
                                                                <td class="py-3 px-3 text-right">Rp <?php echo e(number_format($item['price'] ?? 0, 0, ',', '.')); ?></td>
                                                                <td class="py-3 px-3 text-right font-medium">Rp <?php echo e(number_format($item['subtotal'] ?? 0, 0, ',', '.')); ?></td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <p class="text-gray-500 italic p-4">Tidak ada detail barang untuk transaksi ini.</p>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-500">
                                    <p>Belum ada data transaksi penjualan pada periode ini.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($transactions->hasPages()): ?>
                <div class="p-6 border-t">
                    <?php echo e($transactions->appends(request()->query())->links()); ?>

                </div>
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
<?php /**PATH C:\tpku-finance-baru\resources\views/transaksi/index.blade.php ENDPATH**/ ?>