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
            $table->decimal("cxp",10,3);
            
            $table->decimal("cxc",10,3);
            $table->decimal("prestamos",10,3);
            $table->decimal("abono",10,3);

            


            $table->decimal("cuotacredito",10,3);
            $table->decimal("comisioncredito",10,3);
            $table->decimal("interescredito",10,3);

            $table->decimal("gastofijo",10,3);
            $table->decimal("gastovariable",10,3);
            $table->decimal("fdi",10,3);
            $table->decimal("perdidatasa",10,3);
            $table->decimal("pagoproveedor",10,3);
            $table->decimal("pagoproveedorbs",10,3);
            $table->decimal("pagoproveedorbancodivisa",10,3);
            
            $table->decimal("pagoproveedorbsbs",10,3);
            $table->decimal("pagoproveedortasapromedio",10,3);
            

            
            

            
            $table->decimal("ingreso_credito",10,3);
            $table->decimal("debito",10,3);
            $table->decimal("debitobs",10,3);
            $table->decimal("transferencia",10,3);
            $table->decimal("transferenciabs",10,3);
            $table->decimal("biopago",10,3);
            $table->decimal("biopagobs",10,3);
            $table->decimal("efectivo",10,3);

            $table->decimal("utilidadbruta",10,3);
            $table->decimal("utilidadneta",10,3);


            $table->decimal("cajaregistradora",10,3);
            $table->decimal("cajachica",10,3);
            $table->decimal("cajafuerte",10,3);
            $table->decimal("cajamatriz",10,3);

            $table->decimal("bancobs",10,3);
            $table->decimal("bancodivisa",10,3);
            
            $table->decimal("inventariobase",10,3);
            $table->decimal("inventarioventa",10,3);
            
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
