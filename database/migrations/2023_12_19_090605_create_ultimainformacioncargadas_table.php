<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUltimainformacioncargadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ultimainformacioncargadas', function (Blueprint $table) {
            $table->increments("id");

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');
            $table->date("fecha");
            
            $table->date("date_last_cierres");
            $table->integer("id_last_garantias");
            $table->integer("id_last_fallas");
            $table->integer("id_last_efec");
            $table->integer("id_last_estadisticas");

            $table->integer("id_last_movs")->nullable(true);
            
            
            $table->unique(["id_sucursal","fecha"]);
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
        Schema::dropIfExists('ultimainformacioncargadas');
    }
}
