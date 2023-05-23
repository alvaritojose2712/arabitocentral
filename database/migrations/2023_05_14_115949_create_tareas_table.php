<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer("origen")->unsigned();
            $table->foreign('origen')->references('id')->on('sucursals');

            $table->integer("destino")->unsigned();
            $table->foreign('destino')->references('id')->on('sucursals');

            $table->json("solicitud")->nullable();
            $table->json("respuesta")->nullable();
            
            $table->string("accion");
            $table->integer("estado"); 
            //0,//Solicitado(Consultar)
            //1,//Recibido y respondido
            //2,//Modificado, Insertado eliminado
            //3,//Procesado y guardado por la sucursal

            $table->unique(["origen","destino","accion"]);

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
        Schema::dropIfExists('tareas');
    }
}
