<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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
     * The "booted" method of the model.
     * Ini akan dieksekusi secara otomatis oleh Laravel.
     */
   protected static function booted(): void
{
    static::deleting(function (Transaction $transaction) {
        Log::info('Deleting Transaction ID: ' . $transaction->id);

        try {
            ArusKas::where('referensi_tipe', self::class)
                   ->where('referensi_id', $transaction->id)
                   ->delete();

            Log::info('Berhasil hapus ArusKas untuk transaksi ID: ' . $transaction->id);
        } catch (\Exception $e) {
            Log::error('Gagal hapus ArusKas: ' . $e->getMessage());
        }
    });
}

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
