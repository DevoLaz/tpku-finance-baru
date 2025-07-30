{{-- resources/views/barangs/create.blade.php --}}
<x-app-layout>
  <div class="p-8">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg flex justify-between items-center">
      <div>
        <h1 class="text-3xl font-bold text-white">Tambah Barang</h1>
        <p class="text-green-100">Masukkan data barang baru ke master</p>
      </div>
      <a href="{{ route('master.index') }}"
         class="px-5 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold">
        &larr; Kembali
      </a>
    </div>

    {{-- Validasi error --}}
    @if ($errors->any())
      <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('barangs.store') }}" method="POST">
      @csrf

      <div class="bg-white rounded-lg shadow-md p-6 grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Kode Barang --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
          <input type="text" name="kode_barang" value="{{ old('kode_barang') }}"
                 class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                 placeholder="Opsional, unik jika diisi">
        </div>

        {{-- Nama --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
          <input type="text" name="nama" value="{{ old('nama') }}" required
                 class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                 placeholder="Contoh: Benang Nilon">
        </div>

        {{-- Kategori --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
          <select name="kategori_id" required
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoris as $k)
              <option value="{{ $k->id }}" {{ old('kategori_id')==$k->id?'selected':'' }}>
                {{ $k->nama_kategori }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Unit --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
          <input type="text" name="unit" value="{{ old('unit') }}" required
                 class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                 placeholder="Contoh: pcs / roll">
        </div>

        {{-- Stok Awal --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal <span class="text-red-500">*</span></label>
          <input type="number" name="stok" value="{{ old('stok',0) }}" min="0" required
                 class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
        </div>

        {{-- Harga Jual --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual <span class="text-red-500">*</span></label>
          <input type="number" name="harga_jual" value="{{ old('harga_jual',0) }}" min="0" required
                 class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
        </div>

        {{-- Pilih Supplier --}}
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Supplier</label>
          <select name="suppliers[]" multiple
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 h-32">
            @foreach($suppliers as $s)
              <option value="{{ $s->id }}"
                {{ in_array($s->id, old('suppliers',[])) ? 'selected':'' }}>
                {{ $s->nama }}
              </option>
            @endforeach
          </select>
          <p class="text-xs text-gray-500 mt-1">Tekan Ctrl/Cmd+klik untuk multi‐select</p>
        </div>

        {{-- (Field lain di‐nonaktifkan sementara) --}}
      </div>

      {{-- Button --}}
      <div class="flex justify-end gap-4">
        <a href="{{ route('master.index') }}"
           class="px-6 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</a>
        <button type="submit"
                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
          Simpan Barang
        </button>
      </div>
    </form>
  </div>
</x-app-layout>
