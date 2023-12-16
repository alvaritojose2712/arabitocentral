<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DateTimeInterface;

class comovamos extends Model
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function sucursal()
    {
        return $this->hasOne(\App\Models\sucursal::class, "id", "id_sucursal");
    }

    protected $fillable = [
        "transferencia",
        "biopago",
        "debito",
        "efectivo",
        "tasa",
        "tasa_cop",
        "numventas",
        "total_inventario",
        "total_inventario_base",
        "cred_total",
        "total",
        "precio",
        "precio_base",
        "desc_total",
        "ganancia",
        "porcentaje",
        "fecha",
        "id_sucursal",
    ];
    use HasFactory;
}
