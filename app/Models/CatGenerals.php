<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatGenerals extends Model
{
    protected $fillable = ["descripcion"];

    use HasFactory;
}
