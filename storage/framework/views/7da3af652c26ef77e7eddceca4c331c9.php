<?php echo csrf_field(); ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <div class="md:col-span-2">
        <label for="nama_aset" class="block text-sm font-bold text-gray-700 mb-2">Nama Aset / Modal *</label>
        <input type="text" name="nama_aset" value="<?php echo e(old('nama_aset', $aset->nama_aset ?? '')); ?>" placeholder="Contoh: Modal Awal, Mesin Jahit, Komputer Kantor" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
    </div>

    
    <div>
        <label for="kategori" class="block text-sm font-bold text-gray-700 mb-2">Kategori Aset *</label>
        <input type="text" name="kategori" value="<?php echo e(old('kategori', $aset->kategori ?? '')); ?>" placeholder="Contoh: Kas, Peralatan, Kendaraan" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
    </div>

    
    <div>
        <label for="tanggal_perolehan" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Diperoleh *</label>
        
        <input type="date" name="tanggal_perolehan" value="<?php echo e(old('tanggal_perolehan', isset($aset) ? optional($aset->tanggal_perolehan)->format('Y-m-d') : date('Y-m-d'))); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
    </div>

    
    <div>
        <label for="harga_perolehan" class="block text-sm font-bold text-gray-700 mb-2">Nilai / Harga (Rp) *</label>
        <input type="number" name="harga_perolehan" value="<?php echo e(old('harga_perolehan', $aset->harga_perolehan ?? '')); ?>" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
    </div>

    
    <div>
        <label for="masa_manfaat" class="block text-sm font-bold text-gray-700 mb-2">Masa Manfaat (Tahun) *</label>
        
        <input type="number" name="masa_manfaat" value="<?php echo e(old('masa_manfaat', $aset->masa_manfaat ?? '0')); ?>" min="0" placeholder="Isi 0 jika Kas/Modal" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
    </div>

    
    <div>
        <label for="nilai_residu" class="block text-sm font-bold text-gray-700 mb-2">Nilai Residu/Sisa (Rp)</label>
        <input type="number" name="nilai_residu" value="<?php echo e(old('nilai_residu', $aset->nilai_residu ?? 0)); ?>" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
    </div>

    
    <div class="md:col-span-2">
        <label for="bukti" class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti Pembelian (Opsional)</label>
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
        <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
        <textarea name="deskripsi" rows="3" placeholder="Catatan tambahan..." class="w-full px-4 py-3 border border-gray-300 rounded-lg"><?php echo e(old('deskripsi', $aset->deskripsi ?? '')); ?></textarea>
    </div>
</div>
<div class="mt-8 flex justify-end gap-4">
    <a href="<?php echo e(route('aset-tetap.index')); ?>" class="px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold transition-colors">Batal</a>
    <button type="submit" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors">Simpan Aset</button>
</div>
<?php /**PATH C:\tpku-finance-baru\resources\views/aset-tetap/_form.blade.php ENDPATH**/ ?>