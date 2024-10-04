<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class pedidos extends Model
{
    use HasFactory;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function origen() { 
        return $this->hasOne('App\Models\sucursal',"id","id_origen"); 
    }
    public function destino() { 
        return $this->hasOne('App\Models\sucursal',"id","id_destino"); 
    }
    public function items() { 
        return $this->hasMany('App\Models\items_pedidos',"id_pedido","id"); 
    }
    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","id_destino"); 
    }

    public function cxp() { 
        return $this->hasOne(\App\Models\cuentasporpagar::class,"id","id_cxp"); 
    }
}
