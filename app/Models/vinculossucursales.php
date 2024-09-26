<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vinculossucursales extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_producto_local",
        "idinsucursal_fore",
        "id_sucursal_fore",
        "idinsucursal",
        "id_sucursal",
    ];
}
