<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sucursal extends Model
{
    use HasFactory;

    public function cierres() { 
        return $this->hasMany('App\Models\cierres',"id_sucursal","id"); 
    }

    protected $fillable = [
        "nombre",
        "codigo",
    ];
}
