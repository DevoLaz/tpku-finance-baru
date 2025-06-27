<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsetTetap extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi secara massal
    protected $guarded = ['id'];

    // Otomatis ubah tanggal_perolehan menjadi objek Carbon (tipe data tanggal)
    protected $casts = [
        'tanggal_perolehan' => 'date',
    ];

    /**
     * Accessor untuk menghitung Akumulasi Penyusutan secara dinamis.
     * Kode ini akan berjalan setiap kali kita memanggil $aset->akumulasi_penyusutan
     */
    protected function akumulasiPenyusutan(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                // Jika masa manfaat 0 (untuk Kas/Modal), penyusutan adalah 0.
                if ($this->masa_manfaat == 0) {
                    return 0;
                }

                // Biaya penyusutan per tahun (Metode Garis Lurus)
                $penyusutan_per_tahun = ($this->harga_perolehan - $this->nilai_residu) / $this->masa_manfaat;

                // Biaya penyusutan per bulan
                $penyusutan_per_bulan = $penyusutan_per_tahun / 12;

                // Hitung sudah berapa bulan aset dimiliki sejak tanggal perolehan
                $bulan_berlalu = $this->tanggal_perolehan->diffInMonths(Carbon::now());

                // Akumulasi penyusutan adalah penyusutan per bulan dikali bulan yang sudah berlalu
                $akumulasi = $penyusutan_per_bulan * $bulan_berlalu;

                // Batasi akumulasi agar tidak melebihi nilai yang bisa disusutkan
                $nilai_maks_susut = $this->harga_perolehan - $this->nilai_residu;

                return min($akumulasi, $nilai_maks_susut);
            }
        );
    }

    /**
     * Accessor untuk menghitung Nilai Buku Aset secara dinamis.
     * Nilai Buku = Harga Perolehan - Akumulasi Penyusutan
     */
    protected function nilaiBuku(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->harga_perolehan - $this->akumulasi_penyusutan
        );
    }
}