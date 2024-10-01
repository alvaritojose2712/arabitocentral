<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class tareasSucursales extends Model
{
    use HasFactory;

    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        "id_sucursal",
        "tipo",
        "cambiarproducto",
        "antesproducto",
        "idinsucursal",
        "id_producto_verde",
        "id_producto_rojo",
    ];
}
