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
    // 1. Lakukan validasi seperti biasa
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

    // 2. INI BAGIAN PERBAIKANNYA
    // Kita pastikan semua input opsional yang kosong (null) diubah menjadi 0
    $gaji_pokok = $validatedData['gaji_pokok'] ?? 0;
    $tunjangan_jabatan = $validatedData['tunjangan_jabatan'] ?? 0;
    $tunjangan_transport = $validatedData['tunjangan_transport'] ?? 0;
    $bonus = $validatedData['bonus'] ?? 0;
    $pph21 = $validatedData['pph21'] ?? 0;
    $bpjs = $validatedData['bpjs'] ?? 0;
    $potongan_lain = $validatedData['potongan_lain'] ?? 0;

    // 3. Hitung total di backend agar aman menggunakan nilai yang sudah bersih
    $total_pendapatan = $gaji_pokok + $tunjangan_jabatan + $tunjangan_transport + $bonus;
    $total_potongan = $pph21 + $bpjs + $potongan_lain;
    $gaji_bersih = $total_pendapatan - $total_potongan;

    // 4. Siapkan data final untuk disimpan
    $dataToStore = array_merge($validatedData, [
        'gaji_pokok' => $gaji_pokok,
        'tunjangan_jabatan' => $tunjangan_jabatan,
        'tunjangan_transport' => $tunjangan_transport,
        'bonus' => $bonus,
        'pph21' => $pph21,
        'bpjs' => $bpjs,
        'potongan_lain' => $potongan_lain,
        'total_pendapatan' => $total_pendapatan,
        'total_potongan' => $total_potongan,
        'gaji_bersih' => $gaji_bersih,
    ]);

    // 5. Simpan data gaji yang sudah bersih
    $gaji = Gaji::create($dataToStore);
    $karyawan = Karyawan::find($gaji->karyawan_id);

    // 6. Catat sebagai pengeluaran di buku kas
    ArusKas::create([
        'tanggal' => Carbon::now(),
        'jumlah' => $gaji->gaji_bersih,
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