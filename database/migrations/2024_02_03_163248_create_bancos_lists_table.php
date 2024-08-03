<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBancosListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bancos_lists', function (Blueprint $table) {
            $table->increments("id");
            $table->string("codigo")->unique();
            $table->string("descripcion")->unique();
            $table->string("moneda")->nullable(true)->default("bs");
            $table->timestamps();
        });


       /*  $metodos = [
            ["codigo" => "EFECTIVO", "descripcion"=> "EFECTIVO"],
            ["codigo" => "0102", "descripcion"=> "0102 Banco de Venezuela, S.A. Banco Universal"],
            ["codigo" => "0108", "descripcion"=> "0108 Banco Provincial, S.A. Banco Universal"],
            ["codigo" => "0105", "descripcion"=> "0105 Banco Mercantil C.A., Banco Universal"],
            ["codigo" => "0134", "descripcion"=> "0134 Banesco Banco Universal, C.A."],
            ["codigo" => "0175", "descripcion"=> "0175 Banco Bicentenario del Pueblo, Banco Universal C.A."],
            ["codigo" => "0191", "descripcion"=> "0191 Banco Nacional de Crédito C.A., Banco Universal"],
            ["codigo" => "0151", "descripcion"=> "0151 Banco Fondo Común, C.A Banco Universal"],
            ["codigo" => "ZELLE", "descripcion"=> "ZELLE"],
            ["codigo" => "BINANCE", "descripcion"=> "Binance"],
            ["codigo" => "AirTM", "descripcion"=> "AirTM"],
        ];

        foreach ($metodos as $key => $m) {
            DB::table("bancos_lists")->insert([
                [
                    "codigo" => $m["codigo"],
                    "descripcion" => $m["descripcion"],
                ]
            ]);
        } */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bancos_lists');
    }
}
