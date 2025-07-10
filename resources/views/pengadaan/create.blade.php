<x-app-layout>
    {{-- Menggunakan Alpine.js untuk membuat form dinamis --}}
    <div 
        class="p-8"
        x-data="formPengadaanData()" 
        x-init="initForm(@json($barangs))"
    >
        
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white mb-2">Tambah Pengadaan (Multi-Barang)</h1>
            <p class="text-green-100">Catat satu invoice dengan beberapa barang sekaligus.</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                    <p class="font-bold">Ada kesalahan:</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Menambahkan enctype untuk upload file --}}
            <form action="{{ route('pengadaan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                {{-- Bagian Header Invoice --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border rounded-lg">
                    <div>
                        <label for="no_invoice" class="block text-sm font-bold text-gray-700 mb-2">No Invoice *</label>
                        <input type="text" name="no_invoice" value="{{ old('no_invoice') }}" placeholder="Contoh: INV-2025-001" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        {{-- PERBAIKAN 1: Menyesuaikan nama input tanggal --}}
                        <label for="tanggal_pembelian" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pembelian *</label>
                        <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label for="supplier_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Supplier *</label>
                        <select name="supplier_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="bukti" class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti (Opsional)</label>
                        <input type="file" name="bukti" id="bukti" class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        @error('bukti') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-sm font-bold text-gray-700 mb-2">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="2" placeholder="Catatan untuk invoice ini..." class="w-full px-4 py-3 border border-gray-300 rounded-lg">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                {{-- Bagian Item Barang Dinamis --}}
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Barang</h3>
                    <template x-for="(item, index) in items" :key="index">
                        <div class="grid grid-cols-12 gap-4 items-center p-3 border rounded-lg hover:bg-gray-50">
                            {{-- Pilih Barang --}}
                            <div class="col-span-12 md:col-span-4">
                                <label class="text-xs font-medium text-gray-600">Barang</label>
                                <select :name="`items[${index}][barang_id]`" x-model="item.barang_id" @change="updatePrice(index)" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->id }}" data-harga="{{ $barang->harga_jual }}">{{ $barang->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Jumlah --}}
                            <div class="col-span-4 md:col-span-2">
                                <label class="text-xs font-medium text-gray-600">Jumlah</label>
                                {{-- PERBAIKAN 2: Menyesuaikan nama input jumlah --}}
                                <input type="number" :name="`items[${index}][jumlah_masuk]`" x-model.number="item.jumlah" @input="calculateTotals" min="1" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            {{-- Harga --}}
                            <div class="col-span-4 md:col-span-2">
                                <label class="text-xs font-medium text-gray-600">Harga Beli Satuan</label>
                                {{-- PERBAIKAN 3: Menyesuaikan nama input harga --}}
                                <input type="number" :name="`items[${index}][harga_beli]`" x-model.number="item.harga" @input="calculateTotals" min="0" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            {{-- Total --}}
                            <div class="col-span-4 md:col-span-3">
                                <label class="text-xs font-medium text-gray-600">Subtotal</label>
                                <input type="text" :value="formatRupiah(item.total_harga)" class="w-full mt-1 px-3 py-2 border bg-gray-100 rounded-md font-semibold text-right" readonly>
                            </div>
                            {{-- Tombol Hapus --}}
                            <div class="col-span-12 md:col-span-1 flex items-end">
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="mt-1 p-2 text-red-500 hover:bg-red-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Tombol Tambah Item & Grand Total --}}
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
                    <a href="{{ route('pengadaan.index') }}" class="px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold">Batal</a>
                    <button type="submit" class="flex-1 px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg font-semibold">
                        Simpan Semua Pengadaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        {{-- Menambahkan Alpine.js CDN agar fungsionalitasnya berjalan --}}
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
    @endpush
</x-app-layout>
