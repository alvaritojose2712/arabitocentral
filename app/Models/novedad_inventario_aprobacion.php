<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class novedad_inventario_aprobacion extends Model
{
    use HasFactory;
    protected $fillable = [
        "id_sucursal",
        "idinsucursal",
        "estado",
    ];
}
