<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuntosybiopagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puntosybiopagos', function (Blueprint $table) {
            $table->increments("id");
            $table->string("loteserial")->nullable(true);
            $table->string("banco")->nullable(true);
            $table->string("tipo")->nullable(true);
            $table->integer("categoria")->nullable(true)->default(1);
            $table->string("debito_credito")->nullable(true);
            
            $table->date("fecha");
            $table->date("fecha_liquidacion")->nullable(true)->default(null);
            $table->decimal("monto",10,2)->nullable(true);
            $table->decimal("monto_liquidado",10,2)->nullable(true);
            
            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');

            
            $table->integer("id_beneficiario")->nullable(true)->default(null);
            $table->integer("origen")->nullable(true)->default(1);
            //1 Sucursal
            //2 Administracion
            $table->decimal("monto_dolar",10,2)->nullable(true)->default(0);
            $table->decimal("tasa",10,4)->nullable(true)->default(0);
            
            $table->integer("id_usuario");
            $table->integer("id_comision")->nullable(true);
            $table->integer("id_noreportada")->nullable(true);
            
            //$table->unique(["fecha","id_sucursal","id_usuario","tipo"]);
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
        Schema::dropIfExists('puntosybiopagos');
    }
}
