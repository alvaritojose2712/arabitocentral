<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventario_sucursal_estadisticas extends Model
{
    use HasFactory;
    

    protected $fillable = [
        "cantidad",
        "fecha",
        "id_sucursal",
        "id_itempedido_insucursal",
        "id_pedido_insucursal",
        "id_producto_insucursal",
    ];
}
