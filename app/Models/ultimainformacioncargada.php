<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ultimainformacioncargada extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_sucursal",
        "fecha",
        "date_last_cierres",
        "id_last_garantias",
        "id_last_fallas",
        "id_last_efec",
        "id_last_estadisticas",
        
    ];
}
