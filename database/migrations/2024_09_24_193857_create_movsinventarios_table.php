<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovsinventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movsinventarios', function (Blueprint $table) {
            $table->increments("id");

            $table->integer("idinsucursal");
            
            $table->integer("id_producto");
            $table->integer("id_pedido")->nullable();

            $table->integer("id_usuario");

            $table->integer("cantidad");
            $table->integer("cantidadafter");
            $table->string("origen");

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
        Schema::dropIfExists('movsinventarios');
    }
}
