<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_pedidos', function (Blueprint $table) {
             $table->increments('id');
            

            $table->integer("id_producto")->unsigned();
            $table->foreign('id_producto')->references('id')->on('inventario_sucursals');


            $table->integer("id_pedido")->unsigned();
            $table->foreign('id_pedido')->references('id')->on('pedidos')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->decimal("cantidad",10,2);
            $table->decimal("descuento",10,2)->default(0);
            $table->decimal("monto",10,2);
            $table->timestamps();

            $table->unique(["id_producto","id_pedido"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items_pedidos');
    }
}
