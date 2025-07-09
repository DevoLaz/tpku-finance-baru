<?php

namespace App\Http\Controllers;

use App\Models\AsetTetap;
use App\Models\ArusKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AsetTetapController extends Controller
{
    public function index()
    {
        $asetTetaps = AsetTetap::latest('tanggal_perolehan')->paginate(10);
        return view('aset-tetap.index', compact('asetTetaps'));
    }

    public function create()
    {
        return view('aset-tetap.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tanggal_perolehan' => 'required|date',
            'harga_perolehan' => 'required|numeric|min:0',
            'masa_manfaat' => 'required|integer|min:0',
            'nilai_residu' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                $buktiPath = $request->file('bukti')->store('public/bukti_aset_tetap');
            }
            
            $validatedData['bukti'] = $buktiPath;

            $asetTetap = AsetTetap::create($validatedData);

            // PERBAIKAN LOGIKA: Cek apakah ini setoran modal atau pembelian aset
            if (str_contains(strtolower($request->kategori), 'kas') || str_contains(strtolower($request->nama_aset), 'modal')) {
                 // Jika ini adalah modal, catat sebagai kas masuk
                 ArusKas::create([
                    'tanggal' => $request->tanggal_perolehan,
                    'keterangan' => 'Setoran Modal: ' . $request->nama_aset,
                    'deskripsi' => $request->deskripsi ?: 'Setoran modal awal atau tambahan',
                    'jumlah' => $request->harga_perolehan, // Nilai positif
                    'tipe' => 'masuk', // Tipe: MASUK
                    'kategori' => 'Pendanaan',
                    'referensi_id' => $asetTetap->id,
                    'referensi_tipe' => AsetTetap::class,
                ]);
            } else if ($request->harga_perolehan > 0) {
                // Jika ini adalah pembelian aset, catat sebagai kas keluar
                ArusKas::create([
                    'tanggal' => $request->tanggal_perolehan,
                    'keterangan' => 'Pembelian Aset: ' . $request->nama_aset,
                    'deskripsi' => $request->deskripsi ?: 'Pembelian aset ' . $request->nama_aset,
                    'jumlah' => $request->harga_perolehan, // Nilai tetap positif
                    'tipe' => 'keluar', // Tipe: KELUAR
                    'kategori' => 'Investasi',
                    'referensi_id' => $asetTetap->id,
                    'referensi_tipe' => AsetTetap::class,
                ]);
            }


            DB::commit();
            return redirect()->route('aset-tetap.index')->with('success', 'Aset tetap berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($buktiPath) && $buktiPath) {
                Storage::delete($buktiPath);
            }
            // Mengembalikan pesan error yang lebih jelas ke view
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, AsetTetap $asetTetap)
    {
        $validatedData = $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tanggal_perolehan' => 'required|date',
            'harga_perolehan' => 'required|numeric|min:0',
            'masa_manfaat' => 'required|integer|min:0',
            'nilai_residu' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('bukti')) {
            if ($asetTetap->bukti) {
                Storage::delete($asetTetap->bukti);
            }
            $validatedData['bukti'] = $request->file('bukti')->store('public/bukti_aset_tetap');
        }

        $asetTetap->update($validatedData);

        return redirect()->route('aset-tetap.index')->with('success', 'Aset tetap berhasil diperbarui.');
    }

    public function destroy(AsetTetap $asetTetap)
    {
        DB::beginTransaction();
        try {
            if ($asetTetap->bukti) {
                Storage::delete($asetTetap->bukti);
            }
            
            ArusKas::where('referensi_tipe', AsetTetap::class)
                   ->where('referensi_id', $asetTetap->id)
                   ->delete();

            $asetTetap->delete();
            DB::commit();
            return redirect()->route('aset-tetap.index')->with('success', 'Aset tetap berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus aset: ' . $e->getMessage());
        }
    }
}
