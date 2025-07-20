<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\ArusKas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use PDF;

class TransactionController extends Controller
{


 public function fetchFromApi()
{
    // Bagian kode untuk mengambil data dari API asli kita nonaktifkan sementara
    // $apiUrl = 'http://127.0.0.1:8000/api/sales'; 

    try {
        // --- MULAI BLOK KODE UNTUK TESTING ---

        // Cek dulu apakah file dummy-nya ada di storage/app/
        if (!Storage::exists('dummy_sales_data.json')) {
            return back()->with('error', 'File dummy testing (dummy_sales_data.json) tidak ditemukan.');
        }

        // Ambil konten dari file lokal
        $jsonContent = Storage::get('dummy_sales_data.json');

        // Ubah konten JSON (string) menjadi array PHP
        $salesData = json_decode($jsonContent, true);

        // --- SELESAI BLOK KODE UNTUK TESTING ---


        // Logika di bawah ini tidak perlu diubah, karena akan memproses variabel $salesData
        // baik dari API asli maupun dari file dummy.
        
        $newTransactionsCount = 0;
        DB::beginTransaction();

        foreach ($salesData as $sale) {
            $existingTransaction = Transaction::where('api_sale_id', $sale['id'])->first();

            if (!$existingTransaction) {
                $keterangan = 'Penjualan oleh ' . ($sale['user']['name'] ?? 'N/A');
                if (!empty($sale['customer_name'])) {
                    $keterangan .= ' kepada ' . $sale['customer_name'];
                }

                $transaksi = Transaction::create([
                    'api_sale_id'       => $sale['id'],
                    'tanggal_transaksi' => Carbon::parse($sale['created_at'])->toDateString(),
                    'total_penjualan'   => $sale['total_akhir'],
                    'keterangan'        => $keterangan,
                    'items_detail'      => json_encode($sale['items']),
                ]);

                ArusKas::create([
                    'tanggal'        => $transaksi->tanggal_transaksi,
                    'jumlah'         => $transaksi->total_penjualan,
                    'tipe'           => 'masuk',
                    'deskripsi'      => $keterangan,
                    'referensi_id'   => $transaksi->id,
                    'referensi_tipe' => Transaction::class,
                ]);

                $newTransactionsCount++;
            }
        }

        DB::commit();

        if ($newTransactionsCount > 0) {
            return redirect()->route('transaksi.index')->with('success', "TESTING BERHASIL: {$newTransactionsCount} transaksi baru dari file lokal berhasil disinkronkan.");
        } else {
            return redirect()->route('transaksi.index')->with('success', 'TESTING: Tidak ada data transaksi baru dari file lokal untuk disinkronkan.');
        }

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Terjadi kesalahan saat sinkronisasi: ' . $e->getMessage());
    }
}


    public function index(Request $request)
    {
        $periode = $request->input('periode', 'bulanan');
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $bulan = (int)$request->input('bulan', date('m')); 
        $tahun = $request->input('tahun', date('Y'));

        $query = Transaction::query();

        if ($periode == 'harian') {
            $query->whereDate('tanggal_transaksi', $tanggal);
            $judulPeriode = Carbon::parse($tanggal)->format('d F Y');
        } else { // bulanan
            $query->whereMonth('tanggal_transaksi', $bulan)->whereYear('tanggal_transaksi', $tahun);
            $judulPeriode = Carbon::create()->month($bulan)->format('F') . ' ' . $tahun;
        }

        $filteredTransactions = $query->clone()->get();
        $totalPemasukan = $filteredTransactions->sum('total_penjualan');
        $jumlahTransaksi = $filteredTransactions->count();

        $transactions = $query->latest('tanggal_transaksi')->paginate(15)->withQueryString();

        $daftarTahun = Transaction::selectRaw("YEAR(tanggal_transaksi) as tahun")
                                        ->distinct()
                                        ->orderBy('tahun', 'desc')
                                        ->pluck('tahun');
        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([date('Y')]);
        }

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

    public function create()
    {
        return view('transaksi.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'total_penjualan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $buktiPath = null;
            if ($request->hasFile('bukti')) {
               // Menyimpan file ke public/bukti_transaksi menggunakan disk 'public_uploads'
                $buktiPath = $request->file('bukti')->store('bukti_transaksi', 'public_uploads');
            }
            
            $validatedData['bukti'] = $buktiPath;

            $transaksi = Transaction::create($validatedData);

            // PERBAIKAN: Menyimpan deskripsi dari form ke kolom 'deskripsi' di tabel arus_kas
            ArusKas::create([
                'tanggal' => $transaksi->tanggal_transaksi,
                'jumlah' => $transaksi->total_penjualan,
                'tipe' => 'masuk',
                'deskripsi' => $validatedData['keterangan'] ?: 'Pemasukan dari Penjualan', // Ini adalah perbaikannya
                'referensi_id' => $transaksi->id,
                'referensi_tipe' => Transaction::class,
            ]);

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Rekap penjualan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($buktiPath)) {
                Storage::delete($buktiPath);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);

            ArusKas::where('referensi_tipe', Transaction::class)
                   ->where('referensi_id', $transaction->id)
                   ->delete();

            if ($transaction->bukti) {
                Storage::disk('public_uploads')->delete($transaction->bukti);
            }

            $transaction->delete();

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Rekap transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus rekap: ' . $e->getMessage());
        }
    }

    /**
     * Handle PDF export request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $periode = $request->input('periode', 'bulanan');
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $bulan = (int)$request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $query = Transaction::query();

        if ($periode == 'harian') {
            $query->whereDate('tanggal_transaksi', $tanggal);
            $judulPeriode = Carbon::parse($tanggal)->format('d F Y');
        } else { // bulanan
            $query->whereMonth('tanggal_transaksi', $bulan)->whereYear('tanggal_transaksi', $tahun);
            $judulPeriode = Carbon::create()->month($bulan)->format('F') . ' ' . $tahun;
        }

        // Get all transactions for the PDF, not paginated
        $transactions = $query->latest('tanggal_transaksi')->get();
        $totalPemasukan = $transactions->sum('total_penjualan');

        // Load the view and pass the data
        $pdf = PDF::loadView('transaksi.pdf', compact('transactions', 'totalPemasukan', 'judulPeriode'));
        
        // Generate a dynamic filename
        $fileName = 'laporan-penjualan-' . Str::slug($judulPeriode) . '.pdf';

        // Download the PDF file
        return $pdf->download($fileName);
    }
}
