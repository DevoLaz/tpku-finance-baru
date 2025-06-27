<x-app-layout>
    <div class="p-8">
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white mb-2">Formulir Penggajian Karyawan</h1>
            <p class="text-green-100">Hitung dan catat gaji karyawan untuk periode berjalan.</p>
        </div>
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <p class="font-bold">Oops! Ada yang salah:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="payroll-form" method="POST" action="{{ route('laporan.penggajian.store') }}">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-md p-8">
                    <div class="space-y-8">
                        <section>
                            <h2 class="text-xl font-bold text-gray-800 border-b-2 border-[#173720] pb-2 mb-6">Informasi Dasar</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="karyawan_id" class="block text-sm font-semibold text-gray-700 mb-2">Nama Karyawan</label>
                                    <select id="karyawan_id" name="karyawan_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white" required>
                                        <option value="">Pilih Karyawan...</option>
                                        @foreach ($karyawan as $k)
                                            <option value="{{ $k->id }}" data-gaji="{{ $k->gaji_pokok_default }}">{{ $k->nama_lengkap }} - {{ $k->jabatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="periode" class="block text-sm font-semibold text-gray-700 mb-2">Periode Gaji</label>
                                    <input type="month" id="periode" name="periode" value="{{ date('Y-m') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white" required>
                                </div>
                            </div>
                        </section>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <section>
                                <h2 class="text-xl font-bold text-green-700 border-b-2 border-green-200 pb-2 mb-6">Pendapatan</h2>
                                <div class="space-y-4">
                                    <div>
                                        <label for="gaji_pokok" class="block text-sm font-medium text-gray-600">Gaji Pokok</label>
                                        <input type="number" id="gaji_pokok" name="gaji_pokok" placeholder="0" class="payroll-component income w-full mt-1 px-4 py-2 rounded-lg border border-gray-300" required>
                                    </div>
                                    <div>
                                        <label for="tunjangan_jabatan" class="block text-sm font-medium text-gray-600">Tunjangan Jabatan</label>
                                        <input type="number" id="tunjangan_jabatan" name="tunjangan_jabatan" placeholder="0" class="payroll-component income w-full mt-1 px-4 py-2 rounded-lg border border-gray-300">
                                    </div>
                                    <div>
                                        <label for="tunjangan_transport" class="block text-sm font-medium text-gray-600">Tunjangan Transport</label>
                                        <input type="number" id="tunjangan_transport" name="tunjangan_transport" placeholder="0" class="payroll-component income w-full mt-1 px-4 py-2 rounded-lg border border-gray-300">
                                    </div>
                                    <div>
                                        <label for="bonus" class="block text-sm font-medium text-gray-600">Bonus / Lembur</label>
                                        <input type="number" id="bonus" name="bonus" placeholder="0" class="payroll-component income w-full mt-1 px-4 py-2 rounded-lg border border-gray-300">
                                    </div>
                                </div>
                            </section>
                            <section>
                                <h2 class="text-xl font-bold text-red-700 border-b-2 border-red-200 pb-2 mb-6">Potongan</h2>
                                <div class="space-y-4">
                                    <div>
                                        <label for="pph21" class="block text-sm font-medium text-gray-600">Pajak PPh 21</label>
                                        <input type="number" id="pph21" name="pph21" placeholder="0" class="payroll-component deduction w-full mt-1 px-4 py-2 rounded-lg border border-gray-300">
                                    </div>
                                    <div>
                                        <label for="bpjs" class="block text-sm font-medium text-gray-600">Iuran BPJS</label>
                                        <input type="number" id="bpjs" name="bpjs" placeholder="0" class="payroll-component deduction w-full mt-1 px-4 py-2 rounded-lg border border-gray-300">
                                    </div>
                                    <div>
                                        <label for="potongan_lain" class="block text-sm font-medium text-gray-600">Potongan Lainnya (Kasbon)</label>
                                        <input type="number" id="potongan_lain" name="potongan_lain" placeholder="0" class="payroll-component deduction w-full mt-1 px-4 py-2 rounded-lg border border-gray-300">
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div class="sticky top-8 bg-white/50 backdrop-blur-sm border border-gray-200 rounded-2xl p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Ringkasan Gaji</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-lg">
                                <span class="text-gray-600">Total Pendapatan</span>
                                <span id="summary-pendapatan" class="font-bold text-green-600">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-lg">
                                <span class="text-gray-600">Total Potongan</span>
                                <span id="summary-potongan" class="font-bold text-red-600">Rp 0</span>
                            </div>
                            <div class="border-t-2 border-dashed border-gray-300 my-4"></div>
                            <div class="flex justify-between items-center text-2xl">
                                <span class="font-bold text-gray-800">Gaji Bersih</span>
                                <span id="summary-gaji-bersih" class="font-extrabold text-[#173720]">Rp 0</span>
                            </div>
                        </div>
                        <div class="mt-10 pt-6 border-t border-gray-200 flex flex-col gap-4">
                            <button type="submit" class="w-full px-8 py-4 bg-[#173720] hover:bg-[#2a5a37] text-white font-semibold rounded-lg flex items-center justify-center gap-2">
                                <i data-lucide="save" class="w-5 h-5"></i> Simpan & Proses Gaji
                            </button>
                            <a href="{{ route('laporan.penggajian.index') }}" class="w-full text-center px-8 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('payroll-form');
                const incomeInputs = form.querySelectorAll('.income');
                const deductionInputs = form.querySelectorAll('.deduction');
                const karyawanSelect = document.getElementById('karyawan_id');
                const gajiPokokInput = document.getElementById('gaji_pokok');
                const summaryPendapatan = document.getElementById('summary-pendapatan');
                const summaryPotongan = document.getElementById('summary-potongan');
                const summaryGajiBersih = document.getElementById('summary-gaji-bersih');

                function formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
                }

                function calculatePayroll() {
                    let totalPendapatan = 0;
                    incomeInputs.forEach(input => { totalPendapatan += Number(input.value) || 0; });
                    let totalPotongan = 0;
                    deductionInputs.forEach(input => { totalPotongan += Number(input.value) || 0; });
                    const gajiBersih = totalPendapatan - totalPotongan;
                    summaryPendapatan.textContent = formatRupiah(totalPendapatan);
                    summaryPotongan.textContent = formatRupiah(totalPotongan);
                    summaryGajiBersih.textContent = formatRupiah(gajiBersih);
                }

                calculatePayroll();
                form.querySelectorAll('.payroll-component').forEach(input => { input.addEventListener('input', calculatePayroll); });
                karyawanSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    gajiPokokInput.value = selectedOption.getAttribute('data-gaji') || '';
                    calculatePayroll();
                });
            });
        </script>
    @endpush
</x-app-layout>