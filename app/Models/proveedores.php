<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class proveedores extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    protected $fillable = [
        "descripcion",
        "rif",
        "direccion",
        "telefono",
    ];
}
