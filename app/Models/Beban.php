<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beban extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'jumlah',
        'tanggal',
        'keterangan', // Menambahkan keterangan
        'bukti',
        'kategori_id', // Menambahkan kategori_id
    ];

    /**
     * Get the kategori that owns the Beban
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
