<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    /**
     * Tampilkan halaman Master Data:
     *  - Semua Supplier + relasi barangs
     *  - Semua Kategori (untuk dropdown modal Tambah/Edit Barang)
     *  - Daftar Barang (paginated) beserta relasi kategori & suppliers
     */
    public function index()
    {
        // 1) Semua supplier beserta daftar barang-nya
        $suppliers = Supplier::with('barangs')
                             ->orderBy('nama')
                             ->get();

        // 2) Semua kategori, nanti dipakai di modal tambah/edit Barang
        $kategoris = Kategori::orderBy('nama_kategori')
                             ->get();

        // 3) Daftar barang untuk tabel, dengan relasi kategori & suppliers
        $barangs = Barang::with(['kategori','suppliers'])
                         ->orderBy('nama')
                         ->paginate(15);

        // Kirim ke view master.index
        return view('master.index', compact(
            'suppliers',
            'kategoris',
            'barangs'
        ));
    }
}
