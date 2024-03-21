<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferenciaAprobacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferencia_aprobacions', function (Blueprint $table) {
            $table->increments("id");

            $table->string("loteserial");
            $table->string("banco");

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');

            
            $table->decimal("saldo",10,2);
            
            $table->integer("estatus");
            $table->integer("idinsucursal");
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
        Schema::dropIfExists('transferencia_aprobacions');
    }
}
