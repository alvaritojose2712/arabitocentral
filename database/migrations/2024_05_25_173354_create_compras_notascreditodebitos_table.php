<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasNotascreditodebitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras_notascreditodebitos', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("tipo");
            //0 credito
            //1 debito
            $table->string("num");
            $table->integer("id_proveedor")->unsigned();
            $table->foreign('id_proveedor')->references('id')->on('proveedores');
            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');
            
            $table->decimal("monto",10,2)->nullable()->default(0);
            $table->integer("estatus")->nullable()->default(0);
            $table->integer("id_factura")->nullable()->default(null);

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
        Schema::dropIfExists('compras_notascreditodebitos');
    }
}
