<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_facturas', function (Blueprint $table) {
            $table->increments("id");

            $table->integer("id_factura")->unsigned();
            $table->foreign('id_factura')->references('id')->on('facturas');

            $table->integer("id_producto")->unsigned();
            $table->foreign('id_producto')->references('id')
            ->on('inventarios')->onDelete("cascade");

            $table->decimal("cantidad",8,2);
            $table->string("tipo");

            $table->unique(["id_factura","id_producto"]);


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
        Schema::dropIfExists('items_facturas');
    }
}
