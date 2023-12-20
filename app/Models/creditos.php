<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class creditos extends Model
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }

    public function cliente() { 
        return $this->hasOne(\App\Models\clientes::class,"id","id_cliente"); 
    }
    protected $fillable = [
        "id_cliente",
        "id_sucursal",
        "saldo",
    ];
    use HasFactory;
}
