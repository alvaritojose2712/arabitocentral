<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class tareas extends Model
{
    use HasFactory;
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function origen() { 
        return $this->hasOne('App\Models\sucursal',"id","origen"); 
    }
    public function destino() { 
        return $this->hasOne('App\Models\sucursal',"id","destino"); 
    }
    protected $fillable = [
        "origen",
        "destino",
        "solicitud",
        "respuesta",
        "accion",
        "estado",
    ];
}
