<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class alquileres extends Model
{
    use HasFactory;
    
    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }
    protected $fillable = [
        "descripcion",
        "monto",
        "id_sucursal",
    ];
}
