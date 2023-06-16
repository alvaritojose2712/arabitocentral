<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominas', function (Blueprint $table) {
            $table->increments("id");

            $table->string("nominanombre");
            $table->integer("nominacedula")->unique();
            $table->string("nominatelefono");
            $table->string("nominadireccion");
            $table->date("nominafechadenacimiento");
            $table->date("nominafechadeingreso");
            $table->string("nominagradoinstruccion");

            $table->integer("nominacargo")->unsigned();
            $table->foreign('nominacargo')->references('id')->on('nominacargos');

            $table->integer("nominasucursal")->unsigned();
            $table->foreign('nominasucursal')->references('id')->on('sucursals');

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
        Schema::dropIfExists('nominas');
    }
}
