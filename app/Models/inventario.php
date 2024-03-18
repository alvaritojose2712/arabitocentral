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

    public function categoria() { 
        return $this->hasOne(\App\Models\categorias::class,"id","id_categoria"); 
    }
   
    public function catgeneral() { 
        return $this->hasOne(\App\Models\CatGenerals::class,"id","id_catgeneral"); 
    }

    public function sucursales() { 
        return $this->hasMany('App\Models\inventario_sucursal',"id_vinculacion","id"); 

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
