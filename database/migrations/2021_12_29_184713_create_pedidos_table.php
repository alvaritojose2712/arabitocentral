<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('idinsucursal');

            $table->integer("estado");
            //0 "Pediente"
            //1 "Procesado"
            //2 "Extraído"

            //3 "En Revision"
            //4 "Revisado"

            $table->integer("id_origen")->unsigned();
            $table->foreign('id_origen')->references('id')->on('sucursals');

            $table->integer("id_destino")->unsigned();
            $table->foreign('id_destino')->references('id')->on('sucursals');

            $table->unique(["idinsucursal","id_origen"]);

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
        Schema::dropIfExists('pedidos');
    }
}
