<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuentasporpagarItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentasporpagar_items', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("id_cuenta")->unsigned();
            $table->foreign('id_cuenta')->references('id')->on('cuentasporpagars');

            $table->integer("id_producto");
            /* $table->foreign('id_producto')->references('id')->on('inventario_sucursals'); */
            
            $table->decimal("cantidad",12,2);
            $table->decimal("basef",12,2);
            $table->decimal("base",12,2);
            $table->decimal("venta",12,2);
            $table->integer("estado")->default(0);

            $table->unique(["id_cuenta","id_producto"]);
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
        Schema::dropIfExists('cuentasporpagar_items');
    }
}
