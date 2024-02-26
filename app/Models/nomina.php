<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nomina extends Model
{
    use HasFactory;

    public function pagos() { 
        return $this->hasMany(\App\Models\nominapagos::class,"id_nomina","id"); 
    }
    
    

    public function sucursal() { 
        return $this->hasOne(\App\Models\sucursal::class,"id","nominasucursal"); 
    }

    public function cargo() { 
        return $this->hasOne(\App\Models\nominacargos::class,"id","nominacargo"); 
    }

    protected $fillable = [
        "nominanombre",
        "nominacedula",
        "nominatelefono",
        "nominadireccion",
        "nominafechadenacimiento",
        "nominafechadeingreso",
        "nominagradoinstruccion",
        "nominacargo",
        "nominasucursal",
    ];
}
