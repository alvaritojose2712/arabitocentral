<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class items_pedidos extends Model
{
    use HasFactory;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function producto() { 
        return $this->hasOne('App\Models\inventario_sucursal',"id","id_producto"); 
    }

    protected $fillable = [
        "id_producto",
        "id_pedido",
        "cantidad",
        "descuento",
        "monto",
        "ct_real",
        "barras_real",
        "alterno_real",
        "descripcion_real",
        "vinculo_real",
    ];
}
