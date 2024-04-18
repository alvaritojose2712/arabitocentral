<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class cuentasporpagar_fisicas extends Model
{
    use HasFactory;
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function proveedor() { 
        return $this->hasOne(\App\Models\proveedores::class,"id","id_proveedor"); 
    }
    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }
    protected $fillable = [
        "id_proveedor",
        "id_sucursal",
        "estado",
        "ruta",
        "numfact",
    ];
}
