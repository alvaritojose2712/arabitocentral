<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class nominapagos extends Model
{
    use HasFactory;
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function sucursal() { 
        return $this->hasOne('App\Models\sucursal',"id","id_sucursal"); 
    }

    protected $fillable = [
        "monto",
        "descripcion",
        "id_nomina",
        "id_sucursal",
        "idinsucursal",
        "created_at",
    ];
}
