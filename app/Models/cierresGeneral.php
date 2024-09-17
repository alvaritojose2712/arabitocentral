<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cierresGeneral extends Model
{
    use HasFactory;

    protected $fillable = [
        "cxp",
        "cxc",
        "prestamos",
        "abono",
        "gastofijo",
        "gastovariable",
        "fdi",
        "perdidatasa",
        "pagoproveedor",
        "pagoproveedorbs",
        "ingreso_credito",
        "debito",
        "debitobs",
        "transferencia",
        "transferenciabs",
        "biopago",
        "biopagobs",
        "efectivo",
        "utilidadbruta",
        "utilidadneta",
        "cajaregistradora",
        "cajachica",
        "cajafuerte",
        "cajamatriz",
        "bancobs",
        "bancodivisa",
        "numsucursales",
        "fecha",
    ];
}
