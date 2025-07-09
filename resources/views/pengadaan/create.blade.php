<x-app-layout>
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

            <form action="{{ route('pengadaan.store') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Bagian Header Invoice --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 border rounded-lg">
                    <div>
                        <label for="no_invoice" class="block text-sm font-bold text-gray-700 mb-2">No Invoice *</label>
                        <input type="text" name="no_invoice" value="{{ old('no_invoice') }}" placeholder="Contoh: INV-2025-001" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
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
                    <div class="md:col-span-3">
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
                                        <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Jumlah --}}
                            <div class="col-span-4 md:col-span-2">
                                <label class="text-xs font-medium text-gray-600">Jumlah</label>
                                <input type="number" :name="`items[${index}][jumlah_masuk]`" x-model.number="item.jumlah_masuk" @input="calculateTotals" min="1" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            {{-- Harga --}}
                            <div class="col-span-4 md:col-span-2">
                                <label class="text-xs font-medium text-gray-600">Harga Satuan</label>
                                <input type="text" :value="formatRupiah(item.harga_beli)" class="w-full mt-1 px-3 py-2 border bg-gray-100 rounded-md" readonly>
                            </div>
                            {{-- Total --}}
                            <div class="col-span-4 md:col-span-3">
                                <label class="text-xs font-medium text-gray-600">Subtotal</label>
                                <input type="text" :value="formatRupiah(item.total_harga)" class="w-full mt-1 px-3 py-2 border bg-gray-100 rounded-md font-semibold text-right" readonly>
                            </div>
                            {{-- Tombol Hapus --}}
                            <div class="col-span-12 md:col-span-1 flex items-end">
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="mt-1 p-2 text-red-500 hover:bg-red-100 rounded-full">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Tombol Tambah Item & Grand Total --}}
                <div class="flex justify-between items-center pt-4">
                    <button type="button" @click="addItem" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold flex items-center gap-2">
                        <i data-lucide="plus" class="w-5 h-5"></i>
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
        <script>
            function formPengadaanData() {
                return {
                    items: [{ barang_id: '', jumlah_masuk: 1, harga_beli: 0, total_harga: 0 }],
                    barangsData: [],
                    grandTotal: 0,

                    initForm(barangs) {
                        this.barangsData = barangs;
                        this.calculateTotals();
                    },
                    
                    addItem() {
                        this.items.push({ barang_id: '', jumlah_masuk: 1, harga_beli: 0, total_harga: 0 });
                    },

                    removeItem(index) {
                        this.items.splice(index, 1);
                        this.calculateTotals();
                    },

                    updatePrice(index) {
                        const selectedBarangId = this.items[index].barang_id;
                        const barang = this.barangsData.find(b => b.id == selectedBarangId);
                        this.items[index].harga_beli = barang ? parseFloat(barang.harga_jual) : 0;
                        this.calculateTotals();
                    },

                    calculateTotals() {
                        let total = 0;
                        this.items.forEach(item => {
                            item.total_harga = item.jumlah_masuk * item.harga_beli;
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