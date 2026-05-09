<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;

    protected $table = 'program';

    protected $fillable = [
        'nama_program',
        'lokasi',
        'tgl_mulai',
        'tgl_selesai',
        'target',
        'terkumpul',
        'banner_path',
        'status',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'target' => 'decimal:2',
        'terkumpul' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function setorans()
    {
        return $this->hasMany(Setoran::class, 'idprogram');
    }

    public function targetSetoranPrograms()
    {
        return $this->hasMany(TargetSetoranProgram::class, 'idprogram');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
