<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BarangController extends Controller
{
    /**
     * Halaman master-data (paginated).
     */
    public function index()
    {
        $barangs = Barang::with(['kategori','suppliers'])
                         ->orderBy('nama')
                         ->paginate(15);

        // suppliers + kategori buat modal add/edit
        $suppliers = Supplier::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('master.index', compact('barangs','suppliers','kategoris'));
    }

    /**
     * Simpan barang baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_barang'   => 'nullable|string|unique:barangs,kode_barang',
            'nama'          => 'required|string|max:255',
            'kategori_id'   => 'required|exists:kategoris,id',
            'unit'          => 'required|string|max:50',
            'stok'          => 'required|integer|min:0',
            'harga_jual'    => 'required|numeric|min:0',
            'suppliers'     => 'nullable|array',
            'suppliers.*'   => 'exists:suppliers,id',
        ]);

        DB::transaction(function() use($data) {
            $b = Barang::create($data);
            $b->suppliers()->sync($data['suppliers'] ?? []);
        });

        return redirect()
            ->route('master.index')
            ->with('success','Barang berhasil ditambahkan.');
    }

    /**
     * AJAX: data JSON untuk pre‐fill modal edit.
     */
    public function edit(Barang $barang)
    {
        $barang->load('suppliers');
        return response()->json($barang);
    }

    /**
     * Update barang.
     */
    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'kode_barang'   => 'nullable|string|unique:barangs,kode_barang,'.$barang->id,
            'nama'          => 'required|string|max:255',
            'kategori_id'   => 'required|exists:kategoris,id',
            'unit'          => 'required|string|max:50',
            'stok'          => 'required|integer|min:0',
            'harga_jual'    => 'required|numeric|min:0',
            'suppliers'     => 'nullable|array',
            'suppliers.*'   => 'exists:suppliers,id',
        ]);

        DB::transaction(function() use($barang,$data) {
            $barang->update($data);
            $barang->suppliers()->sync($data['suppliers'] ?? []);
        });

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diperbarui.'
        ]);
    }

    /**
     * Hapus barang.
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return back()->with('success','Barang berhasil dihapus.');
    }

    /**
     * Sinkronisasi eksternal.
     */
    public function fetchFromApi()
    {
        $resp = Http::get('http://143.198.91.106/api/barang/mentah');
        if ($resp->failed()) {
            return back()->with('error','Gagal ambil API. Status '.$resp->status());
        }

        $rows = $resp->json()['data'] ?? [];
        if (empty($rows)) {
            return back()->with('success','Tidak ada data baru.');
        }

        $new=0; $upd=0;
        DB::beginTransaction();
        foreach ($rows as $r) {
            $kat = Kategori::firstOrCreate(['nama_kategori'=>$r['kategori_barang']]);
            $b   = Barang::updateOrCreate(
                ['kode_barang'=>$r['kode_barang']],
                [
                  'nama'=>$r['nama_barang'],
                  'kategori_id'=>$kat->id,
                  'stok'=>$r['stok_barang'],
                  'unit'=>$r['unit_barang'],
                  'harga_jual'=>0
                ]
            );
            if ($b->wasRecentlyCreated) $new++;
            elseif ($b->wasChanged())     $upd++;
        }
        DB::commit();

        return back()->with('success',"Sinkron selesai: {$new} baru, {$upd} diupdate.");
    }


    public function apiIndex()
    {
        // 1. Ambil semua data barang dari database, urutkan berdasarkan nama
        $barangs = Barang::orderBy('nama', 'asc')->get();

        // 2. Kembalikan data dalam format JSON dengan struktur yang baik
        return response()->json([
            'status' => 'success',
            'data' => $barangs
        ]);
    }

}
