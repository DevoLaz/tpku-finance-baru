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
        class="p-8"
        x-data="formPengadaanData()" 
        x-init="initForm(<?php echo json_encode($barangs, 15, 512) ?>)"
    >
        
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white mb-2">Tambah Pengadaan (Multi-Barang)</h1>
            <p class="text-green-100">Catat satu invoice dengan beberapa barang sekaligus.</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <?php if($errors->any()): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                    <p class="font-bold">Ada kesalahan:</p>
                    <ul class="mt-2 list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            
            <form action="<?php echo e(route('pengadaan.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border rounded-lg">
                    <div>
                        <label for="no_invoice" class="block text-sm font-bold text-gray-700 mb-2">No Invoice *</label>
                        <input type="text" name="no_invoice" value="<?php echo e(old('no_invoice')); ?>" placeholder="Contoh: INV-2025-001" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        
                        <label for="tanggal_pembelian" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pembelian *</label>
                        <input type="date" name="tanggal_pembelian" value="<?php echo e(old('tanggal_pembelian', date('Y-m-d'))); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label for="supplier_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Supplier *</label>
                        <select name="supplier_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                            <option value="">-- Pilih Supplier --</option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($supplier->id); ?>" <?php echo e(old('supplier_id') == $supplier->id ? 'selected' : ''); ?>>
                                    <?php echo e($supplier->nama_supplier); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label for="bukti" class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti (Opsional)</label>
                        <input type="file" name="bukti" id="bukti" class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        <?php $__errorArgs = ['bukti'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-sm font-bold text-gray-700 mb-2">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="2" placeholder="Catatan untuk invoice ini..." class="w-full px-4 py-3 border border-gray-300 rounded-lg"><?php echo e(old('keterangan')); ?></textarea>
                    </div>
                </div>

                
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Barang</h3>
                    <template x-for="(item, index) in items" :key="index">
                        <div class="grid grid-cols-12 gap-4 items-center p-3 border rounded-lg hover:bg-gray-50">
                            
                            <div class="col-span-12 md:col-span-4">
                                <label class="text-xs font-medium text-gray-600">Barang</label>
                                <select :name="`items[${index}][barang_id]`" x-model="item.barang_id" @change="updatePrice(index)" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md" required>
                                    <option value="">-- Pilih Barang --</option>
                                    <?php $__currentLoopData = $barangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($barang->id); ?>" data-harga="<?php echo e($barang->harga_jual); ?>"><?php echo e($barang->nama); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div class="col-span-4 md:col-span-2">
                                <label class="text-xs font-medium text-gray-600">Jumlah</label>
                                
                                <input type="number" :name="`items[${index}][jumlah_masuk]`" x-model.number="item.jumlah" @input="calculateTotals" min="1" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            
                            <div class="col-span-4 md:col-span-2">
                                <label class="text-xs font-medium text-gray-600">Harga Beli Satuan</label>
                                
                                <input type="number" :name="`items[${index}][harga_beli]`" x-model.number="item.harga" @input="calculateTotals" min="0" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            
                            <div class="col-span-4 md:col-span-3">
                                <label class="text-xs font-medium text-gray-600">Subtotal</label>
                                <input type="text" :value="formatRupiah(item.total_harga)" class="w-full mt-1 px-3 py-2 border bg-gray-100 rounded-md font-semibold text-right" readonly>
                            </div>
                            
                            <div class="col-span-12 md:col-span-1 flex items-end">
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="mt-1 p-2 text-red-500 hover:bg-red-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                
                <div class="flex justify-between items-center pt-4">
                    <button type="button" @click="addItem" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                        Tambah Barang
                    </button>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Grand Total</p>
                        <p class="text-2xl font-bold text-green-700" x-text="formatRupiah(grandTotal)"></p>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t mt-6">
                    <a href="<?php echo e(route('pengadaan.index')); ?>" class="px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold">Batal</a>
                    <button type="submit" class="flex-1 px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg font-semibold">
                        Simpan Semua Pengadaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        
        <script src="//unpkg.com/alpinejs" defer></script>
        <script>
            function formPengadaanData() {
                return {
                    items: [{ barang_id: '', jumlah: 1, harga: 0, total_harga: 0 }],
                    barangsData: [],
                    grandTotal: 0,

                    initForm(barangs) {
                        this.barangsData = barangs;
                        this.calculateTotals();
                    },
                    
                    addItem() {
                        this.items.push({ barang_id: '', jumlah: 1, harga: 0, total_harga: 0 });
                    },

                    removeItem(index) {
                        this.items.splice(index, 1);
                        this.calculateTotals();
                    },

                    updatePrice(index) {
                        const selectElement = event.target;
                        const selectedOption = selectElement.options[selectElement.selectedIndex];
                        const harga = selectedOption.dataset.harga;
                        this.items[index].harga = harga ? parseFloat(harga) : 0;
                        this.calculateTotals();
                    },

                    calculateTotals() {
                        let total = 0;
                        this.items.forEach(item => {
                            item.total_harga = (item.jumlah || 0) * (item.harga || 0);
                            total += item.total_harga;
                        });
                        this.grandTotal = total;
                    },

                    formatRupiah(number) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
                    }
                }
            }
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
<?php endif; ?>
<?php /**PATH C:\tpku-finance-baru\resources\views/pengadaan/create.blade.php ENDPATH**/ ?>