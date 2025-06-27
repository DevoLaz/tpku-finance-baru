<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi secara massal
    protected $guarded = ['id'];

    // Relasi: Satu data Gaji dimiliki oleh satu Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
