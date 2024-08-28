<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuentasporpagarPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentasporpagar_pagos', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("id_factura")->unsigned();
            $table->foreign('id_factura')->references('id')->on('cuentasporpagars');

            $table->integer("id_pago");

            $table->integer("tipo")->nullable(); //1 BANCO //2 EFECTIVO

            $table->decimal("monto",10,2);

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
        Schema::dropIfExists('cuentasporpagar_pagos');
    }
}
