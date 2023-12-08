<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class cajas extends Model
{
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
        "fecha",
        "tipo",
        "id_sucursal",
        "idinsucursal",
    ];
    use HasFactory;
}
