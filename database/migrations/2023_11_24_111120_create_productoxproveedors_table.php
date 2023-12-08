<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoxproveedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productoxproveedors', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("id_proveedor")->unsigned();
            $table->foreign('id_proveedor')->references('id')->on('proveedores');

            $table->integer("id_producto")->unsigned();
            $table->foreign('id_producto')->references('id')->on('inventarios');

            $table->float("precio",10,2);
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
        Schema::dropIfExists('productoxproveedors');
    }
}
