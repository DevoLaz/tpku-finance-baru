<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\ArusKas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
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
                $buktiPath = $request->file('bukti')->store('public/bukti_transaksi');
            }
            
            $validatedData['bukti'] = $buktiPath;

            $transaksi = Transaction::create($validatedData);

            ArusKas::create([
                'tanggal' => $transaksi->tanggal_transaksi,
                'jumlah' => $transaksi->total_penjualan,
                'tipe' => 'masuk',
                'deskripsi' => 'Pemasukan dari penjualan: ' . ($transaksi->keterangan ?: "Rekap tgl " . $transaksi->tanggal_transaksi->format('d-m-Y')),
                'keterangan' => 'Pemasukan dari penjualan',
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

    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            ArusKas::where('referensi_tipe', Transaction::class)
                   ->where('referensi_id', $transaction->id)
                   ->delete();

            if ($transaction->bukti) {
                Storage::delete($transaction->bukti);
            }

            $transaction->delete();
            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Rekap transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus rekap: ' . $e->getMessage());
        }
    }
}
