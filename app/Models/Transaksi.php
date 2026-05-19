<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'idrek',
        'source_type',
        'source_id',
        'tanggal',
        'jenis',
        'nominal',
        'saldo_awal',
        'saldo_akhir',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
        'saldo_awal' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
    ];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class, 'idrek', 'id');
    }
}
