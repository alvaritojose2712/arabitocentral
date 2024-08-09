<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class bancos extends Model
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function banco() { 
        return $this->hasOne(\App\Models\bancos::class,"id","id_banco"); 
    }
    protected $fillable = [
        "banco",
        "id_usuario",
        "descripcion",
        "fecha",
        "saldo",
        "saldo_real_manual",
        "saldo_inicial",
        "ingreso",
        "egreso",
        "id_banco",
    ];

    use HasFactory;
}
