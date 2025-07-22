<?php echo csrf_field(); ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
    
    <div>
        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
        
        <input type="text" name="nama_lengkap" id="nama_lengkap" value="<?php echo e(old('nama', $karyawan->nama ?? '')); ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300" required placeholder="Contoh: Adam Sholihuddin">
    </div>

    
    <div>
        <label for="posisi" class="block text-sm font-semibold text-gray-700 mb-2">Posisi *</label>
        
        <input type="text" name="jabatan" id="jabatan" value="<?php echo e(old('posisi', $karyawan->posisi ?? '')); ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300" required placeholder="Contoh: Manajer Keuangan">
    </div>

    
    <div>
        <label for="tanggal_masuk" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Masuk *</label>
        
        <input type="date" name="tanggal_bergabung" id="tanggal_bergabung" value="<?php echo e(old('tanggal_masuk', isset($karyawan) ? optional($karyawan->tanggal_masuk)->format('Y-m-d') : '')); ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300" required>
    </div>

    
    <div>
        <label for="gaji_pokok" class="block text-sm font-semibold text-gray-700 mb-2">Gaji Pokok *</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4"><span class="text-gray-500">Rp</span></div>
            
            <input type="number" name="gaji_pokok_default" id="gaji_pokok_default" value="<?php echo e(old('gaji_pokok', $karyawan->gaji_pokok ?? '')); ?>" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300" required placeholder="5000000">
        </div>
    </div>
</div>


<div>
    <label for="status_karyawan" class="block text-sm font-semibold text-gray-700 mb-2">Status Karyawan *</label>
    <select name="status_karyawan" id="status_karyawan" class="w-full px-4 py-2.5 rounded-lg border border-gray-300" required>
        <option value="kontrak" <?php echo e(old('status_karyawan', $karyawan->status_karyawan ?? '') == 'kontrak' ? 'selected' : ''); ?>>Kontrak</option>
        <option value="tetap" <?php echo e(old('status_karyawan', $karyawan->status_karyawan ?? '') == 'tetap' ? 'selected' : ''); ?>>Tetap</option>
        <option value="harian" <?php echo e(old('status_karyawan', $karyawan->status_karyawan ?? '') == 'harian' ? 'selected' : ''); ?>>Harian</option>
    </select>
</div>


<div>
    <label for="nik" class="block text-sm font-semibold text-gray-700 mb-2">NIK</label>
    <input type="text" name="nik" id="nik" value="<?php echo e(old('nik', $karyawan->nik ?? '')); ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300" placeholder="Opsional">
</div>


<div>
    <label for="npwp" class="block text-sm font-semibold text-gray-700 mb-2">NPWP</label>
    <input type="text" name="npwp" id="npwp" value="<?php echo e(old('npwp', $karyawan->npwp ?? '')); ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300" placeholder="Opsional">
</div>


<?php if(isset($karyawan) && $karyawan->exists): ?>
<div>
    <label for="aktif" class="block text-sm font-semibold text-gray-700 mb-2">Status Aktif *</label>
    <select name="aktif" id="aktif" class="w-full px-4 py-2.5 rounded-lg border border-gray-300" required>
        <option value="1" <?php echo e(old('aktif', $karyawan->aktif) == 1 ? 'selected' : ''); ?>>Aktif</option>
        <option value="0" <?php echo e(old('aktif', $karyawan->aktif) == 0 ? 'selected' : ''); ?>>Tidak Aktif</option>
    </select>
</div>
<?php endif; ?>

<div class="mt-10 pt-6 border-t flex justify-end gap-4">
    <a href="<?php echo e(route('karyawan.index')); ?>" class="px-8 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg">Batal</a>
    <button type="submit" class="px-8 py-3 bg-[#173720] text-white font-semibold rounded-lg flex items-center gap-2">
       <i data-lucide="save" class="w-5 h-5"></i>
       Simpan Data
    </button>
</div>
<?php /**PATH C:\tpku-finance-baru\resources\views/karyawan/_form.blade.php ENDPATH**/ ?>