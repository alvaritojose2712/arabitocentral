<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class garantias extends Model
{
    use HasFactory;

	public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }

    protected $fillable = [
        "id_sucursal",
        "id_producto",
        "idinsucursal",
        "cantidad",
        "motivo",
        "id_cliente",
    ];
}
