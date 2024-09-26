<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class cajas extends Model
{

    public function cat() { 
        return $this->hasOne('App\Models\catcajas',"id","categoria"); 
    }

    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }
    public function sucursal_origen() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal_origen"); 
    }
    public function proveedor() { 
        return $this->hasOne(\App\Models\proveedores::class,"id","id_proveedor"); 
    }
    public function beneficiario() { 
        return $this->hasOne(\App\Models\nomina::class,"id","id_beneficiario"); 
    }

    
    

     protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }



    protected $fillable = [
        "id",
        "concepto",
        "categoria",
        "montodolar",
        "montopeso",
        "montobs",
        "dolarbalance",
        "pesobalance",
        "bsbalance",

        "montoeuro",
        "eurobalance",
        "fecha",
        "tipo",
        "id_sucursal",
        "id_sucursal_origen",
        "idinsucursal",
        "id_sucursal_deposito",
        "revisado",
        "id_proveedor",
        "id_beneficiario",
        "id_cxp",
        "origen",
        "unidad",
        "ct",
    ];

    use HasFactory;
}
