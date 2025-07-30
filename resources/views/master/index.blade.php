{{-- resources/views/master/index.blade.php --}}
<x-app-layout>
  <div class="p-8" x-data="masterData()">

    {{-- Notifikasi --}}
    @if(session('success'))
      <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
        <div class="flex">
          <div class="py-1">
            <svg class="fill-current h-6 w-6 text-green-500 mr-4" viewBox="0 0 20 20">
              <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93…"/>
            </svg>
          </div>
          <div>
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
          </div>
        </div>
      </div>
    @endif

    {{-- Header --}}
    <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-white mb-2">Master Data</h1>
          <p class="text-green-100">Kelola daftar Barang & Supplier</p>
        </div>
        <div class="flex gap-3">
          <button @click="openBarangModal()"
                  class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
            <!-- ikon plus -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="8" x2="12" y2="16"/>
              <line x1="8" y1="12" x2="16" y2="12"/>
            </svg>
            <span>Tambah Barang</span>
          </button>
          <button @click="openSupplierModal()"
                  class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold flex items-center gap-2 transition transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M3 7h18M3 12h18M3 17h18"/>
            </svg>
            <span>Tambah Supplier</span>
          </button>
        </div>
      </div>
    </div>

    <!-- {{-- Filter Supplier & Barang --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Filter Supplier</label>
          <select x-model="selectedSupplier" @change="loadBarang()"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            <option value="">— Semua Supplier —</option>
            @foreach($suppliers as $s)
              <option value="{{ $s->id }}">{{ $s->nama }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Daftar Barang (supplier terpilih)</label>
          <select x-model="selectedBarang"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            <option value="">— Pilih Barang —</option>
            <template x-for="b in supplierItems" :key="b.id">
              <option :value="b.id" x-text="b.nama"></option>
            </template>
          </select>
        </div>
      </div>
    </div> -->

    {{-- Daftar Supplier --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
      <h2 class="text-xl font-bold mb-4">Daftar Supplier</h2>
      <table class="w-full text-sm">
        <thead class="bg-gray-200">
          <tr>
            <th class="py-2 px-3 text-left">Nama</th>
            <th class="py-2 px-3 text-left">Kontak</th>
            <th class="py-2 px-3 text-left">Alamat</th>
            <th class="py-2 px-3 text-center w-32">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($suppliers as $sup)
            <tr @click="supOpen[{{ $sup->id }}] = ! supOpen[{{ $sup->id }}]" class="border-b hover:bg-gray-50 cursor-pointer">
              <td class="py-2 px-3">{{ $sup->nama }}</td>
              <td class="py-2 px-3">{{ $sup->kontak ?: '-' }}</td>
              <td class="py-2 px-3">{{ $sup->alamat ?: '-' }}</td>
              <td class="py-2 px-3 text-center" @click.stop>
                <button @click="openSupplierModal({{ $sup->id }})" class="text-blue-500 hover:text-blue-700 mr-3">Edit</button>
                <form action="{{ route('suppliers.destroy', $sup) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus {{ $sup->nama }}?')">
                  @csrf @method('DELETE')
                  <button class="text-red-500 hover:text-red-700">Hapus</button>
                </form>
              </td>
            </tr>
            <tr x-show="supOpen[{{ $sup->id }}]" x-cloak class="bg-gray-50">
              <td colspan="4" class="p-4">
                @if($sup->barangs->isEmpty())
                  <p class="text-gray-500 italic">Barang belum didaftarkan.</p>
                @else
                  <ul class="list-disc list-inside space-y-1">
                    @foreach($sup->barangs as $b)
                      <li>{{ $b->nama }} ({{ $b->kode_barang }}) – stok: {{ $b->stok }}</li>
                    @endforeach
                  </ul>
                @endif
              </td>
            </tr>
          @endforeach
          @if($suppliers->isEmpty())
            <tr>
              <td colspan="4" class="p-4 text-center text-gray-500">Belum ada supplier.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>

    {{-- Daftar Barang --}}
    <div class="bg-white rounded-lg shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-[#173720] text-white">
            <tr>
              <th class="py-4 px-4 text-left uppercase">Kode</th>
              <th class="py-4 px-4 text-left uppercase">Nama</th>
              <th class="py-4 px-4 text-left uppercase">Kategori</th>
              <th class="py-4 px-4 text-left uppercase">Supplier</th>
              <th class="py-4 px-4 text-left uppercase">Unit</th>
              <!-- <th class="py-4 px-4 text-right uppercase">Stok</th> -->
              <th class="py-4 px-4 text-right uppercase">Harga Jual</th>
              <th class="py-4 px-4 text-center uppercase w-32">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($barangs as $item)
              <tr class="border-b hover:bg-gray-50">
                <td class="py-4 px-4">{{ $item->kode_barang }}</td>
                <td class="py-4 px-4">{{ $item->nama }}</td>
                <td class="py-4 px-4">{{ $item->kategori->nama_kategori }}</td>
                <td class="py-4 px-4">{{ $item->suppliers->pluck('nama')->join(', ') ?: '-' }}</td>
                <td class="py-4 px-4">{{ $item->unit }}</td>
                <!-- <td class="py-4 px-4 text-right">{{ $item->stok }}</td> -->
                <td class="py-4 px-4 text-right">Rp {{ number_format($item->harga_jual,0,',','.') }}</td>
                <td class="py-4 px-4 text-center" @click.stop>
                  <button @click="openBarangModal({{ $item->id }})" class="text-blue-500 hover:text-blue-700 mr-3">Edit</button>
                  <form action="{{ route('barangs.destroy',$item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus {{ $item->nama }}?')">
                    @csrf @method('DELETE')
                    <button class="text-red-500 hover:text-red-700">Hapus</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        @if($barangs->hasPages())
          <div class="p-6 border-t">{{ $barangs->links() }}</div>
        @endif
      </div>
    </div>

    {{-- Modal Supplier --}}
    <div x-show="showSupplierModal" x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @keydown.escape.window="closeSupplierModal()">
      <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" @click.outside="closeSupplierModal()">
        <h3 class="text-lg font-semibold mb-4" x-text="modalSupplierTitle"></h3>
        <form @submit.prevent="submitSupplierForm">
          <div class="mb-4">
            <label class="block text-sm font-medium">Nama <span class="text-red-500">*</span></label>
            <input type="text" x-model="supplierForm.nama" required class="w-full border-gray-300 rounded-lg px-3 py-2"/>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Kontak</label>
            <input type="text" x-model="supplierForm.kontak" class="w-full border-gray-300 rounded-lg px-3 py-2"/>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Alamat</label>
            <textarea x-model="supplierForm.alamat" class="w-full border-gray-300 rounded-lg px-3 py-2"></textarea>
          </div>
          <div class="flex justify-end gap-3">
            <button type="button" @click="closeSupplierModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Batal</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    {{-- Modal Barang --}}
    <div x-show="showBarangModal" x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @keydown.escape.window="closeBarangModal()">
      <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg" @click.outside="closeBarangModal()">
        <h3 class="text-xl font-semibold mb-4" x-text="modalBarangTitle"></h3>
        <form @submit.prevent="submitBarangForm()">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Supplier (multi‐select) --}}
            <div class="sm:col-span-2">
              <label class="block text-sm font-medium mb-1">Supplier <span class="text-red-500">*</span></label>
              <select x-model="barangForm.suppliers" multiple required class="w-full border-gray-300 rounded-lg px-3 py-2">
                <template x-for="s in supplierOptions" :key="s.id">
                  <option :value="s.id" x-text="s.nama"></option>
                </template>
              </select>
            </div>
            {{-- Kode Barang --}}
            <div>
              <label class="block text-sm font-medium mb-1">Kode Barang</label>
              <input type="text" x-model="barangForm.kode_barang" class="w-full border-gray-300 rounded-lg px-3 py-2"/>
            </div>
            {{-- Nama --}}
            <div>
              <label class="block text-sm font-medium mb-1">Nama <span class="text-red-500">*</span></label>
              <input type="text" x-model="barangForm.nama" required class="w-full border-gray-300 rounded-lg px-3 py-2"/>
            </div>
            {{-- Kategori --}}
            <div class="sm:col-span-2">
              <label class="block text-sm font-medium mb-1">Kategori <span class="text-red-500">*</span></label>
              <select x-model="barangForm.kategori_id" required class="w-full border-gray-300 rounded-lg px-3 py-2">
                <option value="">— Pilih Kategori —</option>
                <template x-for="k in kategoriOptions" :key="k.id">
                  <option :value="k.id" x-text="k.nama_kategori"></option>
                </template>
              </select>
            </div>
            {{-- Unit --}}
            <div>
              <label class="block text-sm font-medium mb-1">Unit <span class="text-red-500">*</span></label>
              <input type="text" x-model="barangForm.unit" required class="w-full border-gray-300 rounded-lg px-3 py-2"/>
            </div>
            <!-- {{-- Stok --}}
            <div>
              <label class="block text-sm font-medium mb-1">Stok <span class="text-red-500">*</span></label>
              <input type="number" x-model.number="barangForm.stok" min="0" required class="w-full border-gray-300 rounded-lg px-3 py-2"/>
            </div> -->
            {{-- Harga Jual --}}
            <div>
              <label class="block text-sm font-medium mb-1">Harga Jual <span class="text-red-500">*</span></label>
              <input type="number" x-model.number="barangForm.harga_jual" min="0" required class="w-full border-gray-300 rounded-lg px-3 py-2"/>
            </div>
          </div>
          <div class="mt-6 flex justify-end gap-3">
            <button type="button" @click="closeBarangModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Batal</button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Simpan</button>
          </div>
        </form>
      </div>
    </div>

  </div>

  {{-- AlpineJS Component --}}
  @push('scripts')
  <script>
    function masterData() {
      return {
        // filter
        selectedSupplier: '',
        supplierItems: [],
        selectedBarang: '',
        // supplier list open state
        supOpen: {},

        // data from controller
        supplierOptions: @json($suppliers),
        kategoriOptions: @json($kategoris),

        // Supplier modal
        showSupplierModal: false,
        modalSupplierTitle: '',
        supplierForm: { id:null, nama:'', kontak:'', alamat:'' },

        // Barang modal
        showBarangModal: false,
        modalBarangTitle: '',
        barangForm: {
          id: null,
          suppliers: [],          // <-- multi‐select
          kode_barang: '',
          nama: '',
          kategori_id: '',
          unit: '',
          stok: 0,
          harga_jual: 0
        },

        loadBarang() {
          if (!this.selectedSupplier) {
            this.supplierItems = [];
            return;
          }
          fetch(`/suppliers/${this.selectedSupplier}/barangs`)
            .then(r=>r.json())
            .then(js=> this.supplierItems = js);
        },

        // Supplier modal handlers
        openSupplierModal(id=null) {
          if (id) {
            this.modalSupplierTitle = 'Edit Supplier';
            fetch(`/suppliers/${id}/edit`)
              .then(r=>r.json())
              .then(d=> this.supplierForm = {
                id:d.id, nama:d.nama, kontak:d.kontak, alamat:d.alamat
              });
          } else {
            this.modalSupplierTitle = 'Tambah Supplier';
            this.supplierForm = { id:null, nama:'', kontak:'', alamat:'' };
          }
          this.showSupplierModal = true;
        },
        closeSupplierModal() {
          this.showSupplierModal = false;
        },
        submitSupplierForm() {
          let url    = this.supplierForm.id ? `/suppliers/${this.supplierForm.id}` : '/suppliers';
          let method = this.supplierForm.id ? 'PUT' : 'POST';
          fetch(url, {
            method,
            headers:{
              'Content-Type':'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(this.supplierForm)
          }).then(_=> window.location.reload());
        },

        // Barang modal handlers
        openBarangModal(id=null) {
          if (id) {
            this.modalBarangTitle = 'Edit Barang';
            fetch(`/barangs/${id}/edit`, { headers:{ 'Accept':'application/json' } })
              .then(r=>r.json())
              .then(d=> {
                this.barangForm = {
                  id:           d.id,
                  suppliers:    d.suppliers.map(s=>s.id),
                  kode_barang:  d.kode_barang,
                  nama:         d.nama,
                  kategori_id:  d.kategori_id,
                  unit:         d.unit,
                  stok:         d.stok,
                  harga_jual:   d.harga_jual
                };
              });
          } else {
            this.modalBarangTitle = 'Tambah Barang';
            this.barangForm = {
              id:null,
              suppliers:[],
              kode_barang:'',
              nama:'',
              kategori_id:'',
              unit:'',
              stok:0,
              harga_jual:0
            };
          }
          this.showBarangModal = true;
        },
        closeBarangModal() {
          this.showBarangModal = false;
        },
        submitBarangForm() {
          let url    = this.barangForm.id ? `/barangs/${this.barangForm.id}` : '/barangs';
          let method = this.barangForm.id ? 'PUT' : 'POST';
          fetch(url, {
            method,
            headers:{
              'Content-Type':'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(this.barangForm)
          }).then(_=> window.location.reload());
        },
      }
    }
  </script>
  @endpush

</x-app-layout>
