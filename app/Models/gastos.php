<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gastos extends Model
{
    use HasFactory;
    protected $fillable = [
        "descripcion",
        "tipo",
        "categoria",
        "monto",
        "id_sucursal",
        "id_local",
    ];
}
