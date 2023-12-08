<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuntosybiopagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puntosybiopagos', function (Blueprint $table) {
            $table->increments("id");
            $table->string("loteserial");
            $table->string("monto");
            $table->string("banco");
            $table->string("tipo");

            $table->date("fecha");
            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');
            
            $table->integer("id_usuario");
            
            $table->unique(["fecha","id_sucursal","id_usuario","tipo"]);
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
        Schema::dropIfExists('puntosybiopagos');
    }
}
