<?php

namespace App\Http\Controllers;

use App\Models\ArusKas;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar rekap penjualan dengan filter dan ringkasan.
     */
    public function index(Request $request)
    {
        // Ambil input filter dari user
        $periode = $request->input('periode', 'bulanan');
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        // PERBAIKAN: Langsung ubah tipe data bulan menjadi integer
        $bulan = (int)$request->input('bulan', date('m')); 
        $tahun = $request->input('tahun', date('Y'));

        // Query dasar ke tabel transactions
        $query = Transaction::query();

        // Terapkan filter berdasarkan pilihan periode
        if ($periode == 'harian') {
            $query->whereDate('tanggal_transaksi', $tanggal);
            $judulPeriode = Carbon::parse($tanggal)->format('d F Y');
        } else { // bulanan
            $query->whereMonth('tanggal_transaksi', $bulan)->whereYear('tanggal_transaksi', $tahun);
            // Sekarang $bulan sudah pasti integer, jadi aman
            $judulPeriode = Carbon::create()->month($bulan)->format('F') . ' ' . $tahun;
        }

        // Ambil data yang sudah difilter SEBELUM di-paginate untuk perhitungan
        $filteredTransactions = $query->clone()->get();

        // Hitung total untuk summary cards
        $totalPemasukan = $filteredTransactions->sum('total_penjualan');
        $jumlahTransaksi = $filteredTransactions->count();

        // Ambil data untuk ditampilkan di tabel dengan pagination
        $transactions = $query->latest('tanggal_transaksi')->paginate(15)->withQueryString();

        // Siapkan data untuk dropdown filter tahun
        $daftarTahun = Transaction::selectRaw('YEAR(tanggal_transaksi) as tahun')
                                    ->distinct()
                                    ->orderBy('tahun', 'desc')
                                    ->pluck('tahun');
        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([date('Y')]);
        }

        // Kirim semua variabel yang dibutuhkan oleh view
        return view('transaksi.index', compact(
            'transactions',
            'totalPemasukan',
            'jumlahTransaksi',
            'periode',
            'tanggal',
            'bulan',
            'tahun',
            'daftarTahun',
            'judulPeriode'
        ));
    }

    /**
     * Menampilkan form untuk mencatat rekap penjualan baru.
     */
    public function create()
    {
        return view('transaksi.create');
    }

    /**
     * Menyimpan data rekap penjualan dan mencatatnya di arus kas.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'total_penjualan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $transaksi = Transaction::create($validatedData);

        ArusKas::create([
            'tanggal' => $transaksi->tanggal_transaksi,
            'jumlah' => $transaksi->total_penjualan,
            'tipe' => 'masuk',
            'deskripsi' => 'Pemasukan dari penjualan: ' . ($transaksi->keterangan ?: "Rekap tgl " . \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y')),
            'referensi_id' => $transaksi->id,
            'referensi_tipe' => Transaction::class,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Rekap penjualan berhasil disimpan & kas telah bertambah.');
    }
}
