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
    protected $fillable = [
    	"id_pro_sucursal",
		"id_sucursal",
		"id_pro_sucursal_fixed",
		"codigo_barras",
		"codigo_proveedor",
		"id_proveedor",
		"id_categoria",
		"id_marca",
		"unidad",
		"id_deposito",
		"descripcion",
		"iva",
		"porcentaje_ganancia",
		"precio_base",
		"precio",
		"cantidad",
		"check"
    ];
}
