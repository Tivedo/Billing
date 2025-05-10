<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'invoice_item';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
