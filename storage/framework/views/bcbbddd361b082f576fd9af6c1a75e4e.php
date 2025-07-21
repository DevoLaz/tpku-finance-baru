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
    <div class="p-8" x-data="pengadaanForm()">
        
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Tambah Pengadaan (Multi-Barang)</h1>
                    <p class="text-gray-600">Catat satu invoice dengan beberapa barang sekaligus.</p>
                </div>
                
                <a href="<?php echo e(route('barangs.fetchApi')); ?>" class="px-5 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold flex items-center gap-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M3 21v-5h5"/></svg>
                    <span>Sinkronkan Barang</span>
                </a>
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

        
        <form action="<?php echo e(route('pengadaan.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="bg-white rounded-lg shadow-md p-8">
                
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div>
                        <label for="no_invoice" class="block text-sm font-medium text-gray-700 mb-1">No Invoice <span class="text-red-500">*</span></label>
                        <input type="text" id="no_invoice" name="no_invoice" value="<?php echo e(old('no_invoice')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Contoh: INV-2025-001" required>
                        <?php $__errorArgs = ['no_invoice'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label for="tanggal_pembelian" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembelian <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal_pembelian" name="tanggal_pembelian" value="<?php echo e(old('tanggal_pembelian', date('Y-m-d'))); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        <?php $__errorArgs = ['tanggal_pembelian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Supplier <span class="text-red-500">*</span></label>
                        <select id="supplier_id" name="supplier_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            <option value="">-- Pilih Supplier --</option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($supplier->id); ?>" <?php echo e(old('supplier_id') == $supplier->id ? 'selected' : ''); ?>><?php echo e($supplier->nama_supplier); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label for="bukti" class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti (Opsional)</label>
                        <input type="file" id="bukti" name="bukti" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        <?php $__errorArgs = ['bukti'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="mb-8">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                    <textarea id="keterangan" name="keterangan" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Catatan tambahan untuk transaksi ini..."><?php echo e(old('keterangan')); ?></textarea>
                </div>

                
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Detail Barang</h3>
                <div class="space-y-4">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="grid grid-cols-12 gap-4 items-center p-3 bg-gray-50 rounded-lg">
                            
                            <div class="col-span-5">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Pilih Barang</label>
                                <select :name="`items[${index}][barang_id]`" class="w-full px-3 py-2 border border-gray-300 rounded-md" x-model.number="item.barang_id" required>
                                    <option value="">-- Pilih Barang --</option>
                                    <?php $__currentLoopData = $barangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($barang->id); ?>"><?php echo e($barang->nama); ?> (<?php echo e($barang->kode_barang); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah</label>
                                <input type="number" :name="`items[${index}][jumlah_masuk]`" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Qty" x-model.number="item.jumlah" @input="calculateSubtotal(index)" min="1" required>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Harga Beli Satuan</label>
                                <input type="number" :name="`items[${index}][harga_beli]`" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Harga" x-model.number="item.harga" @input="calculateSubtotal(index)" min="0" required>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Subtotal</label>
                                <p class="w-full px-3 py-2 bg-gray-100 rounded-md text-right" x-text="formatRupiah(item.subtotal)"></p>
                            </div>
                            
                            <div class="col-span-1 flex items-end">
                                <button type="button" @click="removeItem(index)" class="mt-4 p-2 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-full transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                
                <div class="flex justify-between items-center mt-6 pt-6 border-t">
                    <button type="button" @click="addItem()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        <span>Tambah Barang</span>
                    </button>
                    <div class="text-right">
                        <p class="text-gray-600 font-medium">Grand Total</p>
                        <p class="text-3xl font-bold text-gray-800" x-text="formatRupiah(grandTotal)"></p>
                    </div>
                </div>

                
                <div class="mt-8 text-right">
                    <button type="submit" class="px-10 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold text-lg">
                        Simpan Transaksi Pengadaan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function pengadaanForm() {
            return {
                items: [{
                    barang_id: '',
                    jumlah: 1,
                    harga: 0,
                    subtotal: 0
                }],
                grandTotal: 0,
                addItem() {
                    this.items.push({
                        barang_id: '',
                        jumlah: 1,
                        harga: 0,
                        subtotal: 0
                    });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculateGrandTotal();
                },
                calculateSubtotal(index) {
                    let item = this.items[index];
                    item.subtotal = (item.jumlah || 0) * (item.harga || 0);
                    this.calculateGrandTotal();
                },
                calculateGrandTotal() {
                    this.grandTotal = this.items.reduce((total, item) => total + item.subtotal, 0);
                },
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
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