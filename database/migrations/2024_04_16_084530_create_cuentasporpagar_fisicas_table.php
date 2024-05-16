<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuentasporpagarFisicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentasporpagar_fisicas', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("estado");
            $table->string("ruta");

            $table->integer("id_proveedor")->unsigned();
            $table->foreign('id_proveedor')->references('id')->on('proveedores');
            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');
            $table->string("numfact");

            $table->unique(["numfact","id_proveedor","id_sucursal"]);

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
        Schema::dropIfExists('cuentasporpagar_fisicas');
    }
}
