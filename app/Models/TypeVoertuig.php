<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeVoertuig extends Model
{
    use HasFactory;

    protected $fillable = [
        'Id',
        'TypeVoertuig',
        'Rijbewijscategorie'
    ];
}
