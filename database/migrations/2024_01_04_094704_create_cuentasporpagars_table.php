<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuentasporpagarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentasporpagars', function (Blueprint $table) {
            $table->increments("id");

            $table->integer("id_proveedor")->unsigned();
            $table->foreign('id_proveedor')->references('id')->on('proveedores');

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');


            $table->string("numfact");
            $table->string("numnota")->nullable(true)->default(null);
            
            $table->string("descripcion");
            
            $table->decimal("subtotal",10,2)->nullable()->default(0);
            $table->decimal("descuento",10,2)->nullable()->default(0);
            $table->decimal("monto_exento",10,2)->nullable()->default(0);
            $table->decimal("monto_gravable",10,2)->nullable()->default(0);
            $table->decimal("iva",10,2)->nullable()->default(0);
            $table->decimal("monto",10,2)->nullable()->default(0);
            $table->decimal("balance",10,2)->nullable()->default(0);

            $table->integer('metodo')->nullable()->default(null); 
            
            $table->date("fechaemision");
            $table->date("fechavencimiento");
            $table->date("fecharecepcion")->nullable(true)->default(null);
            $table->text("nota")->nullable(true)->default(null);
            
            $table->integer("tipo");
            // 1 COMPRAS
            // 2 SERVICIOS
            $table->string("frecuencia")->nullable(true)->default(0);
            
            $table->string("idinsucursal")->nullable(true)->default(null);
            $table->unique(["idinsucursal","id_sucursal"]);
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
        Schema::dropIfExists('cuentasporpagars');
    }
}
