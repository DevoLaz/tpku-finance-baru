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
    <div 
        x-data="{ 
            showModal: false, 
            editingAset: {},
            formAction: ''
        }"
        x-on:keydown.escape.window="showModal = false"
        class="p-8"
    >
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Daftar Aset Tetap</h1>
                    <p class="text-green-100">Kelola semua aset tetap dan modal perusahaan.</p>
                </div>
                <a href="<?php echo e(route('aset-tetap.create')); ?>" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    <span>Tambah Aset</span>
                </a>
            </div>
        </div>

        <!-- Session Messages -->
        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#173720] text-white">
                            <th class="py-3 px-4 text-left text-sm font-bold uppercase">Nama Aset</th>
                            <th class="py-3 px-4 text-right text-sm font-bold uppercase">Harga Perolehan</th>
                            <th class="py-3 px-4 text-right text-sm font-bold uppercase">Akm. Penyusutan</th>
                            <th class="py-3 px-4 text-right text-sm font-bold uppercase">Nilai Buku</th>
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $asetTetaps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <p class="font-medium text-gray-900"><?php echo e($aset->nama_aset); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo e($aset->kategori ?? 'Tidak ada kategori'); ?></p>
                                </td>
                                <td class="py-3 px-4 text-right">Rp <?php echo e(number_format($aset->harga_perolehan, 0, ',', '.')); ?></td>
                                <td class="py-3 px-4 text-right text-red-600">(Rp <?php echo e(number_format($aset->akumulasi_penyusutan, 0, ',', '.')); ?>)</td>
                                <td class="py-3 px-4 text-right font-bold">Rp <?php echo e(number_format($aset->nilai_buku, 0, ',', '.')); ?></td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <?php if($aset->bukti): ?>
                                            <button
                                                type="button"
                                                
                                                data-img-url="<?php echo e(asset($aset->bukti)); ?>"
                                                class="p-2 bg-blue-200 hover:bg-blue-300 text-blue-700 rounded-lg transition-colors" title="Lihat Bukti">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </button>
                                        <?php endif; ?>
                                        <button 
                                            type="button" 
                                            x-on:click="
                                                showModal = true;
                                                editingAset = <?php echo e($aset->toJson()); ?>;
                                                formAction = `<?php echo e(route('aset-tetap.update', $aset->id)); ?>`;
                                            "
                                            class="p-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors" title="Lihat & Edit Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </button>
                                        <form action="<?php echo e(route('aset-tetap.destroy', $aset)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus aset ini?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="p-2 bg-red-200 hover:bg-red-300 text-red-700 rounded-lg transition-colors" title="Hapus Aset">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-12 text-gray-500">
                                    <p>Belum ada data aset.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($asetTetaps->hasPages()): ?>
                <div class="p-6 border-t border-gray-200">
                    <?php echo e($asetTetaps->links()); ?>

                </div>
            <?php endif; ?>
        </div>

        
        <div 
            x-show="showModal" 
            x-transition
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
            style="display: none;"
        >
            <div 
                x-show="showModal"
                x-transition
                x-on:click.outside="showModal = false"
                class="bg-white rounded-lg shadow-xl w-full max-w-2xl"
            >
                <form :action="formAction" method="POST" class="p-6" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="flex justify-between items-center pb-3 border-b">
                        <h3 class="text-xl font-bold text-gray-900">Edit Aset: <span x-text="editingAset.nama_aset"></span></h3>
                        <button type="button" x-on:click="showModal = false" class="text-gray-400 hover:text-gray-600">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>

                    <div class="mt-4 space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Aset</label>
                                <input type="text" name="nama_aset" x-model="editingAset.nama_aset" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Perolehan</label>
                                <input type="date" name="tanggal_perolehan" :value="editingAset.tanggal_perolehan ? editingAset.tanggal_perolehan.substring(0, 10) : ''" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Harga Perolehan</label>
                                <input type="number" name="harga_perolehan" x-model="editingAset.harga_perolehan" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Masa Manfaat (Tahun)</label>
                                <input type="number" name="masa_manfaat" x-model="editingAset.masa_manfaat" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nilai Residu</label>
                                <input type="number" name="nilai_residu" x-model="editingAset.nilai_residu" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Bukti Saat Ini</label>
                                <template x-if="editingAset.bukti">
                                    
                                    <img :src="'/' + editingAset.bukti" class="w-32 h-32 object-cover rounded-lg border">
                                </template>
                                <template x-if="!editingAset.bukti">
                                    <p class="text-sm text-gray-500">Tidak ada bukti.</p>
                                </template>
                                <label class="block text-sm font-bold text-gray-700 mb-2 mt-2">Ganti Bukti (Opsional)</label>
                                <input type="file" name="bukti" class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t flex justify-end gap-3">
                        <button type="button" x-on:click="showModal = false" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-semibold">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
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
<?php endif; ?><?php /**PATH C:\tpku-finance-baru\resources\views/aset-tetap/index.blade.php ENDPATH**/ ?>