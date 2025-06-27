<?php

namespace App\Http\Controllers;

use App\Models\ArusKas;
use App\Models\AsetTetap;
use App\Models\Pengadaan;
use App\Models\Transaction;
use App\Models\Gaji;
use App\Models\Beban; // <-- INI YANG PALING PENTING
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{






    public function arusKas(Request $request)
    {
        // 1. Tentukan periode filter
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan');

        if ($bulan) {
            $tanggalAwalPeriode = Carbon::create($tahun, (int)$bulan, 1)->startOfMonth();
            $tanggalAkhirPeriode = $tanggalAwalPeriode->copy()->endOfMonth();
        } else {
            $tanggalAwalPeriode = Carbon::create($tahun, 1, 1)->startOfYear();
            $tanggalAkhirPeriode = $tanggalAwalPeriode->copy()->endOfYear();
        }

        // 2. Hitung Saldo Awal
        $saldoAwal = ArusKas::where('tanggal', '<', $tanggalAwalPeriode->toDateString())->sum('jumlah');

        // 3. Ambil data sesuai periode
        $arusKasPeriodeIni = ArusKas::whereBetween('tanggal', [$tanggalAwalPeriode, $tanggalAkhirPeriode])
                                    ->orderBy('tanggal')->orderBy('id')->get();

        // 4. Hitung Summary Cards
        $totalKasMasuk = $arusKasPeriodeIni->where('tipe', 'masuk')->sum('jumlah');
        $totalKasKeluar = $arusKasPeriodeIni->where('tipe', 'keluar')->sum('jumlah') * -1;
        $saldoAkhir = $saldoAwal + $totalKasMasuk - $totalKasKeluar;
        $jumlahTransaksiMasuk = $arusKasPeriodeIni->where('tipe', 'masuk')->count();
        $jumlahTransaksiKeluar = $arusKasPeriodeIni->where('tipe', 'keluar')->count();

        // 5. Pisahkan transaksi berdasarkan jenis aktivitas
        $operasionalMasuk = $arusKasPeriodeIni->where('tipe', 'masuk')->where('referensi_tipe', Transaction::class);

        // --- INI PERBAIKANNYA ---
        $operasionalKeluar = $arusKasPeriodeIni->where('tipe', 'keluar')->whereIn('referensi_tipe', [
            Pengadaan::class, 
            Gaji::class, 
            Beban::class 
        ]);

        $investasi = $arusKasPeriodeIni->where('referensi_tipe', AsetTetap::class)->filter(function ($item) {
            return !str_contains(strtolower($item->deskripsi), 'modal');
        });

        $pendanaan = $arusKasPeriodeIni->where('referensi_tipe', AsetTetap::class)->filter(function ($item) {
            return str_contains(strtolower($item->deskripsi), 'modal');
        });

        // 6. Siapkan data untuk grafik
        $dataGrafik = ArusKas::select(
                DB::raw('MONTH(tanggal) as bulan_angka'),
                DB::raw('SUM(CASE WHEN tipe = "masuk" THEN jumlah ELSE 0 END) as kas_masuk'),
                DB::raw('SUM(CASE WHEN tipe = "keluar" THEN jumlah ELSE 0 END) as kas_keluar')
            )
            ->whereYear('tanggal', $tahun)
            ->groupBy('bulan_angka')
            ->orderBy('bulan_angka')
            ->get();

        $dataGrafikFormatted = [];
        $namaBulanGrafik = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        for ($i = 1; $i <= 12; $i++) {
            $dataBulan = $dataGrafik->firstWhere('bulan_angka', $i);
            $dataGrafikFormatted[] = [
                'bulan' => $namaBulanGrafik[$i-1],
                'kas_masuk' => $dataBulan->kas_masuk ?? 0,
                'kas_keluar' => ($dataBulan->kas_keluar ?? 0) * -1,
                'arus_kas_bersih' => ($dataBulan->kas_masuk ?? 0) + ($dataBulan->kas_keluar ?? 0)
            ];
        }
        $dataGrafikJson = json_encode($dataGrafikFormatted);

        // 7. Siapkan data untuk filter dropdown
        $daftarTahun = ArusKas::selectRaw('YEAR(tanggal) as tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([date('Y')]);
        }

        // 8. Kirim semua data ke view
        return view('laporan.arus_kas', compact(
            'saldoAwal', 'totalKasMasuk', 'totalKasKeluar', 'saldoAkhir',
            'jumlahTransaksiMasuk', 'jumlahTransaksiKeluar',
            'operasionalMasuk', 'operasionalKeluar', 'investasi', 'pendanaan',
            'tahun', 'bulan', 'daftarTahun', 'dataGrafikJson'
        ));
    }









    public function labaRugi(Request $request)
{
    // 1. Ambil filter, defaultnya adalah bulan dan tahun saat ini.
    $bulan = $request->input('bulan', date('m'));
    $tahun = $request->input('tahun', date('Y'));

    // Tentukan rentang tanggal berdasarkan filter
    $tanggalAwal = Carbon::create($tahun, (int)$bulan, 1)->startOfMonth();
    $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

    // 2. HITUNG PENDAPATAN
    $pendapatanItems = Transaction::whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])->get();
    $totalPendapatan = $pendapatanItems->sum('total_penjualan');

    // 3. HITUNG SEMUA BEBAN / PENGELUARAN

    // a. Harga Pokok Penjualan (HPP) dari Pengadaan Bahan Baku
    $hppItems = Pengadaan::whereBetween('tanggal_pembelian', [$tanggalAwal, $tanggalAkhir])->get();
    $totalHpp = $hppItems->sum('total_harga');

    // b. Beban Gaji
    $gajiItems = Gaji::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->get();
    $totalBebanGaji = $gajiItems->sum('gaji_bersih');

    // c. Beban Operasional Lainnya
    $bebanItems = Beban::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();
    $totalBebanLain = $bebanItems->sum('jumlah');

    // d. Beban Penyusutan Aset
    $asetFisik = AsetTetap::where('masa_manfaat', '>', 0)->get();
    $totalBebanPenyusutan = 0;
    foreach ($asetFisik as $aset) {
        // Hitung penyusutan per bulan untuk setiap aset
        $penyusutanPerBulan = ($aset->harga_perolehan - $aset->nilai_residu) / ($aset->masa_manfaat * 12);
        $totalBebanPenyusutan += $penyusutanPerBulan;
    }

    // 4. GABUNGKAN SEMUA PENGELUARAN UNTUK TABEL DETAIL
    // Kita buat collection baru agar bisa digabung & diurutkan
    $pengeluaran = collect([]);
    $hppItems->each(function ($item) use ($pengeluaran) {
        $pengeluaran->push(['tanggal' => $item->tanggal_pembelian, 'keterangan' => 'HPP: ' . $item->barang->nama, 'kategori' => 'HPP', 'jumlah' => $item->total_harga]);
    });
    $gajiItems->each(function ($item) use ($pengeluaran) {
        $pengeluaran->push(['tanggal' => $item->created_at, 'keterangan' => 'Gaji: ' . $item->karyawan->nama, 'kategori' => 'Beban Gaji', 'jumlah' => $item->gaji_bersih]);
    });
    $bebanItems->each(function ($item) use ($pengeluaran) {
        $pengeluaran->push(['tanggal' => $item->tanggal, 'keterangan' => $item->nama_beban, 'kategori' => 'Beban Lainnya', 'jumlah' => $item->jumlah]);
    });
    // Tambahkan penyusutan sebagai satu item pengeluaran
    if ($totalBebanPenyusutan > 0) {
        $pengeluaran->push(['tanggal' => $tanggalAkhir, 'keterangan' => 'Beban Penyusutan Aset Tetap', 'kategori' => 'Beban Penyusutan', 'jumlah' => $totalBebanPenyusutan]);
    }

    // Urutkan semua pengeluaran berdasarkan tanggal
    $pengeluaran = $pengeluaran->sortBy('tanggal');

    // 5. HITUNG TOTAL & LABA BERSIH
    $totalPengeluaran = $pengeluaran->sum('jumlah');
    $labaBersih = $totalPendapatan - $totalPengeluaran;

    // 6. SIAPKAN DATA FILTER
    $daftarTahun = Transaction::selectRaw('YEAR(tanggal_transaksi) as tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

    // 7. KIRIM SEMUA DATA KE VIEW
    return view('laporan.laba_rugi', compact(
        'totalPendapatan', 'pendapatanItems',
        'totalPengeluaran', 'pengeluaran',
        'labaBersih',
        'tahun', 'bulan', 'daftarTahun'
    ));
}




public function neraca(Request $request)
{
    // 1. Tentukan tanggal laporan, defaultnya adalah akhir hari ini
    $tanggalLaporan = $request->input('tanggal', date('Y-m-d'));
    $tanggalObj = Carbon::parse($tanggalLaporan)->endOfDay();

    // =======================================================
    // MENGHITUNG SISI ASET (HARTA PERUSAHAAN)
    // =======================================================

    // A. ASET LANCAR
    // Total kas di tangan pada tanggal laporan
    $kas = ArusKas::where('created_at', '<=', $tanggalObj)->sum('jumlah');

    // B. ASET TETAP
    $asetTetapItems = AsetTetap::where('tanggal_perolehan', '<=', $tanggalObj)->get();

    // Hitung total akumulasi penyusutan hingga tanggal laporan
    $totalAkumulasiPenyusutan = 0;
    foreach ($asetTetapItems as $aset) {
        if ($aset->masa_manfaat > 0) {
            $penyusutan_per_bulan = ($aset->harga_perolehan - $aset->nilai_residu) / ($aset->masa_manfaat * 12);
            $bulan_berlalu = $aset->tanggal_perolehan->diffInMonths($tanggalObj);
            $akumulasi = $penyusutan_per_bulan * $bulan_berlalu;
            $totalAkumulasiPenyusutan += min($akumulasi, $aset->harga_perolehan - $aset->nilai_residu);
        }
    }

    // Pisahkan Aset Tetap Fisik dan Aset Kas (Modal)
    $asetFisikItems = $asetTetapItems->where('masa_manfaat', '>', 0);
    $asetKasItems = $asetTetapItems->where('masa_manfaat', '=', 0);

    $nilaiBukuAsetFisik = $asetFisikItems->sum('harga_perolehan') - $totalAkumulasiPenyusutan;

    // TOTAL ASET = Kas di tangan + Nilai Buku Aset Fisik
    $totalAset = $kas + $nilaiBukuAsetFisik;


    // =======================================================
    // MENGHITUNG SISI LIABILITAS & EKUITAS (SUMBER HARTA)
    // =======================================================

    // C. LIABILITAS (Utang)
    // Sesuai permintaan, untuk sekarang kita anggap 0
    $totalLiabilitas = 0;

    // D. EKUITAS (Modal Sendiri)
    // Modal yang disetor (diambil dari aset yang dicatat sebagai Kas/Modal)
    $modalDisetor = $asetKasItems->sum('harga_perolehan');

    // Laba Ditahan (Akumulasi Laba/Rugi dari awal hingga tanggal laporan)
    $totalPendapatan = Transaction::where('tanggal_transaksi', '<=', $tanggalLaporan)->sum('total_penjualan');
    $totalHpp = Pengadaan::where('tanggal_pembelian', '<=', $tanggalLaporan)->sum('total_harga');
    $totalBebanLain = Beban::where('tanggal', '<=', $tanggalLaporan)->sum('jumlah');
    $totalBebanGaji = Gaji::where('created_at', '<=', $tanggalObj)->sum('gaji_bersih');
    // Total beban penyusutan sama dengan yang sudah dihitung untuk sisi Aset
    $totalBebanPenyusutan = $totalAkumulasiPenyusutan;

    $labaDitahan = $totalPendapatan - ($totalHpp + $totalBebanLain + $totalBebanGaji + $totalBebanPenyusutan);

    // TOTAL EKUITAS
    $totalEkuitas = $modalDisetor + $labaDitahan;

    // TOTAL LIABILITAS + EKUITAS
    $totalLiabilitasEkuitas = $totalLiabilitas + $totalEkuitas;

    return view('laporan.neraca', compact(
        'tanggalLaporan', 'kas', 'asetFisikItems', 'totalAkumulasiPenyusutan', 'totalAset',
        'totalLiabilitas',
        'modalDisetor', 'labaDitahan', 'totalEkuitas',
        'totalLiabilitasEkuitas'
    ));
}




    // ... method laporan lainnya ...
}
