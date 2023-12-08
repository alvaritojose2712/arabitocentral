<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('usuario')->unique();
            $table->string('clave');
            $table->integer('tipo_usuario');
            $table->string('area');
            $table->timestamps();
        });
        DB::table("usuarios")->insert([
            [
            "nombre" => "Alvaro Ospino",
            "usuario" => "admin",
            "clave" => Hash::make("Lagalletica2712$$##"),
            "tipo_usuario" => "1",
            "area" => "TI",
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
