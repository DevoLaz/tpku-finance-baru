<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\ArusKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengadaansExport;
use Illuminate\Support\Facades\Http;

class PengadaanController extends Controller
{
    /**
     * Tampilkan daftar pengadaan, dengan filter tanggal & barang.
     */
   public function index(Request $request)
{
    // 1. Ambil data pengadaan dari DB (relasi + filter)
    $pengadaansByInvoice = $this->fetchFiltered($request)
        ->orderBy('tanggal_pembelian', 'desc')
        ->get()
        ->groupBy('no_invoice');

    $barangs = Barang::orderBy('nama')->get();

    // 2. Hitung statistik
    $all                = $pengadaansByInvoice->flatten();
    $totalTransaksi     = $pengadaansByInvoice->count();
    $totalPengeluaran   = $all->sum('total_harga');
    $totalItemMasuk     = $all->sum('jumlah_masuk');
    $rataRataPerTransaksi = $totalTransaksi
        ? $totalPengeluaran / $totalTransaksi
        : 0;

    // 3. Ambil data dari API eksternal (request pengadaan mentah)
    $response = Http::get('http://143.198.91.106/api/pengajuanbarangmentah');
    $requestItems = collect();

    if ($response->successful()) {
        $requestItems = collect($response->json('data'))
            ->where('status_pengadaan', 'diajukan');
    }

    // 4. Kirim semuanya ke view
    return view('pengadaan.index', compact(
        'pengadaansByInvoice',
        'barangs',
        'totalTransaksi',
        'totalPengeluaran',
        'totalItemMasuk',
        'rataRataPerTransaksi',
        'requestItems'
    ));
}

    /**
     * Form tambah satu invoice (multi‐item).
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('nama','asc')->get();
        $barangs   = Barang::orderBy('nama','asc')->get();

        
        $requestId = request('req_id');
        $requestItem = null;

        if ($requestId) {
        $api = Http::get('http://143.198.91.106/api/pengajuanbarangmentah');
        if ($api->successful()) {
        $requestItem = collect($api->json('data'))
            ->firstWhere('id', (string)$requestId);
        }
            }
        return view('pengadaan.create', compact('suppliers','barangs','requestItem'));

    }

    /**
     * Simpan transaksi pengadaan (multi‐item) + insert ke kas.
     */
    public function store(Request $request)
{
    $v = $request->validate([
        'no_invoice'           => 'required|string|max:255',
        'tanggal_pembelian'    => 'required|date',
        'supplier_id'          => 'required|exists:suppliers,id',
        'keterangan'           => 'nullable|string',
        'bukti'                => 'nullable|image|max:2048',
        'items'                => 'required|array|min:1',
        'items.*.barang_id'    => 'required|exists:barangs,id',
        'items.*.jumlah_masuk' => 'required|integer|min:1',
        'items.*.harga_beli'   => 'required|numeric|min:0',
        'request_id'           => 'nullable|integer', // request dari API eksternal
    ]);

    DB::beginTransaction();
    try {
        // 1) Upload bukti jika ada
        $buktiPath = $request->hasFile('bukti')
            ? $request->file('bukti')->store('bukti_pengadaan', 'public')
            : null;

        $grandTotal       = 0;
        $firstPengadaanId = null;

        // 2) Simpan masing‐masing item
        foreach ($v['items'] as $i => $item) {
            $totalItem = $item['jumlah_masuk'] * $item['harga_beli'];
            $grandTotal += $totalItem;

            $peng = Pengadaan::create([
                'no_invoice'        => $v['no_invoice'],
                'tanggal_pembelian' => $v['tanggal_pembelian'],
                'supplier_id'       => $v['supplier_id'],
                'keterangan'        => $v['keterangan'],
                'bukti'             => $buktiPath,
                'barang_id'         => $item['barang_id'],
                'jumlah_masuk'      => $item['jumlah_masuk'],
                'harga_beli'        => $item['harga_beli'],
                'total_harga'       => $totalItem,
            ]);

            if ($i === 0) {
                $firstPengadaanId = $peng->id;
            }

            // update stok barang
            Barang::find($item['barang_id'])
                ->increment('stok', $item['jumlah_masuk']);
        }

        // 3) Catat di Arus Kas
        ArusKas::create([
            'tanggal'        => $v['tanggal_pembelian'],
            'jumlah'         => $grandTotal,
            'tipe'           => 'keluar',
            'deskripsi'      => 'Pembelian bahan (Inv: ' . $v['no_invoice'] . ')',
            'kategori'       => 'Operasional',
            'referensi_tipe' => Pengadaan::class,
            'referensi_id'   => $firstPengadaanId,
        ]);

        DB::commit();

        // 4) Jika ini dari request API eksternal, ubah status jadi "sudah diadakan"
      if ($request->filled('request_id')) {
    try {
        // 1. Ambil SELURUH DAFTAR dari API (URL tanpa ID)
        $getResponse = Http::get("http://143.198.91.106/api/pengajuanbarangmentah");

        if ($getResponse->successful()) {
            // 2. Gunakan Collection Laravel untuk mencari item yang sesuai
            $allRequests = collect($getResponse->json()['data'] ?? []);
            
            // Cari item yang id-nya sama dengan request_id dari form.
            // Konversi ke string agar cocok dengan tipe data dari API ('id' => "2")
            $itemToUpdate = $allRequests->firstWhere('id', (string)$request->request_id);

            // 3. Jika itemnya KETEMU di dalam daftar
            if ($itemToUpdate) {
                // 4. Ubah statusnya dan siapkan untuk dikirim kembali
                $itemToUpdate['status_pengadaan'] = 'sudah diadakan';

                // 5. Lakukan PUT request ke URL spesifik dengan data yang sudah diubah
                $putResponse = Http::put("http://143.198.91.106/api/pengajuanbarangmentah/{$request->request_id}", $itemToUpdate);
                
                // Lempar exception jika PUT request gagal
                $putResponse->throw();

            } else {
                // Jika item tidak ditemukan dalam daftar
                \Log::warning("Request #{$request->request_id} tidak ditemukan di dalam daftar API.");
            }

        } else {
            // Jika request untuk mengambil seluruh daftar gagal
            \Log::error("Gagal mengambil daftar dari API. Status: " . $getResponse->status());
        }

    } catch (\Illuminate\Http\Client\RequestException $e) {
        \Log::error("GAGAL UPDATE API: HTTP Status: " . $e->response->status() . " | Response Body: " . $e->response->body());
    } catch (\Exception $e) {
        \Log::error("GAGAL UPDATE API (General Error): " . $e->getMessage());
    }
}
        return redirect()
            ->route('pengadaan.index')
            ->with('success', 'Transaksi pengadaan berhasil disimpan.');
    }

    catch (\Throwable $e) {
        DB::rollBack();
        if (!empty($buktiPath)) {
            Storage::disk('public')->delete($buktiPath);
        }
        return back()
            ->withInput()
            ->with('error', 'Gagal simpan: ' . $e->getMessage());
    }
}


    /**
     * Hapus seluruh baris untuk satu invoice.
     */
    public function destroy(string $no_invoice)
    {
        DB::beginTransaction();
        try {
            $rows = Pengadaan::where('no_invoice',$no_invoice)->get();
            if ($rows->isEmpty()) {
                return back()->with('error','Invoice tidak ditemukan.');
            }

            $firstId = $rows->first()->id;
            // rollback stok & hapus record
            foreach ($rows as $r) {
                Barang::find($r->barang_id)
                    ->decrement('stok',$r->jumlah_masuk);
                $r->delete();
            }
            // hapus entry kas
            ArusKas::where('referensi_tipe',Pengadaan::class)
                  ->where('referensi_id',$firstId)
                  ->delete();
            // hapus file bukti
            if ($rows->first()->bukti) {
                Storage::disk('public')
                       ->delete($rows->first()->bukti);
            }

            DB::commit();
            return redirect()
                ->route('pengadaan.index')
                ->with('success','Invoice #'.$no_invoice.' berhasil dihapus.');
        }
        catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->with('error','Gagal hapus: '.$e->getMessage());
        }
    }

    /**
     * Export PDF.
     */
    public function exportPdf(Request $request)
    {
        $filtered = $this->fetchFiltered($request)
            ->orderBy('tanggal_pembelian','desc')
            ->get()
            ->groupBy('no_invoice');
        $total = $filtered->flatten()->sum('total_harga');

        $pdf = PDF::loadView('pengadaan.pdf', [
            'pengadaansByInvoice'=> $filtered,
            'totalPengeluaran'   => $total,
            'dari'               => $request->dari,
            'sampai'             => $request->sampai,
        ]);

        return $pdf->download('laporan-pengadaan-'.date('Ymd').'.pdf');
    }

    /**
     * Export Excel.
     */
    public function exportExcel(Request $request)
    {
        $filtered = $this->fetchFiltered($request)
            ->orderBy('tanggal_pembelian','desc')
            ->get()
            ->groupBy('no_invoice');
        $total = $filtered->flatten()->sum('total_harga');

        return Excel::download(
            new PengadaansExport($filtered, $total),
            'laporan-pengadaan-'.date('Ymd').'.xlsx'
        );
    }

    /**
     * API endpoint untuk AJAX / eksternal.
     */
    public function apiIndex()
    {
        $rows = Pengadaan::with('supplier','barang')->get()
            ->map(fn($p) => [
                'pengadaan' => $p->toArray(),
                'supplier'  => $p->supplier,
                'barang'    => $p->barang,
            ]);

        return Response::json([
            'table' => 'pengadaans',
            'rows'  => $rows,
        ]);
    }

    /**
     * Builder query dengan filter tanggal & barang.
     */
    private function fetchFiltered(Request $r)
    {
        $q = Pengadaan::with('supplier','barang');
        if ($r->filled('dari')) {
            $q->whereDate('tanggal_pembelian','>=',$r->dari);
        }
        if ($r->filled('sampai')) {
            $q->whereDate('tanggal_pembelian','<=',$r->sampai);
        }
        if ($r->filled('barang_id')) {
            $invoices = Pengadaan::where('barang_id',$r->barang_id)
                                 ->pluck('no_invoice')
                                 ->unique();
            $q->whereIn('no_invoice',$invoices);
        }
        return $q;
    }
}
