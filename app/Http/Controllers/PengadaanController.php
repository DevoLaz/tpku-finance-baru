<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pengadaan;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\ArusKas;
use Illuminate\Support\Facades\DB;
use Throwable;

class PengadaanController extends Controller
{
    // Method index() tidak perlu diubah, biarkan seperti sebelumnya.
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
        $barangs = Barang::orderBy('nama')->get();
        
        // Menambahkan variabel pengadaans untuk pagination yang mungkin masih terpakai di view lain.
        $pengadaans = Pengadaan::latest()->paginate(10);

        return view('pengadaan.index', compact(
            'pengadaansByInvoice',
            'pengadaans',
            'barangs',
            'totalPengeluaran',
            'totalItemMasuk',
            'totalTransaksi',
            'rataRataPerTransaksi'
        ));
    }


    /**
     * Menampilkan form untuk membuat data pengadaan baru.
     */
    public function create()
    {
        // Ambil semua data barang dan supplier untuk dropdown di form
        $barangs = Barang::orderBy('nama')->get();
        $suppliers = Supplier::orderBy('nama_supplier')->get();

        return view('pengadaan.create', compact('barangs', 'suppliers'));
    }

    /**
     * Menyimpan data pengadaan baru dari form ke database.
     * Metode ini diubah untuk menangani banyak item.
     */
    public function store(Request $request)
    {
        // 1. Validasi data header dan array item
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'no_invoice' => 'required|string|unique:pengadaans,no_invoice',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah_masuk' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $grandTotal = 0;
            $firstPengadaanId = null;

            // 2. Loop melalui setiap item yang dikirim
            foreach ($validatedData['items'] as $itemData) {
                $barang = Barang::find($itemData['barang_id']);
                $hargaBeli = $barang->harga_jual; // Ambil harga dari DB agar aman
                $totalHargaItem = $itemData['jumlah_masuk'] * $hargaBeli;
                $grandTotal += $totalHargaItem;

                // 3. Buat record Pengadaan baru untuk setiap item
                $pengadaan = Pengadaan::create([
                    'supplier_id' => $validatedData['supplier_id'],
                    'tanggal_pembelian' => $validatedData['tanggal_pembelian'],
                    'no_invoice' => $validatedData['no_invoice'],
                    'keterangan' => $validatedData['keterangan'],
                    'barang_id' => $itemData['barang_id'],
                    'jumlah_masuk' => $itemData['jumlah_masuk'],
                    'harga_beli' => $hargaBeli,
                    'total_harga' => $totalHargaItem,
                ]);

                // Simpan ID dari item pertama yang dibuat untuk referensi ArusKas
                if (is_null($firstPengadaanId)) {
                    $firstPengadaanId = $pengadaan->id;
                }
            }

            // 4. Catat SATU KALI sebagai pengeluaran di buku kas
            if ($grandTotal > 0) {
                ArusKas::create([
                    'tanggal' => $validatedData['tanggal_pembelian'],
                    'jumlah' => $grandTotal * -1, // Total dari semua item
                    'tipe' => 'keluar',
                    'deskripsi' => "Pembelian Barang (Invoice: #{$validatedData['no_invoice']})",
                    'referensi_id' => $firstPengadaanId, // Gunakan ID item pertama sebagai referensi
                    'referensi_tipe' => Pengadaan::class,
                ]);
            }

            // 5. Jika semua berhasil, commit transaksi
            DB::commit();

            return redirect()->route('pengadaan.index')->with('success', 'Pengadaan dengan ' . count($validatedData['items']) . ' item berhasil dicatat.');

        } catch (Throwable $e) {
            // 6. Jika ada error, batalkan semua perubahan
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    // Method lain tetap sama
    public function show(Pengadaan $pengadaan) {}
    public function edit(Pengadaan $pengadaan) {}
    public function update(Request $request, Pengadaan $pengadaan) {}
    public function destroy(Pengadaan $pengadaan) {}
}