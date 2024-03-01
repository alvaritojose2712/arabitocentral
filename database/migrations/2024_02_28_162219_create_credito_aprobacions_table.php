<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditoAprobacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credito_aprobacions', function (Blueprint $table) {
            $table->increments("id");

            $table->integer("id_cliente")->unsigned();
            $table->foreign('id_cliente')->references('id')->on('clientes');

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');

            $table->integer("estatus");
            $table->integer("idinsucursal");

            $table->decimal("saldo",10,2);
            
            $table->unique(["id_sucursal","idinsucursal"]);
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
        Schema::dropIfExists('credito_aprobacions');
    }
}
