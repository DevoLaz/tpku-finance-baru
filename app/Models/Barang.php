<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_barang',
        'nama',
        'kategori_id',
        'stok',
        'unit',
        'jenis_kain', // Pastikan nama kolom ini sesuai dengan database Anda
        'harga_jual',
    ];

    /**
     * Get the category that owns the barang.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
