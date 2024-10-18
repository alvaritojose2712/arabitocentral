<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vinculomaestro extends Model
{
    use HasFactory;

    public function producto() { 
        return $this->hasOne(\App\Models\inventario_sucursal::class,"id","id_producto_local"); 
    }

    protected $fillable = [
        "id_producto_local",
        "id_producto_maestro",
    ];
}
