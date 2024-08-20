<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class pedidos_aprobacion extends Model
{
    use HasFactory;


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }
        
    protected $fillable = [
        "id_sucursal",
        "id_sucursal",
        "idinsucursal",
        "estatus",
        "monto",
        "items",
        "pagos",
        "motivo",
        "cliente",
    ];
}
