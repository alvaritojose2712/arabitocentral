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
            ["Lindeidys Sthefany Quiroga fuentes ","24602347","1996-07-06","2022-11-01","04124103268","ADMINISTRACION","sanfernando1"],
            ["Luisa maría Rodríguez Rivera ","27721175","2000-09-22","2023-07-27","04243044641","CAJERO/A","sanfernando1"],
            ["Andrea Coromoto Lara Flores ","27653133","1999-06-03","2022-07-01","04122131617","CAJERO/A","achaguas"],
            ["Diana Patricia Toledo Hidalgo ","28029636","2001-04-16","2022-08-18","04260490512","CAJERO/A","calabozo"],
            ["María de los angeles castillo linares","27291818","1999-07-15","2023-03-01","04129616892","CAJERO/A","maracay"],
            ["Frenny Fernanda Montoya leon ","28029882","2001-09-04","2023-10-15","04129182934","CAJERO/A","maracay"],
            ["MARIA MARCELA NEGRON ARANA","24986853","1994-11-09","2023-06-05","04144862720","CAJERO/A","sanfernando2"],
            ["Karla Del Valle Vargas Abreu ","29946263","2003-09-09","2023-10-16","04243550199","CAJERO/A","valledelapascua2"],
            ["Sandrimar José Guaran García ","30049527","2002-02-19","2022-12-01","04243566473","CAJERO/A","maracay"],
            ["Helen aileen gil gil","28280580","2001-04-23","2023-05-10","04245322202","CAJERO/A","mantecal"],
            ["Eglys Arianny Silva Veliz","20335405","1993-02-24","2023-03-20","04123699187","CAJERO/A","sanfernando1"],
            ["Kamelys Alejandra Chacon Rivas ","27653469","2000-05-17","2023-09-14","04261114032","CAJERO/A","sanfernando1"],
            ["GREISYS VIRMARY PÉREZ SOLORZANO","30388637","2003-01-11","2023-03-01","04243004501","CAJERO/A","sanfernando1"],
            ["Vilmar Nazareth Mejias Vera ","28680747","2002-12-03","2021-09-22","04142497058","CAJERO/A","achaguas"],
            ["María Victoria Hernandez morales ","29558107","2002-06-25","2022-11-26","04243660278","CAJERO/A","valledelapascua"],
            ["Mariangel Helena Marín Moreno","27231479","1998-06-05","2023-04-12","04244567207","CAJERO/A","mantecal"],
            ["Zadi Jahdiel Dueñas Moyetones","23567366","1995-01-14","2023-10-16","04243021236","CAJERO/A","valledelapascua2"],
            ["Nayla María García Herrera ","30649335","2004-08-18","2023-10-12","04128361620","CAJERO/A","sanfernando2"],
            ["Karianny Alexkari Martínez Camejo","30325763","2023-12-14","2023-11-01","04243250353","CAJERO/A","sanjuandelosmorros"],
            ["Sheisser Esperanza Davila Bernal","29647768","2002-04-11","2023-09-29","04243759690","CAJERO/A","valledelapascua"],
            ["Juliana Valentina Villafañe Briceño","31849362","2006-04-04","2023-10-11","04243609892","CAJERO/A","sanfernando2"],
            ["Franyana del Carmen Garrido Castillo ","28662763","2003-02-03","2022-12-05","04247256060","CAJERO/A","bruzual"],
            ["María Alejandra Mora Chacón ","27721183","1999-11-30","2023-06-01","04243795909","CAJERO/A","sanfernando2"],
            ["Cindy estefania kiparissopoulos hernandez","26345137","1998-10-01","2023-01-15","04124521997","CAJERO/A","sanjuandelosmorros"],
            ["KARIANA VICTORIA PARRA REYES ","25617470","1996-09-03","2022-12-21","04162408364","CAJERO/A","valledelapascua2"],
            ["Victoria Estefanía kiparissopoulos Hernández ","31031692","2004-06-11","2023-10-15","04123195226","CAJERO/A","sanjuandelosmorros"],
            ["Yubiskarlys Daniela leal jimenez ","29716608","2002-06-06","2023-03-12","04243339628 ","CAJERO/A","sanjuandelosmorros"],
            ["Dibetci Yatseni Rivero Perez ","21658405","1992-07-30","2023-04-10","04124632111","CAJERO/A","calabozo"],
            ["Albany Rodríguez ","31660312","2005-12-15","2005-12-15","04163359150","CAJERO/A","achaguas"],
            ["María de los angeles Pérez Oropeza ","26944205","1999-06-11","2023-11-14","04129410003","CAJERO/A","valledelapascua"],
            ["María Mercedes Correa Aquino ","27338236","1999-09-27","2020-10-11","04267411922","CAJERO/A","elsaman"],
            ["Rolmari Oriannis Limada Delpino ","26961582","2023-12-22","2023-11-30","04128714915","CAJERO/A","maracay"],
            ["Aliuska Torres ","29791191","2001-04-03","2023-10-06","04243435974","CAJERO/A","calabozo"],
            ["Arelys Zovic García ","28519007","2001-08-22","2022-09-26","0412777161","CAJERO/A","calabozo"],
            ["Samra Silvana Hennawi Salah ","28662645","2001-10-22","2023-05-12","04124606309","CAJERO/A","sanjuandelosmorros"],
            ["Edwin  Guillermo Martinez Ramos ","22615693","1995-04-02","2022-12-22","04128884742","DESPACHADOR","calabozo"],
            ["Kevin Emmanuel Hidalgo Aguirre ","29716907","2000-12-29","2023-06-28","04124639647","DESPACHADOR","sanfernando1"],
            ["Pedro José Garrido bolivar ","24238890","1996-03-21","2023-09-14","04242003185","DESPACHADOR","sanjuandelosmorros"],
            ["Pedro Pablo Ascanio Echenique","21315658","1992-12-19","2023-11-06","04124268533","DESPACHADOR","maracay"],
            ["Edicson Reinaldo Carvajal Jimenez","31067229","2003-05-10","2023-07-24","04268105416","DESPACHADOR","mantecal"],
            ["Pedro carrasquel","26980585","1997-05-08","2022-08-29","04124064765","DESPACHADOR","sanfernando1"],
            ["Cesar jesus altuna españa ","19405011","1988-11-11","2023-02-28","04243235544","DESPACHADOR","sanfernando1"],
            ["Héctor José marcano marcano ","26216812","1998-01-17","2023-08-01","04242243169","DESPACHADOR","sanjuandelosmorros"],
            ["Keyver jixon moreno adarmes","24539738","1993-12-28","2023-09-19","04122963218","DESPACHADOR","sanfernando2"],
            ["Luis Monagas ","24967623","1996-08-11","2022-11-18","04124640492","DESPACHADOR","sanjuandelosmorros"],
            ["Kelvin Euclides Rodríguez Pino","25968918","1996-12-15","2023-08-10","04261698466","DESPACHADOR","sanfernando2"],
            ["Rafael David Daza Oropeza","26231210","1993-12-30","2023-09-28","04269808389","DESPACHADOR","sanfernando2"],
            ["Edgar David Olave Salas","31084346","2005-02-28","2023-10-14","041237723396","DESPACHADOR","sanfernando2"],
            ["Héctor Daniel Colmenares César ","29559750","1999-10-14","2023-06-07","04145029108","DESPACHADOR","bruzual"],
            ["Carlos Gabriel Barreto Jiménez ","28408819","2001-07-14","2023-11-15","04243250353","DESPACHADOR","sanjuandelosmorros"],
            ["Ailan Moisés Segovia Mayorca ","29894019","2003-06-26","2023-10-07","04144782905","DESPACHADOR","sanfernando2"],
            ["Miguel Alejandro Hernández Castro ","21605296","1994-12-15","2023-09-01","04124678594","DESPACHADOR","sanjuandelosmorros"],
            ["Mayed Salah","5555555","2001-04-27","2022-04-17","04245751370","DESPACHADOR","sanfernando2"],
            ["Neomar elisaul Oropeza rattia","31793082","2006-11-27","2023-12-11","04124544512","DESPACHADOR","sanfernando2"],
            ["Jesus Leonardo Herrera Volquez","27697640","2000-01-09","2023-08-21","0000000000","DESPACHADOR","sanfernando2"],
            ["Luis Alejandro Cisnero Ascanio","19816381","1989-12-03","2023-08-14","04167292835","DESPACHADOR","valledelapascua2"],
            ["Jeremy Alejandro campero carrillo","31174863","2005-01-25","2023-09-04","04127701256","DESPACHADOR","sanfernando2"],
            ["ANGEL JOSUE RIVERO OLIVARES","26980433","1999-11-20","2023-12-02","04243080159","DESPACHADOR","sanfernando1"],
            ["Julián Isai gallegos tovar","24838593","1993-06-20","2023-06-23","04127571989","DESPACHADOR","sanfernando2"],
            ["Danilo Israel villao cantos ","921673745","1991-09-20","2023-10-17","04127289173","DESPACHADOR","sanjuandelosmorros"],
            ["Carlos Eduardo Parra Rengifo","29941383","2001-02-01","2023-09-14","04267439947","DESPACHADOR","sanjuandelosmorros"],
            ["Isac alberto muñoz","24755801","1994-07-26","2023-07-27","04262407022","DESPACHADOR","sanfernando1"],
            ["Jyoan Esteban Moreno Quintana ","27231667","1999-02-11","2023-06-27","04243145501 ","DESPACHADOR","achaguas"],
            ["Manuel de Jesús Maestre Bernal ","31730992","2005-08-18","2023-12-02","04144206738","DESPACHADOR","valledelapascua2"],
            ["Andrés Alexis Vargas Medina ","27313750","1998-07-28","2023-10-17","04267310904","DESPACHADOR","valledelapascua2"],
            ["Anthony Alexis Castillo González ","21279739","1994-07-21","2023-03-05","04128255379","DESPACHADOR","sanjuandelosmorros"],
            ["Ronaldo Alberto Jiménez Ramos ","30649237","2002-05-28","2023-08-07","04124673742","DESPACHADOR","sanfernando1"],
            ["Diego Alejandro Matute moreno","30732611","2004-07-11","2023-09-28","04266401246","DESPACHADOR","sanfernando2"],
            ["Kaled Salah","32160004","2006-11-06","2023-11-07","04125247589","DESPACHADOR","sanjuandelosmorros"],
            ["Freddy Antonio Navarro Bolivar","29791813","2003-03-13","2023-02-13","04169435565","DESPACHADOR","sanjuandelosmorros"],
            ["Franyer Jose Ojeda Cancines","30207188","2002-11-28","2023-10-26","04243226126","DESPACHADOR","achaguas"],
            ["Miguel Galipolli","27697473","2001-02-01","2021-02-20","04243407610","DESPACHADOR","achaguas"],
            ["Brangett Rafael González Román ","27665645","2000-11-10","2023-10-05","04124488512","DESPACHADOR","sanjuandelosmorros"],
            ["Pedro lucio Gonzales Landaeta","23600301","1994-05-13","2023-07-14","04124268533","DESPACHADOR","achaguas"],
            ["Geyson Laya","26328015","1996-06-05","2023-08-01","04123443934","DESPACHADOR","calabozo"],
            ["Jilve yohan Rodríguez jimenez","19050345","1987-12-15","2022-06-03","04243576286","DESPACHADOR","valledelapascua"],
            ["Adixon José Bravo Ledezma ","31171730","2005-03-06","2023-11-29","04241989135","DESPACHADOR","valledelapascua2"],
            ["Luis Sosa","26848615","1999-04-25","2023-07-04","04243015448","DESPACHADOR","calabozo"],
            ["Moisés Alejandro Contreras suarez","24239874","1994-05-06","2023-12-09","04123420687","DESPACHADOR","valledelapascua2"],
            ["Luis Jesús Carpio García ","26464487","1999-02-09","2023-12-09","04266336461","DESPACHADOR","valledelapascua2"],
            ["Julio César  Álvarez Álvarez ","25634746","1994-08-13","2023-03-16","04243122915","ENCARGADO DE DEPOSITO","valledelapascua2"],
            ["Juan Diego López Gutiérrez ","30689652","2005-01-12","2023-01-04","04161050254","ENCARGADO DE DEPOSITO","bruzual"],
            ["Jesus David Aguirre Nieves","30207413","2001-08-22","2022-01-26","04145201423","ENCARGADO DE DEPOSITO","achaguas"],
            ["Luis Argenis Mendoza moreno ","27721112","1998-11-07","2023-07-19","04243354370","ENCARGADO DE DEPOSITO","achaguas"],
            ["Yon alexander salazar guedez","14520871","1980-11-05","2023-08-01","04163050360","ENCARGADO DE DEPOSITO","mantecal"],
            ["Freddy Alejandro Mendoza Sánchez ","15608847","1979-05-14","2023-05-01","04166577223","ENCARGADO DE DEPOSITO","sanjuandelosmorros"],
            ["Miguel Alfredo Castillo Jimenez ","21315111","1993-12-19","2023-10-24","0000000","ENCARGADO DE DEPOSITO","valledelapascua"],
            ["EBER JOSE HIDALGO GAMARRA","20722506","1990-07-14","2023-07-01","04163417765","ENCARGADO DE DEPOSITO","maracay"],
            ["LUIS ALEJANDRO COLMENARES NAVARRO","19877456","1989-07-26","2022-09-12","04166580803","ADMINISTRACION","sanfernando1"],
            ["Luis alejandro Torres Martines ","19818841","1989-05-24","2021-02-15","04243382727","GERENTE","mantecal"],
            ["Kaled salah","24823411","1992-09-29","2022-11-01","04123798496","GERENTE","sanfernando1"],
            ["ALEX JOSE MATUTE MARTINEZ ","21317698","1994-02-12","2022-12-05","04267642312","GERENTE","maracay"],
            ["Andres Hernandez ","28421316","2000-02-23","2018-11-16","04143456605","GERENTE","valledelapascua"],
            ["Jose ramon cordero coello","21316180","1994-03-11","2021-02-11","04149491969","GERENTE","sanjuandelosmorros"],
            ["Andres Bernabeht Martinez Martinez","27338604","1998-09-29","2021-09-29","04243225086","GERENTE","bruzual"],
            ["Jack Luis Herrera nelo","19918980","1991-02-16","2022-05-10","04243177217","GERENTE","sanfernando2"],
            ["Anyelow Antonio Rosales Argüello","18251950","1989-01-06","2021-07-15","04163465373","GERENTE","achaguas"],
            ["Gregorio Antonio García ","21146220","2010-10-10","1989-10-30","04167499467","GERENTE","elsaman"],
            ["Luis Carlos González ","21005686","1991-02-05","2022-09-29","04144757575","GERENTE","calabozo"],
            ["Andrés Lara","25420892","2010-09-14","2020-09-21","04129430115","GERENTE","calabozo"],
            ["Lisanyer Naileth Cedeño ","27009020","1997-09-20","2023-04-01","04124076407","LIMPIEZA","sanfernando1"],
            ["Ana Karina González Ezparragoza ","24944934","1991-11-09","2023-06-28","04127289173","LIMPIEZA","sanjuandelosmorros"],
            ["Nurys nohely lopez Yanes","20089821","1989-11-21","2022-02-02","04261137497","LIMPIEZA","achaguas"],
            ["YETNI YULIMAR HIDALGO SOSA ","17199009","1983-03-31","2023-11-13","04243564061","LIMPIEZA","maracay"],
            ["Albany Aponte ","30168173","2002-05-05","2023-10-17","04260476570","LIMPIEZA","calabozo"],
            ["Yoskari Daisbeli Lara Paraco","19962933","1990-03-13","2023-12-05","04243666580","LIMPIEZA","valledelapascua2"],
            ["Vianney abiezer bolivar Martínez ","16528669","1983-04-04","2023-11-06","04164413280","PORTERO","mantecal"],
            ["Pedro alexander seija pacheco","13150630","1977-07-27","2023-09-15","04241939492","PORTERO","sanjuandelosmorros"],
            ["MANUEL NAPOLEON AGUIAR DIAZ ","7263302","1965-09-07","2023-11-13","04127784008","PORTERO","maracay"],
            ["Guiovanny José suarez","27541844","2000-04-25","2023-09-24","04267161828","PORTERO","valledelapascua"],
            ["Moises Bacilio Aguilar Vasquez ","29865868","2002-08-25","2023-12-02","04124246732","PORTERO","valledelapascua2"],
            ["Jenny Daniela Muñoz Villegas","26518044","1998-02-20","2019-08-07","04164406594","SUBGERENTE","maracay"],
            ["Miguel Osia Rodriguez Ruiz","19552701","1988-02-09","2022-10-18","04124243114","SUBGERENTE","sanfernando2"],
            ["Carlos Eduardo Machin Arraez ","29716771","2002-10-04","2022-08-23","04243137290","SUBGERENTE","valledelapascua2"],
            ["Wisam al hennawi al hennaoui","17607397","1987-02-06","2023-10-09","04144899871","SUBGERENTE","mantecal"],
            ["René Dario Mendez Duque","20716746","1990-12-01","2023-11-02","04147216932","VENDEDOR/A","maracay"],
            ["Maria victoria figueroa","28169397","2001-11-14","2023-09-22","04166532994","VENDEDOR/A","valledelapascua2"],
            ["Adriana Nazareth Rebolledo Colmenares","30388530","2004-03-08","2023-11-02","04243572936","VENDEDOR/A","sanfernando1"],
            ["Joseleny Mailyn Anís Juarez ","26220785","1997-08-30","2023-10-09","04265150175","VENDEDOR/A","sanfernando1"],
            ["GEHARD JOHAN AL HENNAOUI VARGAS","24539203","1993-12-16","2023-06-01","04263224177","VENDEDOR/A","sanfernando1"],
            ["Mones Hennawi Salah ","25796046","1996-12-17","2022-12-27","04162510081","VENDEDOR/A","sanjuandelosmorros"],
            ["Rashid Al Hennawi Al hennaoui","33981560","2010-05-31","2022-07-01","000000000","VENDEDOR/A","mantecal"],
            ["Andy Daniel Uvieda Bastardo","25864501","1997-02-25","2022-12-13","04243527536","VENDEDOR/A","achaguas"],
            ["Wily Daniel Meza olivo ","26088100","1997-06-20","2022-03-20","04127789607","VENDEDOR/A","achaguas"],
            ["Milagro Maurimar Moreno Montilla ","28485962","2002-04-12","2023-08-25","04160355133","VENDEDOR/A","sanfernando2"],
            ["ARISTER ROOXETTE COLINA RUIZ","19222206","1989-09-06","2023-11-11","0243573343","VENDEDOR/A","sanjuandelosmorros"],
            ["Valentina Isabel Ledezma Requena","28001610","2001-01-06","2023-11-21","04127770513","VENDEDOR/A","valledelapascua2"],
            ["Yohandry Naliby Benitez Rodríguez ","27697449","1997-12-20","2023-06-30","04243727260","VENDEDOR/A","achaguas"],
            ["María Victoria bacca Silva","28236899","2001-05-17","2023-10-09","04162142259","VENDEDOR/A","sanfernando2"],
            ["Diosdelyn Marianny ","31347153","2003-04-02","2023-06-23","04123150178","VENDEDOR/A","calabozo"],
            ["Javier Alberto Guillén Hidalgo ","24985722","1993-09-30","2023-07-25","04169491607","VENDEDOR/A","mantecal"],
            ["Rocelys Janier Franco Hernández ","30772406","2003-02-20","2023-11-29","04243355731","VENDEDOR/A","maracay"],
            ["María Rangel","27338054","1999-07-21","2023-09-09","04243486240","VENDEDOR/A","achaguas"],
            ["Roxana nazareth torres da Silva ","29781038","2001-03-29","2023-12-15","04243680323","VENDEDOR/A","sanjuandelosmorros"],
            ["Gladymar Fransybet Higuera Arzola ","30483067","2004-02-02","2023-06-15","04263494781","VENDEDOR/A","valledelapascua"],
            ["Delwin Nadiel Beltrán Beltrán ","25419409","1994-12-17","2023-08-07","04266106244","VENDEDOR/A","achaguas"],
            ["Emilucy del rosario ","29941371","2003-06-26","2023-11-30","04124942578","VENDEDOR/A","sanjuandelosmorros"],
            ["Marian Margarita Figuera higuera","29782817","2003-03-06","2023-10-13","04142141788","VENDEDOR/A","valledelapascua"],
            ["Betania Nazareth Padilla Izquiel ","22888063","1992-03-26","2023-12-09","04243566334","VENDEDOR/A","valledelapascua2"],
            ["Jorge Garbi ","21147882","1992-10-07","2023-04-03","04124974396","PORTERO","calabozo"],


            ["Antonio Maestre ","30418309","2004-04-23","2023-11-04","04129070581","DESPACHADOR","calabozo"],
            ["Jhosmaly analis reyes Márquez ","29782243","2002-04-11","2023-11-15","04120362392","CAJERO/A","valledelapascua2"],
            ["Noheglys Andreina Herrera Diaz","27541886","1999-11-28","2023-12-05","04167399177","VENDEDOR/A","valledelapascua2"],

            ["Mariana Arreaza ","30432252","2004-05-17","2023-10-18","04245451230","CAJERO/A","valledelapascua"],

            ["Jerlyn Ilusión Balza Salina ","30375069","2003-10-04","2023-12-03","04269325615","VENDEDOR/A","valledelapascua"],
            ["Carla Sthefania Mijares Higuera","30627539","2004-12-03","2022-11-26","04243073900","CAJERO/A","valledelapascua"],
            ["Yaliquel José González González ","24240041","1995-06-04","2022-12-01","04262442661","VENDEDOR/A","valledelapascua"],
            ["José Daniel Centeno Carrasco ","32075175","2005-05-18","2023-12-09","04162156209","DESPACHADOR","valledelapascua2"],
            ["Ariangel Arias ","25382917","1996-03-16","2023-12-11","+573161819302","DESPACHADOR","valledelapascua2"],

            ["Aamar Yohalid Al hennawi Pérez ","24199380","1994-10-23","2023-10-09","04165139364","PORTERO","maracay"],
            ["Rosnelys Carvajal ","30291102","2004-02-23","2023-11-07","04123150138","CAJERO/A","calabozo"],
            ["Angi Carolina González ","20957642","1991-03-06","2023-10-19","04243148327","LIMPIEZA","valledelapascua"],
            ["ELIAS JOSE BLANCO QUINTO","22292348","1994-10-21","2023-11-13","04123491859","PORTERO","maracay"],
            ["Francisco Alexander Carrión Brizuela","26717412","1999-02-13","2023-11-28","04144588327","DESPACHADOR","valledelapascua"],
            ["Jose Pérez ","26027496","1997-11-12","2022-12-08","04124162389","DESPACHADOR","calabozo"],
            ["Carlos Moisés Mijares Higuera ","30627540","2004-12-03","2023-10-23","04124939950","DESPACHADOR","valledelapascua"],
            
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
