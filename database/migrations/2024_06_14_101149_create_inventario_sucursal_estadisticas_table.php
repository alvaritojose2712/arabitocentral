<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarioSucursalEstadisticasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventario_sucursal_estadisticas', function (Blueprint $table) {
            $table->increments("id");
            
            $table->float("cantidad",12,2);
            $table->date("fecha");

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');

            $table->integer("id_itempedido_insucursal");
            $table->integer("id_pedido_insucursal");
            $table->integer("id_producto_insucursal");

            $table->unique(["id_sucursal","id_itempedido_insucursal"]);


           /*  $table->integer("id_inventario_sucursal")->unsigned();
            $table->foreign('id_inventario_sucursal')->references('id')->on('inventario_sucursals'); */

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
        Schema::dropIfExists('inventario_sucursal_estadisticas');
    }
}
