<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasSucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas_sucursales', function (Blueprint $table) {
            $table->increments('id');

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');
            
            $table->integer("tipo");
            // 1 EDITAR PRODUCTO
            // 2 ELIMINAR DUPLICADO
            
            $table->integer("estado")->default(0);
            // 0 EMITIDO
            // 1 PROCESADO
            
            $table->integer("permiso")->default(0);
            $table->integer("idinsucursal")->nullable();
            $table->json("antesproducto")->nullable();
            $table->json("cambiarproducto")->nullable();

            $table->integer("id_producto_verde")->nullable(); // PRODUCTO QUE QUEDARÁ EXISTENTE
            $table->string("id_producto_rojo")->nullable(); // PRODUCTOS QUE SERÁN ELIMINADOS

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
        Schema::dropIfExists('tareas_sucursales');
    }
}
