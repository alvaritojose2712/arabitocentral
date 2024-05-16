<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class cuentasporpagar_items extends Model
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    use HasFactory;

    public function producto() { 
        return $this->hasOne(\App\Models\inventario_sucursal::class,"id","id_producto"); 
    }

    protected $fillable = [
        "id_cuenta",
        "id_producto",
        "cantidad",
        "basef",
        "base",
        "venta",
        "estado",
    ];
}
