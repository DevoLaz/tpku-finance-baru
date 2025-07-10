<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    use HasFactory;

    /**
     * PERBAIKAN: Menggunakan $guarded agar lebih fleksibel dan menghindari error.
     * Ini mengizinkan semua kolom untuk diisi, selama datanya sudah divalidasi di controller.
     */
    protected $guarded = ['id'];

    // Relasi: Satu pengadaan hanya dimiliki oleh satu Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Relasi: Satu pengadaan hanya dimiliki oleh satu Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
