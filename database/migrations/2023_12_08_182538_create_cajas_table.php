<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cajas', function (Blueprint $table) {
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
            
            $table->integer("responsable")->nullable(true)->default(null);
            $table->integer("asignar")->nullable(true)->default(null);
            

            $table->date("fecha");
            $table->integer("tipo"); //0 chica // 1 Fuerte  
            
            $table->integer("id_sucursal_origen")->nullable();
            $table->integer("id_sucursal_deposito")->nullable();

            $table->integer("revisado")->nullable();
            $table->integer("id_proveedor")->nullable();

            $table->integer("id_beneficiario")->nullable(true)->default(null);
            $table->integer("origen")->nullable(true)->default(1);

            $table->integer("id_cxp")->nullable(true);


            
            
            $table->integer("idinsucursal");  
            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');
            $table->timestamps();

            $table->unique(["idinsucursal","id_sucursal"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cajas');
    }
}
