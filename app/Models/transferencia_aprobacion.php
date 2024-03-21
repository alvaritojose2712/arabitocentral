<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transferencia_aprobacion extends Model
{
    use HasFactory;

    protected $fillable = [
        "loteserial",
        "banco",
        "id_sucursal",
        "saldo",
        "estatus",
        "idinsucursal",
    ];
}
