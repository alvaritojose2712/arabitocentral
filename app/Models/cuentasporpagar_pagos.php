<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class cuentasporpagar_pagos extends Model
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function factura() { 
        return $this->hasOne('App\Models\cuentasporpagars',"id","id_factura"); 
    }

    public function pagos() { 
        return $this->hasMany('App\Models\cuentasporpagars',"id_pago","id"); 
    }
    protected $fillable = [
        "id_factura",
        "id_pago",
        "monto"
    ];
    use HasFactory;
}
