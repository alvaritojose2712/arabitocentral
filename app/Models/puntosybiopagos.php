<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class puntosybiopagos extends Model
{
    use HasFactory;

    protected $fillable = [
        "loteserial",
        "monto",
        "banco",
        "fecha",
        "id_sucursal",
        "id_usuario",
        "tipo",
    ];
}
