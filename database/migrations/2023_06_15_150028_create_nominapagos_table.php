<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominapagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominapagos', function (Blueprint $table) {
            $table->increments("id");

            $table->float("monto",10,2);
            $table->string("descripcion");

            $table->integer("id_nomina")->unsigned();
            $table->foreign('id_nomina')->references('id')->on('nominas');

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
        Schema::dropIfExists('nominapagos');
    }
}
