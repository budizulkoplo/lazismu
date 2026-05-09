<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ranting extends Model
{
    protected $table = 'ranting';

    public $timestamps = false;

    protected $fillable = [
        'nama_ranting',
    ];
}
