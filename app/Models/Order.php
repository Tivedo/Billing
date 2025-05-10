<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';
    protected $guarded = [];
    public $timestamps = false;

    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'order_id');
    }
    public function kontrak()
    {
        return $this->hasOne(Kontrak::class, 'order_id');
    }
}
