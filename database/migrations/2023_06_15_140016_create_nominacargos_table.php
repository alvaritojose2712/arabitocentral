<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominacargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominacargos', function (Blueprint $table) {
            $table->increments("id");
            $table->string("cargosdescripcion")->unique();
            $table->string("cargossueldo");
            $table->timestamps();
        });

       /*  DB::table("nominacargos")->insert([
            ["cargosdescripcion"=>"PORTERO","cargossueldo"=>0],
            ["cargosdescripcion"=>"LIMPIEZA","cargossueldo"=>0],
            ["cargosdescripcion"=>"VENDEDOR/A","cargossueldo"=>0],
            ["cargosdescripcion"=>"DESPACHADOR","cargossueldo"=>0],
            ["cargosdescripcion"=>"CAJERO/A","cargossueldo"=>0],
            ["cargosdescripcion"=>"ENCARGADO DE DEPOSITO","cargossueldo"=>0],
            ["cargosdescripcion"=>"GERENTE","cargossueldo"=>0],
            ["cargosdescripcion"=>"SUBGERENTE","cargossueldo"=>0],
            ["cargosdescripcion"=>"ADMINISTRACION","cargossueldo"=>0],
        ]); */

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nominacargos');
    }
}
