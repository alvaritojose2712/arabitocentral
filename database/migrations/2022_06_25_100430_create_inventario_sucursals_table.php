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
            $table->increments('id');
            $table->integer("id_pro_sucursal")->unsigned();
            $table->integer("id_pro_sucursal_fixed")->unsigned();
            
            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');

            $table->string("codigo_barras");//->unique();

            $table->string("codigo_proveedor")->nullable()->default(null);

            $table->integer("id_proveedor")->unsigned();
            $table->foreign('id_proveedor')->references('id')->on('proveedores');
            

            $table->integer("id_categoria")->unsigned();
            $table->foreign('id_categoria')->references('id')->on('categorias');

            $table->string("id_marca")->nullable()->default("GENÉRICO");

            $table->string("unidad")->nullable()->default("UND");

            $table->string("id_deposito")->nullable()->default(1);
            
            $table->boolean("check")->default(0); //0 -> no importado---- 1 -> Importado


            
            
            $table->string("descripcion");

            $table->decimal("iva",5,2)->nullable()->default(0);

            $table->decimal("porcentaje_ganancia",3,2)->nullable()->default(0);
            $table->decimal("precio_base",8,2)->default(0);
            $table->decimal("precio",8,2)->default(0);

            $table->decimal("cantidad",9,2)->default(0);
            $table->timestamps();
            $table->unique(["id_pro_sucursal","id_sucursal"]);
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
