<?php

namespace App\Http\Controllers;

use App\Models\ArusKas;
use App\Models\AsetTetap;
use App\Models\Pengadaan;
use App\Models\Transaction;
use App\Models\Gaji;
use App\Models\Beban;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDF;

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
        $saldoAwal = ArusKas::where('tanggal', '<', $tanggalAwalPeriode->toDateString())->sum(DB::raw('CASE WHEN tipe = "masuk" THEN jumlah ELSE -jumlah END'));

        // 3. Ambil data sesuai periode
        $arusKasPeriodeIni = ArusKas::whereBetween('tanggal', [$tanggalAwalPeriode, $tanggalAkhirPeriode])
                                        ->orderBy('tanggal')->orderBy('id')->get();

        // 4. Hitung Summary Cards
        $totalKasMasuk = $arusKasPeriodeIni->where('tipe', 'masuk')->sum('jumlah');
        $totalKasKeluar = $arusKasPeriodeIni->where('tipe', 'keluar')->sum('jumlah');
        $saldoAkhir = $saldoAwal + $totalKasMasuk - $totalKasKeluar;
        $jumlahTransaksiMasuk = $arusKasPeriodeIni->where('tipe', 'masuk')->count();
        $jumlahTransaksiKeluar = $arusKasPeriodeIni->where('tipe', 'keluar')->count();

        // ======================================================
        // == PERBAIKAN LOGIKA ADA DI BAWAH INI ==
        // ======================================================

        // 5. Pisahkan transaksi berdasarkan jenis aktivitas
        $operasionalMasuk = $arusKasPeriodeIni->where('tipe', 'masuk')->where('referensi_tipe', Transaction::class);
        
        // Menggunakan filter closure untuk mencakup SEMUA jenis pengeluaran operasional
        $operasionalKeluar = $arusKasPeriodeIni->where('tipe', 'keluar')->filter(function ($item) {
            return in_array($item->referensi_tipe, [
                Pengadaan::class, 
                Gaji::class, 
                Beban::class
            ]) || $item->kategori === 'Operasional'; // Ditambahkan pengecekan 'kategori'
        });

        $investasi = $arusKasPeriodeIni->where('referensi_tipe', AsetTetap::class)->filter(fn ($item) => !str_contains(strtolower($item->deskripsi), 'modal'));
        $pendanaan = $arusKasPeriodeIni->where('referensi_tipe', AsetTetap::class)->filter(fn ($item) => str_contains(strtolower($item->deskripsi), 'modal'));

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
                'kas_keluar' => abs($dataBulan->kas_keluar ?? 0), 
                'arus_kas_bersih' => ($dataBulan->kas_masuk ?? 0) - abs($dataBulan->kas_keluar ?? 0)
            ];
        }
        
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
            'tahun', 'bulan', 'daftarTahun', 
            'dataGrafikFormatted'
        ));
    }

    public function exportArusKasPdf(Request $request)
    {
        // Logika ini sama persis dengan method arusKas untuk memastikan data yang diekspor konsisten
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan');

        if ($bulan) {
            $tanggalAwalPeriode = Carbon::create($tahun, (int)$bulan, 1)->startOfMonth();
            $tanggalAkhirPeriode = $tanggalAwalPeriode->copy()->endOfMonth();
            $judulPeriode = $tanggalAwalPeriode->format('F Y');
        } else {
            $tanggalAwalPeriode = Carbon::create($tahun, 1, 1)->startOfYear();
            $tanggalAkhirPeriode = $tanggalAwalPeriode->copy()->endOfYear();
            $judulPeriode = 'Tahun ' . $tahun;
        }

        $saldoAwal = ArusKas::where('tanggal', '<', $tanggalAwalPeriode->toDateString())->sum(DB::raw('CASE WHEN tipe = "masuk" THEN jumlah ELSE -jumlah END'));
        
        $arusKasPeriodeIni = ArusKas::whereBetween('tanggal', [$tanggalAwalPeriode, $tanggalAkhirPeriode])->orderBy('tanggal')->orderBy('id')->get();

        $totalKasMasuk = $arusKasPeriodeIni->where('tipe', 'masuk')->sum('jumlah');
        $totalKasKeluar = $arusKasPeriodeIni->where('tipe', 'keluar')->sum('jumlah');
        $saldoAkhir = $saldoAwal + $totalKasMasuk - $totalKasKeluar;

        $operasionalMasuk = $arusKasPeriodeIni->where('tipe', 'masuk')->where('referensi_tipe', Transaction::class);
        
        // PERBAIKAN JUGA DI SINI untuk konsistensi data PDF
        $operasionalKeluar = $arusKasPeriodeIni->where('tipe', 'keluar')->filter(function ($item) {
            return in_array($item->referensi_tipe, [
                Pengadaan::class, 
                Gaji::class, 
                Beban::class
            ]) || $item->kategori === 'Operasional';
        });

        $investasi = $arusKasPeriodeIni->where('referensi_tipe', AsetTetap::class)->filter(fn ($item) => !str_contains(strtolower($item->deskripsi), 'modal'));
        $pendanaan = $arusKasPeriodeIni->where('referensi_tipe', AsetTetap::class)->filter(fn ($item) => str_contains(strtolower($item->deskripsi), 'modal'));

        $pdf = PDF::loadView('laporan.arus_kas_pdf', compact(
            'judulPeriode', 'saldoAwal', 'saldoAkhir',
            'operasionalMasuk', 'operasionalKeluar', 'investasi', 'pendanaan'
        ));

        $fileName = 'laporan-arus-kas-' . Str::slug($judulPeriode) . '.pdf';
        return $pdf->download($fileName);
    }
    
    public function labaRugi(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $tanggalAwal = Carbon::create($tahun, (int)$bulan, 1)->startOfMonth();
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();
        $pendapatanItems = Transaction::whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])->get();
        $totalPendapatan = $pendapatanItems->sum('total_penjualan');
        $hppItems = Pengadaan::whereBetween('tanggal_pembelian', [$tanggalAwal, $tanggalAkhir])->get();
        $gajiItems = Gaji::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->get();
        $bebanItems = Beban::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();
        $asetFisik = AsetTetap::where('masa_manfaat', '>', 0)->get();
        $totalBebanPenyusutan = 0;
        foreach ($asetFisik as $aset) {
            $penyusutanPerBulan = ($aset->harga_perolehan - $aset->nilai_residu) / ($aset->masa_manfaat * 12);
            $totalBebanPenyusutan += $penyusutanPerBulan;
        }
        $pengeluaran = collect([]);
        $hppItems->each(function ($item) use ($pengeluaran) {
            $pengeluaran->push(['tanggal' => $item->tanggal_pembelian, 'keterangan' => 'HPP: ' . $item->barang->nama, 'kategori' => 'HPP', 'jumlah' => $item->total_harga]);
        });
        $gajiItems->each(function ($item) use ($pengeluaran) {
            $pengeluaran->push(['tanggal' => $item->created_at, 'keterangan' => 'Gaji: ' . $item->karyawan->nama, 'kategori' => 'Beban Gaji', 'jumlah' => $item->gaji_bersih]);
        });
        $bebanItems->each(function ($item) use ($pengeluaran) {
            $pengeluaran->push(['tanggal' => $item->tanggal, 'keterangan' => $item->nama, 'kategori' => 'Beban Lainnya', 'jumlah' => $item->jumlah]);
        });
        if ($totalBebanPenyusutan > 0) {
            $pengeluaran->push(['tanggal' => $tanggalAkhir, 'keterangan' => 'Beban Penyusutan Aset Tetap', 'kategori' => 'Beban Penyusutan', 'jumlah' => $totalBebanPenyusutan]);
        }
        $pengeluaran = $pengeluaran->sortBy('tanggal');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $labaBersih = $totalPendapatan - $totalPengeluaran;
        $daftarTahun = Transaction::selectRaw('YEAR(tanggal_transaksi) as tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([date('Y')]);
        }
        return view('laporan.laba_rugi', compact(
            'totalPendapatan', 'pendapatanItems',
            'totalPengeluaran', 'pengeluaran',
            'labaBersih',
            'tahun', 'bulan', 'daftarTahun'
        ));
    }

    public function exportLabaRugiPdf(Request $request)
    {
        // Logika ini sama persis dengan method labaRugi
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $tanggalAwal = Carbon::create($tahun, (int)$bulan, 1)->startOfMonth();
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        $judulPeriode = 'Untuk Periode yang Berakhir pada ' . $tanggalAkhir->format('d F Y');

        $pendapatanItems = Transaction::whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])->get();
        $totalPendapatan = $pendapatanItems->sum('total_penjualan');
        $hppItems = Pengadaan::whereBetween('tanggal_pembelian', [$tanggalAwal, $tanggalAkhir])->get();
        $gajiItems = Gaji::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->get();
        $bebanItems = Beban::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();
        $asetFisik = AsetTetap::where('masa_manfaat', '>', 0)->get();
        $totalBebanPenyusutan = 0;
        foreach ($asetFisik as $aset) {
            $penyusutanPerBulan = ($aset->harga_perolehan - $aset->nilai_residu) / ($aset->masa_manfaat * 12);
            $totalBebanPenyusutan += $penyusutanPerBulan;
        }
        $pengeluaran = collect([]);
        $hppItems->each(fn($item) => $pengeluaran->push(['keterangan' => 'HPP: ' . $item->barang->nama, 'jumlah' => $item->total_harga]));
        $gajiItems->each(fn($item) => $pengeluaran->push(['keterangan' => 'Gaji: ' . $item->karyawan->nama, 'jumlah' => $item->gaji_bersih]));
        $bebanItems->each(fn($item) => $pengeluaran->push(['keterangan' => $item->nama, 'jumlah' => $item->jumlah]));
        if ($totalBebanPenyusutan > 0) {
            $pengeluaran->push(['keterangan' => 'Beban Penyusutan Aset Tetap', 'jumlah' => $totalBebanPenyusutan]);
        }
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $labaBersih = $totalPendapatan - $totalPengeluaran;

        $pdf = PDF::loadView('laporan.laba_rugi_pdf', compact(
            'judulPeriode', 'totalPendapatan', 'pendapatanItems',
            'totalPengeluaran', 'pengeluaran', 'labaBersih'
        ));
        
        $fileName = 'laporan-laba-rugi-' . $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->download($fileName);
    }

    public function neraca(Request $request)
    {
        $tanggalLaporan = $request->input('tanggal', date('Y-m-d'));
        $tanggalObj = Carbon::parse($tanggalLaporan)->endOfDay();
        $kas = ArusKas::where('created_at', '<=', $tanggalObj)->sum(DB::raw('CASE WHEN tipe = "masuk" THEN jumlah ELSE -jumlah END'));
        $asetTetapItems = AsetTetap::where('tanggal_perolehan', '<=', $tanggalObj)->get();
        $totalAkumulasiPenyusutan = 0;
        foreach ($asetTetapItems as $aset) {
            if ($aset->masa_manfaat > 0) {
                $penyusutan_per_bulan = ($aset->harga_perolehan - $aset->nilai_residu) / ($aset->masa_manfaat * 12);
                $bulan_berlalu = $aset->tanggal_perolehan->diffInMonths($tanggalObj);
                $akumulasi = $penyusutan_per_bulan * $bulan_berlalu;
                $totalAkumulasiPenyusutan += min($akumulasi, $aset->harga_perolehan - $aset->nilai_residu);
            }
        }
        $asetFisikItems = $asetTetapItems->where('masa_manfaat', '>', 0);
        $asetKasItems = $asetTetapItems->where('masa_manfaat', '=', 0);
        $nilaiBukuAsetFisik = $asetFisikItems->sum('harga_perolehan') - $totalAkumulasiPenyusutan;
        $totalAset = $kas + $nilaiBukuAsetFisik;
        $totalLiabilitas = 0;
        $modalDisetor = $asetKasItems->sum('harga_perolehan');
        $totalPendapatan = Transaction::where('tanggal_transaksi', '<=', $tanggalLaporan)->sum('total_penjualan');
        $totalHpp = Pengadaan::where('tanggal_pembelian', '<=', $tanggalLaporan)->sum('total_harga');
        $totalBebanLain = Beban::where('tanggal', '<=', $tanggalLaporan)->sum('jumlah');
        $totalBebanGaji = Gaji::where('created_at', '<=', $tanggalObj)->sum('gaji_bersih');
        $labaDitahan = $totalPendapatan - ($totalHpp + $totalBebanLain + $totalBebanGaji + $totalAkumulasiPenyusutan);
        $totalEkuitas = $modalDisetor + $labaDitahan;
        $totalLiabilitasEkuitas = $totalLiabilitas + $totalEkuitas;

        return view('laporan.neraca', compact(
            'tanggalLaporan', 'kas', 'asetFisikItems', 'totalAkumulasiPenyusutan', 'totalAset',
            'totalLiabilitas',
            'modalDisetor', 'labaDitahan', 'totalEkuitas',
            'totalLiabilitasEkuitas'
        ));
    }

    public function exportNeracaPdf(Request $request)
    {
        // Logika ini sama persis dengan method neraca
        $tanggalLaporan = $request->input('tanggal', date('Y-m-d'));
        $tanggalObj = Carbon::parse($tanggalLaporan)->endOfDay();
        $kas = ArusKas::where('created_at', '<=', $tanggalObj)->sum(DB::raw('CASE WHEN tipe = "masuk" THEN jumlah ELSE -jumlah END'));
        $asetTetapItems = AsetTetap::where('tanggal_perolehan', '<=', $tanggalObj)->get();
        $totalAkumulasiPenyusutan = 0;
        foreach ($asetTetapItems as $aset) {
            if ($aset->masa_manfaat > 0) {
                $penyusutan_per_bulan = ($aset->harga_perolehan - $aset->nilai_residu) / ($aset->masa_manfaat * 12);
                $bulan_berlalu = $aset->tanggal_perolehan->diffInMonths($tanggalObj);
                $akumulasi = $penyusutan_per_bulan * $bulan_berlalu;
                $totalAkumulasiPenyusutan += min($akumulasi, $aset->harga_perolehan - $aset->nilai_residu);
            }
        }
        $asetFisikItems = $asetTetapItems->where('masa_manfaat', '>', 0);
        $asetKasItems = $asetTetapItems->where('masa_manfaat', '=', 0);
        $nilaiBukuAsetFisik = $asetFisikItems->sum('harga_perolehan') - $totalAkumulasiPenyusutan;
        $totalAset = $kas + $nilaiBukuAsetFisik;
        $totalLiabilitas = 0;
        $modalDisetor = $asetKasItems->sum('harga_perolehan');
        $totalPendapatan = Transaction::where('tanggal_transaksi', '<=', $tanggalLaporan)->sum('total_penjualan');
        $totalHpp = Pengadaan::where('tanggal_pembelian', '<=', $tanggalLaporan)->sum('total_harga');
        $totalBebanLain = Beban::where('tanggal', '<=', $tanggalLaporan)->sum('jumlah');
        $totalBebanGaji = Gaji::where('created_at', '<=', $tanggalObj)->sum('gaji_bersih');
        $labaDitahan = $totalPendapatan - ($totalHpp + $totalBebanLain + $totalBebanGaji + $totalAkumulasiPenyusutan);
        $totalEkuitas = $modalDisetor + $labaDitahan;
        $totalLiabilitasEkuitas = $totalLiabilitas + $totalEkuitas;

        $pdf = PDF::loadView('laporan.neraca_pdf', compact(
            'tanggalLaporan', 'kas', 'asetFisikItems', 'totalAkumulasiPenyusutan', 'totalAset',
            'totalLiabilitas',
            'modalDisetor', 'labaDitahan', 'totalEkuitas',
            'totalLiabilitasEkuitas'
        ));
        
        $fileName = 'laporan-neraca-per-' . $tanggalLaporan . '.pdf';
        return $pdf->download($fileName);
    }
}
