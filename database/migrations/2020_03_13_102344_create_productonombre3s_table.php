<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductonombre3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productonombre3s', function (Blueprint $table) {
            $table->increments("id");
            $table->string("nombre")->index();


            $table->integer("id_productonombre2")->nullable(true);
            //->unsigned();
            //$table->foreign('id_productonombre2')->references('id')->on('productonombre2s');
            
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
        Schema::dropIfExists('productonombre3s');
    }
}
