<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\ArusKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;

class PengadaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengadaan::query()->with('supplier', 'barang');

        // PENYEMPURNAAN: Menambahkan logika filter yang sudah ada di view
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pembelian', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pembelian', '<=', $request->sampai);
        }
        if ($request->filled('barang_id')) {
            // Jika filter barang dipilih, kita perlu mencari semua invoice yang mengandung barang tsb.
            $invoiceNumbers = Pengadaan::where('barang_id', $request->barang_id)->pluck('no_invoice')->unique();
            $query->whereIn('no_invoice', $invoiceNumbers);
        }

        $allPengadaans = $query->latest('tanggal_pembelian')->get();
        $pengadaansByInvoice = $allPengadaans->groupBy('no_invoice');
        
        $barangs = Barang::orderBy('nama')->get();
        $totalTransaksi = $pengadaansByInvoice->count();
        $totalPengeluaran = $allPengadaans->sum('total_harga');
        $totalItemMasuk = $allPengadaans->sum('jumlah_masuk');
        $rataRataPerTransaksi = ($totalTransaksi > 0) ? $totalPengeluaran / $totalTransaksi : 0;

        return view('pengadaan.index', compact(
            'pengadaansByInvoice', 
            'barangs', 
            'totalTransaksi', 
            'totalPengeluaran',
            'totalItemMasuk',
            'rataRataPerTransaksi'
        ));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $barangs = Barang::orderBy('nama')->get();
        return view('pengadaan.create', compact('suppliers', 'barangs'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'no_invoice' => 'required|string|max:255',
            'tanggal_pembelian' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'keterangan' => 'nullable|string',
            'bukti' => 'nullable|image|max:2048',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah_masuk' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                $buktiPath = $request->file('bukti')->store('public/bukti_pengadaan');
            }

            $grandTotal = 0;

            foreach ($validatedData['items'] as $itemData) {
                $totalHargaItem = $itemData['jumlah_masuk'] * $itemData['harga_beli'];
                $grandTotal += $totalHargaItem;

                Pengadaan::create([
                    'no_invoice' => $validatedData['no_invoice'],
                    'tanggal_pembelian' => $validatedData['tanggal_pembelian'],
                    'supplier_id' => $validatedData['supplier_id'],
                    'keterangan' => $validatedData['keterangan'],
                    'bukti' => $buktiPath,
                    'barang_id' => $itemData['barang_id'],
                    'jumlah_masuk' => $itemData['jumlah_masuk'],
                    'harga_beli' => $itemData['harga_beli'],
                    'total_harga' => $totalHargaItem,
                ]);

                $barang = Barang::find($itemData['barang_id']);
                $barang->increment('stok', $itemData['jumlah_masuk']);
            }

            ArusKas::create([
                'tanggal' => $validatedData['tanggal_pembelian'],
                'jumlah' => $grandTotal,
                'tipe' => 'keluar',
                'deskripsi' => 'Pembelian bahan baku (Invoice: ' . $validatedData['no_invoice'] . ')',
                'kategori' => 'Operasional',
            ]);

            DB::commit();
            return redirect()->route('pengadaan.index')->with('success', 'Transaksi pengadaan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($buktiPath)) {
                Storage::delete($buktiPath);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    
    public function destroy($no_invoice)
    {
        DB::beginTransaction();
        try {
            $pengadaans = Pengadaan::where('no_invoice', $no_invoice)->get();

            if($pengadaans->isEmpty()){
                return back()->with('error', 'Invoice tidak ditemukan.');
            }

            ArusKas::where('deskripsi', 'like', '%(Invoice: ' . $no_invoice . ')%')->delete();

            $buktiPath = $pengadaans->first()->bukti;

            foreach ($pengadaans as $pengadaan) {
                $barang = Barang::find($pengadaan->barang_id);
                if ($barang) {
                    $barang->decrement('stok', $pengadaan->jumlah_masuk);
                }
                $pengadaan->delete();
            }
            
            if ($buktiPath && Storage::exists($buktiPath)) {
                Storage::delete($buktiPath);
            }

            DB::commit();
            return redirect()->route('pengadaan.index')->with('success', 'Seluruh data untuk invoice #' . $no_invoice . ' berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Handle PDF export request for procurement.
     */
    public function exportPdf(Request $request)
    {
        $query = Pengadaan::query()->with('supplier', 'barang');

        $dari = $request->input('dari');
        $sampai = $request->input('sampai');

        // Apply filters
        if ($dari) {
            $query->whereDate('tanggal_pembelian', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tanggal_pembelian', '<=', $sampai);
        }
        if ($request->filled('barang_id')) {
            $invoiceNumbers = Pengadaan::where('barang_id', $request->barang_id)->pluck('no_invoice')->unique();
            $query->whereIn('no_invoice', $invoiceNumbers);
        }

        $allPengadaans = $query->latest('tanggal_pembelian')->get();
        $pengadaansByInvoice = $allPengadaans->groupBy('no_invoice');
        
        $totalPengeluaran = $allPengadaans->sum('total_harga');

        // Generate PDF
        $pdf = PDF::loadView('pengadaan.pdf', compact('pengadaansByInvoice', 'totalPengeluaran', 'dari', 'sampai'));
        
        $fileName = 'laporan-pengadaan-' . date('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }
}
