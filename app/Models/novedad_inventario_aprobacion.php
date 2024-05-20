<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class novedad_inventario_aprobacion extends Model
{
    use HasFactory;

    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }
    protected $fillable = [
        "id_sucursal",
        "idinsucursal",
        "responsable",
        "motivo",
        "estado",
        "codigo_barras_old",
        "codigo_proveedor_old",
        "descripcion_old",
        "precio_base_old",
        "precio_old",
        "cantidad_old",
        "id_proveedor_old",
        "id_categoria_old",
        "codigo_barras",
        "codigo_proveedor",
        "descripcion",
        "precio_base",
        "precio",
        "cantidad",
        "id_proveedor",
        "id_categoria",
    ];
}
