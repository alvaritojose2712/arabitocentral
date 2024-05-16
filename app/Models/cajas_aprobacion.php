<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class cajas_aprobacion extends Model
{
    use HasFactory;
    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }

    public function destino() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal_destino"); 
    }
    

    public function cat() { 
        return $this->hasOne('App\Models\catcajas',"id","categoria"); 
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    protected $fillable = [
        "concepto",
        "categoria",
        "montodolar",
        "dolarbalance",
        "montobs",
        "bsbalance",
        "montopeso",
        "pesobalance",
        "montoeuro",
        "eurobalance",
        "estatus",
        "fecha",
        "tipo",
        "id_sucursal",
        "idinsucursal",
        "id_sucursal_destino",
        "id_sucursal_emisora",
        "sucursal_destino_aprobacion",
        
    ];
}
