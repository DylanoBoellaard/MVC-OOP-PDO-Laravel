<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoertuigInstructeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'Id',
        'VoertuigId',
        'InstructeurId'
    ];
}
