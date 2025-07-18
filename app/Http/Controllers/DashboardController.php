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
        // Set timezone ke Asia/Jakarta dan dapatkan tanggal hari ini & bulan ini
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->copy()->toDateString();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        
        // ===== DATA UNTUK KARTU METRIK UTAMA =====
        $totalKas = ArusKas::sum('jumlah');
        $totalAset = AsetTetap::where('masa_manfaat', '>', 0)->get()->sum('nilai_buku');
        $totalKaryawan = Karyawan::where('aktif', true)->count();

        // Pendapatan & Pengeluaran (Bulan Berjalan)
        $pendapatanBulanIni = Transaction::whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])->sum('total_penjualan');
        $pengeluaranGaji = Gaji::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('gaji_bersih');
        $pengeluaranPengadaan = Pengadaan::whereBetween('tanggal_pembelian', [$startOfMonth, $endOfMonth])->sum('total_harga');
        $pengeluaranBeban = Beban::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('jumlah');
        $totalPengeluaranBulanIni = $pengeluaranGaji + $pengeluaranPengadaan + $pengeluaranBeban;
        
        $labaRugiBulanIni = $pendapatanBulanIni - $totalPengeluaranBulanIni;
        
        // ===== DATA UNTUK WIDGET DINAMIS =====

        // 1. Margin Laba Bersih (Net Profit Margin)
        // Rumus: (Laba Bersih / Pendapatan) * 100%
        $marjinLabaBersih = ($pendapatanBulanIni > 0) ? ($labaRugiBulanIni / $pendapatanBulanIni) * 100 : 0;

        // 2. Efisiensi Operasional
        // Efisiensi = (Total Pengeluaran / Total Pendapatan) * 100%
        $efisiensiOperasional = ($pendapatanBulanIni > 0) ? ($totalPengeluaranBulanIni / $pendapatanBulanIni) * 100 : 0;

        // 3. Aktivitas Hari Ini
        $aktivitasMasukHariIni = ArusKas::where('tipe', 'masuk')->whereDate('tanggal', $today)->count();
        $aktivitasKeluarHariIni = ArusKas::where('tipe', 'keluar')->whereDate('tanggal', $today)->count();

        // ===== DATA UNTUK GRAFIK =====

        // 1. Grafik Arus Kas Bulanan (6 Bulan Terakhir)
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
        $pengeluaranChart = [
            'labels' => ['Gaji', 'Pengadaan', 'Beban Operasional'],
            'data' => [$pengeluaranGaji, $pengeluaranPengadaan, $pengeluaranBeban],
        ];

        // ===== DATA UNTUK TABEL TRANSAKSI TERBARU =====
        $transaksiTerbaru = ArusKas::latest()->take(5)->get();

        return view('dashboard', [
            // Data Kartu Utama
            'totalKas' => $totalKas,
            'totalAset' => $totalAset,
            'labaRugiBulanIni' => $labaRugiBulanIni,
            'totalKaryawan' => $totalKaryawan,
            
            // Data Widget Dinamis
            'marjinLabaBersih' => $marjinLabaBersih,
            'efisiensiOperasional' => $efisiensiOperasional,
            'aktivitasMasukHariIni' => $aktivitasMasukHariIni,
            'aktivitasKeluarHariIni' => $aktivitasKeluarHariIni,

            // Data Grafik & Tabel
            'arusKasChart' => $arusKasChart,
            'pengeluaranChart' => $pengeluaranChart,
            'transaksiTerbaru' => $transaksiTerbaru,
        ]);
    }
}
