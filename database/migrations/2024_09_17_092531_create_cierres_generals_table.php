<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCierresGeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cierres_generals', function (Blueprint $table) {
            $table->increments("id");
            $table->decimal("cxp",14,3);
            
            $table->decimal("cxc",14,3);
            $table->decimal("prestamos",14,3);
            $table->decimal("abono",14,3);

            


            $table->decimal("cuotacredito",14,3);
            $table->decimal("comisioncredito",14,3);
            $table->decimal("interescredito",14,3);

            $table->decimal("gastofijo",14,3);
            $table->decimal("gastovariable",14,3);
            $table->decimal("fdi",14,3);
            $table->decimal("perdidatasa",14,3);
            $table->decimal("pagoproveedor",14,3);
            $table->decimal("pagoproveedorbs",14,3);
            $table->decimal("pagoproveedorbancodivisa",14,3);
            
            $table->decimal("pagoproveedorbsbs",14,3);
            $table->decimal("pagoproveedortasapromedio",14,3);
            
            $table->decimal("ingreso_credito",14,3);
            $table->decimal("debito",14,3);
            $table->decimal("debitobs",14,3);
            $table->decimal("transferencia",14,3);
            $table->decimal("transferenciabs",14,3);
            $table->decimal("biopago",14,3);
            $table->decimal("biopagobs",14,3);
            $table->decimal("efectivo",14,3);

            $table->decimal("utilidadbruta",14,3);
            $table->decimal("utilidadneta",14,3);


            $table->decimal("cajaregistradora",14,3);
            $table->decimal("cajachica",14,3);
            $table->decimal("cajafuerte",14,3);
            $table->decimal("cajamatriz",14,3);

            $table->decimal("bancobs",14,3);
            $table->decimal("bancodivisa",14,3);
            
            $table->decimal("inventariobase",14,3);
            $table->decimal("inventarioventa",14,3);
            
            $table->integer("numventas");
            $table->integer("nomina");
            
            
            
            $table->integer("numsucursales");
            $table->integer("estado");
            $table->date("fecha")->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cierres_generals');
    }
}
