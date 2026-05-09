<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nota extends Model
{
    use SoftDeletes;

    protected $table = 'notas';

    protected $fillable = [
        'nota_no',
        'namatransaksi',
        'tanggal',
        'is_zakat',
        'id_infaq',
        'idprogram',
        'idkodetransaksi',
        'total',
        'status',
        'deskripsi',
        'bukti_nota',
        'userid',
        'namauser',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'idprogram');
    }

    public function kodeTransaksi()
    {
        return $this->belongsTo(Kodetransaksi::class, 'idkodetransaksi');
    }

    public function getKelompokAttribute(): string
    {
        if ($this->is_zakat === '1') {
            return 'zakat';
        }

        if ($this->id_infaq === '1') {
            return 'infaq';
        }

        if (!empty($this->idprogram)) {
            return 'program';
        }

        return '-';
    }

    public function getKelompokLabelAttribute(): string
    {
        return match ($this->kelompok) {
            'zakat' => 'Zakat',
            'infaq' => 'Infaq',
            'program' => 'Program',
            default => '-',
        };
    }

    public function getBuktiUrlAttribute(): ?string
    {
        if (!$this->bukti_nota) {
            return null;
        }

        return '/storage/' . ltrim($this->bukti_nota, '/');
    }
}
