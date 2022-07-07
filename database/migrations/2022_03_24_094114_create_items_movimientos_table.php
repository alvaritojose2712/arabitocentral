<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_movimientos', function (Blueprint $table) {
            $table->increments('id');

            $table->string("id_producto",10)->nullable();
            $table->string("descripcion")->nullable();
            $table->string("precio",10)->nullable();
            $table->string("cantidad",10)->nullable();
            $table->integer("tipo"); //1 Entrada | 0 Salida | 2 interno
            $table->string("categoria"); //1 Garantia | 2 Cambio

            $table->integer("id_movimiento")->unsigned();
            $table->foreign('id_movimiento')->references('id')->on('movimientos')
            ->onDelete('cascade')
            ->onUpdate('cascade');

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
        Schema::dropIfExists('items_movimientos');
    }
}
