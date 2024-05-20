<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;


class inventario_sucursal extends Model
{
    use HasFactory;
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function proveedor() { 
        return $this->hasOne(\App\Models\proveedores::class,"id","id_proveedor"); 
    }
    public function categoria() { 
        return $this->hasOne(\App\Models\categorias::class,"id","id_categoria"); 
    }
    
	public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }


   
    public function catgeneral() { 
        return $this->hasOne(\App\Models\CatGenerals::class,"id","id_catgeneral"); 
    }

    public function sucursales() { 
        return $this->hasMany('App\Models\inventario_sucursal',"id_vinculacion","id"); 

    }
    
    protected $fillable = [
    	"id",
        "id_sucursal",
        "idinsucursal",
        "codigo_barras",
        "codigo_proveedor",
        "codigo_proveedor2",
        "id_proveedor",
        "id_categoria",
        "id_catgeneral",
        "id_marca",
        "id_deposito",
        "unidad",
        "descripcion",
        "iva",
        "porcentaje_ganancia",
        "precio_base",
        "precio",
        "precio1",
        "precio2",
        "precio3",
        "bulto",
        "stockmin",
        "stockmax",
        "cantidad",
        "push",
        "id_vinculacion",
        "n1",
        "n2",
        "n3",
        "n4",
        "n5",
    ];
}
