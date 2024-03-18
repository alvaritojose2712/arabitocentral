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

            $table->string("codigo_barras");//->unique();
            $table->string("codigo_proveedor")->nullable()->default(null);
            $table->string("codigo_proveedor2")->nullable()->default(null);

            $table->integer("id_categoria")->unsigned();
            $table->foreign('id_categoria')->references('id')->on('categorias');

            $table->integer("id_catgeneral")->unsigned();
            $table->foreign('id_catgeneral')->references('id')->on('cat_generals');

            
            $table->string("unidad")->nullable()->default("UND");
            
            $table->string("descripcion");
            
            $table->decimal("iva",5,2)->nullable()->default(0);
            
            $table->decimal("precio_base",8,2)->default(0);
            $table->decimal("precio",8,2)->default(0);
            
            $table->decimal("precio1",8,3)->nullable();
            $table->decimal("precio2",8,3)->nullable();
            $table->decimal("precio3",8,3)->nullable();
            $table->integer("bulto")->nullable();
            
            $table->integer("stockmin")->nullable();
            $table->integer("stockmax")->nullable();
            
            $table->decimal("cantidad",9,2)->default(0);
            
            $table->string("marca")->nullable(true);
            $table->foreign('marca')->references('descripcion')->on('marcas');

            $table->string("n1")->nullable(true);
            $table->foreign('n1')->references('nombre')->on('productonombre1s');

            $table->string("n2")->nullable(true);
            $table->foreign('n2')->references('nombre')->on('productonombre2s');

            $table->string("n3")->nullable(true);
            $table->foreign('n3')->references('nombre')->on('productonombre3s');

            $table->string("n4")->nullable(true);
            $table->foreign('n4')->references('nombre')->on('productonombre4s');


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
