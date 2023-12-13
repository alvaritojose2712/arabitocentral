<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComovamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comovamos', function (Blueprint $table) {
            $table->increments("id");

            $table->decimal("transferencia",10,2)->default(0); 
            $table->decimal("biopago",10,2)->default(0); 
            $table->decimal("debito",10,2)->default(0); 
            $table->decimal("efectivo",10,2)->default(0); 
            
            $table->decimal("tasa",10,2)->default(0); 
            $table->decimal("tasa_cop",10,2)->default(0); 
            $table->integer("numventas")->default(0); 
            
            
            
            $table->decimal("total_inventario",10,2)->default(0); 
            $table->decimal("total_inventario_base",10,2)->default(0); 
            $table->decimal("cred_total",10,2)->default(0); 
            $table->decimal("total",10,2)->default(0); 
            $table->decimal("ventas",10,2)->default(0); 
            $table->decimal("precio",10,2)->default(0); 
            $table->decimal("precio_base",10,2)->default(0); 
            $table->decimal("desc_total",10,2)->default(0); 
            $table->decimal("ganancia",10,2)->default(0); 
            $table->decimal("porcentaje",10,2)->default(0); 
            
            $table->date("fecha"); 
            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');
            

            $table->unique(["fecha","id_sucursal"]);
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
        Schema::dropIfExists('comovamos');
    }
}
