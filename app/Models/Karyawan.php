<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    // Izinkan semua kolom ini diisi secara massal
    protected $fillable = [
        'nama_lengkap',
        'jabatan',
        'nik',
        'npwp',
        'status_karyawan',
        'tanggal_bergabung',
        'gaji_pokok_default',
        'aktif',
    ];

    // Laravel akan otomatis mengubah tipe data ini
    protected $casts = [
        'tanggal_bergabung' => 'date',
        'aktif' => 'boolean',
    ];
}
