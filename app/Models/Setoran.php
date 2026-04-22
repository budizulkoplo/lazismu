<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setoran extends Model
{
    use SoftDeletes;

    protected $table = 'setoran';

    protected $fillable = [
        'idmuzaki',
        'idkode_setoran',
        'idprogram',
        'nominal',
        'created_at',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function muzaki()
    {
        return $this->belongsTo(Muzaki::class, 'idmuzaki');
    }

    public function kodeSetoran()
    {
        return $this->belongsTo(KodeSetoran::class, 'idkode_setoran');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'idprogram');
    }
}
