<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id');
            
            $table->float("debito",10,2); 
            $table->float("efectivo",10,2); 
            $table->float("transferencia",10,2); 

            $table->float("tasa",10,2); 
            

            $table->integer("num_ventas");


            $table->date("fecha");
            $table->integer("id_sucursal")->unsigned();
            $table->foreign('id_sucursal')->references('id')->on('sucursals');

            $table->unique(["fecha","id_sucursal"]);
            $table->timestamps();
        });

        DB::table("ventas")->insert([
            ["debito"=>8000,"efectivo"=>90984,"transferencia"=>5004,"tasa"=>4.5,"num_ventas"=>890,"fecha"=>"2021-12-27","id_sucursal"=>1],
            ["debito"=>7000,"efectivo"=>8765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>90,"fecha"=>"2021-12-26","id_sucursal"=>1],
            ["debito"=>6000,"efectivo"=>6765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>130,"fecha"=>"2021-11-23","id_sucursal"=>1],
            ["debito"=>5000,"efectivo"=>4765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>100,"fecha"=>"2021-11-22","id_sucursal"=>1],
            ["debito"=>4000,"efectivo"=>20987,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>560,"fecha"=>"2021-11-21","id_sucursal"=>1],
            ["debito"=>3000,"efectivo"=>8765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>232,"fecha"=>"2021-11-20","id_sucursal"=>1],
            ["debito"=>2400,"efectivo"=>99765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>600,"fecha"=>"2021-11-19","id_sucursal"=>1],


            ["debito"=>8000,"efectivo"=>90984,"transferencia"=>5004,"tasa"=>4.5,"num_ventas"=>890,"fecha"=>"2021-12-27","id_sucursal"=>2],
            ["debito"=>7600,"efectivo"=>8765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>90,"fecha"=>"2021-12-26","id_sucursal"=>2],
            ["debito"=>6700,"efectivo"=>6765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>130,"fecha"=>"2021-11-23","id_sucursal"=>2],
            ["debito"=>5000,"efectivo"=>4765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>100,"fecha"=>"2021-11-22","id_sucursal"=>2],
            ["debito"=>4900,"efectivo"=>20987,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>560,"fecha"=>"2021-11-21","id_sucursal"=>2],
            ["debito"=>3000,"efectivo"=>8765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>32,"fecha"=>"2021-11-20","id_sucursal"=>2],
            ["debito"=>2900,"efectivo"=>99765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>600,"fecha"=>"2021-11-19","id_sucursal"=>2],


            ["debito"=>8000,"efectivo"=>90984,"transferencia"=>5004,"tasa"=>4.5,"num_ventas"=>890,"fecha"=>"2021-12-27","id_sucursal"=>3],
            ["debito"=>7000,"efectivo"=>8765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>390,"fecha"=>"2021-12-26","id_sucursal"=>3],
            ["debito"=>6000,"efectivo"=>6765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>130,"fecha"=>"2021-11-23","id_sucursal"=>3],
            ["debito"=>5000,"efectivo"=>4765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>100,"fecha"=>"2021-11-22","id_sucursal"=>3],
            ["debito"=>4000,"efectivo"=>20987,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>560,"fecha"=>"2021-11-21","id_sucursal"=>3],
            ["debito"=>3000,"efectivo"=>8765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>232,"fecha"=>"2021-11-20","id_sucursal"=>3],
            ["debito"=>2000,"efectivo"=>99765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>600,"fecha"=>"2021-11-19","id_sucursal"=>3],


            ["debito"=>8000,"efectivo"=>90984,"transferencia"=>5004,"tasa"=>4.5,"num_ventas"=>890,"fecha"=>"2021-12-27","id_sucursal"=>4],
            ["debito"=>7000,"efectivo"=>8765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>90,"fecha"=>"2021-12-26","id_sucursal"=>4],
            ["debito"=>6000,"efectivo"=>6765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>130,"fecha"=>"2021-11-23","id_sucursal"=>4],
            ["debito"=>5000,"efectivo"=>4765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>100,"fecha"=>"2021-11-22","id_sucursal"=>4],
            ["debito"=>4000,"efectivo"=>20987,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>560,"fecha"=>"2021-11-21","id_sucursal"=>4],
            ["debito"=>3000,"efectivo"=>8765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>2332,"fecha"=>"2021-11-20","id_sucursal"=>4],
            ["debito"=>2000,"efectivo"=>99765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>600,"fecha"=>"2021-11-19","id_sucursal"=>4],

            ["debito"=>8000,"efectivo"=>90984,"transferencia"=>5004,"tasa"=>4.5,"num_ventas"=>890,"fecha"=>"2021-12-27","id_sucursal"=>5],
            ["debito"=>7000,"efectivo"=>8765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>90,"fecha"=>"2021-12-26","id_sucursal"=>5],
            ["debito"=>6000,"efectivo"=>6765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>130,"fecha"=>"2021-11-23","id_sucursal"=>5],
            ["debito"=>5000,"efectivo"=>4765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>100,"fecha"=>"2021-11-22","id_sucursal"=>5],
            ["debito"=>4000,"efectivo"=>20987,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>5360,"fecha"=>"2021-11-21","id_sucursal"=>5],
            ["debito"=>3000,"efectivo"=>8765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>232,"fecha"=>"2021-11-20","id_sucursal"=>5],
            ["debito"=>2000,"efectivo"=>99765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>600,"fecha"=>"2021-11-19","id_sucursal"=>5],

            ["debito"=>8000,"efectivo"=>90984,"transferencia"=>5004,"tasa"=>4.5,"num_ventas"=>890,"fecha"=>"2021-12-27","id_sucursal"=>6],
            ["debito"=>7000,"efectivo"=>8765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>90,"fecha"=>"2021-12-26","id_sucursal"=>6],
            ["debito"=>6000,"efectivo"=>6765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>130,"fecha"=>"2021-11-23","id_sucursal"=>6],
            ["debito"=>5000,"efectivo"=>4765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>100,"fecha"=>"2021-11-22","id_sucursal"=>6],
            ["debito"=>4000,"efectivo"=>20987,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>560,"fecha"=>"2021-11-21","id_sucursal"=>6],
            ["debito"=>3000,"efectivo"=>8765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>2324,"fecha"=>"2021-11-20","id_sucursal"=>6],
            ["debito"=>2000,"efectivo"=>99765,"transferencia"=>1201,"tasa"=>4.5,"num_ventas"=>600,"fecha"=>"2021-11-19","id_sucursal"=>6],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas');
    }
}
