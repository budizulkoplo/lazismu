<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Muzaki extends Model
{
    use SoftDeletes;

    protected $table = 'muzaki';

    protected $fillable = [
        'nik',
        'nomor_induk_muzaki',
        'jenis_muzaki',
        'nama',
        'tgl_lahir',
        'ranting',
        'aum',
        'alamat',
        'jenis_kelamin',
        'no_hp',
        'email',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function setorans()
    {
        return $this->hasMany(Setoran::class, 'idmuzaki');
    }

    public function targetSetoranPrograms()
    {
        return $this->hasMany(TargetSetoranProgram::class, 'idmuzaki');
    }

    public function getLoginCodeAttribute(): string
    {
        return $this->nomor_induk_muzaki ?: $this->nik;
    }
}
