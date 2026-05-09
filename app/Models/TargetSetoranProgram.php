<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetSetoranProgram extends Model
{
    protected $table = 'target_setoran_program';

    public $timestamps = false;

    protected $fillable = [
        'idprogram',
        'idmuzaki',
        'target',
    ];

    protected $casts = [
        'target' => 'decimal:2',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'idprogram');
    }

    public function muzaki()
    {
        return $this->belongsTo(Muzaki::class, 'idmuzaki');
    }
}
