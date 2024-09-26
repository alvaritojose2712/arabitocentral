<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarantiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garantias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');

            $table->integer("id_producto");
            $table->integer("idinsucursal");

            $table->float("cantidad",10,2)->nullable(true);
            $table->text("motivo")->nullable(true);

            $table->integer("id_cliente");

            
            $table->decimal("cantidad_salida",8,2)->nullable();
            $table->text("motivo_salida")->nullable();

            $table->integer("ci_cajero")->nullable();
            $table->integer("ci_autorizo")->nullable();
            $table->integer("dias_desdecompra")->nullable();
            $table->integer("ci_cliente")->nullable();
            $table->integer("telefono_cliente")->nullable();
            
            $table->string("nombre_cliente")->nullable();
            $table->string("nombre_cajero")->nullable();
            $table->string("nombre_autorizo")->nullable();
            $table->string("trajo_factura")->nullable();
            $table->string("motivonotrajofact")->nullable();
            

            $table->timestamps();
            
            $table->unique(["idinsucursal","id_sucursal"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('garantias');
    }
}
