<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVinculossucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vinculossucursales', function (Blueprint $table) {
            $table->increments("id");

            $table->integer("id_producto_local");
            $table->integer("idinsucursal_fore");
            $table->integer("id_sucursal_fore");
            

            $table->integer("idinsucursal");
            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');

            $table->unique(["idinsucursal","id_sucursal"]);


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
        Schema::dropIfExists('vinculossucursales');
    }
}
