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
    
    <div class="p-8" x-data="pengadaanForm()" x-init="init()">
        
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Tambah Pengadaan (Multi-Barang)</h1>
                    <p class="text-gray-600">Catat satu invoice dengan beberapa barang sekaligus.</p>
                </div>
                
                <a href="<?php echo e(route('barangs.fetchApi')); ?>"
                    class="px-5 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold flex items-center gap-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" />
                        <path d="M21 3v5h-5" />
                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" />
                        <path d="M3 21v-5h5" />
                    </svg>
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

        
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Daftar Pengajuan Pengadaan (dari API)</h3>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Kode Barang</th>
                            <th class="px-4 py-2">Nama Barang</th>
                            <th class="px-4 py-2">Kategori</th>
                            <th class="px-4 py-2">Jumlah Diminta</th>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="request-body" class="divide-y divide-gray-100 bg-white">
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-gray-400">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        
        <form id="pengadaanForm" action="<?php echo e(route('pengadaan.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            

            <div class="bg-white rounded-lg shadow-md p-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div>
                        <label for="no_invoice" class="block text-sm font-medium text-gray-700 mb-1">
                            No Invoice <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="no_invoice" name="no_invoice" value="<?php echo e(old('no_invoice')); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="INV-2025-001" required>
                        <?php $__errorArgs = ['no_invoice'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label for="tanggal_pembelian" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Pembelian <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_pembelian" name="tanggal_pembelian"
                            value="<?php echo e(old('tanggal_pembelian', date('Y-m-d'))); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        <?php $__errorArgs = ['tanggal_pembelian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih Supplier <span class="text-red-500">*</span>
                        </label>
                        
                        <select id="supplier_id" name="supplier_id" x-model="supplierId"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            <option value="">-- Pilih Supplier --</option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($supplier->id); ?>"
                                    <?php echo e(old('supplier_id') == $supplier->id ? 'selected' : ''); ?>>
                                    <?php echo e($supplier->nama); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label for="bukti" class="block text-sm font-medium text-gray-700 mb-1">
                            Upload Bukti (Opsional)
                        </label>
                        <input type="file" id="bukti" name="bukti"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        <?php $__errorArgs = ['bukti'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="mb-8">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                        Keterangan (Opsional)
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        placeholder="Catatan tambahan..."><?php echo e(old('keterangan')); ?></textarea>
                </div>

                
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Detail Barang</h3>
                <div class="space-y-4">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="grid grid-cols-12 gap-4 items-center p-3 bg-gray-50 rounded-lg">
                            
                            <div class="col-span-5">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Pilih Barang</label>
                                
                                <select
                                    :name="`items[${index}][barang_id]`"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md barang-select"
                                    x-model.number="item.barang_id"
                                    @change="updateItemPrice(index, $event)"
                                    :disabled="!supplierId"
                                    required>
                                    <option value="">-- Pilih Supplier Dulu --</option>
                                </select>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah</label>
                                <input type="number"
                                    :name="`items[${index}][jumlah_masuk]`"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                    placeholder="Qty"
                                    x-model.number="item.jumlah"
                                    @input="calculateSubtotal(index)"
                                    min="1" required>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Harga Beli</label>
                                <input type="number"
                                    :name="`items[${index}][harga_beli]`"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                    placeholder="Harga"
                                    x-model.number="item.harga"
                                    @input="calculateSubtotal(index)"
                                    min="0" required>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Subtotal</label>
                                <p class="w-full px-3 py-2 bg-gray-100 rounded-md text-right" x-text="formatRupiah(item.subtotal)"></p>
                            </div>
                            
                            <div class="col-span-1 flex items-end">
                                <button type="button" @click="removeItem(index)"
                                    class="mt-4 p-2 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18" />
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                        <path d="M10 11v6" /> <path d="M14 11v6" />
                                        <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                
                <div class="flex justify-between items-center mt-6 pt-6 border-t">
                    
                    <button type="button" @click="addItem()" :disabled="!supplierId"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold flex items-center gap-2 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="16" />
                            <line x1="8" y1="12" x2="16" y2="12" />
                        </svg>
                        Tambah Barang
                    </button>
                    <div class="text-right">
                        <p class="text-gray-600 font-medium">Grand Total</p>
                        <p class="text-3xl font-bold text-gray-800" x-text="formatRupiah(grandTotal)"></p>
                    </div>
                </div>

                
                <div class="mt-8 text-right">
                    <button type="submit"
                        class="px-10 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold text-lg">
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
                supplierId: null, // PENINGKATAN: Lacak supplier ID yang dipilih
                items: [{
                    barang_id: '',
                    jumlah: 1,
                    harga: 0,
                    subtotal: 0
                }],
                grandTotal: 0,

                init() {
                    // Cek jika ada old value untuk supplier, set di awal
                    const oldSupplierId = document.getElementById('supplier_id').value;
                    if (oldSupplierId) {
                        this.supplierId = oldSupplierId;
                    }
                },

                addItem() {
                    this.items.push({ barang_id: '', jumlah: 1, harga: 0, subtotal: 0 });
                    // PENINGKATAN: Setelah baris baru ditambahkan, langsung load ulang opsi barang
                    // Ini memastikan baris baru juga mendapat daftar barang yang benar
                    this.$nextTick(() => {
                        document.getElementById('supplier_id').dispatchEvent(new Event('change'));
                    });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculateGrandTotal();
                },
                calculateSubtotal(index) {
                    let it = this.items[index];
                    it.subtotal = (it.jumlah || 0) * (it.harga || 0);
                    this.calculateGrandTotal();
                },
                calculateGrandTotal() {
                    this.grandTotal = this.items.reduce((sum, it) => sum + it.subtotal, 0);
                },
                // PENINGKATAN: Fungsi baru untuk update harga saat barang dipilih
                updateItemPrice(index, event) {
                    const selectedOption = event.target.selectedOptions[0];
                    const harga = Number(selectedOption?.dataset.harga || 0);
                    this.items[index].harga = harga;
                    this.calculateSubtotal(index);
                },
                formatRupiah(num) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
                    }).format(num);
                }
            }
        }

        // Script vanilla JS untuk interaksi DOM non-Alpine
        document.addEventListener('DOMContentLoaded', () => {
            // Referensi elemen penting
            const pengadaanFormEl = document.getElementById('pengadaanForm');
            const supplierSelectEl = document.getElementById('supplier_id');
            const requestTableBody = document.getElementById('request-body');

            // Fungsi untuk memuat opsi barang ke semua select
            const loadBarangOptions = (barangs) => {
                document.querySelectorAll('.barang-select').forEach(select => {
                    // Simpan value yang sedang terpilih (jika ada)
                    const selectedValue = select.value;
                    
                    select.innerHTML = '<option value="">-- Pilih Barang --</option>';
                    if (barangs.length === 0) {
                       select.innerHTML = '<option value="">-- Tidak ada barang untuk supplier ini --</option>';
                    } else {
                        barangs.forEach(barang => {
                            const price = parseFloat(barang.harga) || 0;
                            const option = document.createElement('option');
                            option.value = barang.id;
                            option.textContent = `${barang.nama} — ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(price)}`;
                            option.dataset.harga = price;
                            select.appendChild(option);
                        });
                    }

                    // Kembalikan value yang terpilih sebelumnya
                    select.value = selectedValue;
                });
            };

            // Event listener untuk perubahan supplier
            supplierSelectEl.addEventListener('change', () => {
                const supplierId = supplierSelectEl.value;
                if (!supplierId) {
                    loadBarangOptions([]);
                    return;
                }
                fetch(`/suppliers/${supplierId}/barangs`)
                    .then(response => response.json())
                    .then(data => loadBarangOptions(data))
                    .catch(error => console.error('Gagal mengambil data barang:', error));
            });
            
            // Jika ada supplier yang sudah terpilih saat load halaman (dari old input), trigger change
            if(supplierSelectEl.value) {
                supplierSelectEl.dispatchEvent(new Event('change'));
            }

            // Fetch data pengajuan dari API eksternal
            fetch('http://143.198.91.106/api/pengajuanbarangmentah')
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(res => {
                    if (res.status !== 'success' || !Array.isArray(res.data)) {
                        throw new Error('Format API tidak sesuai.');
                    }
                    
                    requestTableBody.innerHTML = ''; // Kosongkan tabel

                    if (res.data.length === 0) {
                        requestTableBody.innerHTML = `<tr><td colspan="7" class="px-4 py-3 text-center text-gray-500">Tidak ada data pengajuan.</td></tr>`;
                        return;
                    }
                    
                    res.data.forEach((item, index) => {
                        requestTableBody.insertAdjacentHTML('beforeend', `
                            <tr>
                                <td class="px-4 py-2">${index + 1}</td>
                                <td class="px-4 py-2">${item.barang.kode_barang}</td>
                                <td class="px-4 py-2">${item.barang.nama_barang}</td>
                                <td class="px-4 py-2">${item.barang.kategori_barang}</td>
                                <td class="px-4 py-2">${item.jumlah} ${item.barang.unit_barang}</td>
                                <td class="px-4 py-2">${item.tanggal_pengadaan}</td>
                                <td class="px-4 py-2">
                                    <button type="button" class="use-request px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs font-medium"
                                        data-id="${item.id}"
                                        data-barang-id="${item.barang.id}"
                                        data-jumlah="${item.jumlah}">
                                        Gunakan
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

                    // Pasang event handler untuk setiap tombol "Gunakan"
                    document.querySelectorAll('.use-request').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const requestId = btn.dataset.id;
                            const barangId = btn.dataset.barangId;
                            const jumlah = btn.dataset.jumlah;

                            // 1. PERBAIKAN KRITIS: Buat hidden input 'request_id'
                            let hiddenInput = pengadaanFormEl.querySelector('input[name="request_id"]');
                            if (!hiddenInput) {
                                hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = 'request_id'; // Nama harus 'request_id'
                                pengadaanFormEl.prepend(hiddenInput);
                            }
                            hiddenInput.value = requestId;

                            // 2. Isi form (asumsi di baris pertama)
                            // Note: Ini akan berfungsi jika user belum memilih supplier
                            // Jika sudah, daftar barangnya mungkin perlu di-refresh
                            const firstItemSelect = pengadaanFormEl.querySelector('[name="items[0][barang_id]"]');
                            const firstItemQty = pengadaanFormEl.querySelector('[name="items[0][jumlah_masuk]"]');
                            
                            if (firstItemSelect && firstItemQty) {
                                // Set nilai pada elemen input
                                firstItemSelect.value = barangId;
                                firstItemQty.value = jumlah;
                                
                                // Dispatch event agar Alpine.js mendeteksi perubahan
                                firstItemSelect.dispatchEvent(new Event('change', { bubbles: true }));
                                firstItemQty.dispatchEvent(new Event('input', { bubbles: true }));
                            }

                            // 3. PENINGKATAN UX: Scroll ke form dan beri notifikasi
                            alert(`Request #${requestId} telah dimasukkan ke dalam form. Silakan pilih supplier dan lengkapi data lainnya.`);
                            pengadaanFormEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        });
                    });
                })
                .catch(err => {
                    console.error("Gagal memuat data pengajuan dari API:", err);
                    requestTableBody.innerHTML = `<tr><td colspan="7" class="px-4 py-3 text-center text-red-500">Gagal memuat data. Periksa koneksi atau konsol untuk detail.</td></tr>`;
                });
        });
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
<?php endif; ?><?php /**PATH C:\tpku-finance-baru\resources\views/pengadaan/create.blade.php ENDPATH**/ ?>