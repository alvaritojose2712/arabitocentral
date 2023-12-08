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
    public function marca() { 
        return $this->hasOne(\App\Models\marcas::class,"id","id_marca"); 
    }
    public function catgeneral() { 
        return $this->hasOne(\App\Models\CatGenerals::class,"id","id_catgeneral"); 
    }
  

    protected $fillable = [
        "id",
        "codigo_proveedor",
        "codigo_barras",
        "id_proveedor",
        "id_categoria",
        "id_marca",
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
    ];
    
}
