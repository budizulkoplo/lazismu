<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KodetransaksiHdr extends Model
{
    use SoftDeletes;

    protected $table = 'kodetransaksi_hdr';

    protected $fillable = [
        'keterangan',
    ];

    public function kodeTransaksis()
    {
        return $this->hasMany(Kodetransaksi::class, 'idheader');
    }
}
