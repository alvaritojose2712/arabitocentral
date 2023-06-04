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
            $table->timestamps();
        });
        DB::table("usuarios")->insert([
            [
            "nombre" => "Alvaro Ospino",
            "usuario" => "admin",
            "clave" => Hash::make("1234"),
            "tipo_usuario" => "1",
            ],
            [
            "nombre" => "admin2",
            "usuario" => "admin2",
            "clave" => Hash::make("1234"),
            "tipo_usuario" => "1",
            ],
            [
            "nombre" => "admin3",
            "usuario" => "admin3",
            "clave" => Hash::make("1234"),
            "tipo_usuario" => "1",
            ],
            [
            "nombre" => "admin4",
            "usuario" => "admin4",
            "clave" => Hash::make("1234"),
            "tipo_usuario" => "1",
            ],

            [
            "nombre" => "admin5",
            "usuario" => "admin5",
            "clave" => Hash::make("1234"),
            "tipo_usuario" => "1",
            ],

            [
            "nombre" => "admin6",
            "usuario" => "admin6",
            "clave" => Hash::make("1234"),
            "tipo_usuario" => "1",
            ],

            [
            "nombre" => "admin7",
            "usuario" => "admin7",
            "clave" => Hash::make("1234"),
            "tipo_usuario" => "1",
            ],

            [
            "nombre" => "admin8",
            "usuario" => "admin8",
            "clave" => Hash::make("1234"),
            "tipo_usuario" => "1",
            ],

            [
            "nombre" => "admin9",
            "usuario" => "admin9",
            "clave" => Hash::make("1234"),
            "tipo_usuario" => "1",
            ],
            // [
            // "nombre" => "Bonai",
            // "usuario" => "caja",
            // "clave" => Hash::make("1234"),
            // "tipo_usuario" => "2",
            // ],
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
