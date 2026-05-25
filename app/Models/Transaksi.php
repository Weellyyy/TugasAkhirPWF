<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['user_id', 'total_harga', 'metode_pembayaran'];

    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
