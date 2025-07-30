<x-app-layout>
  <div class="p-8">
    <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-white">Tambah Supplier</h1>
          <p class="text-green-100">Masukkan data supplier baru.</p>
        </div>
        <a href="{{ route('master.index') }}"
           class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold transition-all transform hover:scale-105">
          Kembali
        </a>
      </div>
    </div>

    @if(session('success'))
      <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md">
        {{ session('success') }}
      </div>
    @endif

    <form action="{{ route('suppliers.store') }}" method="POST" class="bg-white rounded-lg shadow-sm p-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier <span class="text-red-500">*</span></label>
          <input type="text" name="nama" value="{{ old('nama') }}"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                 required>
          @error('nama') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Kontak (Opsional)</label>
          <input type="text" name="kontak" value="{{ old('kontak') }}"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg">
          @error('kontak') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat (Opsional)</label>
        <textarea name="alamat" rows="3"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg"
        >{{ old('alamat') }}</textarea>
        @error('alamat') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
      </div>

      <div class="text-right">
        <button type="submit"
                class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105">
          Simpan Supplier
        </button>
      </div>
    </form>
  </div>
</x-app-layout>
