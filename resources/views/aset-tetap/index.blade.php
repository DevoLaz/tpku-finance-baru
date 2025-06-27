<x-app-layout>
    {{-- 
        Inisialisasi Alpine.js.
        - showModal: untuk buka/tutup pop-up.
        - editingAset: untuk menyimpan data aset yang sedang diedit.
        - formAction: untuk menyimpan URL tujuan form.
    --}}
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
                <a href="{{ route('aset-tetap.create') }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    <span>Tambah Aset</span>
                </a>
            </div>
        </div>

        <!-- Session Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

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
                        @forelse ($asets as $aset)
                            <tr class="border-b hover:bg-gray-50">
                                {{-- PERBAIKAN DI SINI --}}
                                <td class="py-3 px-4">
                                    <p class="font-medium text-gray-900">{{ $aset->nama_aset }}</p>
                                    <p class="text-sm text-gray-500">{{ $aset->kategori }}</p>
                                </td>
                                <td class="py-3 px-4 text-right">Rp {{ number_format($aset->harga_perolehan, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-right text-red-600">(Rp {{ number_format($aset->akumulasi_penyusutan, 0, ',', '.') }})</td>
                                <td class="py-3 px-4 text-right font-bold">Rp {{ number_format($aset->nilai_buku, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-center">
                                    <button 
                                        type="button" 
                                        x-on:click="
                                            showModal = true;
                                            editingAset = {{ $aset->toJson() }};
                                            formAction = `{{ route('aset-tetap.update', $aset->id) }}`;
                                        "
                                        class="p-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors" title="Lihat & Edit Detail">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-gray-500">
                                    <p>Belum ada data aset.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($asets->hasPages())
                <div class="p-6 border-t border-gray-200">
                    {{ $asets->links() }}
                </div>
            @endif
        </div>

        {{-- INI MODAL EDIT NYA --}}
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
                {{-- Form di dalam Modal --}}
                <form :action="formAction" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="flex justify-between items-center pb-3 border-b">
                        <h3 class="text-xl font-bold text-gray-900">Edit Aset: <span x-text="editingAset.nama_aset"></span></h3>
                        <button type="button" x-on:click="showModal = false" class="text-gray-400 hover:text-gray-600">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    {{-- Isi Form diambil dari _form.blade.php, tapi kita copy-paste di sini agar mudah di-bind --}}
                    <div class="mt-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Aset</label>
                                <input type="text" name="nama_aset" x-model="editingAset.nama_aset" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                                <input type="text" name="kategori" x-model="editingAset.kategori" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
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
                                <label class="block text-sm font-bold text-gray-700 mb-2">Masa Manfaat</label>
                                <input type="number" name="masa_manfaat" x-model="editingAset.masa_manfaat" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nilai Residu</label>
                                <input type="number" name="nilai_residu" x-model="editingAset.nilai_residu" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="deskripsi" rows="3" x-model="editingAset.deskripsi" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></textarea>
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
</x-app-layout>