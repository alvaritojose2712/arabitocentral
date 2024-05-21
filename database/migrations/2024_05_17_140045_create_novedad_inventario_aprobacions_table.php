<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovedadInventarioAprobacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('novedad_inventario_aprobacions', function (Blueprint $table) {
            $table->increments("id");
            
            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');
            
            $table->integer("idinsucursal");
            $table->string("responsable");
            $table->text("motivo");
            $table->integer("estado");

            $table->string("codigo_barras_old")->nullable()->default(null);
            $table->string("codigo_proveedor_old")->nullable()->default(null);
            $table->string("descripcion_old")->nullable()->default(null);
            $table->decimal("precio_base_old",8,3)->nullable()->default(0);
            $table->decimal("precio_old",8,3)->nullable()->default(0);
            $table->decimal("cantidad_old",9,2)->nullable()->default(0);

            $table->integer("id_categoria_old")->nullable(true);
            $table->integer("id_proveedor_old")->nullable(true);
            $table->integer("id_categoria")->nullable(true);
            $table->integer("id_proveedor")->nullable(true);

            $table->string("codigo_barras")->nullable()->default(null);
            $table->string("codigo_proveedor")->nullable()->default(null);
            $table->string("descripcion")->nullable()->default(null);
            $table->decimal("precio_base",8,3)->nullable()->default(0);
            $table->decimal("precio",8,3)->nullable()->default(0);
            $table->decimal("cantidad",9,2)->nullable()->default(0);
            
            $table->unique(["id_sucursal","idinsucursal"]);
            
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
        Schema::dropIfExists('novedad_inventario_aprobacions');
    }
}
