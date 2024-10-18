<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->increments('id');

            $table->string("codigo_barras")->nullable()->default(null);//->unique();
            $table->string("codigo_proveedor")->nullable()->default(null);
            $table->string("codigo_proveedor2")->nullable()->default(null);
            $table->integer("id_categoria")->nullable();
            $table->integer("id_catgeneral")->nullable();
            $table->string("unidad")->nullable();
            $table->string("descripcion");
            $table->decimal("iva",5,2)->nullable()->default(0);
            $table->decimal("precio_base",8,2)->nullable()->default(0);
            $table->decimal("precio",8,2)->nullable()->default(0);
            $table->decimal("precio1",8,3)->nullable();
            $table->decimal("precio2",8,3)->nullable();
            $table->decimal("precio3",8,3)->nullable();
            $table->integer("bulto")->nullable();
            $table->integer("stockmin")->nullable();
            $table->integer("stockmax")->nullable();
            $table->decimal("cantidad",9,2)->nullable()->default(0);
            $table->string("marca")->nullable();
            $table->string("n1")->nullable();
            $table->string("n2")->nullable();
            $table->string("n3")->nullable();
            $table->string("n4")->nullable();

            $table->timestamps();

            // $table->float("cantidad",10,2);

        });
        /* $arr = [];

        $inventario = [
        ];


        foreach ($inventario as $key => $value) {
            array_push($arr, 
                [
                    // "id" => $value[0],
                    "codigo_proveedor" => $value[0].$value[2],
                    "codigo_barras" => "MAN".$value[0],
                    "id_proveedor" => 1,
                    "id_categoria" => 1,
                    "id_marca" => 1,
                    "unidad" => $value[5],
                    "id_deposito" => 1,
                    "descripcion" => $value[4],
                    "iva" => 0,
                    "porcentaje_ganancia" => 0,
                    "precio_base" => 1,
                    "precio" => $value[7],
                    "cantidad" => $value[8],

                ]
            );
        }
        DB::table("inventarios")->insert($arr); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventarios');
    }
}
