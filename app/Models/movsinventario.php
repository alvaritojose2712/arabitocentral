<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class movsinventario extends Model
{
    use HasFactory;

    protected $fillable = [
        "idinsucursal",
        "id_producto",
        "id_pedido",
        "id_usuario",
        "cantidad",
        "cantidadafter",
        "origen",
        "id_sucursal",
        "created_at",
    ];
}
