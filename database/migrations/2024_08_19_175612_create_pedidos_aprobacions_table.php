<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosAprobacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_aprobacions', function (Blueprint $table) {
            $table->increments("id");

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');
            $table->integer("idinsucursal");
            
            $table->integer("estatus");
            $table->string("motivo");
            
            $table->decimal("monto",10,2);
            $table->json("items");
            $table->json("pagos");
            $table->json("cliente");
            
            
            
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
        Schema::dropIfExists('pedidos_aprobacions');
    }
}
