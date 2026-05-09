<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kodetransaksi extends Model
{
    protected $table = 'kodetransaksi';

    protected $fillable = [
        'kodetransaksi',
        'transaksi',
        'idheader',
    ];

    public function header()
    {
        return $this->belongsTo(KodetransaksiHdr::class, 'idheader');
    }
}
