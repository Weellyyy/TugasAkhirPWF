<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = ['kategori_id', 'sku', 'nama_produk', 'harga', 'stok', 'lokasi', 'gambar'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
