<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ventas extends Model
{
    use HasFactory;

    protected $fillable = [
        "debito",
        "efectivo",
        "transferencia",
        "tasa",
        "fecha",
        "id_sucursal",
        "num_ventas",
        
    ];
}
