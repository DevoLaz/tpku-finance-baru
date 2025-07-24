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
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    // ... (fungsi fetchFromApi, index, create, store tidak berubah dari versi terakhir Anda) ...
    public function fetchFromApi()
    {
        $apiUrl = 'http://152.42.182.221/api/api/sales/json';
        try {
            $response = Http::get($apiUrl);
            if ($response->failed()) {
                return back()->with('error', 'Gagal terhubung ke API penjualan. Pastikan API server berjalan.');
            }
            $salesData = $response->json();
            if (empty($salesData)) {
                return redirect()->route('transaksi.index')->with('success', 'Tidak ada data transaksi baru dari API untuk disinkronkan.');
            }
            $newTransactionsCount = 0;
            DB::beginTransaction();
            foreach ($salesData as $sale) {
                if (!Transaction::where('api_sale_id', $sale['id'])->exists()) {
                    $keterangan = 'Penjualan oleh ' . ($sale['user']['name'] ?? 'N/A') . (!empty($sale['customer_name']) ? ' kepada ' . $sale['customer_name'] : '');
                    $transaksi = Transaction::create([
                        'api_sale_id'       => $sale['id'],
                        'tanggal_transaksi' => Carbon::parse($sale['created_at'])->toDateString(),
                        'total_penjualan'   => $sale['total'],
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
            $message = $newTransactionsCount > 0 ? "Sinkronisasi berhasil: {$newTransactionsCount} transaksi baru ditambahkan." : 'Semua data sudah sinkron.';
            return redirect()->route('transaksi.index')->with('success', $message);
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
        } else {
            $query->whereMonth('tanggal_transaksi', $bulan)->whereYear('tanggal_transaksi', $tahun);
            $judulPeriode = Carbon::create()->month($bulan)->format('F') . ' ' . $tahun;
        }
        $filteredTransactions = $query->clone()->get();
        $totalPemasukan = $filteredTransactions->sum('total_penjualan');
        $jumlahTransaksi = $filteredTransactions->count();
        $transactions = $query->latest('tanggal_transaksi')->paginate(15)->withQueryString();
        $daftarTahun = Transaction::selectRaw("YEAR(tanggal_transaksi) as tahun")->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([date('Y')]);
        }
        return view('transaksi.index', compact('transactions', 'totalPemasukan', 'jumlahTransaksi', 'periode', 'tanggal', 'bulan', 'tahun', 'daftarTahun', 'judulPeriode'));
    }

    public function create()
    {
        return view('transaksi.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'total_penjualan'   => 'required|numeric|min:0',
            'keterangan'        => 'nullable|string|max:255',
            'bukti'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
                'tanggal'        => $transaksi->tanggal_transaksi,
                'jumlah'         => $transaksi->total_penjualan,
                'tipe'           => 'masuk',
                'deskripsi'      => $validatedData['keterangan'] ?: 'Pemasukan dari Penjualan',
                'referensi_id'   => $transaksi->id,
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

    /**
     * Helper function to check if a transaction period is locked for editing/deleting.
     * A period is locked if the current date is after the end of the transaction's month.
     *
     * @param Carbon $transactionDate The date of the transaction.
     * @return bool True if the period is locked, false otherwise.
     */
    private function isPeriodLocked(Carbon $transactionDate): bool
    {
        return now()->startOfDay()->isAfter($transactionDate->copy()->endOfMonth());
    }

    public function edit(Transaction $transaction)
    {
        if ($this->isPeriodLocked(Carbon::parse($transaction->tanggal_transaksi))) {
            return response()->json(['message' => 'Gagal! Transaksi dari periode sebelumnya tidak dapat diedit.'], 403);
        }
        return response()->json($transaction);
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($this->isPeriodLocked(Carbon::parse($transaction->tanggal_transaksi))) {
            return response()->json(['message' => 'Gagal! Transaksi dari periode sebelumnya tidak dapat diubah.'], 403);
        }

        $validatedData = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'total_penjualan'   => 'required|numeric|min:0',
            'keterangan'        => 'nullable|string|max:255',
            'bukti'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('bukti')) {
                if ($transaction->bukti) {
                    Storage::disk('public_uploads')->delete($transaction->bukti);
                }
                $validatedData['bukti'] = $request->file('bukti')->store('bukti_transaksi', 'public_uploads');
            }
            $transaction->update($validatedData);
            $arusKas = ArusKas::where('referensi_id', $transaction->id)->where('referensi_tipe', Transaction::class)->first();
            if ($arusKas) {
                $arusKas->update([
                    'tanggal'   => $transaction->tanggal_transaksi,
                    'jumlah'    => $transaction->total_penjualan,
                    'deskripsi' => $validatedData['keterangan'] ?: 'Pemasukan dari Penjualan',
                ]);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil diperbarui.', 'transaction' => $transaction->fresh()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Transaction $transaction)
    {
        if ($this->isPeriodLocked(Carbon::parse($transaction->tanggal_transaksi))) {
            return response()->json(['success' => false, 'message' => 'Gagal! Transaksi dari periode sebelumnya tidak dapat dihapus.'], 403);
        }

        DB::beginTransaction();
        try {
            ArusKas::where('referensi_tipe', Transaction::class)->where('referensi_id', $transaction->id)->delete();
            if ($transaction->bukti) {
                Storage::disk('public_uploads')->delete($transaction->bukti);
            }
            $transaction->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Rekap transaksi berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus rekap: ' . $e->getMessage()], 500);
        }
    }
    
    // ... (fungsi exportPdf dan exportExcel tidak berubah) ...
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
        } else {
            $query->whereMonth('tanggal_transaksi', $bulan)->whereYear('tanggal_transaksi', $tahun);
            $judulPeriode = Carbon::create()->month($bulan)->format('F') . ' ' . $tahun;
        }
        $transactions = $query->latest('tanggal_transaksi')->get();
        $totalPemasukan = $transactions->sum('total_penjualan');
        $pdf = PDF::loadView('transaksi.pdf', compact('transactions', 'totalPemasukan', 'judulPeriode'));
        $fileName = 'laporan-penjualan-' . Str::slug($judulPeriode) . '.pdf';
        return $pdf->download($fileName);
    }
    
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
        } else {
            $query->whereMonth('tanggal_transaksi', $bulan)->whereYear('tanggal_transaksi', $tahun);
            $judulPeriode = Carbon::create()->month($bulan)->format('F') . ' ' . $tahun;
        }
        $transactions = $query->latest('tanggal_transaksi')->get();
        $totalPemasukan = $transactions->sum('total_penjualan');
        $fileName = 'laporan-penjualan-' . Str::slug($judulPeriode) . '.xlsx';
        return Excel::download(new TransactionsExport($transactions, $totalPemasukan), $fileName);
    }
}
