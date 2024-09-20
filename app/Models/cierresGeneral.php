<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cierresGeneral extends Model
{
    use HasFactory;

    protected $fillable = [
        "fecha",
        "cxp",
        "cxc",
        "prestamos",
        "abono",
        "gastofijo",
        "gastovariable",
        "cuotacredito",
        "comisioncredito",
        "interescredito",
        "fdi",
        "perdidatasa",
        "pagoproveedor",
        "pagoproveedorbs",
        "pagoproveedorbancodivisa",
        "pagoproveedorbsbs",
        "pagoproveedortasapromedio",
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
        "inventariobase",
        "inventarioventa",
        "numventas",
        "nomina",
        "numsucursales",
        "estado",
    ];
}
