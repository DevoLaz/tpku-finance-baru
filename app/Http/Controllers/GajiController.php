<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\ArusKas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GajiController extends Controller
{
    public function index()
    {
        // Ambil semua data gaji, relasikan dengan karyawan, urutkan
        $penggajian = Gaji::with('karyawan')->latest()->paginate(10);

        // Hitung total untuk summary cards
        $totalGajiKotor = $penggajian->sum('total_pendapatan');
        $totalPotongan = $penggajian->sum('total_potongan');
        $totalGajiBersih = $penggajian->sum('gaji_bersih');

        return view('gaji.index', compact('penggajian', 'totalGajiKotor', 'totalPotongan', 'totalGajiBersih'));
    }

    public function create()
    {
        // Ambil semua karyawan yang aktif untuk dropdown
        $karyawan = Karyawan::where('aktif', true)->orderBy('nama_lengkap')->get();
        return view('gaji.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'periode' => 'required|date_format:Y-m',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'nullable|numeric|min:0',
            'tunjangan_transport' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'pph21' => 'nullable|numeric|min:0',
            'bpjs' => 'nullable|numeric|min:0',
            'potongan_lain' => 'nullable|numeric|min:0',
        ]);

        // Hitung total di backend agar aman
        $total_pendapatan = ($request->gaji_pokok ?? 0) + ($request->tunjangan_jabatan ?? 0) + ($request->tunjangan_transport ?? 0) + ($request->bonus ?? 0);
        $total_potongan = ($request->pph21 ?? 0) + ($request->bpjs ?? 0) + ($request->potongan_lain ?? 0);
        $gaji_bersih = $total_pendapatan - $total_potongan;

        $validatedData['total_pendapatan'] = $total_pendapatan;
        $validatedData['total_potongan'] = $total_potongan;
        $validatedData['gaji_bersih'] = $gaji_bersih;

        // Simpan data gaji
        $gaji = Gaji::create($validatedData);
        $karyawan = Karyawan::find($gaji->karyawan_id);

        // Catat sebagai pengeluaran di buku kas
        ArusKas::create([
            'tanggal' => Carbon::now(),
            'jumlah' => $gaji->gaji_bersih * -1,
            'tipe' => 'keluar',
            'deskripsi' => "Pembayaran Gaji {$karyawan->nama_lengkap} - Periode " . Carbon::parse($gaji->periode)->isoFormat('MMMM Y'),
            'referensi_id' => $gaji->id,
            'referensi_tipe' => Gaji::class,
        ]);

        return redirect()->route('laporan.penggajian.index')->with('success', 'Pembayaran gaji berhasil dicatat.');
    }

    // Method baru untuk menampilkan slip gaji
    public function show(Gaji $gaji)
    {
        // Memuat relasi karyawan untuk ditampilkan di slip
        $gaji->load('karyawan');
        return view('gaji.slip', compact('gaji'));
    }
}