<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBancosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bancos', function (Blueprint $table) {
            $table->increments("id");

            $table->integer("id_banco")->unsigned();
            $table->foreign('id_banco')->references('id')->on('bancos_lists');

            $table->integer("id_usuario")->unsigned();
            $table->foreign('id_usuario')->references('id')->on('usuarios');

            $table->string("descripcion");
            $table->date("fecha");

            $table->decimal("monto",10,2);
            $table->decimal("saldo",10,2);
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
        Schema::dropIfExists('bancos');
    }
}
