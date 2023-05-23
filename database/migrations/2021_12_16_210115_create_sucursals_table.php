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
            $table->string('char')->unique();
            $table->string('codigo')->unique();

            $table->timestamps();
        });

        DB::table("sucursals")->insert([
            ["char"=>"EZ","nombre"=>"Elorza","codigo"=>"ARAELZ"],
            ["char"=>"ML","nombre"=>"Mantecal","codigo"=>"ARAMCAL"],
            ["char"=>"AG","nombre"=>"Achaguas","codigo"=>"ARAAGS"],
            ["char"=>"SM","nombre"=>"El Samán","codigo"=>"ARASMAN"],
            ["char"=>"BZ","nombre"=>"Bruzual","codigo"=>"ARABZAL"],
            ["char"=>"SF","nombre"=>"San Fernando","codigo"=>"ARASFDO"],
            ["char"=>"AC","nombre"=>"CENTRAL","codigo"=>"ARACENTRAL"],
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
