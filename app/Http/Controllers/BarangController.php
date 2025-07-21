<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function fetchFromApi()
    {
        try {
            if (!Storage::exists('dummy_barangs_data.json')) {
                return back()->with('error', 'File dummy testing (dummy_barangs_data.json) tidak ditemukan.');
            }
            $jsonContent = Storage::get('dummy_barangs_data.json');
            $apiResponse = json_decode($jsonContent, true);
            $barangsData = $apiResponse['data'] ?? [];

            if (empty($barangsData)) {
                return back()->with('success', 'Tidak ada data barang baru dari API untuk disinkronkan.');
            }

            $newCount = 0;
            $updatedCount = 0;
            DB::beginTransaction();

            foreach ($barangsData as $item) {
                $barang = Barang::where('kode_barang', $item['kode_barang'])->first();

                // --- PERBAIKAN DI SINI ---
                // Mengganti 'nama' menjadi 'nama_kategori' agar cocok dengan database
                $kategori = Kategori::firstOrCreate(
                    ['nama_kategori' => $item['kategori_barang']] // <-- Diubah di sini
                );

                if (!$barang) {
                    Barang::create([
                        'kode_barang' => $item['kode_barang'],
                        'nama' => $item['nama_barang'],
                        'kategori_id' => $kategori->id,
                        'stok' => $item['stok_barang'],
                        'unit' => $item['unit_barang'],
                        'jenis_lain' => $item['jenis_barang'],
                        'harga_jual' => 0,
                    ]);
                    $newCount++;
                } else {
                    $barang->update([
                        'nama' => $item['nama_barang'],
                        'kategori_id' => $kategori->id,
                        'stok' => $item['stok_barang'],
                        'unit' => $item['unit_barang'],
                        'jenis_lain' => $item['jenis_barang'],
                    ]);
                    $updatedCount++;
                }
            }

            DB::commit();

            $message = "Sinkronisasi selesai. {$newCount} barang baru ditambahkan, {$updatedCount} barang diperbarui.";
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat sinkronisasi data barang: ' . $e->getMessage());
        }
    }
}
