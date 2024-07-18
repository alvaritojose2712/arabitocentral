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

            $table->string("banco");
            $table->date("fecha");

            $table->integer("id_usuario")->nullable(true);

            $table->string("descripcion")->nullable(true);

            $table->decimal("saldo",10,2);

            $table->decimal("saldo_real_manual",10,2);
            $table->decimal("saldo_inicial",10,2);
            $table->decimal("ingreso",10,2);
            $table->decimal("egreso",10,2);
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
