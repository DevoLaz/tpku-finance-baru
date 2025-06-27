<?php

namespace App\Http\Controllers;

use App\Models\AsetTetap;
use App\Models\ArusKas;
use Illuminate\Http\Request;

class AsetTetapController extends Controller
{
    /**
     * Menampilkan daftar semua aset.
     */
    public function index()
    {
        // Mengambil semua data dari model AsetTetap, diurutkan dari yang terbaru
        $asets = AsetTetap::latest()->paginate(10);
        
        // Mengirim data tersebut ke view 'aset-tetap.index'
        return view('aset-tetap.index', compact('asets'));
    }

    /**
     * Menampilkan form tambah aset
     */
    public function create()
    {
        // Membuat variabel $aset kosong agar tidak error di form
        $aset = new AsetTetap();
        return view('aset-tetap.create', compact('aset'));
    }

    /**
     * Menyimpan aset baru
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tanggal_perolehan' => 'required|date',
            'harga_perolehan' => 'required|numeric|min:0',
            'masa_manfaat' => 'required|integer|min:0',
            'nilai_residu' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $aset = AsetTetap::create($validatedData);

        if (str_contains(strtolower($aset->nama_aset), 'modal') || str_contains(strtolower($aset->nama_aset), 'kas')) {
            ArusKas::create([
                'tanggal' => $aset->tanggal_perolehan,
                'jumlah' => $aset->harga_perolehan,
                'tipe' => 'masuk',
                'deskripsi' => 'Setoran Modal: ' . $aset->nama_aset,
                'referensi_id' => $aset->id,
                'referensi_tipe' => AsetTetap::class,
            ]);
        }

        return redirect()->route('aset-tetap.index')->with('success', 'Aset baru berhasil ditambahkan.');
    }

    // Biarkan method lain kosong untuk sekarang
    public function show(AsetTetap $aset_tetap) {}



   public function edit(AsetTetap $aset_tetap)
    {
        // Redirect ke halaman index karena kita akan edit via modal
        return redirect()->route('aset-tetap.index');
    }


     public function update(Request $request, AsetTetap $aset_tetap)
    {
        $validatedData = $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tanggal_perolehan' => 'required|date',
            'harga_perolehan' => 'required|numeric|min:0',
            'masa_manfaat' => 'required|integer|min:0',
            'nilai_residu' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $aset_tetap->update($validatedData);

        return redirect()->route('aset-tetap.index')->with('success', 'Aset berhasil diperbarui.');
    }
    
    public function destroy(AsetTetap $aset_tetap) {}
}
