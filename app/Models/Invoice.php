<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
