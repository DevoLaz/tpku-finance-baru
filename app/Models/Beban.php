<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beban extends Model
{
    use HasFactory;

    // Izinkan semua kolom ini diisi secara massal
    protected $fillable = [
        'tanggal',
        'nama_beban',
        'jumlah',
        'keterangan',
    ];
}