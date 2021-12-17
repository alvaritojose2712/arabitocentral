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

            $table->timestamps();
        });

        DB::table("sucursals")->insert([
            ["nombre"=>"Mantecal","codigo"=>"ARAMCAL"],
            ["nombre"=>"Elorza","codigo"=>"ARAELZ"],
            ["nombre"=>"Achaguas","codigo"=>"ARAAGS"],
            ["nombre"=>"El Samán","codigo"=>"ARASMAN"],
            ["nombre"=>"Bruzual","codigo"=>"ARABZAL"],
            ["nombre"=>"San Fernando","codigo"=>"ARASFDO"]
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
