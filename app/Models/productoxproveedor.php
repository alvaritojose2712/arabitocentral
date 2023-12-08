<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;


class productoxproveedor extends Model
{
    use HasFactory;


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function proveedor() { 
        return $this->hasOne(\App\Models\proveedores::class,"id","id_proveedor"); 
    }

    public function producto() { 
        return $this->hasOne(\App\Models\inventario::class,"id","id_producto"); 
    }


}
