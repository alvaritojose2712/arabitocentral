<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFallasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fallas', function (Blueprint $table) {
            $table->increments("id");

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');

            $table->integer("id_producto")->unsigned();
            $table->foreign('id_producto')->references('id')->on('inventarios');

            $table->float("cantidad",10,2)->nullable();

            $table->integer("id_local");
            $table->unique(["id_sucursal","id_local"]);
            
            $table->timestamps();

        });

        DB::table("fallas")->insert([
            ["id_sucursal"=>1,"id_producto"=>12, "id_local"=>1],
            ["id_sucursal"=>1,"id_producto"=>13, "id_local"=>2],
            ["id_sucursal"=>1,"id_producto"=>14, "id_local"=>3],
            ["id_sucursal"=>1,"id_producto"=>15, "id_local"=>4],
            ["id_sucursal"=>1,"id_producto"=>16, "id_local"=>5],
            ["id_sucursal"=>1,"id_producto"=>17, "id_local"=>6],
            ["id_sucursal"=>1,"id_producto"=>18, "id_local"=>7],
            ["id_sucursal"=>1,"id_producto"=>19, "id_local"=>8],
            ["id_sucursal"=>1,"id_producto"=>20, "id_local"=>9],
            ["id_sucursal"=>1,"id_producto"=>21, "id_local"=>10],
            ["id_sucursal"=>1,"id_producto"=>22, "id_local"=>11],
            ["id_sucursal"=>1,"id_producto"=>23, "id_local"=>12],
            ["id_sucursal"=>1,"id_producto"=>24, "id_local"=>13],
            ["id_sucursal"=>1,"id_producto"=>25, "id_local"=>14],
            ["id_sucursal"=>1,"id_producto"=>26, "id_local"=>15],
            ["id_sucursal"=>1,"id_producto"=>27, "id_local"=>16],
            ["id_sucursal"=>1,"id_producto"=>28, "id_local"=>17],
            ["id_sucursal"=>1,"id_producto"=>29, "id_local"=>18],







            ["id_sucursal"=>2,"id_producto"=>12, "id_local"=>19],
            ["id_sucursal"=>2,"id_producto"=>13, "id_local"=>20],
            ["id_sucursal"=>2,"id_producto"=>14, "id_local"=>21],
            ["id_sucursal"=>2,"id_producto"=>15, "id_local"=>22],
            ["id_sucursal"=>2,"id_producto"=>16, "id_local"=>23],
            ["id_sucursal"=>2,"id_producto"=>17, "id_local"=>24],
            ["id_sucursal"=>2,"id_producto"=>18, "id_local"=>25],
            ["id_sucursal"=>2,"id_producto"=>19, "id_local"=>26],
            ["id_sucursal"=>2,"id_producto"=>20, "id_local"=>27],
            ["id_sucursal"=>2,"id_producto"=>21, "id_local"=>28],
            ["id_sucursal"=>2,"id_producto"=>22, "id_local"=>29],
            ["id_sucursal"=>2,"id_producto"=>23, "id_local"=>30],
            ["id_sucursal"=>2,"id_producto"=>24, "id_local"=>31],
            ["id_sucursal"=>2,"id_producto"=>25, "id_local"=>32],
            ["id_sucursal"=>2,"id_producto"=>26, "id_local"=>33],
            ["id_sucursal"=>2,"id_producto"=>27, "id_local"=>34],
            ["id_sucursal"=>2,"id_producto"=>28, "id_local"=>35],
            ["id_sucursal"=>2,"id_producto"=>29, "id_local"=>36],





            ["id_sucursal"=>3,"id_producto"=>12, "id_local"=>37],
            ["id_sucursal"=>3,"id_producto"=>13, "id_local"=>38],
            ["id_sucursal"=>3,"id_producto"=>14, "id_local"=>39],
            ["id_sucursal"=>3,"id_producto"=>15, "id_local"=>40],
            ["id_sucursal"=>3,"id_producto"=>16, "id_local"=>41],
            ["id_sucursal"=>3,"id_producto"=>17, "id_local"=>42],
            ["id_sucursal"=>3,"id_producto"=>18, "id_local"=>43],
            ["id_sucursal"=>3,"id_producto"=>19, "id_local"=>44],
            ["id_sucursal"=>3,"id_producto"=>20, "id_local"=>45],
            ["id_sucursal"=>3,"id_producto"=>21, "id_local"=>46],
            ["id_sucursal"=>3,"id_producto"=>22, "id_local"=>47],
            ["id_sucursal"=>3,"id_producto"=>23, "id_local"=>48],
            ["id_sucursal"=>3,"id_producto"=>24, "id_local"=>49],
            ["id_sucursal"=>3,"id_producto"=>25, "id_local"=>50],
            ["id_sucursal"=>3,"id_producto"=>26, "id_local"=>51],
            ["id_sucursal"=>3,"id_producto"=>27, "id_local"=>52],
            ["id_sucursal"=>3,"id_producto"=>28, "id_local"=>53],
            ["id_sucursal"=>3,"id_producto"=>29, "id_local"=>54],






            ["id_sucursal"=>4,"id_producto"=>12, "id_local"=>55],
            ["id_sucursal"=>4,"id_producto"=>13, "id_local"=>56],
            ["id_sucursal"=>4,"id_producto"=>14, "id_local"=>57],
            ["id_sucursal"=>4,"id_producto"=>15, "id_local"=>58],
            ["id_sucursal"=>4,"id_producto"=>16, "id_local"=>59],
            ["id_sucursal"=>4,"id_producto"=>17, "id_local"=>60],
            ["id_sucursal"=>4,"id_producto"=>18, "id_local"=>61],
            ["id_sucursal"=>4,"id_producto"=>19, "id_local"=>62],
            ["id_sucursal"=>4,"id_producto"=>20, "id_local"=>63],
            ["id_sucursal"=>4,"id_producto"=>21, "id_local"=>64],
            ["id_sucursal"=>4,"id_producto"=>22, "id_local"=>65],
            ["id_sucursal"=>4,"id_producto"=>23, "id_local"=>66],
            ["id_sucursal"=>4,"id_producto"=>24, "id_local"=>67],
            ["id_sucursal"=>4,"id_producto"=>25, "id_local"=>68],
            ["id_sucursal"=>4,"id_producto"=>26, "id_local"=>69],
            ["id_sucursal"=>4,"id_producto"=>27, "id_local"=>70],
            ["id_sucursal"=>4,"id_producto"=>28, "id_local"=>71],
            ["id_sucursal"=>4,"id_producto"=>29, "id_local"=>72],







            ["id_sucursal"=>5,"id_producto"=>12, "id_local"=>73],
            ["id_sucursal"=>5,"id_producto"=>13, "id_local"=>74],
            ["id_sucursal"=>5,"id_producto"=>14, "id_local"=>75],
            ["id_sucursal"=>5,"id_producto"=>15, "id_local"=>76],
            ["id_sucursal"=>5,"id_producto"=>16, "id_local"=>77],
            ["id_sucursal"=>5,"id_producto"=>17, "id_local"=>78],
            ["id_sucursal"=>5,"id_producto"=>18, "id_local"=>79],
            ["id_sucursal"=>5,"id_producto"=>19, "id_local"=>80],
            ["id_sucursal"=>5,"id_producto"=>20, "id_local"=>81],
            ["id_sucursal"=>5,"id_producto"=>21, "id_local"=>82],
            ["id_sucursal"=>5,"id_producto"=>22, "id_local"=>83],
            ["id_sucursal"=>5,"id_producto"=>23, "id_local"=>84],
            ["id_sucursal"=>5,"id_producto"=>24, "id_local"=>85],
            ["id_sucursal"=>5,"id_producto"=>25, "id_local"=>86],
            ["id_sucursal"=>5,"id_producto"=>26, "id_local"=>87],
            ["id_sucursal"=>5,"id_producto"=>27, "id_local"=>88],
            ["id_sucursal"=>5,"id_producto"=>28, "id_local"=>89],
            ["id_sucursal"=>5,"id_producto"=>29, "id_local"=>90],


            ["id_sucursal"=>6,"id_producto"=>12, "id_local"=>91],
            ["id_sucursal"=>6,"id_producto"=>13, "id_local"=>92],
            ["id_sucursal"=>6,"id_producto"=>14, "id_local"=>93],
            ["id_sucursal"=>6,"id_producto"=>15, "id_local"=>94],
            ["id_sucursal"=>6,"id_producto"=>16, "id_local"=>95],
            ["id_sucursal"=>6,"id_producto"=>17, "id_local"=>96],
            ["id_sucursal"=>6,"id_producto"=>18, "id_local"=>97],
            ["id_sucursal"=>6,"id_producto"=>19, "id_local"=>98],
            ["id_sucursal"=>6,"id_producto"=>20, "id_local"=>99],
            ["id_sucursal"=>6,"id_producto"=>21, "id_local"=>100],
            ["id_sucursal"=>6,"id_producto"=>22, "id_local"=>101],
            ["id_sucursal"=>6,"id_producto"=>23, "id_local"=>102],
            ["id_sucursal"=>6,"id_producto"=>24, "id_local"=>103],
            ["id_sucursal"=>6,"id_producto"=>25, "id_local"=>104],
            ["id_sucursal"=>6,"id_producto"=>26, "id_local"=>105],
            ["id_sucursal"=>6,"id_producto"=>27, "id_local"=>106],
            ["id_sucursal"=>6,"id_producto"=>28, "id_local"=>107],
            ["id_sucursal"=>6,"id_producto"=>29, "id_local"=>108],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fallas');
    }
}
