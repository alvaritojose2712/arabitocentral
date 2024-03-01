<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class credito_aprobacion extends Model
{
    use HasFactory;
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function cliente() { 
        return $this->hasOne(\App\Models\clientes::class,"id","id_cliente"); 
    }
    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }
    protected $fillable = [
        "id_cliente",
        "id_sucursal",
        "estatus",
        "idinsucursal",
        "saldo",
    ];
}
