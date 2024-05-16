<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class items_facturas extends Model
{
    use HasFactory;
    protected $fillable = [
        "id_factura",
        "id_producto",
        "cantidad",
        "tipo"
    ];

    public function producto() { 
        return $this->hasOne('App\Models\inventario_sucursals',"id","id_producto"); 
    }
}
