<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // TAMBAHKAN BAGIAN INI UNTUK MEMBERI IZIN
    protected $fillable = [
        'barang_id',
        'supplier_id',
        'tanggal_pembelian',
        'no_invoice',
        'jumlah_masuk',
        'harga_beli',
        'total_harga',
        'keterangan',
        'bukti', 
    ];

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