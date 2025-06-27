<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pengadaan;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\ArusKas;

class PengadaanController extends Controller
{
    /**
     * Menampilkan halaman utama (Riwayat Pengadaan) dengan data dan filter.
     */
    public function index(Request $request)
    {
        // Query dasar untuk mengambil data pengadaan beserta relasinya
        $query = Pengadaan::with(['barang', 'supplier']);

        // Terapkan filter berdasarkan input dari form
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pembelian', [$request->dari, $request->sampai]);
        }
        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        // --- Logika untuk Summary Cards ---
        // Kita hitung totalnya dari data yang sudah difilter, SEBELUM di-paginate
        $filteredPengadaans = $query->get();
        $totalPengeluaran = $filteredPengadaans->sum('total_harga');
        $totalItemMasuk = $filteredPengadaans->sum('jumlah_masuk');
        $totalTransaksi = $filteredPengadaans->count();
        $rataRataPerTransaksi = ($totalTransaksi > 0) ? $totalPengeluaran / $totalTransaksi : 0;

        // Ambil data untuk ditampilkan di tabel dengan pagination
        $pengadaans = $query->latest('tanggal_pembelian')->paginate(10)->withQueryString();

        // Ambil data untuk dropdown filter
        $barangs = Barang::orderBy('nama')->get();

        // Kirim semua data yang sudah diolah ke view
        return view('pengadaan.index', compact(
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
     */
    public function store(Request $request)
{
    // 1. Validasi data (tanpa harga, karena diambil dari DB)
    $validatedData = $request->validate([
        'barang_id' => 'required|exists:barangs,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'tanggal_pembelian' => 'required|date',
        'no_invoice' => 'required|string|unique:pengadaans,no_invoice',
        'jumlah_masuk' => 'required|integer|min:1',
        'keterangan' => 'nullable|string',
    ]);

    // 2. Ambil harga asli dari database
    $barang = Barang::findOrFail($validatedData['barang_id']);
    $harga_asli = $barang->harga_jual; 

    // 3. Hitung total harga di sisi SERVER
    $total_harga_server = $validatedData['jumlah_masuk'] * $harga_asli;

    // 4. Tambahkan harga dan total_harga yang aman
    $validatedData['harga_beli'] = $harga_asli;
    $validatedData['total_harga'] = $total_harga_server;

    // 5. Simpan data pengadaan
    $pengadaan = Pengadaan::create($validatedData);

    // 6. Catat pengeluaran di buku kas
    ArusKas::create([
        'tanggal' => $pengadaan->tanggal_pembelian,
        'jumlah' => $pengadaan->total_harga * -1,
        'tipe' => 'keluar',
        'deskripsi' => "Pembelian barang: {$barang->nama} (Inv: #{$pengadaan->no_invoice})",
        'referensi_id' => $pengadaan->id,
        'referensi_tipe' => Pengadaan::class,
    ]);

    return redirect()->route('pengadaan.index')->with('success', 'Pengadaan berhasil & kas telah dicatat.');
}

    // Biarkan method lain kosong dulu, kita akan buat nanti jika perlu
    public function show(Pengadaan $pengadaan) {}
    public function edit(Pengadaan $pengadaan) {}
    public function update(Request $request, Pengadaan $pengadaan) {}
    public function destroy(Pengadaan $pengadaan) {}
}