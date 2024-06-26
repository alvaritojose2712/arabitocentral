<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajasAprobacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cajas_aprobacions', function (Blueprint $table) {
            $table->increments("id");
            $table->string("concepto"); 
            $table->integer("categoria");

            $table->decimal("montodolar",10,2)->default(0);
            $table->decimal("dolarbalance",10,2)->default(0); 
            $table->decimal("montobs", 10, 2)->default(0);
            $table->decimal("bsbalance",10,2)->default(0); 
            $table->decimal("montopeso",10,2)->default(0);
            $table->decimal("pesobalance",10,2)->default(0); 
            $table->decimal("montoeuro",10,2)->default(0);
            $table->decimal("eurobalance",10,2)->default(0); 
            
            $table->integer("estatus")->default(0);

            $table->date("fecha");
            $table->integer("tipo"); //0 chica // 1 Fuerte  

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');
            
            $table->integer("idinsucursal");
            
            $table->integer("id_sucursal_destino")->nullable(true)->default(null);
            $table->integer("id_sucursal_emisora")->nullable(true)->default(null);

            $table->integer("sucursal_destino_aprobacion")->nullable(true)->default(null);

            $table->unique(["id_sucursal","idinsucursal"]);
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
        Schema::dropIfExists('cajas_aprobacions');
    }
}
