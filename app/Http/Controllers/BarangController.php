<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // <-- Pastikan ini ada
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function fetchFromApi()
    {
        try {
            // 1. Mengambil data dari API
            $response = Http::get('http://143.198.91.106/api/barang/mentah');

            // Cek jika request ke API gagal
            if ($response->failed()) {
                // Langsung hentikan proses jika API tidak bisa diakses
                return back()->with('error', 'Gagal mengambil data dari API. Status: ' . $response->status());
            }
            
            // 2. Mengambil data dari respons JSON
            $apiResponse = $response->json();
            $barangsData = $apiResponse['data'] ?? [];

            if (empty($barangsData)) {
                return back()->with('success', 'Tidak ada data barang baru dari API untuk disinkronkan.');
            }

            $newCount = 0;
            $updatedCount = 0;
            DB::beginTransaction();

            foreach ($barangsData as $item) {
                // Mencari atau membuat kategori baru jika belum ada
                $kategori = Kategori::firstOrCreate(
                    ['nama_kategori' => $item['kategori_barang']]
                );

                // 3. Menyederhanakan logika dengan updateOrCreate
                // Jika barang ada, update. Jika tidak, buat baru.
                $barang = Barang::updateOrCreate(
                    [
                        'kode_barang' => $item['kode_barang'] // Kondisi untuk mencari barang
                    ],
                    [
                        'nama' => $item['nama_barang'],
                        'kategori_id' => $kategori->id,
                        'stok' => $item['stok_barang'],
                        'unit' => $item['unit_barang'],
                        'jenis_kain' => $item['jenis_barang'] ?? null, // Menggunakan 'jenis_kain' dan memberi nilai default
                        'harga_jual' => 0, // Sesuai kode lama, harga jual diset 0
                    ]
                );
                
                // Menghitung jumlah data baru dan yang diupdate
                if ($barang->wasRecentlyCreated) {
                    $newCount++;
                } elseif ($barang->wasChanged()) {
                    $updatedCount++;
                }
            }

            DB::commit();

            $message = "Sinkronisasi selesai. {$newCount} barang baru ditambahkan, {$updatedCount} barang diperbarui.";
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            // Memberi pesan error yang lebih spesifik
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}