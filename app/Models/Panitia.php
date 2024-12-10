<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panitia extends Model
{
    use HasFactory;

    protected $table = 'panitias';

    protected $fillable = [
        'nama',
        'jabatan',
        'unit',
        'alamat',
        'email',
        'no_hp',
        'foto'
    ];
}
