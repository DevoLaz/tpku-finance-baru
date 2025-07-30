<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['nama','kontak','alamat'];

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'barang_supplier')
                    ->withPivot('harga_beli')
                    ->withTimestamps();
    }
}
