<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nominaprestamos extends Model
{
    use HasFactory;

    protected $fillable = [
        "monto",
        "descripcion",
        "id_nomina",
        "id_sucursal",
        "idinsucursal",
    ];
}
