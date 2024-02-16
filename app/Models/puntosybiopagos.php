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
    protected $fillable = [
        "monto",
        "loteserial",
        "banco",
        "fecha",
        "id_sucursal",
        "id_usuario",
        "tipo",
        "categoria",
        "fecha_liquidacion",
        "monto_liquidado",
    ];
}
