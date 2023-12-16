<?php

use App\Models\sucursal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominas', function (Blueprint $table) {
            $table->increments("id");

            $table->string("nominanombre");
            $table->integer("nominacedula")->unique();
            $table->string("nominatelefono");
            $table->string("nominadireccion");
            $table->date("nominafechadenacimiento");
            $table->date("nominafechadeingreso");
            $table->string("nominagradoinstruccion");

            $table->integer("nominacargo")->unsigned();
            $table->foreign('nominacargo')->references('id')->on('nominacargos');

            $table->integer("nominasucursal")->unsigned();
            $table->foreign('nominasucursal')->references('id')->on('sucursals');

            $table->timestamps();
        });

        $arr = [


            ["Rafael Orta ", "22882946", "1977-10-29", "2022-12-29", "04264406746", "VENDEDOR/A", "calabozo"],
            ["Sebastian palma", "28442201", "2002-04-17", "2023-12-12", "04163414630", "ENCARGADO DE DEPOSITO", "valledelapascua"],
            ["Miguel Alejandro amaricua acuñas ", "30264417", "1999-02-11", "2023-11-28", "04126340661", "DESPACHADOR", "valledelapascua2"],
            ["José Daniel taquiva Martinez ", "30689878", "2004-02-04", "2022-02-15", "04162788689", "DESPACHADOR", "valledelapascua"],
            ["Gabriel Alejandro Jaspe", "28442202", "2002-05-29", "2023-12-04", "04269162097", "PORTERO", "valledelapascua"],
            ["Carlos Enrique Alvarez Correa", "24620118", "1996-04-07", "2023-12-06", "04125967273", "PORTERO", "valledelapascua"],
            ["Rafael Lugo ", "19580153", "1990-05-28", "2023-11-13", "04160944788", "DESPACHADOR", "calabozo"],
            ["Ana Virginia Nieves salinas", "27907080", "2000-01-13", "2023-11-28", "04124528745", "VENDEDOR/A", "valledelapascua"],
            ["José gregorio Ojeda Gamez ", "37375109", "2005-02-14", "2023-12-09", "04124935856", "PORTERO", "valledelapascua"],
            ["Luis emilio Melendez Rivas ", "28680575", "2002-09-30", "2021-06-09", "04166539136", "ENCARGADO DE DEPOSITO", "elsaman"],
            ["Elena yoskaiza Salazar Leon", "27338249", "0199-12-30", "2022-08-04", "04260635111", "CAJERO/A", "maracay"],
            ["Osmagly Anamelis Vidal Reyes", "31573406", "2005-04-20", "2023-12-15", "04243472258", "VENDEDOR/A", "valledelapascua"],
            ["Alberto José González higuera", "213130091", "1989-09-01", "2023-12-15", "04121972746", "DESPACHADOR", "valledelapascua2"],
            ["César David Zamora bolivar ", "29865518", "2001-05-22", "2023-12-02", "04243625940", "DESPACHADOR", "valledelapascua2"],
            ["Carlos Eduardo Alvarez Montezuma", "28292480", "1999-10-15", "2023-12-01", "0416-1669283", "DESPACHADOR", "valledelapascua2"],
            ["Wily Daniel Meza olivo ", "26088100", "1997-06-20", "2022-03-20", "04127789607", "VENDEDOR/A", "maracay"],
            ["Manuel García ", "18405409", "1984-11-18", "2023-10-13", "04243532263", "DESPACHADOR", "calabozo"],

        ];

        $cargos = [];
        $cargos["PORTERO"] = 1;
        $cargos["LIMPIEZA"] = 2;
        $cargos["VENDEDOR/A"] = 3;
        $cargos["DESPACHADOR"] = 4;
        $cargos["CAJERO/A"] = 4;
        $cargos["ENCARGADO DE DEPOSITO"] = 6;
        $cargos["GERENTE"] = 7;
        $cargos["SUBGERENTE"] = 8;
        $cargos["ADMINISTRACION"] = 9;

        $sucursalDa = [];
        $sucursalDa["elorza"] = 1;
        $sucursalDa["mantecal"] = 2;
        $sucursalDa["achaguas"] = 3;
        $sucursalDa["elsaman"] = 4;
        $sucursalDa["bruzual"] = 5;
        $sucursalDa["sanfernando1"] = 6;
        $sucursalDa["sanfernando2"] = 7;
        $sucursalDa["calabozo"] = 8;
        $sucursalDa["valledelapascua"] = 9;
        $sucursalDa["valledelapascua2"] = 10;
        $sucursalDa["sanjuandelosmorros"] = 11;
        $sucursalDa["maracay"] = 12;


        foreach ($arr as $key => $e) {
            DB::table("nominas")->insert([
                [
                    "nominanombre" => $e[0],
                    "nominacedula" => $e[1],
                    "nominafechadenacimiento" => $e[2],
                    "nominafechadeingreso" => $e[3],
                    "nominatelefono" => $e[4],
                    "nominadireccion" => "",
                    "nominagradoinstruccion" => 1,

                    "nominacargo" => $cargos[$e[5]],
                    "nominasucursal" => $sucursalDa[$e[6]],
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nominas');
    }
}
