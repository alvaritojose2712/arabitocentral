<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class puntosybiopagos extends Model
{
    use HasFactory;

    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }
    protected $fillable = [
        "monto",
        "loteserial",
        "banco",
        "fecha",
        "id_sucursal",
        "id_usuario",
        "tipo"
    ];
}
