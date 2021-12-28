<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion');
            $table->string('rif');
            $table->text('direccion');
            $table->string('telefono');

            $table->unique("rif");

            $table->timestamps();
        });
        DB::table("proveedores")->insert([
            [
                "descripcion"=>"Proveedor 1",
                "rif"=>"216282228",
                "direccion"=>"Elorza",
                "telefono"=>"0426896585",
            ],

            [
                "descripcion"=>"Comercial Rio 2020",
                "rif"=>"2323232",
                "direccion"=>"BQTO",
                "telefono"=>"0426893232",
            ],

            [
                "descripcion"=>"Ferre Los centauros",
                "rif"=>"J-756565",
                "direccion"=>"MCAY",
                "telefono"=>"0426893232",
            ],

            [
                "descripcion"=>"Las mercedes",
                "rif"=>"V-86321632",
                "direccion"=>"MCBO",
                "telefono"=>"34343646465",
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
}
