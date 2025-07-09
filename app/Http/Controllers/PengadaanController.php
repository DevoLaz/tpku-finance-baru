<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pengadaan;
use App\Models\Supplier;
use App\Models\ArusKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PengadaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengadaan::with(['barang', 'supplier']);

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pembelian', [$request->dari, $request->sampai]);
        }
        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $filteredPengadaans = $query->latest('tanggal_pembelian')->get();

        $totalPengeluaran = $filteredPengadaans->sum('total_harga');
        $totalItemMasuk = $filteredPengadaans->sum('jumlah_masuk');
        $totalTransaksi = $filteredPengadaans->unique('no_invoice')->count();
        $rataRataPerTransaksi = ($totalTransaksi > 0) ? $totalPengeluaran / $totalTransaksi : 0;

        $pengadaansByInvoice = $filteredPengadaans->groupBy('no_invoice');
        // PERBAIKAN: Menggunakan 'nama' sesuai dengan nama kolom di tabel barangs
        $barangs = Barang::orderBy('nama')->get(); 
        
        return view('pengadaan.index', compact(
            'pengadaansByInvoice',
            'barangs',
            'totalPengeluaran',
            'totalItemMasuk',
            'totalTransaksi',
            'rataRataPerTransaksi'
        ));
    }

    public function create()
    {
        // PERBAIKAN: Menggunakan 'nama' sesuai dengan nama kolom di tabel barangs
        $barangs = Barang::orderBy('nama')->get();
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        return view('pengadaan.create', compact('barangs', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'no_invoice' => 'required|string|unique:pengadaans,no_invoice',
            'keterangan' => 'nullable|string',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah_masuk' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
        ]);

        $buktiPath = null;
        DB::beginTransaction();
        try {
            if ($request->hasFile('bukti')) {
                $buktiPath = $request->file('bukti')->store('public/bukti_pengadaan');
            }

            $grandTotal = 0;
            $firstPengadaanId = null;

            foreach ($validatedData['items'] as $itemData) {
                $barang = Barang::find($itemData['barang_id']);
                $totalHargaItem = $itemData['jumlah_masuk'] * $itemData['harga_beli'];
                $grandTotal += $totalHargaItem;

                $pengadaan = Pengadaan::create([
                    'supplier_id' => $validatedData['supplier_id'],
                    'tanggal_pembelian' => $validatedData['tanggal_pembelian'],
                    'no_invoice' => $validatedData['no_invoice'],
                    'keterangan' => $validatedData['keterangan'],
                    'barang_id' => $itemData['barang_id'],
                    'jumlah_masuk' => $itemData['jumlah_masuk'],
                    'harga_beli' => $itemData['harga_beli'],
                    'total_harga' => $totalHargaItem,
                    'bukti' => $buktiPath,
                ]);
                
                $barang->stok += $itemData['jumlah_masuk'];
                $barang->save();

                if (is_null($firstPengadaanId)) {
                    $firstPengadaanId = $pengadaan->id;
                }
            }

            if ($grandTotal > 0) {
                ArusKas::create([
                    'tanggal' => $validatedData['tanggal_pembelian'],
                    'jumlah' => $grandTotal,
                    'tipe' => 'keluar',
                    'keterangan' => "Pembelian Barang (Invoice: #{$validatedData['no_invoice']})",
                    'kategori' => 'Operasional',
                    'referensi_id' => $firstPengadaanId,
                    'referensi_tipe' => Pengadaan::class,
                ]);
            }

            DB::commit();
            return redirect()->route('pengadaan.index')->with('success', 'Pengadaan dengan ' . count($validatedData['items']) . ' item berhasil dicatat.');

        } catch (Throwable $e) {
            DB::rollBack();
            if ($buktiPath) {
                Storage::delete($buktiPath);
            }
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function destroy($no_invoice)
    {
        DB::beginTransaction();
        try {
            $pengadaans = Pengadaan::where('no_invoice', $no_invoice)->get();

            if($pengadaans->isEmpty()){
                return redirect()->route('pengadaan.index')->with('error', 'Invoice tidak ditemukan.');
            }

            $buktiPath = $pengadaans->first()->bukti;
            $firstPengadaanId = $pengadaans->first()->id;

            ArusKas::where('referensi_tipe', Pengadaan::class)
                   ->where('referensi_id', $firstPengadaanId)
                   ->delete();

            foreach ($pengadaans as $pengadaan) {
                $barang = Barang::find($pengadaan->barang_id);
                if($barang) {
                    $barang->stok -= $pengadaan->jumlah_masuk;
                    $barang->save();
                }
                $pengadaan->delete();
            }

            if ($buktiPath) {
                Storage::delete($buktiPath);
            }

            DB::commit();
            return redirect()->route('pengadaan.index')->with('success', 'Seluruh data untuk invoice #' . $no_invoice . ' berhasil dihapus.');

        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus invoice: ' . $e->getMessage()]);
        }
    }
}
