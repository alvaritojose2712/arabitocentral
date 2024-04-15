<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class cuentasporpagar extends Model
{
    use HasFactory;

    /* protected $casts = [
        'fechaemision'  => 'date:d-m-Y',
        'fechavencimiento'  => 'date:d-m-Y',
        'fecharecepcion'  => 'date:d-m-Y',
        'created_at' => 'datetime:d-m-Y H:00',
        'updated_at' => 'datetime:d-m-Y H:00',
    ]; */
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

    public function items() { 
        return $this->hasMany('App\Models\cuentasporpagar_items',"id_cuenta","id"); 
    }

    public function pagos() { 
        return $this->belongsToMany(\App\Models\cuentasporpagar::class, 'cuentasporpagar_pagos', 'id_factura', 'id_pago')->withPivot('monto');
    }


    public function facturas() { 
        return $this->belongsToMany(\App\Models\cuentasporpagar::class, 'cuentasporpagar_pagos', 'id_pago', 'id_factura')->withPivot('monto');
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
        "estatus",

        "monto_abonado",
        "id_cuentaporpagar",
        "aprobado",
        "metodo",

        "montobs1",
        "tasabs1",
        "metodobs1",
        "refbs1",
        "montobs2",
        "tasabs2",
        "metodobs2",
        "refbs2",
        "montobs3",
        "tasabs3",
        "metodobs3",
        "refbs3",
        "montobs4",
        "tasabs4",
        "metodobs4",
        "refbs4",
        "montobs5",
        "tasabs5",
        "metodobs5",
        "refbs5",
    ];
}
