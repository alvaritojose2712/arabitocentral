<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductonombre4sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productonombre4s', function (Blueprint $table) {
            $table->increments("id");
            $table->string("nombre")->index();


            $table->integer("id_productonombre3")->nullable(true);
            //->unsigned();
            //$table->foreign('id_productonombre3')->references('id')->on('productonombre3s');
            
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
        Schema::dropIfExists('productonombre4s');
    }
}
