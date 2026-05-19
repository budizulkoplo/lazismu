<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    protected $table = 'rekening';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'namarek',
        'saldo',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'idrek', 'id');
    }
}
