<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class inventario extends Model
{
    use HasFactory;
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function nivel2() { 
        return $this->hasMany('App\Models\vinculomaestro',"id_producto_maestro","id"); 
    }

    
  

    protected $fillable = [
        "id",
        "codigo_proveedor",
        "codigo_proveedor2",
        "codigo_barras",
        "id_proveedor",
        "id_categoria",
        "id_catgeneral",
        "unidad",
        "id_deposito",
        "descripcion",
        "iva",
        "porcentaje_ganancia",
        "precio_base",
        "precio",
        "cantidad",
        "bulto",
        "precio1",
        "precio2",
        "precio3",
        "stockmin",
        "stockmax",
        "marca",
        "n1",
        "n1",
        "n1",
        "n1",
    ];
    
}
