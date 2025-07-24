<x-app-layout>
    <div class="p-8" x-data="transactionModal()">
        {{-- Bagian Header --}}
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Riwayat Transaksi Penjualan</h1>
                    <p class="text-green-100">Daftar semua rekap pemasukan dari penjualan.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('transaksi.fetchApi') }}" class="px-6 py-3 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/><path d="M16 12h5"/><path d="M12 7v5"/></svg>
                        <span>Sinkronkan API</span>
                    </a>
                    <a href="{{ route('transaksi.create') }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg font-semibold flex items-center gap-2">
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        <span>Catat Penjualan</span>
                    </a>
                    <!-- Tombol Ekspor Dropdown -->
                    <div class="relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold text-sm rounded-lg transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                    <span>Ekspor</span>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('transaksi.exportPdf', request()->query())">Ekspor PDF</x-dropdown-link>
                                <x-dropdown-link :href="route('transaksi.exportExcel', request()->query())">Ekspor Excel</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifikasi --}}
        <div class="session-notification">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
        </div>
        <div id="notification" class="hidden fixed top-5 right-5 z-50"></div>

        {{-- Filter --}}
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('transaksi.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                        <select name="periode" onchange="togglePeriodeFilter(this.value)" class="w-full pl-4 pr-8 py-2 rounded border-gray-300">
                            <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="harian" {{ $periode == 'harian' ? 'selected' : '' }}>Harian</option>
                        </select>
                    </div>
                    <div id="filter-harian" class="{{ $periode == 'harian' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full px-4 py-2 rounded border-gray-300">
                    </div>
                    <div id="filter-bulanan" class="{{ $periode == 'bulanan' ? '' : 'hidden' }} grid grid-cols-2 gap-2 md:col-span-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="tahun" class="w-full pl-4 pr-8 py-2 rounded border-gray-300">
                                @forelse($daftarTahun as $thn)
                                    <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                                @empty
                                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                @endforelse
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                            <select name="bulan" class="w-full pl-4 pr-8 py-2 rounded border-gray-300">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">Tampilkan</button>
                        <a href="{{ route('transaksi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white p-2 rounded text-center transition">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Ringkasan Total --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-4">
                <p class="text-sm text-gray-600">Jumlah Rekap Penjualan</p>
                <p class="text-2xl font-bold text-blue-600">{{ $jumlahTransaksi }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <p class="text-sm text-gray-600">Total Pemasukan pada {{ $judulPeriode }}</p>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Tabel Utama --}}
        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-bold uppercase">Tanggal</th>
                            <th class="py-3 px-4 text-left text-sm font-bold uppercase">Keterangan</th>
                            <th class="py-3 px-4 text-right text-sm font-bold uppercase">Total Pemasukan</th>
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase">Bukti</th>
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase">Aksi</th>
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase w-24">Detail</th>
                        </tr>
                    </thead>
                    @forelse ($transactions as $transaction)
                        <tbody x-data="{ open: false }" id="transaction-row-{{ $transaction->id }}">
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 tanggal">{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-gray-600 keterangan">{{ $transaction->keterangan ?: '-' }}</td>
                                <td class="py-3 px-4 text-right font-bold text-green-600 total_penjualan">Rp {{ number_format($transaction->total_penjualan, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-center bukti">
                                    @if ($transaction->bukti)
                                        <a href="{{ asset('uploads/' . $transaction->bukti) }}" target="_blank" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-md">Lihat</a>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- PERBAIKAN: Logika penguncian baru dengan metode Carbon yang benar --}}
                                        @if (now()->startOfDay()->isBeforeOrEqualTo(\Carbon\Carbon::parse($transaction->tanggal_transaksi)->endOfMonth()))
                                            <button @click="openModal('{{ $transaction->id }}')" class="text-blue-500 hover:text-blue-700 p-1 rounded-full hover:bg-blue-100" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                            </button>
                                            
                                            <button @click="deleteTransaction('{{ $transaction->id }}')" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-100" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        @else
                                            <span class="px-3 py-1 text-xs font-medium text-gray-600 bg-gray-200 rounded-full">Terkunci</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center cursor-pointer" @click="open = !open">
                                    @if(!empty($transaction->items_detail) && json_decode($transaction->items_detail))
                                        <button class="text-blue-500 hover:text-blue-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            <tr x-show="open" x-transition class="bg-gray-50" style="display: none;">
                                <td colspan="6" class="p-0">
                                    <div class="p-4">
                                        @php $items = json_decode($transaction->items_detail, true); @endphp
                                        @if(is_array($items) && !empty($items))
                                            <h4 class="font-bold text-lg mb-2 text-gray-700">Detail Barang Terjual:</h4>
                                            <table class="w-full text-sm mt-2">
                                                <thead class="bg-gray-200">
                                                    <tr>
                                                        <th class="py-2 px-3 text-left font-semibold text-gray-600">Nama Barang</th>
                                                        <th class="py-2 px-3 text-center font-semibold text-gray-600">Qty</th>
                                                        <th class="py-2 px-3 text-right font-semibold text-gray-600">Harga Satuan</th>
                                                        <th class="py-2 px-3 text-right font-semibold text-gray-600">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($items as $item)
                                                        <tr class="border-b border-gray-200 last:border-b-0">
                                                            <td class="py-3 px-3">{{ $item['name'] ?? 'N/A' }}</td>
                                                            <td class="py-3 px-3 text-center">{{ $item['qty'] ?? 'N/A' }}</td>
                                                            <td class="py-3 px-3 text-right">Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                                                            <td class="py-3 px-3 text-right font-medium">Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="text-gray-500 italic p-4">Tidak ada detail barang untuk transaksi ini.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @empty
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-500">
                                    <p>Belum ada data transaksi penjualan pada periode ini.</p>
                                </td>
                            </tr>
                        </tbody>
                    @endforelse
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="p-6 border-t">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Edit -->
        <div x-show="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @keydown.escape.window="closeModal()">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl" @click.outside="closeModal()">
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h2 class="text-xl font-bold">Edit Transaksi</h2>
                    <button @click="closeModal()" class="text-gray-500 hover:text-gray-800">&times;</button>
                </div>
                
                <form @submit.prevent="submitForm" id="editForm" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal_transaksi_edit" class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi_edit" x-model="formData.tanggal_transaksi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label for="total_penjualan_edit" class="block text-sm font-medium text-gray-700">Total Penjualan</label>
                            <input type="number" name="total_penjualan" id="total_penjualan_edit" x-model="formData.total_penjualan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="keterangan_edit" class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" id="keterangan_edit" rows="3" x-model="formData.keterangan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label for="bukti_edit" class="block text-sm font-medium text-gray-700">Ubah Bukti (Opsional)</label>
                            <input type="file" name="bukti" id="bukti_edit" class="mt-1 block w-full">
                            <p class="text-xs text-gray-500 mt-2">Biarkan kosong jika tidak ingin mengubah bukti.</p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-4">
                        <button type="button" @click="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    function togglePeriodeFilter(value) {
        if (value === 'harian') {
            document.getElementById('filter-harian').style.display = 'block';
            document.getElementById('filter-bulanan').style.display = 'none';
        } else {
            document.getElementById('filter-harian').style.display = 'none';
            document.getElementById('filter-bulanan').style.display = 'grid';
        }
    }
    
    function transactionModal() {
        return {
            show: false,
            transactionId: null,
            formData: {
                tanggal_transaksi: '',
                total_penjualan: '',
                keterangan: ''
            },
            openModal(id) {
                this.transactionId = id;
                fetch(`/transaksi/${id}/edit`)
                    .then(response => {
                        if (!response.ok) { 
                            return response.json().then(err => { throw new Error(err.message || 'Gagal mengambil data.'); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        this.formData.tanggal_transaksi = new Date(data.tanggal_transaksi).toISOString().split('T')[0];
                        this.formData.total_penjualan = data.total_penjualan;
                        this.formData.keterangan = data.keterangan;
                        this.show = true;
                    })
                    .catch(error => { this.showNotification(error.message, 'error'); });
            },
            closeModal() {
                this.show = false;
                this.transactionId = null;
                document.getElementById('editForm').reset();
            },
            submitForm() {
                const form = document.getElementById('editForm');
                const formData = new FormData(form);
                formData.append('_method', 'PUT');

                fetch(`/transaksi/${this.transactionId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.updateTableRow(data.transaction);
                        this.showNotification(data.message, 'success');
                        this.closeModal();
                    } else { throw new Error(data.message || 'Gagal memperbarui data.'); }
                })
                .catch(error => { this.showNotification(error.message, 'error'); });
            },
            deleteTransaction(id) {
                if (!confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
                    return;
                }

                fetch(`/transaksi/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.getElementById(`transaction-row-${id}`);
                        if (row) {
                            row.remove();
                        }
                        this.showNotification(data.message, 'success');
                    } else {
                        throw new Error(data.message || 'Gagal menghapus data.');
                    }
                })
                .catch(error => {
                     this.showNotification(error.message, 'error');
                });
            },
            updateTableRow(data) {
                const row = document.querySelector(`#transaction-row-${data.id} > tr`);
                if (row) {
                    const tgl = new Date(data.tanggal_transaksi);
                    const options = { year: 'numeric', month: 'short', day: 'numeric' };
                    row.querySelector('.tanggal').textContent = tgl.toLocaleDateString('id-ID', options);
                    row.querySelector('.keterangan').textContent = data.keterangan;
                    row.querySelector('.total_penjualan').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total_penjualan);
                    
                    const buktiCell = row.querySelector('.bukti');
                    if (data.bukti) {
                        buktiCell.innerHTML = `<a href="/uploads/${data.bukti}" target="_blank" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-md">Lihat</a>`;
                    } else {
                        buktiCell.textContent = '-';
                    }
                }
            },
            showNotification(message, type = 'success') {
                const notifDiv = document.getElementById('notification');
                // PERBAIKAN: Hapus notifikasi session yang lama sebelum menampilkan yang baru
                document.querySelectorAll('.session-notification').forEach(el => el.remove());

                notifDiv.className = `p-4 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
                notifDiv.textContent = message;
                notifDiv.classList.remove('hidden');
                setTimeout(() => { notifDiv.classList.add('hidden'); }, 3000);
            }
        }
    }
</script>
</x-app-layout>
