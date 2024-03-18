<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarcasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marcas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion')->index();
            $table->timestamps();
        });
        /* DB::table("marcas")->insert([
            ["descripcion"=>"COVO"],
            ["descripcion"=>"EXXEL"],
            ["descripcion"=>"INGCO"],
            ["descripcion"=>"TOTAL"],
            ["descripcion"=>"VERT"],
            ["descripcion"=>"CHESTERWOOD"],
            ["descripcion"=>"HOFFMAN"],
            ["descripcion"=>"LINCOLN"],
            ["descripcion"=>"ATOAN"],
            ["descripcion"=>"RUN"],
            ["descripcion"=>"ZASC"],
            ["descripcion"=>"METCO"],
            ["descripcion"=>"GENERICO"],
            ["descripcion"=>"ANGEL LIGHT"],
            ["descripcion"=>"CLASSIC LUX"],
            ["descripcion"=>"Q-LED"],
            ["descripcion"=>"KOBATEX"],
            ["descripcion"=>"REIK"],
            ["descripcion"=>"LESSO PLUS"],
            ["descripcion"=>"TREXA "],
            ["descripcion"=>"H&S "],
            ["descripcion"=>"SUPRACAL"],
            ["descripcion"=>"TROEN"],
            ["descripcion"=>"PROTONIC"],
            ["descripcion"=>"T-GET"],
            ["descripcion"=>"VITRON"],
            ["descripcion"=>"BTICINO"],
            ["descripcion"=>"PROTECTOR "],
            ["descripcion"=>"EXCELINE "],
            ["descripcion"=>"TRIC"],
            ["descripcion"=>"TUBRICA"],
            ["descripcion"=>"GRIVEN"],
            ["descripcion"=>"PEGA TANQUE "],
            ["descripcion"=>"MEGA GREY "],
            ["descripcion"=>"ABRACOL"],
            ["descripcion"=>"PEGA SOLD 43"],
            ["descripcion"=>"COVO"],
            ["descripcion"=>"REINCO "],
            ["descripcion"=>"VENCERAMICA "],
            ["descripcion"=>"CORONA"],
            ["descripcion"=>"COLOREAL"],
            ["descripcion"=>"SEVENS "],
            ["descripcion"=>"MANPICA"],
            ["descripcion"=>"IPA"],
            ["descripcion"=>"BITUPLAS"],
            ["descripcion"=>"3M"],
            ["descripcion"=>"COBRA"],
            ["descripcion"=>"LF"],
            ["descripcion"=>"AQUAFINA"],
            ["descripcion"=>"METALES ALEADOS"],
            ["descripcion"=>"FERMETAL"],
            ["descripcion"=>"CEBRA"],
            ["descripcion"=>"HAKUNA"],
            ["descripcion"=>"SECURITY"],
            ["descripcion"=>"BELLOTA"],
            ["descripcion"=>"LENOX"],
            ["descripcion"=>"LYNOX"],
            ["descripcion"=>"TRICAL"],
            ["descripcion"=>"CASTOR"],
            ["descripcion"=>"LEO"],
            ["descripcion"=>"ARMOR "],
            ["descripcion"=>"CISA"],
            ["descripcion"=>"RABBIT"],
            ["descripcion"=>"BLACK&DECKER"],
            ["descripcion"=>"DOMOSA "],
            ["descripcion"=>"MAGPOWER"],
            ["descripcion"=>"SOLPOWER"],
            ["descripcion"=>"GENPAR"],
            ["descripcion"=>"HATO 500"],
            ["descripcion"=>"MOTTO 500"],
            ["descripcion"=>"GAVILAN"],
            ["descripcion"=>"OREGON"],
            ["descripcion"=>"ELEFANTE"],
            ["descripcion"=>"OMEGA"],
            ["descripcion"=>"OSTER"],
            ["descripcion"=>"EDMIRA"],
            ["descripcion"=>"MILEXUS"],
            ["descripcion"=>"GAMA"],
            ["descripcion"=>"SJ"],
            ["descripcion"=>"KUCCE"],
            ["descripcion"=>"WESTINGHOUSE"],
            ["descripcion"=>"CONDESA"],
            ["descripcion"=>"GALANZ"],
            ["descripcion"=>"CORONA"],
            ["descripcion"=>"ROYAL"],
            ["descripcion"=>"JAGUAR"],
            ["descripcion"=>"KR"],
            ["descripcion"=>"KHALED"],
            ["descripcion"=>"MYSTIC"],
            ["descripcion"=>"F.M"],
            ["descripcion"=>"TITANIUM"],
            ["descripcion"=>"LIAM"],
            ["descripcion"=>"ORIGINAL"],
            ["descripcion"=>"BAOFENG"],
        ]); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marcas');
    }
}
