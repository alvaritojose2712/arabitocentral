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

            $table->string("codigo_proveedor")->unique();
            $table->string("codigo_barras")->unique();

            $table->integer("id_proveedor")->unsigned();
            $table->foreign('id_proveedor')->references('id')->on('proveedores');
            

            $table->integer("id_categoria")->unsigned();
            $table->foreign('id_categoria')->references('id')->on('categorias');

            $table->integer("id_marca")->unsigned();
            $table->foreign('id_marca')->references('id')->on('marcas');

            $table->string("unidad");

            $table->integer("id_deposito")->unsigned();
            $table->foreign('id_deposito')->references('id')->on('depositos');

            
            
            $table->string("descripcion");

            

            

            $table->float("iva",10,2);

            $table->float("porcentaje_ganancia",10,2);
            $table->float("precio_base",10,2);
            $table->float("precio",10,2);

            $table->timestamps();
            

            // $table->float("cantidad",10,2);

        });
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
