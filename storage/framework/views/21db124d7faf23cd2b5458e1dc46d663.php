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
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Laporan Neraca</h1>
                    
                    <p class="text-indigo-100">Laporan untuk periode yang berakhir pada <?php echo e(\Carbon\Carbon::parse($tanggalLaporan)->format('d F Y')); ?></p>
                </div>
                
                <div class="relative">
                    <?php if (isset($component)) { $__componentOriginaldf8083d4a852c446488d8d384bbc7cbe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown','data' => ['align' => 'right','width' => '48']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => '48']); ?>
                         <?php $__env->slot('trigger', null, []); ?> 
                            <button class="inline-flex items-center px-4 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold text-sm rounded-lg transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                <span>Ekspor Laporan</span>
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                         <?php $__env->endSlot(); ?>

                         <?php $__env->slot('content', null, []); ?> 
                            <?php if (isset($component)) { $__componentOriginal68cb1971a2b92c9735f83359058f7108 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal68cb1971a2b92c9735f83359058f7108 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => route('laporan.neraca.exportPdf', request()->query())]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('laporan.neraca.exportPdf', request()->query()))]); ?>
                                <span>Ekspor PDF</span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $attributes = $__attributesOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__attributesOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $component = $__componentOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__componentOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal68cb1971a2b92c9735f83359058f7108 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal68cb1971a2b92c9735f83359058f7108 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => route('laporan.neraca.exportExcel', request()->query())]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('laporan.neraca.exportExcel', request()->query()))]); ?>
                                <span>Ekspor Excel</span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $attributes = $__attributesOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__attributesOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $component = $__componentOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__componentOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
                         <?php $__env->endSlot(); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe)): ?>
<?php $attributes = $__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe; ?>
<?php unset($__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldf8083d4a852c446488d8d384bbc7cbe)): ?>
<?php $component = $__componentOriginaldf8083d4a852c446488d8d384bbc7cbe; ?>
<?php unset($__componentOriginaldf8083d4a852c446488d8d384bbc7cbe); ?>
<?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="<?php echo e(route('laporan.neraca')); ?>" class="flex items-end gap-4">
                <div>
                    <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">Pilih Periode Bulan</label>
                    <select name="bulan" id="bulan" class="w-full px-4 py-2 rounded-lg border-gray-300">
                        <?php for($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo e($m); ?>" <?php echo e($bulan == $m ? 'selected' : ''); ?>>
                                <?php echo e(\Carbon\Carbon::create()->month($m)->format('F')); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Pilih Periode Tahun</label>
                    <select name="tahun" id="tahun" class="w-full px-4 py-2 rounded-lg border-gray-300">
                        <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?php echo e($y); ?>" <?php echo e($tahun == $y ? 'selected' : ''); ?>>
                                <?php echo e($y); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">Tampilkan</button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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
                        Neraca Tidak Seimbang! Selisih: Rp <?php echo e(number_format(abs($totalAset - $totalLiabilitasEkuitas), 0, ',', '.')); ?>

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
<?php endif; ?><?php /**PATH C:\tpku-finance-baru\resources\views/laporan/neraca.blade.php ENDPATH**/ ?>