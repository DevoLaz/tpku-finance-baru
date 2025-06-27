<x-app-layout>
    @section('title', 'Tambah Pengadaan')

    <div class="flex-1 p-8 overflow-y-auto">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Tambah Pengadaan Barang</h1>
                    <p class="text-green-100">Tambah pembelian barang baru untuk menambah stok</p>
                </div>
                <a href="{{ route('pengadaan.index') }}" 
                   class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <!-- Form -->
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
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Pilih Barang -->
                    <div>
                        <label for="barang_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Barang *</label>
                        <select name="barang_id" id="barang_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" 
                                required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" 
                                        {{-- INI PERBAIKAN UTAMANYA --}}
                                        data-harga="{{ $barang->harga_jual }}" 
                                        {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                    [{{ $barang->kode_barang }}] {{ $barang->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilih Supplier -->
                    <div>
                        <label for="supplier_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Supplier *</label>
                        <select name="supplier_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sisanya tetap sama -->
                    <div>
                        <label for="tanggal_pembelian" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pembelian *</label>
                        <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label for="no_invoice" class="block text-sm font-bold text-gray-700 mb-2">No Invoice *</label>
                        <input type="text" name="no_invoice" value="{{ old('no_invoice') }}" placeholder="INV-2025-001" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label for="jumlah_masuk" class="block text-sm font-bold text-gray-700 mb-2">Jumlah Masuk *</label>
                        <input type="number" name="jumlah_masuk" id="jumlah_masuk" value="{{ old('jumlah_masuk') }}" min="1" placeholder="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label for="harga_beli" class="block text-sm font-bold text-gray-700 mb-2">Harga Beli (per unit) *</label>
                        <input type="number" name="harga_beli" id="harga_beli" value="{{ old('harga_beli') }}" min="0" step="0.01"  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed" readonly>
                    </div>
                </div>

                <!-- Total Harga -->
                <div class="bg-blue-50 p-6 rounded-lg border-2 border-blue-200">
                    <label class="block text-sm font-bold text-blue-800 mb-2">Total Harga</label>
                    <input type="text" id="total_harga_display" value="Rp 0" class="w-full px-4 py-3 bg-blue-100 border border-blue-300 rounded-lg text-blue-900 font-bold text-xl" readonly>
                    <input type="hidden" name="total_harga" id="total_harga" value="0">
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3" placeholder="Catatan tambahan..." class="w-full px-4 py-3 border border-gray-300 rounded-lg">{{ old('keterangan') }}</textarea>
                </div>

                <!-- Tombol -->
                <div class="flex gap-4 pt-6 border-t">
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg font-semibold">Simpan Pengadaan</button>
                    <a href="{{ route('pengadaan.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Pastikan listener dipasang setelah halaman siap
            document.addEventListener('DOMContentLoaded', function() {
                // Ambil elemen-elemen yang dibutuhkan
                const barangSelect = document.getElementById('barang_id');
                const jumlahInput = document.getElementById('jumlah_masuk');
                const hargaInput = document.getElementById('harga_beli');

                // Fungsi untuk menghitung total
                const hitungTotal = () => {
                    const jumlah = parseFloat(jumlahInput.value) || 0;
                    const harga = parseFloat(hargaInput.value) || 0;
                    const total = jumlah * harga;

                    document.getElementById('total_harga_display').value = 'Rp ' + total.toLocaleString('id-ID');
                    document.getElementById('total_harga').value = total;
                };

                // Listener saat pilihan barang berubah
                barangSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const harga = selectedOption.dataset.harga || 0;
                    hargaInput.value = harga;
                    hitungTotal(); // Langsung hitung total setelah harga di-update
                });

                // Listener saat jumlah atau harga diketik manual
                jumlahInput.addEventListener('input', hitungTotal);
                hargaInput.addEventListener('input', hitungTotal);
            });
        </script>
    @endpush
</x-app-layout>