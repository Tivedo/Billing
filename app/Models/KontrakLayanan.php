<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontrakLayanan extends Model
{
    protected $table = 'kontrak_layanan';
    protected $guarded = ['id'];

    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class, 'kontrak_id');
    }
}
