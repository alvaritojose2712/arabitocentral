<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class compras_notascreditodebito extends Model
{
    use HasFactory;

    protected $fillable = [
        "tipo",
        "num",
        "id_proveedor",
        "id_sucursal",
        "monto",
        "estatus",
        "id_factura",
    ];
}
