<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion');

            $table->timestamps();
        });
         DB::table("categorias")->insert([
            ["descripcion"=>"VETERINARIA"],
            ["descripcion"=>"TORNILLERIA"],
            ["descripcion"=>"TERMOS"],
            ["descripcion"=>"TELEFONIA"],
            ["descripcion"=>"TECNOLOGIA"],
            ["descripcion"=>"REPUESTOS"],
            ["descripcion"=>"REFRIGERACION"],
            ["descripcion"=>"QUINCALLERIA"],
            ["descripcion"=>"PLOMERIA"],
            ["descripcion"=>"PLANTAS"],
            ["descripcion"=>"PINTURA"],
            ["descripcion"=>"PESCA"],
            ["descripcion"=>"PEGAS"],
            ["descripcion"=>"NAILOS"],
            ["descripcion"=>"MOTOS"],
            ["descripcion"=>"MECANICA"],
            ["descripcion"=>"MALLAS"],
            ["descripcion"=>"LENTES"],
            ["descripcion"=>"JARDINERIA"],
            ["descripcion"=>"INTERNET"],
            ["descripcion"=>"ILUMINACION"],
            ["descripcion"=>"HOGAR"],
            ["descripcion"=>"HERRERIA"],
            ["descripcion"=>"HERRAMIENTAS"],
            ["descripcion"=>"GRIFERIA"],
            ["descripcion"=>"GAS"],
            ["descripcion"=>"FONTANERIA"],
            ["descripcion"=>"ELECTRONICA"],
            ["descripcion"=>"ELECTRODOMESTICO"],
            ["descripcion"=>"ELECTRICIDAD"],
            ["descripcion"=>"DISCO"],
            ["descripcion"=>"CORDONES"],
            ["descripcion"=>"CONSTRUCCION"],
            ["descripcion"=>"CERRADURA"],
            ["descripcion"=>"CERAMICA"],
            ["descripcion"=>"BATERIA"],
            ["descripcion"=>"ALAMBRE"],
            ["descripcion"=>"AGRICOLA"],
            ["descripcion"=>"ACEITES"]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorias');
    }
}
