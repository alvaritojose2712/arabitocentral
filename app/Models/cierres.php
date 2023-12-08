<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cierres extends Model
{
    use HasFactory;

    protected $fillable = [
        "debito",
        "efectivo",
        "transferencia",
        "caja_biopago",
        "dejar_dolar",
        "dejar_peso",
        "dejar_bss",
        "efectivo_guardado",
        "efectivo_guardado_cop",
        "efectivo_guardado_bs",
        "tasa",
        "nota",
        "fecha",
        "numventas",
        "precio",
        "precio_base",
        "ganancia",
        "porcentaje",
        "desc_total",
        "efectivo_actual",
        "efectivo_actual_cop",
        "efectivo_actual_bs",
        "puntodeventa_actual_bs",
        "id_sucursal",
        
        "tasacop",
        "inventariobase",
        "inventarioventa",
        "numreportez",
        "ventaexcento",
        "ventagravadas",
        "ivaventa",
        "totalventa",
        "ultimafactura",
        "credito",
        "creditoporcobrartotal",
        "vueltostotales",
        "abonosdeldia",
        "efecadiccajafbs",
        "efecadiccajafcop",
        "efecadiccajafdolar",
        "efecadiccajafeuro",

        "puntolote1",
        "puntolote1montobs",
        "puntolote2",
        "puntolote2montobs",
        "biopagoserial",
        "biopagoserialmontobs",
    ];
}
