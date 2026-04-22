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
        'nama',
        'tgl_lahir',
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
}
