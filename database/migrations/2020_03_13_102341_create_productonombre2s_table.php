<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductonombre2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productonombre2s', function (Blueprint $table) {
            $table->increments("id");
            $table->string("nombre")->index();


            $table->integer("id_productonombre1")->nullable(true);
            //->unsigned();
            //$table->foreign('id_productonombre1')->references('id')->on('productonombre1s');
            
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
        Schema::dropIfExists('productonombre2s');
    }
}
