<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeSetoran extends Model
{
    protected $table = 'kode_setoran';
    public $timestamps = false;

    protected $fillable = [
        'jenis_setoran',
    ];

    public function setorans()
    {
        return $this->hasMany(Setoran::class, 'idkode_setoran');
    }
}
