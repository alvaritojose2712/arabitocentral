getGastos<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->increments('id');
            $table->string("descripcion");
            $table->boolean("tipo"); 
            // 1 Entregado
            // 0 Pendientes
            $table->integer("categoria");

            // 1 Vueltos
            // 2 Nómina
            // 3 Funcionamiento
            // 4 Pago a proveedores
            // 5 Otros
            // 6 Devolución

            $table->float("monto",10,2);
            $table->timestamps();

            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');


            $table->integer("id_local");
            $table->unique(["id_sucursal","id_local"]);


        });

        DB::table("gastos")->insert([
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 1000, "id_sucursal" => 1, "id_local"=> 1],
            ["descripcion" => "Chil CA", "tipo" => 1, "categoria" => 4, "monto" => 1100, "id_sucursal" => 1, "id_local"=> 2],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 1200, "id_sucursal" => 1, "id_local"=> 3],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 1300, "id_sucursal" => 1, "id_local"=> 4],
            ["descripcion" => "Jose FP", "tipo" => 1, "categoria" => 4, "monto" => 1400, "id_sucursal" => 1, "id_local"=> 5],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 1500, "id_sucursal" => 1, "id_local"=> 6],
            ["descripcion" => "MIstic INC", "tipo" => 1, "categoria" => 4, "monto" => 1600, "id_sucursal" => 1, "id_local"=> 7],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 1700, "id_sucursal" => 1, "id_local"=> 8],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 1800, "id_sucursal" => 1, "id_local"=> 9],
            ["descripcion" => "Robertyo Bolaos INC", "tipo" => 1, "categoria" => 4, "monto" => 1900, "id_sucursal" => 1, "id_local"=> 10],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 2000, "id_sucursal" => 1, "id_local"=> 11],
            ["descripcion" => "Aceite", "tipo" => 1, "categoria" => 3, "monto" => 2100, "id_sucursal" => 1, "id_local"=> 12],
            ["descripcion" => "Azucar", "tipo" => 1, "categoria" => 3, "monto" => 2200, "id_sucursal" => 1, "id_local"=> 13],
            ["descripcion" => "Cafe", "tipo" => 1, "categoria" => 3, "monto" => 2300, "id_sucursal" => 1, "id_local"=> 14],
            ["descripcion" => "Agua", "tipo" => 1, "categoria" => 3, "monto" => 2400, "id_sucursal" => 1, "id_local"=> 15],
            ["descripcion" => "Vueltos", "tipo" => 1, "categoria" => 1, "monto" => 2500, "id_sucursal" => 1, "id_local"=> 16],
            ["descripcion" => "Amer", "tipo" => 0, "categoria" => 5, "monto" => 2600, "id_sucursal" => 1, "id_local"=> 17],

             ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 2700, "id_sucursal" => 2, "id_local"=> 18],
            ["descripcion" => "Chil CA", "tipo" => 1, "categoria" => 4, "monto" => 2800, "id_sucursal" => 2, "id_local"=> 19],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 2900, "id_sucursal" => 2, "id_local"=> 20],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 3000, "id_sucursal" => 2, "id_local"=> 21],
            ["descripcion" => "Jose FP", "tipo" => 1, "categoria" => 4, "monto" => 3100, "id_sucursal" => 2, "id_local"=> 22],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 3200, "id_sucursal" => 2, "id_local"=> 23],
            ["descripcion" => "MIstic INC", "tipo" => 1, "categoria" => 4, "monto" => 3300, "id_sucursal" => 2, "id_local"=> 24],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 3400, "id_sucursal" => 2, "id_local"=> 25],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 3500, "id_sucursal" => 2, "id_local"=> 26],
            ["descripcion" => "Robertyo Bolaos INC", "tipo" => 1, "categoria" => 4, "monto" => 3600, "id_sucursal" => 2, "id_local"=> 27],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 3700, "id_sucursal" => 2, "id_local"=> 28],
            ["descripcion" => "Aceite", "tipo" => 1, "categoria" => 3, "monto" => 3800, "id_sucursal" => 2, "id_local"=> 29],
            ["descripcion" => "Azucar", "tipo" => 1, "categoria" => 3, "monto" => 3900, "id_sucursal" => 2, "id_local"=> 30],
            ["descripcion" => "Cafe", "tipo" => 1, "categoria" => 3, "monto" => 4000, "id_sucursal" => 2, "id_local"=> 31],
            ["descripcion" => "Agua", "tipo" => 1, "categoria" => 3, "monto" => 4100, "id_sucursal" => 2, "id_local"=> 32],
            ["descripcion" => "Vueltos", "tipo" => 1, "categoria" => 1, "monto" => 4200, "id_sucursal" => 2, "id_local"=> 33],
            ["descripcion" => "Amer", "tipo" => 0, "categoria" => 5, "monto" => 4300, "id_sucursal" => 2, "id_local"=> 34],


             ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 4400, "id_sucursal" => 3, "id_local"=> 35],
            ["descripcion" => "Chil CA", "tipo" => 1, "categoria" => 4, "monto" => 4500, "id_sucursal" => 3, "id_local"=> 36],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 4600, "id_sucursal" => 3, "id_local"=> 37],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 4700, "id_sucursal" => 3, "id_local"=> 38],
            ["descripcion" => "Jose FP", "tipo" => 1, "categoria" => 4, "monto" => 4800, "id_sucursal" => 3, "id_local"=> 39],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 4900, "id_sucursal" => 3, "id_local"=> 40],
            ["descripcion" => "MIstic INC", "tipo" => 1, "categoria" => 4, "monto" => 5000, "id_sucursal" => 3, "id_local"=> 41],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 5100, "id_sucursal" => 3, "id_local"=> 42],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 5200, "id_sucursal" => 3, "id_local"=> 43],
            ["descripcion" => "Robertyo Bolaos INC", "tipo" => 1, "categoria" => 4, "monto" => 5300, "id_sucursal" => 3, "id_local"=> 44],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 5400, "id_sucursal" => 3, "id_local"=> 45],
            ["descripcion" => "Aceite", "tipo" => 1, "categoria" => 3, "monto" => 5500, "id_sucursal" => 3, "id_local"=> 46],
            ["descripcion" => "Azucar", "tipo" => 1, "categoria" => 3, "monto" => 5600, "id_sucursal" => 3, "id_local"=> 47],
            ["descripcion" => "Cafe", "tipo" => 1, "categoria" => 3, "monto" => 5700, "id_sucursal" => 3, "id_local"=> 48],
            ["descripcion" => "Agua", "tipo" => 1, "categoria" => 3, "monto" => 5800, "id_sucursal" => 3, "id_local"=> 49],
            ["descripcion" => "Vueltos", "tipo" => 1, "categoria" => 1, "monto" => 5900, "id_sucursal" => 3, "id_local"=> 50],
            ["descripcion" => "Amer", "tipo" => 0, "categoria" => 5, "monto" => 6000, "id_sucursal" => 3, "id_local"=> 51],

             ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 6100, "id_sucursal" => 4, "id_local"=> 52],
            ["descripcion" => "Chil CA", "tipo" => 1, "categoria" => 4, "monto" => 6200, "id_sucursal" => 4, "id_local"=> 53],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 6300, "id_sucursal" => 4, "id_local"=> 54],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 6400, "id_sucursal" => 4, "id_local"=> 55],
            ["descripcion" => "Jose FP", "tipo" => 1, "categoria" => 4, "monto" => 6500, "id_sucursal" => 4, "id_local"=> 56],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 6600, "id_sucursal" => 4, "id_local"=> 57],
            ["descripcion" => "MIstic INC", "tipo" => 1, "categoria" => 4, "monto" => 6700, "id_sucursal" => 4, "id_local"=> 58],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 6800, "id_sucursal" => 4, "id_local"=> 59],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 6900, "id_sucursal" => 4, "id_local"=> 60],
            ["descripcion" => "Robertyo Bolaos INC", "tipo" => 1, "categoria" => 4, "monto" => 7000, "id_sucursal" => 4, "id_local"=> 61],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 7100, "id_sucursal" => 4, "id_local"=> 62],
            ["descripcion" => "Aceite", "tipo" => 1, "categoria" => 3, "monto" => 7200, "id_sucursal" => 4, "id_local"=> 63],
            ["descripcion" => "Azucar", "tipo" => 1, "categoria" => 3, "monto" => 7300, "id_sucursal" => 4, "id_local"=> 64],
            ["descripcion" => "Cafe", "tipo" => 1, "categoria" => 3, "monto" => 7400, "id_sucursal" => 4, "id_local"=> 65],
            ["descripcion" => "Agua", "tipo" => 1, "categoria" => 3, "monto" => 7500, "id_sucursal" => 4, "id_local"=> 66],
            ["descripcion" => "Vueltos", "tipo" => 1, "categoria" => 1, "monto" => 7600, "id_sucursal" => 4, "id_local"=> 67],
            ["descripcion" => "Amer", "tipo" => 0, "categoria" => 5, "monto" => 7700, "id_sucursal" => 4, "id_local"=> 68],

             ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 7800, "id_sucursal" => 5, "id_local"=> 69],
            ["descripcion" => "Chil CA", "tipo" => 1, "categoria" => 4, "monto" => 7900, "id_sucursal" => 5, "id_local"=> 70],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 8000, "id_sucursal" => 5, "id_local"=> 71],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 8100, "id_sucursal" => 5, "id_local"=> 72],
            ["descripcion" => "Jose FP", "tipo" => 1, "categoria" => 4, "monto" => 8200, "id_sucursal" => 5, "id_local"=> 73],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 8300, "id_sucursal" => 5, "id_local"=> 74],
            ["descripcion" => "MIstic INC", "tipo" => 1, "categoria" => 4, "monto" => 8400, "id_sucursal" => 5, "id_local"=> 75],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 8500, "id_sucursal" => 5, "id_local"=> 76],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 8600, "id_sucursal" => 5, "id_local"=> 77],
            ["descripcion" => "Robertyo Bolaos INC", "tipo" => 1, "categoria" => 4, "monto" => 8700, "id_sucursal" => 5, "id_local"=> 78],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 8800, "id_sucursal" => 5, "id_local"=> 79],
            ["descripcion" => "Aceite", "tipo" => 1, "categoria" => 3, "monto" => 8900, "id_sucursal" => 5, "id_local"=> 80],
            ["descripcion" => "Azucar", "tipo" => 1, "categoria" => 3, "monto" => 9000, "id_sucursal" => 5, "id_local"=> 81],
            ["descripcion" => "Cafe", "tipo" => 1, "categoria" => 3, "monto" => 9100, "id_sucursal" => 5, "id_local"=> 82],
            ["descripcion" => "Agua", "tipo" => 1, "categoria" => 3, "monto" => 9200, "id_sucursal" => 5, "id_local"=> 83],
            ["descripcion" => "Vueltos", "tipo" => 1, "categoria" => 1, "monto" => 9300, "id_sucursal" => 5, "id_local"=> 84],
            ["descripcion" => "Amer", "tipo" => 0, "categoria" => 5, "monto" => 9400, "id_sucursal" => 5, "id_local"=> 85],

             ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 9500, "id_sucursal" => 6, "id_local"=> 86],
            ["descripcion" => "Chil CA", "tipo" => 1, "categoria" => 4, "monto" => 9600, "id_sucursal" => 6, "id_local"=> 87],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 9700, "id_sucursal" => 6, "id_local"=> 88],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 9800, "id_sucursal" => 6, "id_local"=> 89],
            ["descripcion" => "Jose FP", "tipo" => 1, "categoria" => 4, "monto" => 9900, "id_sucursal" => 6, "id_local"=> 90],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 10000, "id_sucursal" => 6, "id_local"=> 91],
            ["descripcion" => "MIstic INC", "tipo" => 1, "categoria" => 4, "monto" => 10100, "id_sucursal" => 6, "id_local"=> 92],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 10200, "id_sucursal" => 6, "id_local"=> 93],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 10300, "id_sucursal" => 6, "id_local"=> 94],
            ["descripcion" => "Robertyo Bolaos INC", "tipo" => 1, "categoria" => 4, "monto" => 10400, "id_sucursal" => 6, "id_local"=> 95],
            ["descripcion" => "PAgo empleados", "tipo" => 1, "categoria" => 2, "monto" => 10500, "id_sucursal" => 6, "id_local"=> 96],
            ["descripcion" => "Aceite", "tipo" => 1, "categoria" => 3, "monto" => 10600, "id_sucursal" => 6, "id_local"=> 97],
            ["descripcion" => "Azucar", "tipo" => 1, "categoria" => 3, "monto" => 10700, "id_sucursal" => 6, "id_local"=> 98],
            ["descripcion" => "Cafe", "tipo" => 1, "categoria" => 3, "monto" => 10800, "id_sucursal" => 6, "id_local"=> 99],
            ["descripcion" => "Agua", "tipo" => 1, "categoria" => 3, "monto" => 10900, "id_sucursal" => 6, "id_local"=> 100],
            ["descripcion" => "Vueltos", "tipo" => 1, "categoria" => 1, "monto" => 11000, "id_sucursal" => 6, "id_local"=> 101],
            ["descripcion" => "Amer", "tipo" => 0, "categoria" => 5, "monto" => 11100, "id_sucursal" => 6, "id_local"=> 102]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gastos');
    }
}
