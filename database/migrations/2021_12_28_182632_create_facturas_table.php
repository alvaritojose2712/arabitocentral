<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments("id");
            
            $table->integer("id_proveedor")->unsigned();
            $table->foreign('id_proveedor')->references('id')->on('proveedores');
            
            $table->string("numfact");
            $table->unique(["numfact","id_proveedor"]);
            $table->string("descripcion");
            $table->decimal("monto",10,2)->nullable()->default(0);
            $table->date("fechavencimiento");
            
            $table->boolean("push")->default(0);
            
            $table->integer("estatus");
            //0 Pend
            //1 Procesado
            //2 Extraido

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
        Schema::dropIfExists('facturas');
    }
}
