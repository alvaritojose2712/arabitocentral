<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nominacargos extends Model
{
    use HasFactory;

    protected $fillable = [
        "cargosdescripcion",
        "cargossueldo",
    ];
}
