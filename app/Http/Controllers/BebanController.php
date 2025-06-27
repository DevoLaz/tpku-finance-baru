<?php

namespace App\Http\Controllers;

use App\Models\Beban;
use App\Models\ArusKas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BebanController extends Controller
{
    public function index()
    {
        $bebans = Beban::latest('tanggal')->paginate(15);
        return view('beban.index', compact('bebans'));
    }

    public function create()
    {
        return view('beban.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'nama_beban' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // 1. Simpan data beban
        $beban = Beban::create($validatedData);

        // 2. Catat sebagai pengeluaran di buku kas
        ArusKas::create([
            'tanggal' => $beban->tanggal,
            'jumlah' => $beban->jumlah * -1, // Diberi nilai negatif karena uang keluar
            'tipe' => 'keluar',
            'deskripsi' => "Beban: " . $beban->nama_beban,
            'referensi_id' => $beban->id,
            'referensi_tipe' => Beban::class,
        ]);

        return redirect()->route('beban.index')->with('success', 'Beban berhasil dicatat & kas telah berkurang.');
    }
}