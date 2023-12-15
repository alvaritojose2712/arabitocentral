<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class cajas extends Model
{

    public function cat() { 
        return $this->hasOne('App\Models\catcajas',"id","categoria"); 
    }

    public function responsable() { 
        return $this->hasOne('App\Models\catcajas',"id","responsable"); 
    }

    public function asignar() { 
        return $this->hasOne('App\Models\catcajas',"id","asignar"); 
    }
    

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

        "montoeuro",
        "eurobalance",
        "fecha",
        "tipo",
        "id_sucursal",
        "idinsucursal",
    ];
    use HasFactory;
}
