<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = ['kategori_id', 'nama_produk', 'harga', 'stok', 'gambar'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
