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
        'idrekening',
        'nominal',
        'nominal_digunakan',
        'nominal_pdm',
        'created_at',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'nominal_digunakan' => 'decimal:2',
        'nominal_pdm' => 'decimal:2',
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

    public function rekening()
    {
        return $this->belongsTo(Rekening::class, 'idrekening', 'idrek');
    }

    public function getNominalDigunakanCalculatedAttribute(): float
    {
        return match (strtolower($this->kodeSetoran->jenis_setoran ?? '')) {
            'zakat' => (float) $this->nominal * 0.70,
            'infaq' => (float) $this->nominal * 0.80,
            'program' => (float) $this->nominal,
            default => (float) $this->nominal,
        };
    }

    public function getNominalPdmCalculatedAttribute(): float
    {
        return match (strtolower($this->kodeSetoran->jenis_setoran ?? '')) {
            'zakat' => (float) $this->nominal * 0.30,
            'infaq' => (float) $this->nominal * 0.20,
            default => 0,
        };
    }
}
