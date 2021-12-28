<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class facturas extends Model
{
    use HasFactory;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function proveedor() { 
        return $this->hasOne('App\Models\proveedores',"id","id_proveedor"); 
    }
    public function items() { 
        return $this->hasMany('App\Models\items_facturas',"id_factura","id"); 
    }
    public function producto() { 
        return $this->hasOne('App\Models\inventario',"id","id_producto"); 
    }
    protected $fillable = [
    "id",
    "id_proveedor",
    "numfact",
    "descripcion",
    "monto",
    "fechavencimiento",
    "estatus"
    ];
}
