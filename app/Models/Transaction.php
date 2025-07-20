<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tanggal_transaksi',
        'total_penjualan',
        'keterangan',
        'bukti',
        'api_sale_id',    // Ditambahkan untuk menyimpan ID dari API
        'items_detail',   // Ditambahkan untuk menyimpan detail barang
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_transaksi' => 'date',
        'items_detail' => 'array', // Memberitahu Laravel bahwa kolom ini adalah JSON/array
    ];
}
