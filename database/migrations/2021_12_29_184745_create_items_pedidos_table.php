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
            

            $table->integer("id_producto");


            $table->integer("id_pedido")->unsigned();
            $table->foreign('id_pedido')->references('id')->on('pedidos')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->decimal("cantidad",10,2);
            $table->decimal("descuento",10,2)->default(0);
            $table->decimal("monto",10,2);

            $table->decimal("ct_real",12,2)->nullable()->default(null);
            $table->string("barras_real")->nullable()->default(null);
            $table->string("alterno_real")->nullable()->default(null);

            $table->string("descripcion_real")->nullable()->default(null);
            $table->integer("vinculo_real")->nullable()->default(null);

            
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
