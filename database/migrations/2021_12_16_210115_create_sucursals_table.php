<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSucursalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('codigo')->unique();
            
            $table->string('direccion')->nullable();
            $table->string('gerente')->nullable();
            $table->timestamps();
        });

        DB::table("sucursals")->insert([
            ["nombre"=>"Elorza","codigo"=>"elorza"],
            ["nombre"=>"Mantecal","codigo"=>"mantecal"],
            ["nombre"=>"Achaguas","codigo"=>"achaguas"],
            ["nombre"=>"El Samán","codigo"=>"elsaman"],
            ["nombre"=>"Bruzual","codigo"=>"bruzual"],
            ["nombre"=>"San Fernando","codigo"=>"sanfernando1"],
            ["nombre"=>"San Fernando 2","codigo"=>"sanfernando2"],
            ["nombre"=>"Calabozo","codigo"=>"calabozo"],
            ["nombre"=>"Valle de la Pascua","codigo"=>"valledelapascua"],
            ["nombre"=>"Valle de la Pascua 2","codigo"=>"valledelapascua2"],
            ["nombre"=>"San Juan de los Morros","codigo"=>"sanjuandelosmorros"],
            ["nombre"=>"Maracay","codigo"=>"maracay"],
            
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sucursals');
    }
}
