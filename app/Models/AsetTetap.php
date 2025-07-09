<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsetTetap extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Menggunakan $fillable lebih aman daripada $guarded.
     */
    protected $fillable = [
        'nama_aset',
        'kategori',
        'tanggal_perolehan',
        'harga_perolehan',
        'masa_manfaat',
        'nilai_residu',
        'deskripsi',
        'bukti',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'tanggal_perolehan' => 'date',
    ];

    /**
     * Accessor untuk menghitung Akumulasi Penyusutan secara dinamis.
     */
    protected function akumulasiPenyusutan(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                // Handle jika masa_manfaat adalah 0 atau null untuk mencegah division by zero
                if (!$this->masa_manfaat) {
                    return 0;
                }

                $penyusutan_per_tahun = ($this->harga_perolehan - $this->nilai_residu) / $this->masa_manfaat;
                $penyusutan_per_bulan = $penyusutan_per_tahun / 12;
                $bulan_berlalu = $this->tanggal_perolehan->diffInMonths(Carbon::now());
                $akumulasi = $penyusutan_per_bulan * $bulan_berlalu;
                $nilai_maks_susut = $this->harga_perolehan - $this->nilai_residu;

                return min($akumulasi, $nilai_maks_susut);
            }
        );
    }

    /**
     * Accessor untuk menghitung Nilai Buku Aset secara dinamis.
     */
    protected function nilaiBuku(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->harga_perolehan - $this->akumulasi_penyusutan
        );
    }
}
