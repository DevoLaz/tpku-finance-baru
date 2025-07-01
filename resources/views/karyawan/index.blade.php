<x-app-layout>
    <div class="p-8">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Daftar Karyawan</h1>
                    <p class="text-green-100">Manajemen semua data karyawan perusahaan.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('laporan.penggajian.index') }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg font-semibold flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        <span>Kembali ke Penggajian</span>
                    </a>
                    <a href="{{ route('karyawan.create') }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                        <span>Tambah Karyawan</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Nama Lengkap</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Posisi</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Tgl. Masuk</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($karyawans as $karyawan)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 font-medium">{{ $karyawan->nama_lengkap }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $karyawan->jabatan }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ \Carbon\Carbon::parse($karyawan->tanggal_bergabung)->isoFormat('DD MMMM YYYY') }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="text-blue-500 hover:text-blue-700 p-2 rounded-full hover:bg-blue-100">
                                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                                        </a>
                                        <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-100">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-500">
                                    <p>Belum ada data karyawan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $karyawans->links() }}
            </div>
        </div>
    </div>
    
    @push('scripts')
        <script>
            lucide.createIcons();
        </script>
    @endpush
</x-app-layout>