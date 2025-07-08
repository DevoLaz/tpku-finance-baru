<?php

namespace App\Http\Controllers;

use App\Models\ArusKas;
use App\Models\AsetTetap;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Pengadaan;
use App\Models\Transaction;
use App\Models\Beban;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama dengan ringkasan data keuangan.
     */
    public function index()
    {
        // Set timezone ke Asia/Jakarta
        $now = Carbon::now('Asia/Jakarta');
        
        // ===== DATA UNTUK KARTU METRIK UTAMA =====
        $totalKas = ArusKas::sum('jumlah');
        $totalAset = AsetTetap::where('masa_manfaat', '>', 0)->get()->sum('nilai_buku');
        $totalKaryawan = Karyawan::where('aktif', true)->count();

        // Laba Rugi Bersih (Bulan Berjalan)
        $awalBulanIni = $now->copy()->startOfMonth();
        $akhirBulanIni = $now->copy()->endOfMonth();

        $pendapatanBulanIni = Transaction::whereBetween('tanggal_transaksi', [$awalBulanIni, $akhirBulanIni])->sum('total_penjualan');
        $pengeluaranBulanIni = ArusKas::where('tipe', 'keluar')->whereBetween('tanggal', [$awalBulanIni, $akhirBulanIni])->sum('jumlah') * -1;
        $labaRugiBulanIni = $pendapatanBulanIni - $pengeluaranBulanIni;
        
        // ===== DATA UNTUK GRAFIK =====

        // 1. Grafik Arus Kas (6 Bulan Terakhir)
        $dataArusKas = ArusKas::select(
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as periode"),
                DB::raw("SUM(CASE WHEN tipe = 'masuk' THEN jumlah ELSE 0 END) as kas_masuk"),
                DB::raw("SUM(CASE WHEN tipe = 'keluar' THEN ABS(jumlah) ELSE 0 END) as kas_keluar")
            )
            ->where('tanggal', '>=', $now->copy()->subMonths(5)->startOfMonth())
            ->groupBy('periode')
            ->orderBy('periode')
            ->get();
            
        $arusKasChart = $dataArusKas->map(function ($item) {
            return [
                'bulan' => Carbon::parse($item->periode)->isoFormat('MMM'),
                'masuk' => $item->kas_masuk,
                'keluar' => $item->kas_keluar
            ];
        });

        // 2. Grafik Komposisi Pengeluaran (Bulan Berjalan)
        $gaji = Gaji::whereBetween('created_at', [$awalBulanIni, $akhirBulanIni])->sum('gaji_bersih');
        $pengadaan = Pengadaan::whereBetween('tanggal_pembelian', [$awalBulanIni, $akhirBulanIni])->sum('total_harga');
        $beban = Beban::whereBetween('tanggal', [$awalBulanIni, $akhirBulanIni])->sum('jumlah');

        $pengeluaranChart = [
            'labels' => ['Gaji', 'Pengadaan', 'Beban Operasional'],
            'data' => [$gaji, $pengadaan, $beban],
        ];

        // ===== DATA UNTUK TABEL TRANSAKSI TERBARU =====
        $transaksiTerbaru = ArusKas::latest()->take(5)->get();

        return view('dashboard', [
            'totalKas' => $totalKas,
            'totalAset' => $totalAset,
            'labaRugiBulanIni' => $labaRugiBulanIni,
            'totalKaryawan' => $totalKaryawan,
            'arusKasChart' => $arusKasChart,
            'pengeluaranChart' => $pengeluaranChart,
            'transaksiTerbaru' => $transaksiTerbaru,
        ]);
    }
}