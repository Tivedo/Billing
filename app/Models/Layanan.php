<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';
    protected $fillable = ['nama', 'harga', 'deskripsi'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
