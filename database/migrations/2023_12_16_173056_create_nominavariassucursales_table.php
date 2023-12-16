<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominavariassucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominavariassucursales', function (Blueprint $table) {
            $table->increments("id");



            $table->integer("id_nomina")->unsigned();
            $table->foreign('id_nomina')->references('id')->on('nominas');

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');
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
        Schema::dropIfExists('nominavariassucursales');
    }
}
