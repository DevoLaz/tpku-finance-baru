<?php

namespace App\Http\Controllers;

use App\Models\Beban;
use App\Models\Kategori;
use App\Models\ArusKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BebanController extends Controller
{
    public function index()
    {
        $bebans = Beban::with('kategori')->latest()->paginate(10);
        return view('beban.index', compact('bebans'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('beban.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        // PERBAIKAN: Menyesuaikan validasi dengan nama input dari form
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'nama_beban' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                $buktiPath = $request->file('bukti')->store('public/bukti_beban');
            }

            // PERBAIKAN: Memetakan 'nama_beban' dari form ke kolom 'nama' di database
            $beban = Beban::create([
                'tanggal' => $validatedData['tanggal'],
                'nama' => $validatedData['nama_beban'],
                'jumlah' => $validatedData['jumlah'],
                'keterangan' => $validatedData['keterangan'],
                'bukti' => $buktiPath,
                'kategori_id' => 1, // Asumsi default kategori, bisa disesuaikan
            ]);

            ArusKas::create([
                'tanggal' => $beban->tanggal,
                'keterangan' => 'Beban: ' . $beban->nama,
                'deskripsi' => $beban->keterangan ?: 'Pembayaran beban ' . $beban->nama,
                'jumlah' => $beban->jumlah,
                'tipe' => 'keluar',
                'kategori' => 'Operasional',
                'referensi_id' => $beban->id,
                'referensi_tipe' => Beban::class,
            ]);

            DB::commit();
            return redirect()->route('beban.index')->with('success', 'Beban berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($buktiPath)) {
                Storage::delete($buktiPath);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Beban $beban)
    {
        DB::beginTransaction();
        try {
            if ($beban->bukti) {
                Storage::delete($beban->bukti);
            }
            
            ArusKas::where('referensi_tipe', Beban::class)
                   ->where('referensi_id', $beban->id)
                   ->delete();

            $beban->delete();
            DB::commit();
            return redirect()->route('beban.index')->with('success', 'Beban berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus beban: ' . $e->getMessage());
        }
    }
}
