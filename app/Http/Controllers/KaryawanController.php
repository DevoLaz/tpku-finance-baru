<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::latest('tanggal_bergabung')->paginate(10);
        return view('karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        $karyawan = new Karyawan(); // Kirim variabel karyawan kosong agar tidak error di form
        return view('karyawan.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nik' => 'nullable|string|max:16',
            'npwp' => 'nullable|string|max:16',
            'status_karyawan' => 'required|in:kontrak,tetap,harian',
            'tanggal_bergabung' => 'required|date',
            'gaji_pokok_default' => 'required|numeric|min:0',
        ]);

        Karyawan::create($validatedData);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan baru berhasil ditambahkan.');
    }

    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nik' => 'nullable|string|max:16',
            'npwp' => 'nullable|string|max:16',
            'status_karyawan' => 'required|in:kontrak,tetap,harian',
            'tanggal_bergabung' => 'required|date',
            'gaji_pokok_default' => 'required|numeric|min:0',
            'aktif' => 'required|boolean',
        ]);

        $karyawan->update($validatedData);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil dihapus.');
    }
}
