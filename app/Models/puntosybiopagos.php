<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class puntosybiopagos extends Model
{
    use HasFactory;
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_sucursal"); 
    }
    public function beneficiario() { 
        return $this->hasOne(\App\Models\nomina::class,"id","id_beneficiario"); 
    }

    public function cat() { 
        return $this->hasOne('App\Models\catcajas',"id","categoria"); 
    }
    protected $fillable = [
        "monto",
        "loteserial",
        "banco",
        "fecha",
        "id_sucursal",
        "id_usuario",
        "tipo",
        "categoria",
        "debito_credito",
        "fecha_liquidacion",
        "monto_liquidado",
        "id_beneficiario",
        "origen",
        "monto_dolar",
        "tasa",
        "id_comision",
    ];
}
