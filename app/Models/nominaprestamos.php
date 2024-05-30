<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class nominaprestamos extends Model
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }
    use HasFactory;

    protected $fillable = [
        "monto",
        "descripcion",
        "id_nomina",
        "id_sucursal",
        "idinsucursal",
    ];
}
