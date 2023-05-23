<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtSucursalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ct_sucursals', function (Blueprint $table) {
            $table->increments("id");

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');

            $table->integer("id_producto")->unsigned();
            $table->foreign('id_producto')->references('id')->on('inventarios');

            $table->float("cantidad",10,2);

            $table->float("precio",10,2)->nullable(); //En caso de precio especial para la zona

            $table->unique(["id_sucursal","id_producto"]);

            $table->timestamps();
        });

        /* DB::table("ct_sucursals")->insert([
            ["id_sucursal"=>1,"id_producto"=>1,"cantidad"=>561],
            ["id_sucursal"=>1,"id_producto"=>2,"cantidad"=>342],
            ["id_sucursal"=>1,"id_producto"=>3,"cantidad"=>543],
            ["id_sucursal"=>1,"id_producto"=>4,"cantidad"=>124],
            ["id_sucursal"=>1,"id_producto"=>5,"cantidad"=>645],
            ["id_sucursal"=>1,"id_producto"=>6,"cantidad"=>1236],
            ["id_sucursal"=>1,"id_producto"=>7,"cantidad"=>747],
            ["id_sucursal"=>1,"id_producto"=>8,"cantidad"=>348],
            ["id_sucursal"=>1,"id_producto"=>9,"cantidad"=>559],
            ["id_sucursal"=>1,"id_producto"=>10,"cantidad"=>5710],
            ["id_sucursal"=>1,"id_producto"=>11,"cantidad"=>2111],

            ["id_sucursal"=>2,"id_producto"=>1,"cantidad"=>5612],
            ["id_sucursal"=>2,"id_producto"=>2,"cantidad"=>3413],
            ["id_sucursal"=>2,"id_producto"=>3,"cantidad"=>5414],
            ["id_sucursal"=>2,"id_producto"=>4,"cantidad"=>1215],
            ["id_sucursal"=>2,"id_producto"=>5,"cantidad"=>6416],
            ["id_sucursal"=>2,"id_producto"=>6,"cantidad"=>12317],
            ["id_sucursal"=>2,"id_producto"=>7,"cantidad"=>7418],
            ["id_sucursal"=>2,"id_producto"=>8,"cantidad"=>3419],
            ["id_sucursal"=>2,"id_producto"=>9,"cantidad"=>5520],
            ["id_sucursal"=>2,"id_producto"=>10,"cantidad"=>5721],
            ["id_sucursal"=>2,"id_producto"=>11,"cantidad"=>2122],

            ["id_sucursal"=>3,"id_producto"=>1,"cantidad"=>5623],
            ["id_sucursal"=>3,"id_producto"=>2,"cantidad"=>3424],
            ["id_sucursal"=>3,"id_producto"=>3,"cantidad"=>5425],
            ["id_sucursal"=>3,"id_producto"=>4,"cantidad"=>1226],
            ["id_sucursal"=>3,"id_producto"=>5,"cantidad"=>6427],
            ["id_sucursal"=>3,"id_producto"=>6,"cantidad"=>12328],
            ["id_sucursal"=>3,"id_producto"=>7,"cantidad"=>7429],
            ["id_sucursal"=>3,"id_producto"=>8,"cantidad"=>3430],
            ["id_sucursal"=>3,"id_producto"=>9,"cantidad"=>5531],
            ["id_sucursal"=>3,"id_producto"=>10,"cantidad"=>5732],
            ["id_sucursal"=>3,"id_producto"=>11,"cantidad"=>2133],

            ["id_sucursal"=>4,"id_producto"=>1,"cantidad"=>5634],
            ["id_sucursal"=>4,"id_producto"=>2,"cantidad"=>3435],
            ["id_sucursal"=>4,"id_producto"=>3,"cantidad"=>5436],
            ["id_sucursal"=>4,"id_producto"=>4,"cantidad"=>1237],
            ["id_sucursal"=>4,"id_producto"=>5,"cantidad"=>6438],
            ["id_sucursal"=>4,"id_producto"=>6,"cantidad"=>12339],
            ["id_sucursal"=>4,"id_producto"=>7,"cantidad"=>7440],
            ["id_sucursal"=>4,"id_producto"=>8,"cantidad"=>3441],
            ["id_sucursal"=>4,"id_producto"=>9,"cantidad"=>5542],
            ["id_sucursal"=>4,"id_producto"=>10,"cantidad"=>5743],
            ["id_sucursal"=>4,"id_producto"=>11,"cantidad"=>2144],

            ["id_sucursal"=>5,"id_producto"=>1,"cantidad"=>5645],
            ["id_sucursal"=>5,"id_producto"=>2,"cantidad"=>3446],
            ["id_sucursal"=>5,"id_producto"=>3,"cantidad"=>5447],
            ["id_sucursal"=>5,"id_producto"=>4,"cantidad"=>1248],
            ["id_sucursal"=>5,"id_producto"=>5,"cantidad"=>6449],
            ["id_sucursal"=>5,"id_producto"=>6,"cantidad"=>12350],
            ["id_sucursal"=>5,"id_producto"=>7,"cantidad"=>7451],
            ["id_sucursal"=>5,"id_producto"=>8,"cantidad"=>3452],
            ["id_sucursal"=>5,"id_producto"=>9,"cantidad"=>5553],
            ["id_sucursal"=>5,"id_producto"=>10,"cantidad"=>5754],
            ["id_sucursal"=>5,"id_producto"=>11,"cantidad"=>2155],

            ["id_sucursal"=>6,"id_producto"=>1,"cantidad"=>5656],
            ["id_sucursal"=>6,"id_producto"=>2,"cantidad"=>3457],
            ["id_sucursal"=>6,"id_producto"=>3,"cantidad"=>5458],
            ["id_sucursal"=>6,"id_producto"=>4,"cantidad"=>1259],
            ["id_sucursal"=>6,"id_producto"=>5,"cantidad"=>6460],
            ["id_sucursal"=>6,"id_producto"=>6,"cantidad"=>12361],
            ["id_sucursal"=>6,"id_producto"=>7,"cantidad"=>7462],
            ["id_sucursal"=>6,"id_producto"=>8,"cantidad"=>3463],
            ["id_sucursal"=>6,"id_producto"=>9,"cantidad"=>5564],
            ["id_sucursal"=>6,"id_producto"=>10,"cantidad"=>5765],
            ["id_sucursal"=>6,"id_producto"=>11,"cantidad"=>2166],
        ]); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ct_sucursals');
    }
}
