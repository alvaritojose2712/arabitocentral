<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catcajas extends Model
{

    protected $fillable = [
        "id",
        "nombre",
        "tipo",
        "catgeneral",
    ];
    use HasFactory;
}
