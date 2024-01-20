<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class cuentasporpagar extends Model
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

    public function cuenta() { 
        return $this->hasOne(\App\Models\cuentasporpagar::class,"id","id_cuentaporpagar"); 
    }

    protected $fillable = [
        "id_proveedor",
        "id_sucursal",
        "numfact",
        "numnota",
        "descripcion",
        "subtotal",
        "descuento",
        "monto_exento",
        "monto_gravable",
        "iva",
        "monto",
        "fechaemision",
        "fechavencimiento",
        "fecharecepcion",
        "nota",
        "tipo",
        "frecuencia",
        "idinsucursal",
        "balance",

        "monto_abonado",
        "id_cuentaporpagar",
    ];
}
