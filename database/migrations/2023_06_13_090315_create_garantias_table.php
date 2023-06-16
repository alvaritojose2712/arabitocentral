<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarantiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garantias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');

            $table->integer("id_producto")->unsigned();
            $table->foreign('id_producto')->references('id')->on('inventarios');

            $table->float("cantidad",10,2);
            $table->text("motivo");

            $table->integer("id_cliente")->unsigned();
            $table->foreign('id_cliente')->references('id')->on('clientes');

            $table->timestamps();
            
            $table->unique(["id_producto","id_sucursal"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('garantias');
    }
}
