<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatGeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_generals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion');

            $table->timestamps();
        });

        DB::table("cat_generals")->insert([
            ["descripcion"=>"TUBOS"],
            ["descripcion"=>"CONEXIONES"],
            ["descripcion"=>"ABRASIVOS"],
            ["descripcion"=>"TOMA CORRIENTE"],
            ["descripcion"=>"SOLDADURA"],
            ["descripcion"=>"HIERRO"],
            ["descripcion"=>"MATERIALES DE CONSTRUCCION"],
            ["descripcion"=>"BOMBILLOS"],
            ["descripcion"=>"LAMPARAS"],
            ["descripcion"=>"CABLES"],
            ["descripcion"=>"TOMA CORRIENTE"],
            ["descripcion"=>"APAGADOR"],
            ["descripcion"=>"PROTECTOR"],
            ["descripcion"=>"SOCATES"],
            ["descripcion"=>"PEGAS "],
            ["descripcion"=>"ADHESIVOS"],
            ["descripcion"=>"LLAVES "],
            ["descripcion"=>"GRIFERIA"],
            ["descripcion"=>"BROCHAS"],
            ["descripcion"=>"ANTICORROSIVO"],
            ["descripcion"=>"RODILLOS"],
            ["descripcion"=>"PINTURA"],
            ["descripcion"=>"HERRAMIENTAS MANUALES"],
            ["descripcion"=>"CERRADURAS"],
            ["descripcion"=>"ESMERIL"],
            ["descripcion"=>"HIDROJET"],
            ["descripcion"=>"MAQUINA DE SOLDAR"],
            ["descripcion"=>"TALADRO"],
            ["descripcion"=>"PISTOLA DE PINTAR"],
            ["descripcion"=>"AGRICOLA"],
            ["descripcion"=>"CONGELADOR"],
            ["descripcion"=>"LAVADORA"],
            ["descripcion"=>"LICUADORA "],
            ["descripcion"=>"COCINA"],
            ["descripcion"=>"AIRE "],
            ["descripcion"=>"TV"],
            ["descripcion"=>"VENTILADOR"],
            ["descripcion"=>"NEVERA"],
            ["descripcion"=>"TOPE"],
            ["descripcion"=>"CAFETERA"],
            ["descripcion"=>"PLANCHA DE ROPA"],
            ["descripcion"=>"CHINOS"],

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_generals');
    }
}
