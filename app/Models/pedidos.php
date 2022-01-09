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
    
    public function sucursal() { 
        return $this->hasOne('App\Models\sucursal',"id","id_sucursal"); 
    }
    public function items() { 
        return $this->hasMany('App\Models\items_pedidos',"id_pedido","id"); 
    }
}
