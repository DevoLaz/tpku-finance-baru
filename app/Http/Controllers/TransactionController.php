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
use App\Exports\TransactionsExport; // --- DITAMBAHKAN ---
use Maatwebsite\Excel\Facades\Excel; // --- DITAMBAHKAN ---

class TransactionController extends Controller
{
    /**
     * Fetch data from the external sales API and synchronize it.
     */
    public function fetchFromApi()
    {
        // Ganti dengan URL API penjualan Anda yang sebenarnya
        $apiUrl = 'http://152.42.182.221/api/api/sales/json';

        try {
            // --- BLOK KODE UNTUK MENGAMBIL DATA DARI API ASLI ---
            $response = Http::get($apiUrl);

            // Cek jika request ke API gagal (misal: server down, URL salah)
            if ($response->failed()) {
                return back()->with('error', 'Gagal terhubung ke API penjualan. Pastikan API server berjalan.');
            }

            // Ubah respons JSON dari API menjadi array PHP
            $salesData = $response->json();

            // Cek jika data yang diterima kosong atau tidak valid
            if (empty($salesData)) {
                 return redirect()->route('transaksi.index')->with('success', 'Tidak ada data transaksi baru dari API untuk disinkronkan.');
            }
            // --- SELESAI BLOK KODE API ---

            $newTransactionsCount = 0;
            DB::beginTransaction();

            foreach ($salesData as $sale) {
                // Cari transaksi berdasarkan ID unik dari API
                $existingTransaction = Transaction::where('api_sale_id', $sale['id'])->first();

                // Jika transaksi belum ada, buat baru
                if (!$existingTransaction) {
                    $keterangan = 'Penjualan oleh ' . ($sale['user']['name'] ?? 'N/A');
                    if (!empty($sale['customer_name'])) {
                        $keterangan .= ' kepada ' . $sale['customer_name'];
                    }

                    $transaksi = Transaction::create([
                        'api_sale_id'       => $sale['id'],
                        'tanggal_transaksi' => Carbon::parse($sale['created_at'])->toDateString(),
                        'total_penjualan'   => $sale['total'],
                        'keterangan'        => $keterangan,
                        'items_detail'      => json_encode($sale['items']), // Pastikan 'items' ada di response API
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
                return redirect()->route('transaksi.index')->with('success', "Sinkronisasi berhasil: {$newTransactionsCount} transaksi baru dari API telah ditambahkan.");
            } else {
                return redirect()->route('transaksi.index')->with('success', 'Semua data sudah sinkron. Tidak ada transaksi baru dari API.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            // Memberikan pesan error yang lebih detail untuk debugging
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
               $buktiPath = $request->file('bukti')->store('bukti_transaksi', 'public_uploads');
            }
            
            $validatedData['bukti'] = $buktiPath;

            $transaksi = Transaction::create($validatedData);

            ArusKas::create([
                'tanggal' => $transaksi->tanggal_transaksi,
                'jumlah' => $transaksi->total_penjualan,
                'tipe' => 'masuk',
                'deskripsi' => $validatedData['keterangan'] ?: 'Pemasukan dari Penjualan',
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

        $transactions = $query->latest('tanggal_transaksi')->get();
        $totalPemasukan = $transactions->sum('total_penjualan');

        $pdf = PDF::loadView('transaksi.pdf', compact('transactions', 'totalPemasukan', 'judulPeriode'));
        
        $fileName = 'laporan-penjualan-' . Str::slug($judulPeriode) . '.pdf';

        return $pdf->download($fileName);
    }
    
    // --- FUNGSI BARU UNTUK EXPORT EXCEL --- //
    public function exportExcel(Request $request)
    {
        $periode = $request->input('periode', 'bulanan');
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $bulan = (int)$request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
    
        $query = Transaction::query();
    
        if ($periode == 'harian') {
            $query->whereDate('tanggal_transaksi', $tanggal);
            $judulPeriode = Carbon::parse($tanggal)->format('d-m-Y');
        } else { // bulanan
            $query->whereMonth('tanggal_transaksi', $bulan)->whereYear('tanggal_transaksi', $tahun);
            $judulPeriode = Carbon::create()->month($bulan)->format('F') . ' ' . $tahun;
        }
    
        $transactions = $query->latest('tanggal_transaksi')->get();

        $totalPemasukan = $transactions->sum('total_penjualan');
    
        $fileName = 'laporan-penjualan-' . Str::slug($judulPeriode) . '.xlsx';
    
        return Excel::download(new TransactionsExport($transactions, $totalPemasukan), $fileName);
    }
}