<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarioSucursalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventario_sucursals', function (Blueprint $table) {
            $table->increments("id");

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');

            $table->integer("idinsucursal")->nullable(true);


            $table->string("codigo_barras")->nullable()->default(null);//->unique();
            $table->string("codigo_proveedor")->nullable()->default(null);
            $table->string("codigo_proveedor2")->nullable()->default(null);

            $table->integer("id_proveedor");
            
            $table->integer("id_categoria")->nullable(true);
            $table->integer("id_catgeneral")->nullable(true);
            $table->string("id_marca")->nullable()->default("GENÉRICO");
            $table->string("id_deposito")->nullable()->default(1);


            $table->string("unidad")->nullable()->default("UND");


            
            
            $table->string("descripcion");

            $table->decimal("iva",5,2)->nullable()->default(0);

            $table->decimal("porcentaje_ganancia",3,2)->nullable()->default(0);
            $table->decimal("precio_base",8,3)->nullable()->default(0);
            $table->decimal("precio",8,3)->default(0);

            $table->decimal("precio1",8,3)->nullable();
            $table->decimal("precio2",8,3)->nullable();
            $table->decimal("precio3",8,3)->nullable();
            $table->integer("bulto")->nullable();

            $table->integer("stockmin")->nullable();
            $table->integer("stockmax")->nullable();

            $table->decimal("cantidad",9,2)->default(0);

            $table->boolean("push")->nullable()->default(0);

            $table->integer('id_vinculacion')->nullable();
            $table->unique(["id_sucursal","idinsucursal"]);   
            

            $table->string("n1")->nullable(true)->default(null);
            $table->string("n2")->nullable(true)->default(null);
            $table->string("n3")->nullable(true)->default(null);
            $table->string("n4")->nullable(true)->default(null);
            $table->string("n5")->nullable(true)->default(null);

            
            $table->timestamps();
            
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventario_sucursals');
    }
}
