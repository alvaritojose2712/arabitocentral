<?php

namespace App\Http\Controllers;
use App\Models\cuentasporpagar;
use App\Models\novedad_inventario_aprobacion;
set_time_limit(3000000);
use App\Models\marcas;
use App\Models\productonombre1;
use App\Models\productonombre2;
use App\Models\productonombre3;
use App\Models\productonombre4s;
use App\Models\productonombre5s;


use App\Models\proveedores;
use App\Models\categorias;
use App\Models\CatGenerals;



use App\Models\cuentasporpagar_items;
use App\Models\inventario;
use App\Models\sucursal;
use App\Models\inventario_sucursal;
use App\Models\moneda;
use App\Models\fallas;
use App\Models\pedidos;
use App\Models\items_pedidos;
use App\Models\vinculossucursales;


use App\Http\Requests\StoreinventarioRequest;
use App\Http\Requests\UpdateinventarioRequest;
use Illuminate\Http\Request;
use Response;


class InventarioController extends Controller
{
public function sendInventario(Request $req)
{
    $inv = $req->inventario;


    
}


    public function getEstaInventario(Request $req)
{
    $fechaQEstaInve = $req->fechaQEstaInve;

    $fecha1pedido = $req->fechaFromEstaInve;
    $fecha2pedido = $req->fechaToEstaInve;
    
    $orderByEstaInv = $req->orderByEstaInv;
    $orderByColumEstaInv = $req->orderByColumEstaInv;
    
    $tipoestadopedido = 1;

    
    return inventario::with([
        "proveedor",
        "categoria",
        "deposito",
    ])
    ->whereIn("id",function($q) use ($fecha1pedido,$fecha2pedido,$tipoestadopedido){
        $q->from("items_pedidos")
        ->whereIn("id_pedido",function($q) use ($fecha1pedido,$fecha2pedido,$tipoestadopedido){
            $q->from("pedidos")
            ->whereBetween("created_at",["$fecha1pedido 00:00:01","$fecha2pedido 23:59:59"])
            
            ->select("id");
        })
        ->select("id_producto");

    })
        ->where(function($q) use ($fechaQEstaInve)
    {
        $q->orWhere("descripcion","LIKE","%$fechaQEstaInve%")
        ->orWhere("codigo_proveedor","LIKE","%$fechaQEstaInve%");
        
    })
    ->selectRaw("*,@cantidadtotal := (SELECT sum(cantidad) FROM items_pedidos WHERE id_producto=inventarios.id AND created_at BETWEEN '$fecha1pedido 00:00:01' AND '$fecha2pedido 23:59:59') as cantidadtotal,(@cantidadtotal*inventarios.precio) as totalventa")
    ->orderByRaw(" $orderByColumEstaInv"." ".$orderByEstaInv)
    ->get();
    // ->map(function($q)use ($fecha1pedido,$fecha2pedido){
    //     $items = items_pedidos::whereBetween("created_at",["$fecha1pedido 00:00:01","$fecha2pedido 23:59:59"])
    //     ->where("id_producto",$q->id)->sum("cantidad");

    //     $q->cantidadtotal = $items
    //     // $q->items = $items->get();

    //     return $q;
    // })->sortBy("cantidadtotal");



}
function estatusVinculacion() {
    $su = sucursal::all();

    foreach ($su as $i => $sucursal) {
        $countSu = inventario_sucursal::where("id_sucursal",$sucursal->id)->where("codigo_barras","NOT LIKE","TOR-%")->where("cantidad","<>",0);
        $total = $countSu->count(); 
        $vinculados = $countSu->whereNotNull("id_vinculacion")->count(); 
        $porcen = ($vinculados / $total)*100;
        echo $porcen." ".$sucursal->codigo."<br/>";
    }
}
function vincularinventario() {
    $all = inventario_sucursal::orderBy("codigo_proveedor","asc")->where("codigo_barras","NOT LIKE","TOR-%")->where("cantidad","<>",0)->get();
    foreach ($all as $i => $e) {
        $barra = preg_replace("/[^a-zA-Z0-9]/", "", strtoupper($e->codigo_barras));
        
        $match = inventario::where("codigo_barras",$barra)->first();

        if ($match) {
            $invSucursal = inventario_sucursal::find($e->id);
            $invSucursal->id_vinculacion = $match->id;
            $invSucursal->save();
        }
    }
}

function sendNovedadCentral(Request $req) {
    $novedad = $req->novedad;
    $codigo_origen = $req->codigo_origen;

    $old = $novedad["producto"];
    $idinsucursal = $novedad["id"];
    $estado = 0;
    
    $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
    $id_sucursal = $id_ruta["id_origen"];


    $n = novedad_inventario_aprobacion::updateOrCreate([
        "id_sucursal" => $id_sucursal,
        "idinsucursal" => $idinsucursal,
    ],[
        "id_sucursal" => $id_sucursal,
        "idinsucursal" => $idinsucursal,

        "responsable"=>$novedad["responsable"],
        "motivo"=>$novedad["motivo"],
        "estado"=>$estado,
        
        "codigo_barras_old" => isset($old["codigo_barras"])?$old["codigo_barras"]:null,
        "codigo_proveedor_old" => isset($old["codigo_proveedor"])?$old["codigo_proveedor"]:null,
        "descripcion_old" => isset($old["descripcion"])?$old["descripcion"]:null,
        "precio_base_old" => isset($old["precio_base"])?$old["precio_base"]:null,
        "precio_old" => isset($old["precio"])?$old["precio"]:null,
        "cantidad_old" => isset($old["cantidad"])?$old["cantidad"]:null,

        "id_proveedor_old" => isset($old["id_proveedor"])?$old["id_proveedor"]:null,
        "id_categoria_old" => isset($old["id_categoria"])?$old["id_categoria"]:null,
        
        "codigo_barras" => $novedad["codigo_barras"],
        "codigo_proveedor" => $novedad["codigo_proveedor"],
        "descripcion" => $novedad["descripcion"],
        "precio_base" => $novedad["precio_base"],
        "precio" => $novedad["precio"],
        "cantidad" => $novedad["cantidad"],
        
        "id_proveedor" => $novedad["id_proveedor"],
        "id_categoria" => $novedad["id_categoria"],

        
        
    ]);

    if ($n) {
        return Response::json(["estado"=>true, "msj"=>"Central: Éxito al Registrar novedad #".$n->id]);
    }
    
    
}

function delInventarioSucursalDuplicado() {
    $in = inventario_sucursal::selectRaw("id,codigo_barras,codigo_proveedor,descripcion,COUNT(descripcion)")->whereRaw("codigo_barras REGEXP '^[0-9]+$'")->groupBy("descripcion")->havingRaw("COUNT(descripcion) > 1")->get();

    foreach ($in as $key => $e) {
        inventario_sucursal::where("id","<>",$e->id)->where("descripcion",$e->descripcion)->delete(); 
        
        //echo $e->id."____".$e->codigo_proveedor."____________".$e->codigo_barras."____________".$e->descripcion."<br>";
    }
}
function delInventarioDuplicado() {
    $in = inventario::selectRaw("descripcion, COUNT(descripcion)")->groupBy("descripcion")->havingRaw("COUNT(descripcion) > 1")->get();

    foreach ($in as $key => $e) {
        $q = inventario::where("descripcion",$e->descripcion)->orderBy("codigo_proveedor","asc");
        inventario::where("descripcion",$e->descripcion)->orderBy("codigo_proveedor","asc")->limit($q->count()-1)->delete();
    }
}
function setNombres1(){
    $all = inventario::all();
    $arr = ["ABANICO","ABRAZADERA","ABRELATAS","ABRIDOR","ABRILLANTADOR","ACCESORIO","ACEITE","ACEITERA","ACELERADOR","ACEROLIC","ACIDO","ACOPLE","ACORDEON","ADAPTADOR","ADHESIVO","AFEITADORA","AFILADOR","AGITADOR","AGUJA","AIRE","AISLADOR","ALAMBRE","ALCAYATA","ALCOHOL","ALDABA","ALFOMBRA","ALICATE","ALMOHADAS","ALMOHADILLA","AMACA","AMARRACABLE","AMARRE","AMBIENTADOR","AMORTIGUADOR","ANCLAJE","ANGULO","ANILLO","ANTICIZALLA","ANTICORROSIVO","ANTIRESBALANTE","ANTORCHA","ANZUELO","APAGADOR","ARADELA","ARANDELA","ARCO","AREMACHADORA","ARETES","ARICULARES","ARMELLA","ARMORTIGUADOR","ARNES","ARO","ARRANCADOR","ARRANQUE","ARRASTRADOR","ARROCERA","ARTICUALDOR","ASFALTO","ASIENTO","ASPA","ASPERJADORA","ASPERSOR","ASPIARADORA","ASPIRADOR","ATOMIZADOR","AUDIFONO","AVELLANADOR","AVISO","AYUDANTE","BAJANTE","BALANCIN","BALANZA","BALASTRO","BANDEJA","BANDOLA","BANQUITO","BARNIZ","BARRA","BASE","BASTON","BATERIA","BATIDOR","BEBEDERO","BERNIER","BIASAGRA","BICICLETA","BIDON","BIELA","BIMBILLO","BIMETALICO","BISAGRA","BISTURI","BLOQUE","BOBINA","BOCINA","BOLA","BOLSA","BOMBA","BOMBILLO","BOMBIN","BOQUILLA","BOQUITOQUI","BORDEADORA","BORNE","BOTA","BOTELLA","BOTELLON","BOTIN","BOTON","BOYA","BRAZO","BREAKER","BREAKERA","BRIDA","BRILLO","BROCA","BROCHA","BROCHE","BUJE","BUJIA","BUSING","BUTACA","CABEZA","CABEZAL","CABEZOTE","CABILLA","CABLE","CABO","CACHIMBO","CADENA","CADENILLA","CAFETERA","CAJA","CAJETIN","CAL","CALADORA","CALCULADORA","CALENTADOR","CALIBRADOR","CAMARA","CAMILLA","CAMISA","CAMPANA","CANALETA","CANASTILLA","CANCAMO","CANDADO","CANILLA","CAPACITOR","CAPILAR","CAPOTE","CAPUCHO","CARACOL","CARBONES","CARBURADOR","CARCASA","CARDAN","CARETA","CARGADOR","CARRETE","CARRETILLA","CARRO","CARRUCHA","CARTUCHO","CASCO","CAUCHOS","CAUTIN","CAVA","CEMENTO","CEPILLADORA","CEPILLO","CERA","CERAMICA","CERCHA","CERRADURA","CERROJO","CESTA","CHALECO","CHAMPU","CHASIS","CHAVETA","CHILLA","CHINCHORRO","CHOCHE","CHORRO","CHUPON","CICEL","CICHA","CIERRA","CILINDRO","CINCEL","CINCHA","CINTURON","CIZALLA","CLAVADORA","CLAVIJA","CLAVO","CLIP","COBERTOR","COCEDOR","COCINA","CODO","COLA","COLADOR","COLECTOR","COLETO","COMPRESOR","COMPUTADORA","CONCRETO","CONDENSADOR","CONECTOR","CONEXION","CONGELADOR","CONTACTOR","CONTADOR","CONTENEDOR","CONTROLADOR","CONVERTIDOR","COPA","CORDEL","CORDON","CORNETA","CORONA","CORREA","CORTACERAMICA","CORTADOR","CORTAPERNOS","CORTATUBOS","CORTINA","CORTINERO","COTUFERA","CREOLINA","CRIMPEADORA","CROCHE","CROCHERA","CROMATO","CUADRANTE","CUBIERTA","CUCHARA","CUCHILLA","CUCHILLO","CUELLO","CUERDA","CUERO","CUPILLA","CURVA","DADO","DELANTAL","DESAGUE","DESCOMPRESIONADOR","DESCORCHADOR","DESENGRASANTE","DESMALEZADORA","DESTAPADOR","DESTORCEDOR","DESTORNILLADOR","DETECTOR","DIABLO","DICROICO","DIELECTRICO","DIFUSOR","DILUYENTE","DIMMER","DIODO","DISCO","DISPENSADOR","DOBLADOR","DREMEL","DRENAJE","DRIZA","DUCHA","DUCHO","DUCTO","DUPLICADORA","ECHUFE","EJE","ELECTROBOMBA","ELECTRODO","ELECTRON","ELEVADOR","ELIMINADOR","EMBOBINADO","EMBOLO","EMBRAGUE","EMBUDO","EMBULO","EMPACADURA","EMPALME","EMPAQUE","ENCENDIDO","ENCHUFE","ENCILADORA","engranaje","ENGRASADOR","ENRUTADOR","ENVASE","ESCALERA","ESCAPE","ESCARDILLA","ESCOBA","ESCOBILLA","ESCUADRA","ESLABON","ESMALTE","ESMERIL","ESPADA","ESPARRAGO","ESPATULA","ESPEJO","ESPIGA","ESPONJA","ESPUMA","ESQUINERO","ESTACION","ESTANTERIA","ESTILIZADOR","ESTOPERA","ESTUCHE","ESTUCO","ESTUFA","EXACTO","EXHIBIDOR","EXPANSOR","EXPRIMIDOR","EXTENSION","EXTINTOR","EXTRACTOR","FAJA","FIBRA","FIGURA","FIJA","FIJADOR","FILTRO","FLANCHE","FLAPPER","FLEJE","FLOTANTE","FLUORESCENTE","FONDO","FONDOMINIO","FORMULA","FORRO","FOTOCELDA","FOTOCELULA","FREGADERO","FREIDORA","FRENO","FUMIGADORA","FUNDA","FUNDENTE","FUSIBLE","GALVANIZADOR","GANCHO","GARRA","GARRUCHA","GAS","GATILLO","GATO","GAVETERO","GENERADOR","GOMA","GORRITO","GRABADOR","GRADUADOR","GRAMA","GRAPA","GRAPADORA","GRASA","GRASERA","GRIFO","GRILL","GRILLETE","GRUA","GUANTE","GUARAL","GUAYA","HACHA","HAMACA","HEMBRA","HERRAJE","HERVIDOR","HIDROJET","HIDRONEUMATICO","HIELERA","HOJA","HOJILLA","HORNO","IMPELER","IMPRESORA","IMPULSOR","INDUCIDO","INFLADOR","INGLETADORA","INODORO","INTERRUPTOR","INYECTADORA","INYECTOR","JABON","JABONERA","JARRA","JAULA","JERINGA","JUNTA","KEROSENE","KOALA","KREOLINA","LAJA","LAMINA","LAMPARA","LANA","LANZA","LAPIZ","LASER","LAVADERO","LAVADINA","LAVADORA","LAVAMANO","LENTE","LICUADORA","LIGA","LIJA","LIJADORA","LIMA","LIMIPIADOR","LIMPIADOR","LIMPIAPARABRISAS","LINEA","LINTERNA","LLANA","LLAVE","LLAVERO","LLAVES","LONA","LUBRICADOR","LUBRICANTE","LUCES","LUMINARIA","LUPA","LUSTRADOR","MACHETE","MACHO","MAGNETO","MALETA","MALETIN","MALLA","MANDARRIA","MANDO","MANDRIL","MANGO","MANGUERA","MANILLA","MANOMETRO","MANTEL","MANTO","MANUBRIO","MAQUINA","MARCO","MARIPOSA","MARTILLO","MASCARA","MASCARILLA","MASILLA","MASTIQUE","MAZO","MECATE","MECATILLO","MECHA","MEDIDOR","MEGAFONO","MEMORIA","MESA","METRO","MEZCLADORA","MICROFONO","MICROMOTOR","MICROONDA","MIMBRE","MOCHILA","MODIFICADOR","MOLDURA","MOLEDORA","MOLINILLO","MOLINO","MONOLENTES","MONOMANDO","MOPA","MORRAL","MOSQUITERO","MOTO","MOTOBOMBA","MOTOR","MOTOSIERRA","MUEBLE","MUELLE","MULTIAMPERIMETRO","MULTICORTADORA","MULTIHERRAMIENTA","MULTIMETRO","MULTIORGANIZADOR","NABAJA","NAFTALINA","NAILO","NAVAJA","NEBULIZADOR","NEVERA","NIPLE","NIVEL","NIVELADOR","OLLA","OREJA","ORGANIZADOR","ORRIN","OVALILLOS","OXIDO","OXIHIERRO","PALA","PALANCA","PALANQUIN","PALETA","PALIN","PALO","PALUSTRA","PANEL","PAPEL","PAPELERA","PARABICHO","PARAGUA","PARCHO","PARRILLERA","PASADOR","PASTA","PEGA","PEGO","PEINILLA","PELACABLE","PENDRIVE","PERFORADORA","PERILLA","PERRITO","PESO","PICAPORTE","PICATODO","PICINA","PICO","PIE DE AMIGO","PIEDRA","PILA","PIMPINA","PINCEL","PINTURA","PINZA","PIPOTE","PISTOLA","PISTON","PIZARRA","PLANCHA","PLANEL","PLANTA","PLETINA","PLOMADA","POCETA","PODADORA","POLEA","POLISOMBRA","PONCHERA","PONCHO","PORCELANATO","PORTACANDADO","PORTALAMPARA","PORTALLAVE","PORTAVIDRIO","PRENSA","PRESOSTATO","PRESSCONTROL","PRESURIZADOR","PRIMER","PROBADOR","PROCESADOR","PROTECTOR","PUERTA","PULIDORA","PULITURA","PULMON","PULSADOR","PULVERIZADOR","PURIFICADOR","QUEMADOR","RABO","RACHET","RADIO","RAMPLUG","RAQUETA","RASPADOR","RASTRILLO","REBANADOR","REBORDEADOR","RECIPIENTE","RECTIFICADOR","REFLECTOR","REFRIGERADOR","REGADERA","REGLA","REGLETA","REGULADOR","REJILLA","RELAY","RELOJ","REMACHADOR","REMACHE","REMOVEDOR","REPETIDOR","REPRODUCTOR","RESISTENCIA","RESORTE","RESPIRADERO","RETEN","REVERBERO","RIZADOR","ROCIADOR","RODAMIENTO","RODILLERAS","RODILLO","ROLDANA","ROLINERA","ROMANA","ROTOMARTILLO","ROUTER","RUEDA","SACA","SALA DE BAÑO","SALPICADOR","SANDWICHERA","SAPITO","SAPO","SAPOLIN","SARGENTO","SARTEN","SECADOR","SEGUETA","SELECTOR","SELLADOR","SELLO","SEMILLAS","SENSOR","SEPARADOR","SERRUCHO","SIERRA","SIFON","SIKA","SILICON","SILLA","SINFIN","SOCATE","SOGA","SOLDADOR","SOLUCION","SOPLADORA","SOPLETE","SOPORTE","SPRAY","SUELA","SUERO","SUPERVISOR","SUPRESOR","SURTIDOR","TABLERO","TACHUELA","TALADRO","TANQUE","TAPA","TAPABOCA","TAPON","TAQUETE","TARJETA","TARRAJA","TARRAYA","TECLADO","TEE","TEFLON","TEIPE","TEJA","TELA","TELEDUCHA","TELEFONO","TELEVISOR","TENAZA","TENEDOR","TENSIOMETRO","TENSOR","TERMICO","TERMINAL","TERMO","TERMOMETRO","TERMOSTATO","TERRACOTA","TESTER","TETERA","THINNER","TIJERA","TIMBRE","TIMER","TINA","TIRALINEA","TIRRAP","TIRRO","TIZA","TOALLA","TOALLERO","TOATADORA","TOBO","TOMACORRIENTE","TOPE","TORNILLO","TORQUE","TORQUIMETRO","TOSTADOR","TOSTY","TRAMPA","TRANSFORMADOR","TRANSMISION","TRANSPALETA","TRANSPORTADOR","TRAPICHE","TRIANGULO","TRINQUETE","TRIPA","TRIPOIDE","TRONZADORA","TUALLERO","TUBO","TUERCA","ULE","UNION","UPS","VALVULA","VAPOLETA","VARILLA","VASO","VASTAGO","VENENO","VENTILADOR","VIBRADOR","VIDRIO","VOLANTE","VOLTIAMPERIMETRO","VOLTIMETRO","WAFLERA","YEE","YESO","YESQUERO","YOYO","YURTIRUO","ZAPATA","ZAPATOS","ZINCHA","ZUNCHO",];

    foreach ($arr as $key => $e) {
        //$descripcion = explode(" ",$e->descripcion);

       /*  if (isset($descripcion[0])) {
            if ( ctype_alpha($descripcion[0])) {
              */   
                productonombre1::updateOrCreate([
                    "nombre"=>$e
                ],[
                    "nombre"=>$e
                ]);
          /*   }
        } */
    }
}
function setNMarcasInventario() {
    $a = marcas::orderBy("id","desc")->get();
    foreach ($a as $key => $e) {
        inventario::where("descripcion","LIKE","%".$e->nombre."%")->update(["marca"=>$e->descripcion]);
    }
}
function setNombres1Inventario() {
    $a = productonombre1::orderBy("id","desc")->get();
    foreach ($a as $key => $e) {
        inventario::where("descripcion","LIKE",$e->nombre." %")->update(["n1"=>$e->nombre]);
    }
}
function addnewNombre(Request $req) {
    $palabra = $req->palabra;
    $type = $req->type;



    switch ($type) {
        case 'n1':
            if(productonombre2::where("nombre",$palabra)->first() ||
            productonombre3::where("nombre",$palabra)->first() ||
            productonombre4::where("nombre",$palabra)->first() ||
            marcas::where("descripcion",$palabra)->first()){
                throw new \Exception("Ya existe", 1);
                
            }
            $new = productonombre1::updateOrCreate(["nombre"=>strtoupper($palabra)],["nombre"=>strtoupper($palabra)]);
        break;

        case 'n2':
            if(productonombre1::where("nombre",$palabra)->first() ||
            productonombre3::where("nombre",$palabra)->first() ||
            productonombre4::where("nombre",$palabra)->first() ||
            marcas::where("descripcion",$palabra)->first()){
                throw new \Exception("Ya existe", 1);
                
            }
            productonombre2::updateOrCreate(["nombre"=>strtoupper($palabra)],["nombre"=>strtoupper($palabra)]);
        break;

        case 'n3':
            if(productonombre1::where("nombre",$palabra)->first() ||
            productonombre2::where("nombre",$palabra)->first() ||
            productonombre4::where("nombre",$palabra)->first() ||
            marcas::where("descripcion",$palabra)->first()){
                throw new \Exception("Ya existe", 1);
                
            }
            productonombre3::updateOrCreate(["nombre"=>strtoupper($palabra)],["nombre"=>strtoupper($palabra)]);
        break;

        case 'n4':
            if(productonombre1::where("nombre",$palabra)->first() ||
            productonombre2::where("nombre",$palabra)->first() ||
            productonombre3::where("nombre",$palabra)->first() ||
            marcas::where("descripcion",$palabra)->first()){
                throw new \Exception("Ya existe", 1);
                
            }
            productonombre4::updateOrCreate(["nombre"=>strtoupper($palabra)],["nombre"=>strtoupper($palabra)]);
        break;

        case 'marca':
            if(productonombre1::where("nombre",$palabra)->first() ||
            productonombre2::where("nombre",$palabra)->first() ||
            productonombre3::where("nombre",$palabra)->first() ||
            productonombre4::where("nombre",$palabra)->first()){
                throw new \Exception("Ya existe", 1);
                
            }
            marcas::updateOrCreate(["descripcion"=>strtoupper($palabra)],["descripcion"=>strtoupper($palabra)]);
        break;
    }
}
function saveCuatroNombres(Request $req) {
    $selectIdVinculacion = $req->selectIdVinculacion;
    $inputselectvinculacion1 = $req->inputselectvinculacion1;
    $inputselectvinculacion2 = $req->inputselectvinculacion2;
    $inputselectvinculacion3 = $req->inputselectvinculacion3;
    $inputselectvinculacion4 = $req->inputselectvinculacion4;
    $inputselectvinculacion5 = $req->inputselectvinculacion5;
    $inputselectvinculacionmarca = $req->inputselectvinculacionmarca;

    foreach ($selectIdVinculacion as $key => $e) {
        $isPermiso = true;
        /* if ($inputselectvinculacion1 && $inputselectvinculacion2 && $inputselectvinculacion3 && $inputselectvinculacion3) {
            $check = inventario::where("n1",$inputselectvinculacion1)->where("n2",$inputselectvinculacion2)->where("n3",$inputselectvinculacion3)
            # code...
        } */
        if ($isPermiso) {
            $query = inventario_sucursal::find($e);
            if ($inputselectvinculacion1) {
                $query->n1 = $inputselectvinculacion1;
            } 
            if ($inputselectvinculacion2) {
                $query->n2 = $inputselectvinculacion2;
            } 
            if ($inputselectvinculacion3) {
                $query->n3 = $inputselectvinculacion3;
            } 
            if ($inputselectvinculacion4) {
                $query->n4 = $inputselectvinculacion4;
            } 
            if ($inputselectvinculacion5) {
                $query->n5 = $inputselectvinculacion5;
            } 
            if ($inputselectvinculacionmarca) {
                $query->id_marca = $inputselectvinculacionmarca;
            } 
            $query->save();
        }
    }

}
function modNombres(Request $req) {
    $id = $req->id;
    $type = $req->type;
    $tiponombre = $req->tiponombre;
    $newvalue = $req->newvalue;

    $field = "";

    switch ($tiponombre) {
        case "n1":
            $obj = productonombre1::find($id);
            $field = "nombre";
        break;
        case "n2":
            $obj = productonombre2::find($id);
            $field = "nombre";
        break;
        case "n3":
            $obj = productonombre3::find($id);
            $field = "nombre";
        break;
        case "n4":
            $obj = productonombre4s::find($id);
            $field = "nombre";
        break;
        case "n5":
            $obj = productonombre5s::find($id);
            $field = "nombre";
        break;
        case "id_marca":
            $obj = marcas::find($id);
            $field = "descripcion";
        break;
        case "id_categoria":
            $obj = categorias::find($id);
            $field = "descripcion";
        break;
        case "id_catgeneral":
            $obj = CatGenerals::find($id);
            $field = "descripcion";
        break;
    }
    if ($type=="eliminar") {
       // $obj->delete();
    }else{
        if ($newvalue) {
            $obj[$field] = strtoupper($newvalue);
            $obj->save();
        }
    }
}

function newNombres(Request $req) {
    $id = $req->id;
    $type = $req->type;
    $tiponombre = $req->tiponombre;
    $newvalue = $req->newvalue;

    $field = "";

    switch ($tiponombre) {
        case "n1":
            $obj = new productonombre1;
            $field = "nombre";
        break;
        case "n2":
            $obj = new productonombre2;
            $field = "nombre";
        break;
        case "n3":
            $obj = new productonombre3;
            $field = "nombre";
        break;
        case "n4":
            $obj = new productonombre4s;
            $field = "nombre";
        break;
        case "n5":
            $obj = new productonombre5s;
            $field = "nombre";
        break;
        case "id_marca":
            $obj = new marcas;
            $field = "descripcion";
        break;
        case "id_categoria":
            $obj = new categorias;
            $field = "descripcion";
        break;
        case "id_catgeneral":
            $obj = new CatGenerals;
            $field = "descripcion";
        break;
    }
    if ($type=="eliminar") {
       // $obj->delete();
    }else{
        if ($newvalue) {
            $obj[$field] = strtoupper($newvalue);
            $obj->save();
        }
    }
}
function buscarNombres(Request $req) {
    $qnombres = $req->qnombres;
    $qtiponombres = $req->qtiponombres;

    switch ($qtiponombres) {
        case "n1":
            $obj = productonombre1::when($qnombres,function($q) use($qnombres) {
                $q->where("nombre","LIKE","%$qnombres%");
            })->orderBy("id","asc")->get();
        break;
        case "n2":
            $obj = productonombre2::when($qnombres,function($q) use($qnombres) {
                $q->where("nombre","LIKE","%$qnombres%");
            })->orderBy("id","asc")->get();
        break;
        case "n3":
            $obj = productonombre3::when($qnombres,function($q) use($qnombres) {
                $q->where("nombre","LIKE","%$qnombres%");
            })->orderBy("id","asc")->get();
        break;
        case "n4":
            $obj = productonombre4s::when($qnombres,function($q) use($qnombres) {
                $q->where("nombre","LIKE","%$qnombres%");
            })->orderBy("id","asc")->get();
        break;
        case "n5":
            $obj = productonombre5s::when($qnombres,function($q) use($qnombres) {
                $q->where("nombre","LIKE","%$qnombres%");
            })->orderBy("id","asc")->get();
        break;
        case "id_marca":
            $obj = marcas::when($qnombres,function($q) use($qnombres) {
                $q->where("descripcion","LIKE","%$qnombres%");
            })->orderBy("id","asc")->get();
        break;
        case "id_categoria":
            $obj = categorias::when($qnombres,function($q) use($qnombres) {
                $q->where("descripcion","LIKE","%$qnombres%");
            })->orderBy("id","asc")->get();
        break;
        case "id_catgeneral":
            $obj = CatGenerals::when($qnombres,function($q) use($qnombres) {
                $q->where("descripcion","LIKE","%$qnombres%");
            })->orderBy("id","asc")->get();
        break;
    }

        
    return $obj->map(function($q) use ($qtiponombres) {
        $q->tipo = $qtiponombres;
        return $q;
    });
}

function getDatinputSelectVinculacion() {
    $datavinculacion1 = productonombre1::orderBy("nombre","asc")->get();
    $datavinculacion2 = productonombre2::orderBy("nombre","asc")->get();
    $datavinculacion3 = productonombre3::orderBy("nombre","asc")->get();
    $datavinculacion4 = productonombre4s::orderBy("nombre","asc")->get();
    $productonombre5s = productonombre5s::orderBy("nombre","asc")->get();
    $datavinculacionmarca = marcas::orderBy("descripcion","asc")->get();
    $categorias = categorias::orderBy("descripcion","asc")->get();
    $CatGenerals = CatGenerals::orderBy("descripcion","asc")->get();
    
    
    $proveedores = proveedores::orderBy("descripcion","asc")->get();


    
    
    
    
    return [
        "datavinculacion1" => $datavinculacion1,
        "datavinculacion2" => $datavinculacion2,
        "datavinculacion3" => $datavinculacion3,
        "datavinculacion4" => $datavinculacion4,
        "datavinculacionmarca" => $datavinculacionmarca,
        "datavinculacion5" => $productonombre5s,
        "datavinculaciocat" => $CatGenerals,
        "datavinculaciocatesp" => $categorias,
        "datavinculacioproveedor" => $proveedores,
    ];
}
function unicoinventario() {
    $all = inventario_sucursal::orderBy("codigo_proveedor","asc")->get();
    foreach ($all as $i => $e) {
        $barra = preg_replace("/[^a-zA-Z0-9]/", "", strtoupper($e->codigo_barras));

        $alterno = $e->codigo_proveedor;
        $descripcion = $e->descripcion;
        $base = !$e->precio_base?0:$e->precio_base;
        $venta = !$e->precio?0:$e->precio;
        inventario::updateOrCreate([
            "codigo_barras" => $barra,
        ],[
            "codigo_barras" => $barra,
            "codigo_proveedor2" => $e->codigo_barras,
            "codigo_proveedor" => $alterno,
            "cantidad" => $e->cantidad,
            "unidad" => $e->unidad,
            "id_categoria" => 17,
            "descripcion" => $descripcion,
            "precio_base" => $base,
            "precio" => $venta,
            "iva" => 0,
            "id_catgeneral" => 1,
        ]);
    }
}
public function reporteFalla(Request $req)
    {
        $id_proveedor = $req->id;

        $sucursal = sucursal::all()->first();
        $proveedor = proveedores::find($id_proveedor);

        if ($proveedor&&$id_proveedor) {
            $fallas = fallas::With("producto")->whereIn("id_producto",function($q) use ($id_proveedor)
            {
                $q->from("inventarios")->where("id_proveedor",$id_proveedor)->select("id");
            })->get();

            return view("reportes.fallas",[
                "fallas"=>$fallas, 
                "sucursal"=>$sucursal,
                "proveedor"=>$proveedor,
            ]);
        }


    }
public function delProductoFun($id)
{
    try {

        $i = inventario::find($id);
        
        //$this->setMovimientoNotCliente(null,$i->descripcion,$i->cantidad,$i->precio,"Eliminación de Producto");

        
        $i->delete();
        return true;   
    } catch (\Exception $e) {
        throw new \Exception("Error al eliminar. ".$e->getMessage(), 1);
        
    }
}
public function delProducto(Request $req)
{
    $id = $req->id;
    try {
        $this->delProductoFun($id);
        return Response::json(["msj"=>"Éxito al eliminar","estado"=>true]);   
    } catch (\Exception $e) {
        return Response::json(["msj"=>$e->getMessage(),"estado"=>false]);
        
    }  
}

public function guardarNuevoProducto(Request $req)
{   
    /* try {
        $this->guardarProducto([
            "req_id_factura" => $req->id_factura,
            "req_inpInvcantidad" => $req->inpInvcantidad,
            "req_id" => $req->id,
            "req_inpInvbarras" => $req->inpInvbarras,
            "req_inpInvalterno" => $req->inpInvalterno,
            "req_inpInvunidad" => $req->inpInvunidad,
            "req_inpInvcategoria" => $req->inpInvcategoria,
            "req_inpInvdescripcion" => $req->inpInvdescripcion,
            "req_inpInvbase" => $req->inpInvbase,
            "req_inpInvventa" => $req->inpInvventa,
            "req_inpInviva" => $req->inpInviva,
            "id_catgeneral" => $req->id_catgeneral
        ]);
        return Response::json(["msj"=>"Éxito","estado"=>true]);   
    } catch (\Exception $e) {
        return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
    } */
        
}


public function insertItemFact($id_factura,$insertOrUpdateInv,$ctInsert,$beforecantidad,$ctNew,$tipo)
{
    $find_factura = factura::find($id_factura);

    if($insertOrUpdateInv && $find_factura){

        $id_pro = $insertOrUpdateInv->id;
        $check_fact = items_factura::where("id_factura",$id_factura)->where("id_producto",$id_pro)->first();

        if ($check_fact) {
            $ctNew = $ctInsert - ($beforecantidad - $check_fact->cantidad);
        }


        if ($ctNew==0) {
            items_factura::where("id_factura",$id_factura)->where("id_producto",$id_pro)->delete();
        }else{
            items_factura::updateOrCreate([
                "id_factura" => $id_factura,
                "id_producto" => $id_pro,
            ],[
                "cantidad" => $ctNew,
                "tipo" => $tipo,

            ]);

        }

    }
}
public function getFallas(Request $req)
{


    $qFallas = $req->qFallas;
    $orderCatFallas = $req->orderCatFallas;
    $orderSubCatFallas = $req->orderSubCatFallas;
    $ascdescFallas = $req->ascdescFallas;
    
    // $query_frecuencia = items_pedidos::with("producto")->select(['id_producto'])
    //     ->selectRaw('COUNT(id_producto) as en_pedidos, SUM(cantidad) as cantidad')
    //     ->groupBy(['id_producto']);

    // if ($orderSubCatFallas=="todos") {
    //     // $query_frecuencia->having('cantidad', '>', )
    // }else if ($orderSubCatFallas=="alta") {
    //     $query_frecuencia->having('cantidad', '>', )
    // }else if ($orderSubCatFallas=="media") {
    //     $query_frecuencia->having('cantidad', '>', )
    // }else if ($orderSubCatFallas=="baja") {
    //     $query_frecuencia->having('cantidad', '>', )
    // }

    // return $query_frecuencia->get();
    if ($orderCatFallas=="categoria") {
        
        return fallas::with(["producto"=>function($q){
            $q->with(["proveedor","categoria"]);
        }])->get()->groupBy("producto.categoria.descripcion");

    }else if ($orderCatFallas=="proveedor") {
        return fallas::with(["producto"=>function($q){
            $q->with(["proveedor","categoria"]);
        }])->get()->groupBy("producto.proveedor.descripcion");

    }
}
public function setFalla(Request $req)
{   
    try {
        fallas::updateOrCreate(["id_producto"=>$req->id_producto],["id_producto"=>$req->id_producto]);
        
        return Response::json(["msj"=>"Falla enviada con Éxito","estado"=>true]);   
    } catch (\Exception $e) {
        return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
        
    } 
}
public function delFalla(Request $req)
{   
    try {
        fallas::find($req->id)->delete();
        
        return Response::json(["msj"=>"Falla Eliminada","estado"=>true]);   
    } catch (\Exception $e) {
        return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
        
    } 
}
public function checkFalla($id,$ct)
{   
    if ($id) {
        if ($ct>1) {
            $f = fallas::where("id_producto",$id);
            if ($f) {
                $f->delete();
            }
        }else if($ct<=0){

            fallas::updateOrCreate(["id_producto"=>$id],["id_producto"=>$id]);
        }
    }
}

    public function reporteInventario(Request $req)
    {
        $costo = 0;
        $venta = 0;

        $descripcion = $req->descripcion;
        $precio_base = $req->precio_base;
        $precio = $req->precio;
        $cantidad = $req->cantidad;
        $proveedor = $req->proveedor;
        $categoria = $req->categoria;

        $codigo_proveedor = $req->codigo_proveedor;
        $codigo_barras = $req->codigo_barras;

        $data= inventario::with("lotes","proveedor","categoria")->where(function($q) use ($codigo_proveedor,$codigo_barras,$descripcion,$precio_base,$precio,$cantidad,$proveedor,$categoria)
        {

            if($descripcion){$q->where("descripcion","LIKE",$descripcion."%");}
            if($codigo_proveedor){$q->where("codigo_proveedor","LIKE",$codigo_proveedor."%");}
            if($codigo_barras){$q->where("codigo_barras","LIKE",$codigo_barras."%");}

            if($precio_base){$q->where("precio_base",$precio_base);}
            if($precio){$q->where("precio",$precio);}
            if($cantidad){$q->where("cantidad",$cantidad);}
            if($proveedor){$q->where("id_proveedor",$proveedor);}
            if($categoria){$q->where("id_categoria",$categoria);}
        })->get()
        ->map(function($q) use (&$costo,&$venta)
        {
            if (count($q->lotes)) {
                $q->cantidad = $q->lotes->sum("cantidad"); 
            }
            $c = $q->cantidad*$q->precio_base;
            $v = $q->cantidad*$q->precio;

            $q->t_costo = number_format($c,"2"); 
            $q->t_venta = number_format($v,"2");
            
            $costo += $c;
            $venta += $v;

            return  $q;
        });
        $sucursal = sucursal::all()->first();
        $proveedores = proveedores::all();
        $categorias = categorias::all();
        
        
        return view("reportes.inventario",[
            "data"=>$data,
            "sucursal"=>$sucursal,
            "categorias"=>$categorias,
            "proveedores"=>$proveedores,

            "descripcion"=>$descripcion,
            "precio_base"=>$precio_base,
            "precio"=>$precio,
            "cantidad"=>$cantidad,
            "proveedor"=>$proveedor,
            "categoria"=>$categoria,
        
            "count" => count($data),
            "costo" => number_format($costo,"2"),
            "venta" => number_format($venta,"2"),

            "view_codigo_proveedor" => $req->view_codigo_proveedor==="off"?false:true,
            "view_codigo_barras" => $req->view_codigo_barras==="off"?false:true,
            "view_descripcion" => $req->view_descripcion==="off"?false:true,
            "view_proveedor" => $req->view_proveedor==="off"?false:true,
            "view_categoria" => $req->view_categoria==="off"?false:true,
            "view_cantidad" => $req->view_cantidad==="off"?false:true,
            "view_precio_base" => $req->view_precio_base==="off"?false:true,
            "view_t_costo" => $req->view_t_costo==="off"?false:true,
            "view_precio" => $req->view_precio==="off"?false:true,
            "view_t_venta" => $req->view_t_venta==="off"?false:true,
            

        ]);
    }



    function unicoin() {
         $arr = [
            ["AIRE ACONDICIONADO 25000BTU 220V GTRONIC","A/A-25000-GTRONIC","DGWF-25CMUMA"],
            ["AIRE ACONDICIONADO MYSTIC 36000 BTU 3T","A-A-36000-MYSTIC-3T","A/A-3T"],
            ["AIRE ACONDICIONADO MYSTIC 60000 BTU 5T","A/A-60000-MYSTIC-5T","A/A-5T"],
            ["AIRE ACONDICIONADO OMEGA 36000 BTU 3T","A-A-36000-OMEGA-3T","OAP-36W"],
            ["AIRE ACONDICIONADO PORTATIL 12000BTU 110V MILEXUS","A/A-12000BTU-PORTATIL","ML-AP-12K"],
            ["AIRE DE VENTANA 10000BTU SJ ELECTRONICS 220V ","A/A-10000-SJ-220","SJ-10CM"],
            ["AIRE DE VENTANA 12000 110V SJ","SJ-12CR110",""],
            ["AIRE DE VENTANA 12000BTU 110V CONDESA ","CWAC110V1212",""],
            ["AIRE DE VENTANA 12000BTU 110V MILEXUS","ML-AV12KWA-110V","ML-AV12KWA-110V"],
            ["AIRE DE VENTANA 12000BTU 220V GTRONIC","A/A-12000-GTRONIC","DGWF-12CMUMA"],
            ["AIRE DE VENTANA 12000BTU 220V NORVAIR","A/A-12000-NORVAIR-220","12000BTU-NORVAIR"],
            ["AIRE DE VENTANA 12000BTU MYSTIC","A-A-12000-MYSTIC","MYSTIC-12"],
            ["AIRE DE VENTANA 12000BTU OMEGA 110V","A/A-12000-OMEGA-110V","OAW-12C1"],
            ["AIRE DE VENTANA 12000BTU OMEGA 220V","A/A-12000-OMEGA-220V","OAW-12M"],
            ["AIRE DE VENTANA 14000BTU 110V SJ ELECTRONICS","A/A-14000-SJ-110","SJ-14CM"],
            ["AIRE DE VENTANA 14000BTU 220V SJ ELECTRONICS","A/A-14000-SJ-220V","SJ-14CM"],
            ["AIRE DE VENTANA 18000BTU 220V GTRONIC","DGWF-18CMUMA","DGWF-18CMUMA"],
            ["AIRE DE VENTANA 5000BTU 110V GTRONIC","A/A-5000-GTRONIC","DGWF-O5CMUMA"],
            ["AIRE DE VENTANA 5000BTU 110V MYSTIC","A/A-5000-MYSTIC","MY-AV5080"],
            ["AIRE DE VENTANA 5000BTU 110V OMEGA","A/A-5000-OMEGA-110","OAW-05M"],
            ["AIRE DE VENTANA 6000BTU OMEGA","A/A-6000-OMEGA-110","OAW-06M"],
            ["AIRE DE VENTANA 6000BTU SJ ELECTRONICS 110V ","A/A-6000-SJ-110","AIRE-SJ-6000BTU"],
            ["AIRE DE VENTANA 8000BTU 110V ARTIC","8000BTU-ARTIC","AWAC8KBTU110"],
            ["AIRE DE VENTANA 8000BTU 110V CONDESA ","CWAC110V0812",""],
            ["AIRE DE VENTANA 8000BTU 110V OMEGA","A/A-8000-OMEGA-110","OAW-08C"],
            ["AIRE DE VENTANA 8000BTU 220V ROYAL","A/A-8000-ROYAL-220","AIRE-ROYAL-8000"],
            ["AIRE DE VENTANA 9000BTU OMEGA","A/A-9000-OMEGA","OAW-09C"],
            ["AIRE DE VENTANA EDMIRA 12000BTU 220V ","TAC12CSBH",""],
            ["AIRE DE VENTANA EDMIRA 8000BTU ","AIRE-EDMIRA-8000",""],
            ["AIRE DE VENTANA GPLUS 6000BTU 110V","GP-W061MC",""],
            ["AIRE DE VENTANA HYUNDAI 12000BTU 110V ","HYNAV12500D20",""],
            ["AIRE DE VENTANA HYUNDAI 5000BTU 110V ","HYNAV5000P20","HYNAV5000P20"],
            ["AIRE DE VENTANA KR 12000BTU 220V ","KR1250",""],
            ["AIRE DE VENTANA MILEXUS 12000BTU 110V ","ML-AV12KA-110V-12000BTU","ML-AV12KA-110V"],
            ["AIRE DE VENTANA MILEXUS 12000BTU 220V ","ML-AV12KWA-220V-12000BTU","ML-AV12KWA-220V"],
            ["AIRE DE VENTANA MILEXUS 18000BTU 220V ","ML-AV18KWA",""],
            ["AIRE DE VENTANA MILEXUS 5000BTU 110V","A/A-5000BTU-MILEXUS","ML-AV05KWA"],
            ["AIRE DE VENTANA MILEXUS 8000BTU ","ML-AV08KWRA-800BTU","8000BTU"],
            ["AIRE DE VENTANA ROYAL 12000BTU 220V ","RAW12",""],
            ["AIRE DE VENTANA SJ ELECTRONIC 10000BTU 220V ","SJ-10CM",""],
            ["AIRE DE VENTANA SJ ELECTRONIC 14000BTU 220V ","SJ-14CM",""],
            ["AIRE DE VENTANA SJ ELECTRONIC 14000BTU 220V ","A/A-14000-SJ-220","AIRE-SJ-14CM"],
            ["AIRE DE VENTANA SJ ELECTRONIC 20000BTU 220V ","A/A-20000-SJ-220","AIRE-SJ-20CR"],
            ["AIRE DE VENTANA SJ ELECTRONICS 9000BTU 110V ","SJ-9000BTU",""],
            ["AIRE SPLIT 12000BTU 220V GPLUS","GP-S112C 220V","GP-S112C 220V"],
            ["AIRE SPLIT 12000BTU 220V CONDESA ","A/A-12000-CONDESA-220","12000BTU-CONDESA"],
            ["AIRE SPLIT 12000BTU 220V GBR","A/A-12000-GBR-220","AIRE-SPLIT-GBR"],
            ["AIRE SPLIT 12000BTU 220V GTRONIC","A/A-12000-GTRONIC-SPLIT","DGSX-12CRN1-WEI"],
            ["AIRE SPLIT 18000 BTU 220V MYSTIC","A-A-18000-MYSTIC-SPLIT","A/A-18BTU"],
            ["AIRE SPLIT BM 18000BTU","BM-18000L23",""],
            ["AIRE SPLIT HYUNDAI 18000BTU 220V","HYNA18000WA23",""],
            ["AIRE SPLIT KEYTON 12000BTU 220V ","KAS-12CR-2",""],
            ["AIRE SPLIT MILEXUS 12000BTU 220V","ML-SPAC-12K-220V",""],
            ["AIRE SPLIT MILEXUS 18000BTU 220V","ML-SPAC-18K-220V",""],
            ["AIRE SPLIT OMEGA 12000BTU C/C 220V","A/A-12000-OMEGA-220-SPLIT",""],
            ["AIRE SPLIT SJ ELECTRONIC 12000BTU 220V","ASSJ-12CRB",""],
            ["AIRE SPLIT SJ ELECTRONICS 18000BTU 220V","ASSJ-18CRB220V",""],
            ["AIRE SPLIT TCL 12000BTU 220V ","split-tcl-12000btu",""],
            ["ALAMBRE DE PUAS 400MTRS CALIBRE 17 TOROADO","ALAMBRE-PUAS-17",""],
            ["ALAMBRE DE PUAS 500MTS","GANADERO-500",""],
            ["ALAMBRE DE PUAS 500MTS HATO","HATO-500",""],
            ["ALAMBRE DE PUAS 500MTS MOTTO500","MOTTO-500",""],
            ["ALAMBRE DE SOLDADURA COVO","7453078546438","CV-TW-25G"],
            ["ALAMBRE GALVANIZADO PARA CERCAS ELECTRICAS 2.51MM CALIBRE 12.5 1080MTS","ALAMBRE-CERCA-ELECTRICA","ALAMBRE-G-E-251-1080"],
            ["ALAMBRE LISO 800g","ALAMBRE-LISO",""],
            ["ALAMBRON ESTRIADO A-4MM","ALAM-ESTR-40",""],
            ["ALAMBRON ESTRIADO A-5MM","ALAM-ESTR-50",""],
            ["ALAMBRON ESTRIADO A-6MM ","ALAM-ESTR-60",""],
            ["ALAMBRON ESTRIADO A-7MM","ALAM-ESTR-70",""],
            ["ALAMBRON ESTRIADO A-8MM","ALAM-ESTR-80",""],
            ["APAGADOR CON TOMA ACRILICO BLANCO TROEN","7453078511795","TR-ATW-1K1C"],
            ["APAGADOR CON TOMA ACRILICO NEGRO TROEN","7453078510026",""],
            ["APAGADOR CON TOMA ACRILICO VERDE AGUA TROEN","7453078513560",""],
            ["APAGADOR CON TOMA NEGRO TROEN","7453029106469","A136-ETB-1K1C"],
            ["APAGADOR CON TOMA SENCILLO BLANCO TROEN","7453038497862",""],
            ["APAGADOR CON TOMA SENCILLO PLATEADO TROEN","7453038486460","A136-ETP-1K1C"],
            ["APAGADOR DE EMPOTRAR DOBLE BLANCO TROEN","7453038493161","TR-HYD-2K"],
            ["APAGADOR DOBLE 3 WAY DE ACRILICO BLANCO TROEN","7453078511870",""],
            ["APAGADOR DOBLE 3 WAY DORADO TROEN","7453038484008","A136-ETG-2K-3W"],
            ["APAGADOR DOBLE 3 WAY NEGRO TROEN ","7453010083991","A136-ETB-2K-3W"],
            ["APAGADOR DOBLE ACRILICO BLANCO TROEN","7453038464604","TR-ATW-2K"],
            ["APAGADOR DOBLE ACRILICO NEGRO TROEN","7453038453110",""],
            ["APAGADOR DOBLE ACRILICO VERDE AGUA 3 WAY TROEN","7453078511092","TR-ATG-2K-3W"],
            ["APAGADOR DOBLE ACRILICO VERDE AGUA TROEN","7453078510804","TR-ATG-2K"],
            ["APAGADOR INDIVIDUAL 3 WAY DORADO TROEN","7453078506654","A136-ETG-1K-3W"],
            ["APAGADOR INDIVIDUAL 3 WAY NEGRO TROEN","7453038418690","A136-ETB-1K-3W"],
            ["APAGADOR INDIVIDUAL 3 WAY PLATEADO TROEN","7453010003272","A136-ETP-1K-3W"],
            ["APAGADOR INDIVIDUAL 3W BLANCO TROEN","7453038436304","A136-ETW-1K-3W"],
            ["APAGADOR INDIVIDUAL ACERO INOXIDABLE 3 WAY TROEN","7453078504353","TR-ETSS-1K-3W"],
            ["APAGADOR INDIVIDUAL ACERO INOXIDABLE TROEN","7453038419055","TR-ETSS-1K"],
            ["APAGADOR INDIVIDUAL ACRILICO BLANCO 3 WAY TROEN","7453010008987","TR-ATW-1K-3W"],
            ["APAGADOR INDIVIDUAL ACRILICO VERDE AGUA 3WAY TROEN","7453078511542","TR-ATG-1K-3W"],
            ["APAGADOR INDIVIDUAL PLATEADO TROEN","7453038485500",""],
            ["APAGADOR INDIVIDUAL TROEN BLANCO","7453078513638","A136-ET118Z-1"],
            ["APAGADOR INDIVIDUAL VERDE AGUA TROEN","7453078511900","TR-ATG-1K"],
            ["APAGADOR PARA EMPOTRAR SENCILLO HUESO TROEN","7453010085568","A136-ETR-1K"],
            ["APAGADOR SENSILLO PARA EMPOTRAR CLASSIC LUX","YL-2110","646463131341"],
            ["APAGADOR SUPERFICIAL CLASSICLUX","APAGADOR-SENCILLO","INT-22"],
            ["APAGADOR SUPERFICIAL TROEN","7453038488044","A136-G221-1"],
            ["APAGADOR TRIPLE DE ACRILICO NEGRO TROEN","7453038430081","TR-ATB-3K"],
            ["APAGADOR TRIPLE DE ACRILICO VERDE AGUA TROEN","7453078509471","TR-ATG-3K"],
            ["APAGADOR TRIPLE DE EMPOTRAR BLANCO TROEN","7453078513652","A136-ET118Z-3"],
            ["APAGADOR TRIPLE TROEN NEGRO","7453010013684","A136-ETB-3K"],
            ["APAGADOR TROE INDIVIDUAL NEGRO","7453038494854","A136-ETB-1K"],
            ["BATIDORA AMASADORA GTRONIC 5L ","1050201371057",""],
            ["BATIDORA AMASADORA GTRONIC 6L ","1230201371237",""],
            ["BATIDORA CON PEDESTAL WESTINGHOUSE STAND MIXER 4.2L 5 VEL.WKHM334BK","4895218324521",""],
            ["BATIDORA DE MANO 5V SUJOYA","737186238100",""],
            ["BATIDORA DE MANO GRIS KUCCE","HMKC002GRLUX",""],
            ["BATIDORA DE PEDESTAL 500W 5 VLC HOMEVER","105001","GTM-8028B"],
            ["BATIDORA DE PEDESTAL SOKANY","6974824284516","SK-6620"],
            ["BATIDORA MANUAL 250W 5V OSTER","034264411869","BAT-03"],
            ["BATIDORA MANUAL 7V VELOCIDADES ROYAL REAL","4957895213250","SQ-504"],
            ["BATIDORA MANUAL GT09 GTRONIC","980201370921","GT09"],
            ["BATIDORA MANUAL SOKANY","6925778941246","SK-133"],
            ["BATIDORA MLPLUS PEDESTAL ","BAT-PE2553",""],
            ["BATIDORA SCARLETT 7 VELOCIDADES ","6171765886391",""],
            ["BATIDORA SONEVIEW B5000 ","BT-B5000",""],
            ["BATIDORA SUJOYA 5V PEDESTAL","737186238124",""],
            ["BATIDORA V/VIDRIO OSTER","034264481329","BAT-06"],
            ["BIG PANEL LED BACKELIT 48W CUADRADO LUZ FRIA CHESTERWOD","7592346015723",""],
            ["BIG PANEL LED SIDELIT 48W CUADRADO LUZ FRIA CHESTERWOOD","7592346015709",""],
            ["BIG PANEL LED SIDELIT 72W RECTANGULAR LUZ FRIA CHESTERWOOD","7592346015716",""],
            ["BISAGRA 2-1/2 P/MUEBLE DORADA BEST VALUE F01M25","7453001130536",""],
            ["BISAGRA 2P/ MUEBLE DORADO","7453001130529","F01M20"],
            ["BISAGRA 3 P/MUEBLE DORADA BEST BALUE","7453001130543",""],
            ["BISAGRA 3X3 P/PUERTA DE MADERA WADFOW","6942123017456","WYD1530"],
            ["BISAGRA CASOLETA SIN FRENOS SECURITY","7453038486897","CH-2619"],
            ["BISAGRA CUADRADA DE ACERO RABBIT","679231557371","RB-3X3AB"],
            ["BISAGRA DE ARMILLAR DE HIERRO 3 SECURITY","7453038469890","A307-WHG-3"],
            ["BISAGRA DE GABINETE COBRIZADA","7453038401876","SBL-0402-AC"],
            ["BISAGRA DE HIERRO 4","BISAGRA-4-HIERRO","02-14-03"],
            ["BISAGRA DE HIERRO DE 2 PAR","BISAGRA-2-HIERRO",""],
            ["BISAGRA DE MADERA 2x2 DORADA","17702587955376","T1106-0002"],
            ["BISAGRA DE PRESION BRONCE HUMMER","7453100257868","HUM-1061"],
            ["BISAGRA DE PRESION CROMADA BEST VALUE","7453001132035","F01217CP"],
            ["BISAGRA DE PRESION HUMMER","7453100257875","HUM-1062"],
            ["BISAGRA ESCONDIDA 4 PAR SECURITY","7453010049317","A307-HG1530-4"],
            ["BISAGRA GALVANIZADA 3.5X3.5 P/PUERTA WADFOW","6942123017999","WYD3535"],
            ["BISAGRA GALVANIZADA 3X3P/PUERTA WADFOW","6942123010228","WYD4530"],
            ["BISAGRA P/ASIENTO NEGRA ORQUIDEA PAR VENCERAMICA","715920421834","SP002058141"],
            ["BISAGRA PARA PUERTA 3X3 HUMMER","7453100257844","HUM-1059"],
            ["BISAGRA PARA PUERTA DE MADERA 3X3 HUMMER","7453100257905","HUM-1065"],
            ["BISAGRA PARA PUERTA DORADA 2.5X2.5 HUMMER","7453100257837","HUM-1058"],
            ["BISAGRA PARA PUERTA DORADA 4X4 HUMMER","7453100257851","HUM1060"],
            ["BISAGRA PARA SOLDAR 3.5X3.5 SECURITY","7453038411592",""],
            ["BISAGRA PARA SOLDAR 4 X 4 SECURITY","7453038472333","A307-WHG2-4"],
            ["BISAGRA PORTA CANDADO 25 GALBANIZADO 7PZAS WADFOW","6976057337823","WAN2125"],
            ["BISAGRA PORTACANDADO 15 WADFOW","6976057338035","WAN2115"],
            ["BISAGRA PUERTA DE HIERRO 3","BISAGRA-H-3","BISAGRA-H-3"],
            ["BISAGRA PUERTA DE HIERRO 4 SECURITY","7453038453028","A307-WHG-4"],
            ["BISAGRA PUERTA DE HIERRO 5 SECURITY ","7453038480130","A307-WHG-5"],
            ["BISAGRA PUERTA DE MADERA 3X3 20SECURITY","7453038448246","9A145-HHDG3"],
            ["BISAGRA PUERTA DE MADERA DORADA 3X3 SECURITY","7453010084752","9A145-HHDG3-GP"],
            ["BISAGRA SECURITY PUERTA DE MADERA 3","7453038478519","9A145-HHDG3-CP"],
            ["BISAGRA SOLDAR 3X3 BEST VALUE","7453001100270","F01S13"],
            ["BISAGRA SOLDAR 3X3 BESTVALUE","7453001132066","F01S30"],
            ["BOMBA CENTRIFUGA CISTERNA 2HP 220-240V INGCO","CPM15008",""],
            ["BOMBA COMPLETA FUMIGADORA MAGPOWER WX900","BOM-FUMIG-WX900","BOM-FUMIG-SOLPOWER"],
            ["BOMBA DE ACEITE 390","BOB-390",""],
            ["BOMBA DE ACEITE COMPLETA DE MOTOSIERRA STHIL 381","BOM-381",""],
            ["BOMBA DE ACEITE DE MOTOSIERRA CHINA FITS","BOM-5200","BOM-5200"],
            ["BOMBA DE ACEITE DE MOTOSIERRA HUSQ 288","BOM-288",""],
            ["BOMBA DE ACEITE DE MOTOSIERRA STHIL 660 FITS","BOM-660",""],
            ["BOMBA DE ACEITE DE STHIL 382","BOM-382",""],
            ["BOMBA DE ACEITE H395 FITS","BOM-395","BOM-395"],
            ["BOMBA DE ACEITE HUSQ-061,268,272 ","BOM-061","BOMBA-061"],
            ["BOMBA DE ACEITE MOTOSIERRA STHIL MS290/MS310/MS390","BOM-310-290","BOM-390"],
            ["BOMBA DE ACEITE MS380","BOM-380","BOM-380"],
            ["BOMBA DE AGUA 1/2 HP EMTOP ","6941556216573","ULWPPV03701"],
            ["BOMBA DE AGUA ELECTRICA P/BOTELLON","6903505665689","AZ6568"],
            ["BOMBA DE AGUA PERIFERICA 1/2 ALUMINIO CHESTERWOOD","GB-60A","CHEQ1003686"],
            ["BOMBA DE AGUA PERIFERICA 1/2 COBRE CHESTERWOOD","COBRE1/2HP","QB-60C"],
            ["BOMBA DE AGUA PERIFERICA 1/2HP 110V CHESTERWOOD ","BOMBA-1/2HP CHESTERWOOD",""],
            ["BOMBA DE AGUA PERIFERICA 1HP 110V CHESTERWOOD ","ELECTROBOMBA-1HPCHESTERWOOD",""],
            ["BOMBA DE AGUA PERIFERICA 1HP ALUMINIO CHESTERWOOD","GB-80A","CHEQ1003688"],
            ["BOMBA DE AGUA PERIFERICA 1HP COBRE CHESTERWOOD","COBRE1HP","CHEQ1003687"],
            ["BOMBA DE AGUA SUMERGIBLE + CONTROL 1500W 2HP INGCO","UDWP11001-SB",""],
            ["BOMBA DE AGUA SUMERGIBLE 750W 1HP INGCO","USPD7508",""],
            ["BOMBA DE AIRE CON PEDAL 60PSI COVO","7453038418942","CV-FTP-1"],
            ["BOMBA DE AIRE D/OIE 17MM EXXEL ","07-009-046",""],
            ["BOMBA DE AIRE DE PIE BICICLETAS PICINA 165MM JADEVER","6942210206602","JDPP2501"],
            ["BOMBA DE AIRE DE PIE C/MANOMETRO COVO","7453038410823","CV-FTP-2"],
            ["BOMBA DE AIRE DE PIE EXXEL","6904267357003","07-009-045"],
            ["BOMBA DE AIRE MANUAL 160PSI COVO","7453010076177","CV-PM6045"],
            ["BOMBA DE AIRE MANUAL SECURITY","7453010080723","7A010-BOM-P38"],
            ["BOMBA DE AIRE PARA BICICLETA COVO","7453010090395","CV-PM5938B"],
            ["BOMBA DE AIRE PARA BICICLETAS BALONES PICINAS 370MM JADEVER","6942210206596","JDPP1C01"],
            ["BOMBA DE COMBUSTIBLE 155W 12V/13A SACO","7450029098282","04-FR-19831"],
            ["BOMBA DE FUMIGACION MAG B22G","BOMBA-FUMIGACION-MAG-B22G","BOMBA-FUMIGACION-MAG-B22G"],
            ["BOMBA DE FUMIGADORA ESTACIONARIA MAGB30-30 MAGPOWER","MAG30G","EFU-1015"],
            ["BOMBA DE INFLAR 21 PRETUL","7501206640050",""],
            ["BOMBA DE INYECCION 10HP 186F FITS","BOM-186F","BOM-10HP-186F"],
            ["BOMBA DE MOTO BICICLETA 120PSI","BOMBA-120PSI","BOMBA-120PSI"],
            ["BOMBA DE MOTOSIERRA HUSQ 365 FITS","BOM-365",""],
            ["BOMBA DEAGUA 1HP EMTOP","6941556224240",""],
            ["BOMBA MANUAL DE HIERRO 90","BOMBA-HIERRO-MANUAL",""],
            ["BOMBA MANUAL P/INFLAR 22CM, MAXI TOOLS","6952585026231","PH-WS2623"],
            ["BOMBA MANUAL PLASTICA 90","BOMBA-PLASTICA-MANAU",""],
            ["BOMBA MOLINO DE HIERRO","BOMBA-MOLINO",""],
            ["BOMBA PARA DESOLDAR 195MM WADFOW","6942431483493","WXX1601"],
            ["BOMBA SUMERGIBLE 1.5HP EMTOP","6972951241501",""],
            ["BOMBA SUMERGIBLE 1/2HP EMTOP","6972951241440",""],
            ["BOMBA SUMERGIBLE 1HP EMTOP","BOMBA-S-E",""],
            ["BOMBA SUMERGIBLE 2HP EMTOP","6972951241525",""],
            ["BOMBA SUMERGIBLE 3/4 HP EMTOP","6972951241464",""],
            ["BOMBA SUMERGIBLE 3HP 220V GENPAR","GBS-4-300-21M",""],
            ["BOMBA SUMERGIBLE 3HP DOMOSA","DS-300",""],
            ["BOMBA SUMERGIBLE 4 EMTOP ","6972951245028","ULWPPD03701"],
            ["BOMBA SUMERGIBLE DE ACHIQUE LEO 5HP 220V 60HZ","SUMERGIBLE-5HP-LEO","KBZ23.7"],
            ["BOMBA SUMERGIBLE DOMOSA 1.5HP","DS-150",""],
            ["BOMBA SUMERGIBLE DOMOSA 1HP","DS-100",""],
            ["BOMBA SUMERGIBLE DOMOSA 1HP DS100","DS100",""],
            ["BOMBA SUMERGIBLE DOMOSA 2HP","DS-200",""],
            ["BOMBA SUMERGIBLE DOMOSA 2HP DS200","DS200",""],
            ["BOMBA SUMERGIBLE DOMOSA 3HP DS300 ","DS300",""],
            ["BOMBA SUMERGIBLE DOMOSA 5.5HP ","DS-550T19",""],
            ["BOMBA SUMERGIBLE GRIVEN 1HP 220V","GV-SP-1-HP220",""],
            ["BOMBA SUMERGIBLE LEO 0.5HP 3 8 ETP 220V","SUMERGIBLE-0.5HP-LEO","3XRM3/8"],
            ["BOMBA SUMERGIBLE LEO 1.5HP 220V","4DW55-5",""],
            ["BOMBA SUMERGIBLE LEO 1.5HP 220V","4XRM3-13",""],
            ["BOMBA SUMERGIBLE LEO 1.5HP 4 8 ETP 220V","4XRM5-8","4XRM5/8"],
            ["BOMBA SUMERGIBLE LEO 10HP 6 220V","SUMERGIBLE-10HP-LEO","6XRS17/9"],
            ["BOMBA SUMERGIBLE LEO 1HP","SUMERGIBLE-1HP-LEO","AXRM3/9"],
            ["BOMBA SUMERGIBLE LEO 2HP 4 7 ETP 220V","4XRM9-7","4XRM9/7"],
            ["BOMBA SUMERGIBLE LEO 7.5HP 4 16 ETP 220V","SUMERGIBLE-7.5HP-LEO","4XR14/16"],
            ["BOMBA SUMERGIBLE PROFUNDO 4 EMTOP 550W 0.75HP","6972951245042","ULWPPD05501"],
            ["BOMBA SUMERGIBLE SHIMGE 1HP 220V","4SGM8-3",""],
            ["BOMBA SUMERGIBLE SOLAR 25-DSS-0.4HP DOMOSA","25-DSS-0.4HP-DOMOSA","25-DSS-0.4HP-DOMOSA"],
            ["BOMBA SUMERGIBLE SOLAR 25-DSS-1HP DOMOSA","25-DSS-1HP-DOMOSA","25-DSS-1HP-DOMOSA"],
            ["BOMBILLO 30W CHESTERWOOD","7594320513584","LIFETIME/8000H"],
            ["BOMBILLO 35W ESPIRAL","7594320513591","ESL-BLS-616"],
            ["BOMBILLO AHORRADOR 105W METALES ALIADOS","7592978368808","BOM1254"],
            ["BOMBILLO AHORRADOR DE ESPIRAL 23W AXUM ","7594320517636",""],
            ["BOMBILLO AHORRADOR ESPIRAL 18W AXUM ","6958526552173",""],
            ["BOMBILLO AHORRADOR LED 12W FERCO","7592072231404","FLB-A60E27-1"],
            ["BOMBILLO AHORRADOR LED 15W","7592072231565","FLB-A70E27-1"],
            ["BOMBILLO AHORRADOR LED BULBO 18W","7453118000159","SL-QPA003-18W"],
            ["BOMBILLO AHORRADOR TIPO VELA 6W FERCO","7594320517568","FLB-C376W"],
            ["BOMBILLO BULBO LED 12W RUN ","736373168909","BBL02"],
            ["BOMBILLO BULBO LED 15W RUN","736373168916","BBL03"],
            ["BOMBILLO BULBO LED 18W RUN","736373168923","BBL04"],
            ["BOMBILLO BULBO LED 9W RUN","736373168893","BBL01"],
            ["BOMBILLO DE 20W LED BODB","6978001556243","6978001556243"],
            ["BOMBILLO DE COLORES PEQUEÑO","COLORBULB",""],
            ["BOMBILLO DE EMERGENCIA 15W RUM","736373171114","BLEM05"],
            ["BOMBILLO ESPIRAL AHORRADOR 18W RUN ","736373169111","BAHES01"],
            ["BOMBILLO ESPIRAL AHORRADOR 20W RUM","736373170391","BAHES02"],
            ["BOMBILLO INCANDECENTE 100W","5852868200529",""],
            ["BOMBILLO LED 10W EXXEL","205000007431","03-004-132"],
            ["BOMBILLO LED 12W ANGEL LIGHT","7453010048624","ALB-TLP-12W"],
            ["BOMBILLO LED 12W AXUM","7594320516097","040103"],
            ["BOMBILLO LED 12W BRESLIGHT","7453087826088","EL-1842"],
            ["BOMBILLO LED 12W CHESTERWOOD","7592346015396",""],
            ["BOMBILLO LED 12W CLASSIC LUX","2120450078003","BI-L12"],
            ["BOMBILLO LED 12W EXXEL ","205000007443","03-004-138"],
            ["BOMBILLO LED 12W LUZ CALIDA ANGEL LIGHT","7453038485081","A105-TLP-12W-Y"],
            ["BOMBILLO LED 12W VERT","6180201079310","BEW-126"],
            ["BOMBILLO LED 12W VERT","6234127890129","BAE-126"],
            ["BOMBILLO LED 12W VERT","1110201310101","12WEL"],
            ["BOMBILLO LED 15W ANGEL LIGHT ","7453038491655",""],
            ["BOMBILLO LED 15W ANGEL LIGHT ","7453038488396","A105-TLP-15W-B"],
            ["BOMBILLO LED 15W AXUM","7594320401324","040132"],
            ["BOMBILLO LED 15W CLASSIC LUX","3749658585107","BI-L16"],
            ["BOMBILLO LED 15W VERT","6680201370009","BAE-156"],
            ["BOMBILLO LED 17W CLASSIC LUX","3478541255840","BI-L19"],
            ["BOMBILLO LED 18W ANGEL LIGTH","7453038484336","A105-TLP-18W-B"],
            ["BOMBILLO LED 18W AXUM","7594320401331","040133"],
            ["BOMBILLO LED 18W EXXEL","205000007445","03-004-139"],
            ["BOMBILLO LED 2.5W VERT","2780201386186","BG9-254"],
            ["BOMBILLO LED 30W AXUM","7594320516813","040115"],
            ["BOMBILLO LED 3W LUZ NATURAL ANGEL LIGHT ","7453038400008","AL-MR16-3W"],
            ["BOMBILLO LED 40W AXUM","7594320516820","040116"],
            ["BOMBILLO LED 40W EXXEL","205000010795","03-004-214"],
            ["BOMBILLO LED 40W EXXEL03004133 ","205000007433","P03-004-133"],
            ["BOMBILLO LED 40W RUN ","736373170506","BBLBOT01"],
            ["BOMBILLO LED 40W TIPO T CLASSIC LUX ","125741474100","BI-L43-5"],
            ["BOMBILLO LED 42W PLEGABLE TIPO VENTILADOR ANGEL LIGHT","7453038484206","A105-TLL-42W"],
            ["BOMBILLO LED 50W EXXEL","205000007435","03-004-134"],
            ["BOMBILLO LED 50W EXXEL","205000010797","003-004-215"],
            ["BOMBILLO LED 55W WALES AC85-265V","6970466902221",""],
            ["BOMBILLO LED 5W TIPO VELA ANGEL LIGHT ","7453038496308","A105-C37-5W"],
            ["BOMBILLO LED 60W EXXEL","205000010799","03-004-216"],
            ["BOMBILLO LED 60W RUN ","736373170513","BBLBOT02"],
            ["BOMBILLO LED 60W TIPO T CLASSIC LUX ","701474141001","BI-143-9"],
            ["BOMBILLO LED 6W ANGEL LIGHT","7453038493307","A105-WSP20-6W"],
            ["BOMBILLO LED 7W AXUM A60-7W ","7594320516073","040101"],
            ["BOMBILLO LED 7W CHESTERWOOD","7592346015372",""],
            ["BOMBILLO LED 7W DICROICO GU10 CHESTERWOOD","7592346015426",""],
            ["BOMBILLO LED 7W DICROICO MR16 CHESTERWOOD","7592346015419",""],
            ["BOMBILLO LED 7W E27 CLASSIC LUX","7854621562232695485","BI-L05"],
            ["BOMBILLO LED 7W TIPO VELA CHESTERWOOD","7592346015402",""],
            ["BOMBILLO LED 7W VERT","6880281378883","BAE-76"],
            ["BOMBILLO LED 80W EXXEL","205000010801","03-004-217"],
            ["BOMBILLO LED 9W ANGEL LIGHT","7453038429559","ALB-TLP-9W"],
            ["BOMBILLO LED 9W ANGEL LIGHT","7453038487665","A105-TLP-9W-B"],
            ["BOMBILLO LED 9W AXUM","7594320516080","040102"],
            ["BOMBILLO LED 9W BRESLIGHT","7453087826064","EL-1840"],
            ["BOMBILLO LED 9W CHESTERWOOD","7592346015389",""],
            ["BOMBILLO LED 9W EXXEL","205000007441","03-004-137"],
            ["BOMBILLO LED 9W FOTOCELDA CLASSIC LUX","571380000012","BI-L25-1"],
            ["BOMBILLO LED 9W OJO BUEY CUADRADO AXUM ","7594320517308",""],
            ["BOMBILLO LED 9W SUPER LIGHT","7453038461030","A105-GLB-9W-GNS"],
            ["BOMBILLO LED 9W VERT","6234097890099","BAE-96"],
            ["BOMBILLO LED 9W VERT","9605201009602","9WEL"],
            ["BOMBILLO LED CLASSIC LUX 24W MULTIVOLTAJE","7652134412001","BI-L20"],
            ["BOMBILLO LED CLASSIC LUX 7W MULTIVOLTAJE ","3796582411637","BL-E27-7W"],
            ["BOMBILLO LED CLASSIC LUX 9W MULTIVOLTAJE ","8744112233551","BI-L08"],
            ["BOMBILLO LED CORNETA BLUETOOH C/CON ","7453038455428","A105-ML01"],
            ["BOMBILLO LED DE COLORES C/CONTROL 9W CLASSIC LUX","6527781245446546","85-265VAC"],
            ["BOMBILLO LED DE EMERGENCIA 12W CLASSIC LUX","781940008210",""],
            ["BOMBILLO LED DE EMERGENCIA 30W RUN","736373171091","BLEM03"],
            ["BOMBILLO LED DE EMERGENCIA 9W RUN ","736373169128","BBL07"],
            ["BOMBILLO LED DE EMERGENCIA RUN ","0736373169173","BBL08"],
            ["BOMBILLO LED DICROICO 3W ANGEL LIGHT ","7453038499026","A105-TLPGU-3W"],
            ["BOMBILLO LED DICROICO 3W MR16 ANGEL LIGHT ","7453038471428","A105-LTPMR-3W"],
            ["BOMBILLO LED DICROICO GU10 5W AXUM","7594320516776",""],
            ["BOMBILLO LED DICROICO GU10 7W AXUM ","7594320516783",""],
            ["BOMBILLO LED EMERGENCIA 15W CLASSIC LUX","786452100001","BI-L83"],
            ["BOMBILLO LED EMERGENCIA 9W CLASSIC LUX ","000001034578",""],
            ["BOMBILLO LED HALO 11W ANGEL LIGHT","7453038419130","A105-SUFO-11W"],
            ["BOMBILLO LED MULTIVOLTAJE 15W ANGEL LIGHT","7453010039509","ALB-TLP-15W"],
            ["BOMBILLO LED PLEGABLE 60W D-LED 03-004-143","205000007650",""],
            ["BOMBILLO LED PLEGABLE 80W D-LED 03-004-144","205000007652",""],
            ["BOMBILLO LED PLEGABLE D-LED 32W 03-004-141","205000007646",""],
            ["BOMBILLO LED PLEGABLE FLOR 36W ","7453038488402","A105-TLL-36W"],
            ["BOMBILLO LED RECARGABLE 30W EXXEL 03-004-194 ","205000008827","03-004-194"],
            ["BOMBILLO LED TIPO BULBO 12W LUZ AMARILLA","7592978003648",""],
            ["BOMBILLO LED TIPO BULBO 15W LUZ AMARILLA","7592978003662",""],
            ["BOMBILLO LED TIPO T 50W CLASSIC LUX ","346521123123","BI-L43-7"],
            ["BOMBILLO LED TUBULAR 4W CLASSIC LUX ","57491564164116110","BI-130"],
            ["BOMBILLO LED TUBULAR 7W 65K E27 CLASSIC LUX","51646132131656511","BI-L28"],
            ["BOMBILLO LED TUBULAR N12W 65K E27 CLASSIC LUX ","51646132131656516","BI-L27"],
            ["BOMBILLO MULTICOLOR LED 9W ANGEL LIGHT ","7453038457774","A105-TLP-9W-RGB"],
            ["BOMBILLO OJO DE BUEY 5W VERT","6110201371053","GUS-56"],
            ["BOMBILLO OJO DE BUEY CIRCULAR CIRCULAR LED 9W AXUM","7594320517285",""],
            ["BOMBILLO OJO DE BUEY CIRCULAR LED 7W AXUM ","7594320517278",""],
            ["BOMBILLO OJO DE BUEY LED SAMSUNG 5W VERT","6634507190199",""],
            ["BOMBILLO OJO DE BUEY&nbsp; CUADRADO LED 7W AXUM ","7594320517292",""],
            ["BOMBILLO PLEGABLE 3 ASPAS 45W CLASSIC LUX ","5631852001478","BI-L72"],
            ["BOMBILLO RECAR 100W 081 5-8 HORAS","6985846120362","105-120-36"],
            ["BOMBILLO RECAR 50W GRANDE","4650093514616","081-T95"],
            ["BOMBILLO RECARGABLE 12W 85-260V EXXEL","205000008821",""],
            ["BOMBILLO RECARGABLE 15W 85-260V EXXEL ","205000008823","03-004-192"],
            ["BOMBILLO RECARGABLE LED 15W 85-260V EXXEL ","205000008825","03-004-193"],
            ["BOMBILLO RECARGABLE TAMAÑO 120MM 150W 4HORAS","BOMBILLO-RECAGABLE-150W","BOMBILLO-RECAGABLE-150W"],
            ["BOMBILLO VELA DE 60W CLASSIC LUX","6864240758602","BOMBILLO-VELA60W"],
            ["BOMBILLO VELA E27 120V CLARO CLASSIC LUX","6864240758619","E27-40W"],
            ["BOMBILLO VINTAGE LED 4W","7453038492287","A105-A60C-4W-R"],
            ["BOMBILLO VINTAGE LED 4W 110V ","7453038493963","A105-A60C-4W-G"],
            ["BROCHA 1 1/2 EMTOP","6941556211585",""],
            ["BROCHA 1 ECONOMICA EMTOP","6972951246926","EPBH01702"],
            ["BROCHA 1 EMTOP","6941556224370",""],
            ["BROCHA 1 HAKUNA MATATA","759147140915",""],
            ["BROCHA 1 HUMMER","7453100258681","HUM-1143"],
            ["BROCHA 1 PRO PAINT","7453038488587","BROCHA-1-PROPAINT"],
            ["BROCHA 1 PRO PAINT","7453038472715","A145-9PD2011-1"],
            ["BROCHA 1.5 HUMMER","7453100258698","HUM-1144"],
            ["BROCHA 1.5 PRO PAINT","7453038488433","BROCHA-1.5-PROPAINT"],
            ["BROCHA 1.5 PRO PAINT","7453038472708","A145-9PD2011-1.5"],
            ["BROCHA 1/2 HUMMER","7453100266280","HUM-1244"],
            ["BROCHA 2 1/2 CERDAS BLANCAS COVO","7453010042424","CV-PD01-2.5W"],
            ["BROCHA 2 CERDAS NATURALES NEGRAS COVO","7453038499774","CD-PD01-2B"],
            ["BROCHA 2 COVO CV-PD01-2W","7453010042349",""],
            ["BROCHA 2 EMTOP","6941556219260",""],
            ["BROCHA 2 HAKUNA MATATA","7591471410922",""],
            ["BROCHA 2 HUMMER","7453100258704","HUM-1145"],
            ["BROCHA 2 INGCO","6925582153163","CHPTB78602"],
            ["BROCHA 2 METCO","9780201379723","MET2165"],
            ["BROCHA 2 PRO PAINT","7453038487221","BROCHA-2-PROPAINT"],
            ["BROCHA 2 PRO PAINT","7453038469852","A145-9PD2011-2"],
            ["BROCHA 2.5 CERDAS NATURALES NEGRAS COVO","7453038479028","CV-PD01-2-5B"],
            ["BROCHA 2.5 EMTOP","6941556206475","EPBH25702"],
            ["BROCHA 2.5 HUMMER","7453100258711","HUM-1146"],
            ["BROCHA 2.5 PRO PAINT","7453038488884","BROCHA-2.5-PROPAINT"],
            ["BROCHA 2.5 PRO PAINT","7453038469845","A145-9PD2011-2.5"],
            ["BROCHA 3 CERDAS NATURALES COVO","7453038484428","CV-PD01-3W"],
            ["BROCHA 3 CERDAS NATURALES NEGRAS COVO","7453010089238","CV-PD01-3B"],
            ["BROCHA 3 ECONOMICA EMTOP","6941556214159","EPBH03702"],
            ["BROCHA 3 EMTOP","6972951246940","EPBH03601"],
            ["BROCHA 3 HAKUNA MATATA","7591471410939",""],
            ["BROCHA 3 HUMMER","7453100258728","HUM-1147"],
            ["BROCHA 3 METCO","9780201379822","MET3165"],
            ["BROCHA 3 PRO PAINT","7453038485777","A145-9PD1902-3"],
            ["BROCHA 4 HAKUNA MATATA","7591471410946",""],
            ["BROCHA 4 HUMMER","7453100258735","HUM1148"],
            ["BROCHA 4 METCO","9780201379921","MET4165"],
            ["BROCHA 4 PRO PAINT","7453038490917","BROCHA-4-PROPAINT"],
            ["BROCHA 5 HAKUNA MATATA","7591471410953",""],
            ["BROCHA 5 HUMMER","7453100258742","HUM-1149"],
            ["BROCHA C/MANGO PLASTICO AZUL 5 EXXEL","6976217732505","00-005-033"],
            ["BROCHA CERCHA BLANCA 4 COVO","7453010086282","CV-PD01-4W"],
            ["BROCHA CERDA 3PZAS 1 2 3 PGD EXXEL","205000010218","00-005-186"],
            ["BROCHA CERDA BLANCA 1 COVO","7453038421966","CV-PD01-1W"],
            ["BROCHA CERDA BLANCA 1.5 COVO","7453038414838","CV-PD01-1-5W"],
            ["BROCHA CERDA MANGO PLASTICO 1 EXXEL ","6976217732109","00-005-027"],
            ["BROCHA CERDA MANGO PLASTICO 1.5 EXXEL","6976217732154","00.005-028"],
            ["BROCHA CERDA MANGO PLASTICO 2 EXXEL","6976217732208","00-005-029"],
            ["BROCHA CERDA MANGO PLASTICO 2.5 EXXEL","6976217732253","00-005-030"],
            ["BROCHA CERDA MANGO PLASTICO 3 EXXEL","6976217732307","00-005-031"],
            ["BROCHA CERDA MANGO PLASTICO 4 EXXEL","6976217732406","00-005-032"],
            ["BROCHA CERDA NEGRA 1 COVO","7453010086237","CV-´PD01-1B"],
            ["BROCHA CERDA NEGRA 1.5","7453038414678",""],
            ["BROCHA CERDAS NEGRAS 4 COVO","7453038499392","CV-PD01-4B"],
            ["BROCHA JABALI 1","BRO-0018","BRO-0018"],
            ["BROCHA JABALI 3","BRO-02","BRO-03"],
            ["BROCHA JABALI 3.5","BRO-31/2","BRO-31/2"],
            ["BROCHA JABALI 4","BRO-04","BRO-04"],
            ["BROCHA MANGO DE GOMA 2 KOBATEX","6973125162325","BROCH-ABC-1205"],
            ["BROCHA MANGO DE GOMA 3 KOBATEX","6973125162332","BROCH-ABC-1206"],
            ["BROCHA MANGO DE GOMA DE 4 KOBATEX","6973125162349","BROCHA-ABC-1207"],
            ["BROCHA PARA PINTAR 1 WADFOW","6975085809128","WPB1901"],
            ["BROCHA PARA PINTAR 1.1 JADEVER","6942210210357","JDPB1901"],
            ["BROCHA PARA PINTAR 1.5 INGCO","6941640124807","CHPTB78615"],
            ["BROCHA PARA PINTAR 2 JADEVER","6942210210364","JDPB1902"],
            ["BROCHA PARA PINTAR 2 WADFOW","6975085809623","WPB1902"],
            ["BROCHA PARA PINTAR 3 INGCO","6925582153170","CHPTB78603"],
            ["BROCHA PROFESIONAL 6 COVO","7453078502809",""],
            ["BROCHAS SET DE 5 PIEZAS EMTOP","6941556224271",""],
            ["BUSING GALVANIZADO 1 1/2 X 1","BUSING-G-11/2-1","BUSING-G-11/2-1"],
            ["BUSING GALVANIZADO 1 1/4 A 1 ","BUSING-11/4-1","BUSING-G-11/4-1"],
            ["BUSING GALVANIZADO 1 X 1/2","BUSING-G-1-1/2","BUSING-G-1-1/2"],
            ["BUSING GALVANIZADO 1 X 3/4","BUSING-G-1-3/4","BUSING-G-1-3/4"],
            ["BUSING GALVANIZADO 1-1/2 X 1","BUSING-1-1/2X1",""],
            ["BUSING GALVANIZADO 1-1/2 X 1-1/4","BUSING-G-1-1/2X1-1/460",""],
            ["BUSING GALVANIZADO 1-1/2 X 1/2","BUSING-1-1/2X1/2",""],
            ["BUSING GALVANIZADO 1-1/2 X 3/4","BUSING-1-1/2X3/4",""],
            ["BUSING GALVANIZADO 1-1/4 X 1","BUSING-1-1/4X1",""],
            ["BUSING GALVANIZADO 1-1/4 X 3/4","BUSING-G-1-1/4X3/4",""],
            ["BUSING GALVANIZADO 2 X1-1/4","BUSING-G-2X1-1/4",""],
            ["BUSING GALVANIZADO 2X1 ","PLO-BGAB-2X1","BUSING-GAL-2X1"],
            ["BUSING GALVANIZADO 2X1-1/4 ","BUSIN-G-2X1-1/4","BUSIN-G-2X1-1/4"],
            ["BUSING GALVANIZADO 2X3/4","BUSING-G-2X3/4",""],
            ["BUSING GALVANIZADO DE 2 X1-1/2","BUSING-G-2-11/2","BUSING-G-2-11/2"],
            ["BUSING GALVANIZADO DE 3/4 X 1/2","BUSING-3/4-1/2","BUSING-G-3/4-1/2"],
            ["BUSING PLASTICO CON ROSCA 1 A 1/2","BUSING-PVC-1-1/2","BUSING-PVC-1-1/2"],
            ["BUSING PLASTICO CON ROSCA 1 A 3/4","BUSING-PVC-1-3/4","BUSING-PVC-1-3/4"],
            ["CABILLA CUADRADA 3/8","CABILLA-3/8-CUADRADA","CABILLA-3/8-CUADRADA"],
            ["CABILLA ESTRIADA 1/2 12MM X 6MTS","CAB-12",""],
            ["CABILLA ESTRIADA 3/8 10MM X 6MTS","CAB-10",""],
            ["CABILLA LISA 12MM X 6MTS","CAB-LISA",""],
            ["CABLE 10 ICONEL THW 100 COBRE","CABLE-10-ICONEL-1G","CABLE-10-ICONEL-1G"],
            ["CABLE 10 TROEN THW 100 COBRE","CABLE-10-TROEN-1G","CABLE-10-TROEN-1G"],
            ["CABLE 12 1GUIA DE COBRE V-TEG","CABLE-1X12 6852","AWG10-WHI 6852"],
            ["CABLE 12 ICONEL THW 100 COBRE","CABLE-12-ICONEL-1G","CABLE-12-ICONEL-1G"],
            ["CABLE 12 SACO 100M COBRE","CABLE-1X12-SACO","CABLE-1X12-SACO"],
            ["CABLE 12 TROEN THW 100 COBRE","CABLE-12-TROEN-1G","CABLE-12-TROEN-1G"],
            ["CABLE 2X10 TROEN SPT 100 COBRE","CABLE-2X10SPT","CABLE-2X10SPT"],
            ["CABLE 2X12 SPT 100M COBRE DISEL TOOLS","CABLE-2X12-SACO","CABLE-2X12-SACO"],
            ["CABLE 2X12 TROEN SPT 100 COBRE","CABLE-2X12SPT","CABLE-2X12SPT"],
            ["CABLE 2X14 TROEN SPT 100 COBRE","CABLE-2X14SPT","CABLE-2X14SPT"],
            ["CABLE 2X16 TROEN SPT 100 COBRE","CABLE-2X16SPT","CABLE-2X16SPT"],
            ["CABLE 3.5MM A RCA","SJX-35","SJX-35"],
            ["CABLE 8 ICONEL THW 100 COBRE","CABLE-8-ICONEL-1G","CABLE-8-ICONEL-1G"],
            ["CABLE 8 TROEN THW 100 COBRE","CABLE-8-TROEN-1G","CABLE-8-TROEN-1G"],
            ["CABLE AUXIALIAR DE SONIDO PLUS- MACHO 1.8 MTS RUN","734896112065","C-STE"],
            ["CABLE AUXILIAR 600AMP INGCO","6925582139891","HBTCP6008"],
            ["CABLE AUXILIAR BATERIA 600AM EMTOP","6972951241617","EBCEL0601"],
            ["CABLE BLANCO USB TIPO C","DC12WK-G-TIPOC",""],
            ["CABLE CARGADOR DOBLE FUNCION USB MICRO-TIPO C TROEN","7453038490931","TR-UMC-A"],
            ["CABLE CARGADOR MICRO USB 1MTS","CABLE CARGADOR","CABLE CARGADOR"],
            ["CABLE CARGADOR USB IPHONE","7453038435949",""],
            ["CABLE COAXIAL RG 6 305MT METALES ALIADOS","CCX0103",""],
            ["CABLE DE CANTV 10MTS","CABLE-CANTV-10MTS",""],
            ["CABLE DE EXTENSION BLANCO 3 TOMAS 5 MTS RUN","736373169326","EXTB05"],
            ["CABLE DE RED 3MTS","CABLE-RED-3MTS","CAT6-3"],
            ["CABLE DE RED 5MTS","CABLE-RED-5MTS","CAT6-5"],
            ["CABLE DE RED INTERNET UTP 305 METROS TROEN","7453010047436","TR-UTP-6CAT-305M"],
            ["CABLE DE RED RJ45 10MT","YD01162000003ZL","CAT6-10"],
            ["CABLE DE RED RJ45 3MT","CABLE-3M","CABLE-3M"],
            ["CABLE DE RED RJ45 3MTS","ZL-3","CAT6-3"],
            ["CABLE DE RED RJ45 5MT","ZL-5M","CAT6-5"],
            ["CABLE DE SUMERGIBLE PLANO 3X10 AWG X METRO 305MTS","CABLE-3X10-S",""],
            ["CABLE DE SUMERGIBLE PLANO 3X8 AWG X METRO 305MTS","CABLE-3X8-S",""],
            ["CABLE MICRO 3A","3A","3A"],
            ["CABLE MICRO DE NYLON COLORES VARIOS","CABLE-MICRO",""],
            ["CABLE MICRO USB 1MTS TROEN ","7453010085124","TR-UM-A"],
            ["CABLE PARA INTERNET CAT6E 15MTRS FUTURO TOOLS","5852868304197","CAT6-15"],
            ["CABLE PARA INTERNET CAT6E 20MTRS FUTURO TOOLS","5852868304203","CAT6-20"],
            ["CABLE PARA TELEFONO 10MTRS FUTURO TOOLS","5852868991304","99130"],
            ["CABLE PARA TELEFONO 15MTRS ","5852868991311","QH641"],
            ["CABLE PARA TELEFONO 20MTRS FUTURO TOOLS","5852868991328",""],
            ["CABLE PARA TELEFONO 5MTRS FUTURO TOOLS","5852868991298","99129"],
            ["CABLE PASA CORRIENTE EXXEL","205000010241","07-003-016"],
            ["CABLE PLUS 3.5 3MT NYLON","CABLE-PLUS-3M","CABLE-PLUS-3M"],
            ["CABLE RCA 1MT","2RCA-1MT",""],
            ["CABLE SPT 2X12 100 COBRE TRIC","2X12-DIESEL","SPT12-COBRE-DIESEL"],
            ["CABLE SPT 2X14 100COBRE DIESEL TOOLS","CABLE-2X14-SACO","CABLE-2X14-SACO"],
            ["CABLE SPT 2X16 100COBRE SACO DIESEL TOOLS","CABLE-2X16","CABLE-2X16"],
            ["CABLE SPT DUPLE 2X12 COBRE ECONOMICO ","2X12-ECONOMICO","2X12-ECONOMICO"],
            ["CABLE THH 12X1 100 COBRE SACO","CABLE-12X1-SACO","CABLE-1X12-SACO"],
            ["CABLE THHN 10X1 100 COBRE PROTONIC","CABLE-10X1-PROTONIC","CABLE-10-PROTONIC-1G"],
            ["CABLE THHN 12X1 100 COBRE PROTONIC","CABLE-12X1-PROTONIC","CABLE-12-PROTONIC-1G"],
            ["CABLE THHN 8X1 100 COBRE PROTONIC","CABLE-8X1-PROTONIC","CABLE-8-PROTONIC-1G"],
            ["CABLE THHW 10X1 100 COBRE CABLESCA","CABLE-10X1-CABLESCA","THHW10100"],
            ["CABLE THHW 12X1 100 COBRE CABLESCA","CABLE-12X1-CABLESCA","THHW12100"],
            ["CABLE THHW 8X1 100 COBRE CABLESCA","CABLE-8X1-CABLESCA","THHW8100"],
            ["CABLE TIPO C LDO-B09","6571254231136","LDO-B09"],
            ["CABLE TIPO PLUS","CABLETIPOPLUS","CABLETIPOPLUS"],
            ["CABLE TN-160 TIPO C ","7897779848758","TN-160"],
            ["CABLE TN-170 TIPO C","7887784987856","TN-170"],
            ["CABLE UBS TIPO C ","T61",""],
            ["CABLE USB DOBLE FUNCION ","7453010059323","TR-UMI-A"],
            ["CABLE USB MICRO LDO-S700","8846113233131",""],
            ["CABLE USB MICRO TN-120","7887478948743",""],
            ["CABLE USB MICRO V9 K1T Y TIPO C","CABLE-USB-MICRO",""],
            ["CABLE USB PARA IPHONE","UBS-IPHONE",""],
            ["CABLE USB TIPO C- TIPO C 1M EMTOP","6941556239596","EUCC02"],
            ["CABLE USB TIPO-C INGCO","6925582126204","IUCC01"],
            ["CABLES AUXILIARES PARA AUTOMOVIL 180 AMP SECURITY","7453078546315","A136-C180"],
            ["CANDADO 30MM CASTOR","7453038482363","PC-43421-30"],
            ["CANDADO 30MM JADEVER","6942210202871","JDPD1430"],
            ["CANDADO 30MM VISALOCK ","7591264924902","91002490"],
            ["CANDADO 32MM ARMOR","6904267774213","05-007-149"],
            ["CANDADO 32MM LOXIT","8499481697698",""],
            ["CANDADO 32MM RIO","CANDADO-32MM-RIO","CANDADO-32MM-RIO"],
            ["CANDADO 38MM LOXIT","8499481697695",""],
            ["CANDADO 38MM RIO","CANDADO-38MM-RIO","CANDADO-38MM-RIO"],
            ["CANDADO 40MM ARCO LARGO JADEVER","6942210203014","JDPD2440"],
            ["CANDADO 40MM BRONCE LION ","140-40MM","140-40MM"],
            ["CANDADO 40MM CASTOR","7453038482370","PC-43421-40"],
            ["CANDADO 40MM JADEVER","6942210202949","JDPD1440"],
            ["CANDADO 40MM VISALOCK ","7591264924926","91002492"],
            ["CANDADO 50MM ARCO LARGO JADEVER","6942210203021","JDPD2450"],
            ["CANDADO 50MM ARCO LARGO VISALOCK ","7591264924933","91002493"],
            ["CANDADO 50MM LOXIT","8499481697694",""],
            ["CANDADO 50MM RIO","CANDADO-50MM-RIO","CANDADO-50MM-RIO"],
            ["CANDADO 50MM VISALOCK ","7591264924940","91002494"],
            ["CANDADO 60MM EXXEL","205000009614","05-007-160"],
            ["CANDADO 60MM JADEVER","6942210202987","JDPD1460"],
            ["CANDADO 65MM VISALOCK ","7591264924964","91002496"],
            ["CANDADO 70MM GLOBAL","7453010090449","7453010090449"],
            ["CANDADO ANTICINZALLA 70MM CASTOR","7453038418171",""],
            ["CANDADO ANTICIZALLA 60MM COVO","7453038432702","CV-BPD-60MM"],
            ["CANDADO ANTICIZALLA 60MM HUMMER ACERO SOLIDO","7453100257998","HUM-1074"],
            ["CANDADO ANTICIZALLA 70MM GRIS AMOR","6904267774251","05-007-153"],
            ["CANDADO ANTICIZALLA 75MM COVO","7453038444255","7453038444255"],
            ["CANDADO ANTICIZALLA 75MM EXXEL","7591508601408","01-408"],
            ["CANDADO ANTICIZALLA 80MM LIEBAO ","KV'MX2'0002","KV-MX2-0002"],
            ["CANDADO ANTICIZALLA 80MM PROVAL","7453012355201","PRO497-80"],
            ["CANDADO ANTICIZALLA 90MM COVO","7453010064785","7453010064785"],
            ["CANDADO ANTICIZALLA 90MM LIEBAO ","KV'MX2'0003","KV-MX2-0003"],
            ["CANDADO ANTICIZALLA BLINDADO DE 94MM/ LLAVE ASTRAL","7451304213024","ZCE-5487CH"],
            ["CANDADO ANTICIZALLA BRONCE 90MM EXXEL","7591508901409","01-409"],
            ["CANDADO ANTICIZALLA CASTOR 80MM","CANDADO-CASTOR","CANDADO-CASTOR"],
            ["CANDADO ANTICIZALLA DE BRONCE 60MM INGCO","6925582103762","DBBPL0602"],
            ["CANDADO ANTICIZALLA DE HIERRO 60MM RUN","734896111877","CHAZ60"],
            ["CANDADO ANTICIZALLA DE HIERRO 70MM ARMOR","205000008614","05-007-139"],
            ["CANDADO ANTICIZALLA DE HIERRO 70MM RUN","734896111884","CHAZ70"],
            ["CANDADO ANTICIZALLA DE HIERRO 80MM ARMOR","205000008611","05-007-138"],
            ["CANDADO ANTICIZALLA DE HIERRO 80MM ZOE","7451304213727","ZCE-1895EV"],
            ["CANDADO ANTICIZALLA DE HIERRO 90MM ARMOR","205000008608","05-007-137"],
            ["CANDADO ANTICIZALLA DE HIERRO 90MM ZOE","7451304213734","ZCE-1269EW"],
            ["CANDADO ANTICIZALLA DE HIERRO PULIDO 70MM RUN","734896118746","CHPUAZ70"],
            ["CANDADO ANTICIZALLA DE HIERRO PULIDO 80MM RUN","734896111372",""],
            ["CANDADO ANTICIZALLA DE HIERRO SECURITY","7453078542157","CANDADO-SECURITY"],
            ["CANDADO ANTICIZALLA GRIS 80MM ARMOR","6904267774268","05-007-154"],
            ["CANDADO ANTICIZALLA GRIS 90MM ARMOR","6904267774275","05-007-155"],
            ["CANDADO DE ACERO ANTIPALANCA 40MM EMTOP","6941556226251","EPDKS4004"],
            ["CANDADO DE ACERO ANTIPALANCA 50MM EMTOP","6941556226282","EPDKS5004"],
            ["CANDADO DE ACERO ANTIPALANCA 60MM EMTOP","6941556226312","EPDKS6004"],
            ["CANDADO DE ACERO ANTIPALANCA 70MM EMTOP ","6941556226534","EPDKS7004"],
            ["CANDADO DE ACERO TIPO ARCO 32MM VERT","9780265216200","CNB-32"],
            ["CANDADO DE ACERO TIPO ARCO 38MM VERT","9780265216217","CNB-38"],
            ["CANDADO DE BLOQUE DE LATON 60MM EMTOP ","6941556218508","EPDKH6001"],
            ["CANDADO DE BLOQUE DE LATON 80MM EMTOP ","6941556208745","EPDKH8001"],
            ["CANDADO DE BLOQUE DE LATON 90MM EMTOP","6941556206192","EPDKH9001"],
            ["CANDADO DE BLOQUE LATON 70MM EMTOP","6941556210687","EPDKH7001"],
            ["CANDADO DE BRONCE 30MM RUN","734896111839","CBPL30"],
            ["CANDADO DE BRONCE 50MM RUN","734896111853","50MM"],
            ["CANDADO DE BRONCE 60MM RUN","734896111860","60MM"],
            ["CANDADO DE HIERRO 25MM CASTOR","7453010081379","7A136-YHS-25"],
            ["CANDADO DE HIERRO 30MM CASTOR ","7453010081386","7A136-YHS-30"],
            ["CANDADO DE HIERRO 32MM EMTOP","6941556205485","EPDKR3001"],
            ["CANDADO DE HIERRO 32MM GANCHO REFORZADO COVO","7453029100504","CV-PDI-32MM"],
            ["CANDADO DE HIERRO 32MM INGCO","6928073680452","DIPLO301"],
            ["CANDADO DE HIERRO 38MM EMTOP","6941556218379","EPDKR4001"],
            ["CANDADO DE HIERRO 38MM GANCHO REFORZADO COVO","7453038426114","CV-PDI-38MM"],
            ["CANDADO DE HIERRO 38MM ZOE","7451304213673","ZCE-4527EN"],
            ["CANDADO DE HIERRO 40MM CASTOR","CAN-CAS-40MM","CANDADO-CASTOR-40MM"],
            ["CANDADO DE HIERRO 50MM CASTOR ","7453010081409","7A136-YHS-50"],
            ["CANDADO DE HIERRO 50MM EMTOP","6941556216207","EPDKR5001"],
            ["CANDADO DE HIERRO 63MM EMTOP ","6941556213336","EPDKR6001"],
            ["CANDADO DE HIERRO 63MM GANCHO REFORZADO COVO","7453038442848","CV-PDI-63MM"],
            ["CANDADO DE HIERRO 75MM EMTOP ","6941556212841","EPDKR7001"],
            ["CANDADO DE HIERRO ARCO 45MM COVO","7453038443289","CV-PDI-45MM"],
            ["CANDADO DE HIERRO CROM 63MM RUN","734896111983","CHPL63"],
            ["CANDADO DE HIERRO CROMADO 50MM RUN","734896111976","CHPL50"],
            ["CANDADO DE HIERRO GRILLETE LARGO 32MM EMTOP","6941556223724","EPDKR3001L"],
            ["CANDADO DE HIERRO GRILLETE LARGO 38MM EMTOP","6941556221447","EPDKR4001L"],
            ["CANDADO DE HIERRO GRILLETE LARGO 50MM EMTOP","6941556207731","EPDKR5001L"],
            ["CANDADO DE HIERRO GRILLETE LARGO 63MM EMTOP ","6941556213619","EPDKR6001L"],
            ["CANDADO DE HIERRO RESISTENTE 50MM EMTOP","6941556226473","EPDKP5005"],
            ["CANDADO DE HIERRO RESISTENTE 65MM EMTOP","6941556226503","EPDKP6505"],
            ["CANDADO DE LATON 3 DIGITOS 3X20MM EMTOP","6941556218621","EPDKC2033"],
            ["CANDADO DE LATON 3 DIGITOS 3X30MM EMTOP","6941556205973","EPDKC3033"],
            ["CANDADO DE LATON 30MM EMTOP","6941556218720","EPDKB3021"],
            ["CANDADO DE LATON 4 DIGITOS 4X40MM EMTOP","6941556218133","EPDKC4043"],
            ["CANDADO DE LATON 40MM EMTOP","6941556203252","EPDKB4021"],
            ["CANDADO DE LATON 50MM EMTOP","6941556203283","EPDKB5021"],
            ["CANDADO DE LATON ARCO LARGO 20MM EMTOP","6941556223700","EPDKB2021L"],
            ["CANDADO DE LATON ARCO LARGO 30MM EMTOP","6941556223434","EPDKB3021L"],
            ["CANDADO DE LATON ARCO LARGO 50MM EMTOP","6941556213435","EPDKB5021L"],
            ["CANDADO DE LATON ARCO LARGO 50MM EMTOP","6941556210663","EPDKB4021L"],
            ["CANDADO DE LATON ARCO LARGO 60MM EMTOP","6941556208714","EPDKB6021L"],
            ["CANDADO DE LATON ARCO LARGO 70MM EMTOP ","6941556212872","EPDKB7021L"],
            ["CANDADO DE SEGURIDAD PARA BICICLETA SECURITY","7453010012380","TY-811"],
            ["CANDADO DORADO HIERRO 40MM ARMOR","2050000086236","05-007-142"],
            ["CANDADO DORADO HIERRO 50MM ARMOR","2050000086205","141PN20"],
            ["CANDADO DORADO HIERRO 60MM ARMOR","2050000086175","05-007-140"],
            ["CANDADO GLOBAL 90MM","7453010030735","A307-PD12010-90MM"],
            ["CANDADO LAMINADO 40MM EMTOP 121","6941556226343","EPDKL4006"],
            ["CANDADO LAMINADO 50MM EMTOP","6941556226374","EPDKL5006"],
            ["CANDADO LAMINADO 50MM HUMMER","7453100258018","HUM-1076"],
            ["CANDADO PADLOCK 50MM ARMOR","6904267774237","05-007-151"],
            ["CANDADO PADLOCK 63MM ARMOR 05-007-152","6904267774244","05-007-152"],
            ["CANDADO PLANO DE BRONCE 25MM ZOE","7451304212959","ZCE-6664CA"],
            ["CANDADO PLANO DE BRONCE 30MM ZOE","7451304212966","ZCE-1799CB"],
            ["CANDADO PLANO DE BRONCE 40MM RUN","734896111846","CBPL40"],
            ["CANDADO PLANO DE BRONCE 40MM ZOE","7451304212973","ZCE-5641CC"],
            ["CANDADO PLANO DE BRONCE 50MM ZOE","7451304212980","ZCE-5975CD"],
            ["CANDADO RUN 90MM","CANDADO-90MM","CANDADO-90MM"],
            ["CANDADO SECURITY PARA BICICLETA 74CM","7453010049683","TY-811-M"],
            ["CANDADO TIPO ARCO DE BRONCE 30MM INGCO","6925582103816","DBPLO302"],
            ["CANDADO TIPO ARCO DE BRONCE 40MM INGCO","6925582103823","DBPL0402"],
            ["CANDADO TIPO ARCO DE BRONCE 50MM INGCO","6925582103830",""],
            ["CANILLA 1/2 X 1/2 MAS LLAVE DE ARRESTO METALES ALIADOS","7592978008469","COMBO-MA1"],
            ["CANILLA 1/2 X 1/2 PLASTICA FEMOI","7595519000014","C0001"],
            ["CANILLA 1/2 X 5/8 MAS LLAVE DE ARRESTO METALES ALIADOS","7592978393626-L","COMBO-MA2"],
            ["CANILLA 1/2 X 5/8 PLASTICA FEMOI","7595519000021","C0002"],
            ["CANILLA 1/2 X 7/8 PVC GRIVEN","7453038423656","GVB-THS40-1-2"],
            ["CANILLA DE MALLA 1/2 x 1/2","5689235435860",""],
            ["CANILLA DE MALLA 1/2 x 15/8 INFINITY","6933810455989",""],
            ["CANILLA DE PLASTICO 1/2X1/2","734896112591","TUF01"],
            ["CANILLA DE PLASTICO 40CM 1/2X7/8 RUN","734896112607","TUF02"],
            ["CANILLA FLEXIBLE 1/2 X 1/2 GRIVEN","7453038490320","A367-HSB20-1-2"],
            ["CANILLA FLEXIBLE DE PLASTICO 1/2X1/2 KOBATEX","4627876159982","KBT-CA-1001"],
            ["CANILLA FLEXIBLE GRIVEN PLASTICA 1/2X1/2 50CM","7453009013008",""],
            ["CANILLA FLEXIBLE METALICA 50CM 1/2 X 1/2 RUN","734896112638",""],
            ["CANILLA FLEXIBLE TEJIDA 50CM DE 1/2 X 1/2 RUN","734896112621","TUF04"],
            ["CANILLA P/POCETA DE METAL 1/2 x 5/8","CANILLADEMETAL",""],
            ["CANILLA PARA POCETA METALES ALEADOS 25CM 1/2X7/8","CANILLA-MA-1/2X7/8","8008728"],
            ["CANILLA PLASTICA 1/2x1/2","CANILLAPLASTICA1/2X1",""],
            ["CANILLA PLASTICA 1/2x1/2 GRIVAL","7706157380134","380130001"],
            ["CANILLA PLASTICA 1/2X1/2 METALES ALEADOS","CAN-0401","CAN-0401"],
            ["CANILLA PLASTICA DE 60MM 1/2X5/8 PLASTICA METALES ALEADOS","CAN-0102","CAN-0102"],
            ["CANILLA PLASTICA METALES ALEADOS PARA LAVAMANO 1/2 X 1/2","7592978393619","CAN-0201"],
            ["CANILLA PLASTICA PARA POCETA 1/2 X 5/8 METALES ALIADOS","7592978393626","CAN-0202"],
            ["CANILLA TIPO T 1/2 X 1/2 GRIVEN","7453010093020","GV-HST75-1-2"],
            ["CEMENTO BLANCO ARGOS 20KG","CEMENTO-BLANCO","CEMENTO-BLANCO"],
            ["CEMENTO CONTACTO ABRACOL 250ML","ABRACOL-250CC","ABRACOL-250CC"],
            ["CEMENTO CONTACTO ABRACOL 375ML","ABRACOL-375CC","ABRACOL-375CC"],
            ["CEMENTO CONTACTO PEGA SOLD 43 1/32G","7592203430515","PEGASOLD"],
            ["CEMENTO CONTACTO PEGA SOLD 43 1/4","PEGASOLD-43-1/4","PEGASOLD43"],
            ["CEMENTO CONTACTO X 120ML ABRACOL","30020502","PEGA1-0120"],
            ["CEMENTO DE CONTACTO 1 BELL POWER","CEMENTO DE CONTACTO 1",""],
            ["CEMENTO DE CONTACTO 240ML BELL POWER","7594000462171","7V0240"],
            ["CEMENTO DE CONTACTO ABRACOL 250ML ","7707353933704",""],
            ["CEMENTO DE CONTACTO BELL POWER 120MM .","7599442000011",""],
            ["CEMENTO DE CONTACTO BELPOWER 1/4 GLM","7594000461112",""],
            ["CEMENTO DE CONTACTO PEGA SOLD 43X1/8 ","7592203430522","30004300002"],
            ["CEMENTO GRIS POR KILO","CEMENTO-GRIS-KILO",""],
            ["CEMENTO GRIS SACO 42.5KG","CEMENTO-GRIS","CEMENTO-GRIS"],
            ["CEMENTO PARA PVC 1/32G, PEGA SOL 300","7592203300511",""],
            ["CEMENTO PLASTICO DE CUÑETE 4G BITUPLAST","7592092000820",""],
            ["CEMENTO PLASTICO DE GALON 1G BITUPLAST","7592092000837",""],
            ["CEMENTO PLASTICO TAPA GOTERA 1/4 SENSACOLOR","CEMENTO-PLASTICO-1/4","20510-00"],
            ["CEMENTO PLASTICO TAPA GOTERA DE 1G SENSACOLOR","CEMENTO-PLASTICO-1G","20510-01"],
            ["CERCHA C-15CM X 6MTRS","CERCH-15CM","CERCH-15CM"],
            ["CERCHA N°10 X 6MTS","CERCHA",""],
            ["CERRADURA CERROJO CROMADO 45MM COVO","7453038482684","D-101B-SS"],
            ["CERRADURA CERROJO DOBLE BROCE COVO","7453010006426","JM-102B-AC"],
            ["CERRADURA CILINDRO FIJO 1 PASADOR COVO","7453038475112",""],
            ["CERRADURA CILINDRO FIJO GATER ECONOMICA ","6931679303014","007C.FDER"],
            ["CERRADURA CILINDRO FIJO LEO SECURITY HOME","7450029088825","04-FR-18885"],
            ["CERRADURA CILINDRO FIJO SECURYTI","7453010073749","60121"],
            ["CERRADURA CILINDRO FIJO, VERDE DERECHA RABBIT","7453050012128","RB-50121-R"],
            ["CERRADURA CILINDRO/MARIPOSA ACERO INOX ZOE","7451304212591","ZCE-8885AP"],
            ["CERRADURA CILINDRO/MARIPOSA DORADO ZOE","7451304212607","ZCE-7453AQ"],
            ["CERRADURA DE BAÑO ACERO BRILL CJ","734896113666","CERO103"],
            ["CERRADURA DE BAÑO ACERO INOX CJ","0734896113642","CERO101"],
            ["CERRADURA DE EMBUTIR 25MM EXXEL","2000000371016","37-101"],
            ["CERRADURA DE EMBUTIR 35MM COVO","7453038412179","CV-SLOCK-35MM"],
            ["CERRADURA DE EMBUTIR 35MM RABBIT","679231557272","RB-35MM-BL"],
            ["CERRADURA DE EMBUTIR 35MM SECURITY ","7453038426770","A307-L7365-S"],
            ["CERRADURA DE EMBUTIR 45MM MARTECH LGM","MARTECH-45MM","MARTECH-45MM"],
            ["CERRADURA DE EMBUTIR SECURITY 16MM","7453038489775","A307-F1684G-16"],
            ["CERRADURA DE GAVETA COVO 2 CROMADA","74530100045494","CV-DL600N"],
            ["CERRADURA DE HAB/OCIFINA ACERO BRILL BLIST","734896113727","CERO109"],
            ["CERRADURA DE HAB/OFICINA ACERO BRILL BLIST","734896113680","CERO105"],
            ["CERRADURA DE HAB/OFICINA ACERO BRLL CJ","734896113659","CERO102"],
            ["CERRADURA DE HAB/OFICINA ACERO INOX BLIST","734896113697","CERO106"],
            ["CERRADURA DE HAB/OFICINA ACERO INOX BLIST","734896113710","CERO108"],
            ["CERRADURA DE HAB/OFICINA ACERO INOXIDABLE","734896114175","CERO129"],
            ["CERRADURA DE HAB/OFICINA ACERO INOXIDABLE","734896114168","CERO128"],
            ["CERRADURA DE HAB/OFICINA BRILL BLIST","734896113703","CERO107"],
            ["CERRADURA DE HAB/OFICINA DE ACERO INOX BLIST","734896113673","CERO104"],
            ["CERRADURA DE MANILLA 45MM COVO","7453038483339","DL-6411B-SN"],
            ["CERRADURA DE MANILLA BLACK COVO","7453038406031","DL-6411B-ORB"],
            ["CERRADURA DE MANILLA DE HA/OFICINA ACERO INOXIDABLE","734896114205","CERO132"],
            ["CERRADURA DE MANILLA DE HAB/OFICINA ACERO BLIST","734896113857","CERO117"],
            ["CERRADURA DE MANILLA DE HAB/OFICINA ACERO INOX BLIST","734896113895","CERO121"],
            ["CERRADURA DE MANILLA DE HAB/OFICINA BRILL BLIST","734896113888","CERO120"],
            ["CERRADURA DE MANILLA DE HAB/OFICINA BRILL BLIST","734896113864","CERO"],
            ["CERRADURA DE MANILLA DE HAB/OFICINA DE ACERO BRILLANTE","734896114212","CERO133"],
            ["CERRADURA DE MANILLA DE HAB/OFICINA INOX BLIST","734896113871","CERO119"],
            ["CERRADURA DE MANILLA DE HAB/OFICINANEGRO MATE","734896114229","CERO134"],
            ["CERRADURA DE MANILLA IZQUIERDA 45MM COVO","7453038494397","DL-6971B-SN-L"],
            ["CERRADURA DE MANILLA MADERA CLARAHABI-OFIC RUN","7591996006051",""],
            ["CERRADURA DE POMO ARMOR","205000009518","05-008-157"],
            ["CERRADURA DE POMO ARMOR BRONCE ","205000009516","05-008-156"],
            ["CERRADURA DE POMO ARMOR CROMADA ","205000009514","05-008-155"],
            ["CERRADURA DE POMO ARMOR NEGRO ","205000009520","PN202205"],
            ["CERRADURA DE POMO BRONCE EXXEL","205000009528","05-008-162"],
            ["CERRADURA DE POMO ECONOMICA HUMMER","7453100258049","HUM-1079"],
            ["CERRADURA DE POMO LATON ANTIGUO PARA DORMITORIO SACO","7453006060593","04-FR-11590"],
            ["CERRADURA DE POMO P/HABITACION SECURITY","7453038468862","DL-587-S-S"],
            ["CERRADURA DE POMO P/PUERTAS DE MADERA KOBATEX ","6928456254263","CERR/POM/AL"],
            ["CERRADURA DE POMO PARA BAÑO BRONCE COVO","7453038473330","DL-6872BK-AC"],
            ["CERRADURA DE POMO PARA BAÑO HUMMER","7453100258056","HUM-1080"],
            ["CERRADURA DE POMO PARA BAÑO SATINADA COVO","7453010060619","DL-6872BK-AB"],
            ["CERRADURA DE POMO PARA DORMITORIO BRONCE SECURITY ","7453038443869","DL-587-AC"],
            ["CERRADURA DE POMO PARA DORMITORIO COVO","7453038479219","DL-6871B-AB"],
            ["CERRADURA DE POMO PARA DORMITORIO COVO","7453038480529",""],
            ["CERRADURA DE POMO PARA DORMITORIO SECURITY ","7453078538877","DL-587-S/S-P"],
            ["CERRADURA DE POMO PLATEADA HUMMER","7453100258117","HUM-1086"],
            ["CERRADURA DE POMO PLATEADA RZEO","4547675012168",""],
            ["CERRADURA DE POMO RABBIT","679231557739",""],
            ["CERRADURA DE POMO TIPO COPA HUMMER","7453100258063","HUM-1081"],
            ["CERRADURA DE POMO TIPO ESFERA EXXEL","205000009530","05-008-163"],
            ["CERRADURA DE POMO/ACERO INOXIDABLE ZOE","7451304214557","ZCE-7649AL"],
            ["CERRADURA DE SEGURIDAD CONTI","6975202307186","CDS-20-50"],
            ["CERRADURA DE SOBREPONER DERECHA P/METAL HUMMER","7453100258087","HUM-1083"],
            ["CERRADURA DE SOBREPONER DERECHA ZOE","7451304214717","ZCE-7236BC"],
            ["CERRADURA DE SOBREPONER DERECHO 1 PASADOR CILINDRICO COVO","7453010048556","50121-1MC"],
            ["CERRADURA DE SOBREPONER DIENTE DE PERRO CISA","7591264017260","SKU12640004"],
            ["CERRADURA DE SOBREPONER IZQUIERDA 3 PASADORES COVO","7453038440363",""],
            ["CERRADURA DE SOBREPONER IZQUIERDA ZOE","7451304214724","ZCE-4756BD"],
            ["CERRADURA DIENTE DE PERRO COVO","7453038411912","CV-TCLK-BL"],
            ["CERRADURA EXXEL CILINDRO FIJO","8005344014272","05-010-006"],
            ["CERRADURA HAB/OFICINA ACERO INOX CJ","0734896113635","CERO100"],
            ["CERRADURA MANILLA PLATEADA NATURAL MOVIL.. MAXI TOOLS","6906503021198","MT-8507ET"],
            ["CERRADURA P/VITRINA BEST VALUE","7453001110989","C17098"],
            ["CERRADURA PARA CAJON COVO","7453010045494","CV-DL600N"],
            ["CERRADURA PARA DORMITORIO DE POMO COBRE COVO","7453038447928","DL-3871-AC"],
            ["CERRADURA PARA DORMITORIO DE POMO LATON COVO","7453038440752","DL-3871-AB"],
            ["CERRADURA PARA DORMITORIO DE POMO SATINADO COVO","7453038444613","DL-3871-SS"],
            ["CERRADURA PARA EMBUTIR 25MM CISA","7591264051639","12640747"],
            ["CERRADURA PARA EMBUTIR 25MM COVO","7453038418447","CV-SLOCK-25MM"],
            ["CERRADURA PARA EMBUTIR 25MM INGCO","6928073677872","DML258521"],
            ["CERRADURA PARA EMBUTIR 35MM CISA","7591264051622","12640746"],
            ["CERRADURA PARA EMBUTIR 35MM EXXEL","2000000371023","05-011-003"],
            ["CERRADURA PARA GABINETE. RABBIT","CERRADURA-GABINETE","401S"],
            ["CERRADURA PARA GAVETA SECURITY","7453038477543","A136-CJ105CP"],
            ["CERRADURA PARA PUERTA DE HIERRO CISA","7591264000811",""],
            ["CERRADURA PARA PUERTA DE HIERRO CISA ECONOMICA","7591264033871","7591264033871"],
            ["CERRADURA PARA VITRINA SECURITY","7453010051440","602-120"],
            ["CERRADURA POMO DE BRONCE SECURITY","7453038440561","DL-587-AB"],
            ["CERRADURA POMO DE PLATA ZETA LOCK","7450029091580","04-FR-19161"],
            ["CERRADURA SOBREPONER DERECHA COVO","7453038425414","CV-TCLK-BX"],
            ["CERROJO ARMOR 101 BN NEGRO NIQUEL ","205000009540","05-008-168"],
            ["CERROJO ARMOR 101 CROMO PULIDO ","05-008-166",""],
            ["CERROJO ARMOR 101 DORADO ","205000009538","05-008-167"],
            ["CERROJO ARMOR 102 CROMO PULIDO ","205000009548","05-008-172"],
            ["CERROJO ARMOR 102 DORADO ","05-008-173",""],
            ["CERROJO ARMOR 102 NEGRO NIQUEL ","205000009552","05-008-174"],
            ["CERROJO CILINDRO MARIPOSA 45MM BRONCE COVO","7453038482677","D-101B-AC"],
            ["CERROJO CILINDRO MARIPOSA 45MM NEGRO MATE COVO","7453038445467","D-101B-ORB"],
            ["CERROJO LLAVE ACERO BRILL BLIST RUN","734896113765","CER0113"],
            ["CERROJO LLAVE BRONCE BRILL BLIST RUN","734896113741","CER0111"],
            ["CERROJO LLAVE LLAVE NEGRO MATE","734896114199","CERO131"],
            ["CERROJO LLAVE MARIPOSA SECURITY","7453010014650","D-101A-B"],
            ["CERROJO LLAVE PERILLA NEGRO MATE","734896114182","CERO130"],
            ["CERROJO LLAVE-LLAVE ACERO BRILL BLIST","734896113772","CERO114"],
            ["CERROJO LLAVE-PERILLA ACERO INOX BLIST","734896113734","CERO110"],
            ["CERROJO LLAVE-PERILLA DORADO MATE BLIST","734896113758","CERO112"],
            ["CLAVO DE ACERO 1","CLAVO-ACERO-1",""],
            ["CLAVO DE ACERO 1/2","CLAVO-ACERO-1/2",""],
            ["CLAVO DE ACERO 1/2 BOLSITA ","CLAVO-ACERO-1PQT",""],
            ["CLAVO DE ACERO 2","CLAVO-ACERO-2",""],
            ["CLAVO DE ACERO 3","CLAVO-ACERO-3",""],
            ["CLAVO DE ACERO 3/4","CLAVO-ACERO-3/4",""],
            ["CLAVO DE ACERO 3/4 BOLSITA ","CLAVO-ACERO-3/4PQT",""],
            ["CLAVO DE ACERO 4","CLAVO-ACERO-4",""],
            ["CLAVO DE ACERO DE 3 1/2","7453118006960","Z-K3.5"],
            ["CLAVO DE ACERO PARA CONCRETO 1 1/2","CLAVO-ACERO-11/2",""],
            ["CLAVO DE CONCRETO 3 1/2","CLAVO-ACERO-3 1/2",""],
            ["CLAVO DULCE DE 1","CLAVO-1",""],
            ["CLAVO DULCE DE 1 1/2","CLAVO-1-1/2",""],
            ["CLAVO DULCE DE 2","CLAVO-2",""],
            ["CLAVO DULCE DE 2 1/2","CLAVO-2-1/2",""],
            ["CLAVO DULCE DE 3","CLAVO-3",""],
            ["CLAVO DULCE DE 3 1/2","CLAVO-3-1/2",""],
            ["CLAVO DULCE DE 4","CLAVO-4",""],
            ["CLAVO DULCE DE 5","CLAVO-5",""],
            ["COCHA DE BIELA DIESEL 5HP 170F STD","COCNHA DE BIELA 5HP-STD","COCNHA DE BIELA 5HP-STD"],
            ["COCINA 1HORNILLA HOT PLATE 1000W","WY-02","WY-02"],
            ["COCINA 4H CON HORNO ROYAL REAL","COCINA-4H-HORNO-ROYAL","RR-K581"],
            ["COCINA 4H DE ACERO INOXIDABLE OMEGA","OEG-4TIC","OEG-4TIC"],
            ["COCINA 4H DELUXE CONDESA","COND-SS20S-23","COND-SS20S-23"],
            ["COCINA 4H GRIS MYSTIC","COC-4H-MYSTIC-G","PNCC-G"],
            ["COCINA 4H NEGRA MYSTIC","COC-4H-MYSTIC-N","PNCC-B"],
            ["COCINA 5H DELUXE CONDESA","COND-SS30B-23","COND-SS30B-23"],
            ["COCINA 6 HORILLAS C/TAPA DE VIDRIO GRIS OMEGA","OEG-6TGC","OEG-6TGC"],
            ["COCINA 6 HORNILLAS C/TAPA DE VIDRIO BLANCA OMEGA","OEG-6TWC","OEG-6TWC"],
            ["COCINA 6 HORNILLAS C/TAPA DE VIDRIO DE ACERO INOXIDABLE OMEGA","OEG-6TIC","OEG-6TIC"],
            ["COCINA 6 HORNILLAS C/TAPA DE VIDRIO NEGRO OMEGA","OEG-6TBC","OEG-6TBC"],
            ["COCINA A GAS 2 HORNILLAS VH","VH-002SB","VH-002SB"],
            ["COCINA A GAS 2H BADER CON ENCENDIDO ELECTRICO ","COCINA-2H-BADER",""],
            ["COCINA A GAS 3H VH VENE","6903812811311","VH-003SB"],
            ["COCINA A GAS 4 HORNILLAS PHILCO","PHC500BWCTR","PHC500BWCTR"],
            ["COCINA A GAS 4H CONDESA","300001534",""],
            ["COCINA A GAS 4H DE MESA ROYAL REAL ","REE-4-3","REE-4-3"],
            ["COCINA A GAS 4H TAPA DE VIDRIO OMEGA","COC-4H-OMEGA","OEG-4h"],
            ["COCINA A GAS 4H VH VENE","6903812811458","VH-004BW"],
            ["COCINA A GAS 5H CONDESA","300001531",""],
            ["COCINA A GAS 5H FERRETTI 90 LPG DELUXE","COCINA-5H-FERRETI-LPG",""],
            ["COCINA A GAS 5H FERRETTI NG Y LPG APTO","COCINA-5H-FERRETI-NG",""],
            ["COCINA A GAS 5H MYSTIC TOPACIO PLUS","MY-STP5QT410",""],
            ["COCINA A GAS 6H JAGUAR FERRETI ","COCINA-JAGUAR-6H-30","CANNES 90 6H"],
            ["COCINA A GAS 6HORNILLA OMEGA TAPA DE VIDRIO","COC-6H-OMEGA-H","OEG-6RBG"],
            ["COCINA A GAS CON HORNO PREMIER","EF8310NG204B","EF8310NG204B"],
            ["COCINA A GAS CONDESA 5H ","COCINA-CONDESA 5H",""],
            ["COCINA A GAS GPLUS 4H","GP-C0C20",""],
            ["COCINA A GAS JAGUAR 2H DE 20 +2PLATO.","C20SSACERO",""],
            ["COCINA A GAS JAGUAR 3H DE 20 +1PLATO","C20NEMBLACK",""],
            ["COCINA A GAS JAGUAR 3H DE 20 +1PLATO","4H-JAGUAR","C20SILVER"],
            ["COCINA A GAS JAGUAR 6H ","COCINA-JAGUAR-6H",""],
            ["COCINA A GAS KALED CON HORNO 4H","1000021838",""],
            ["COCINA A GAS MYSTIC 2H DE MESA CON ENCENDIDO ELECTRICO","COCINA-MYSTIC-2H",""],
            ["COCINA A GAS OMEGA 4H BLANCA C/TAPA DE VIDRIO","OEG-4TWC","OEG-4TWC"],
            ["COCINA A GAS OMEGA 4H GRIS ","4H-OMEGA-GRIS","OEG-4RGG"],
            ["COCINA A GAS OMEGA 4H GRIS C/TAPA DE VIDRIO","OEG-4TGC","OEG-4TGC"],
            ["COCINA A GAS OMEGA 4H NEGRO C/TAPA DE VIDRIO","OEG-4TBC","OEG-4TBC"],
            ["COCINA A GAS OMEGA 6HOR C/ENCENDIDO","OEG-6RIA",""],
            ["COCINA A GAS ROYAL REAL 1 H ","RE-1-1","RE-1-1"],
            ["COCINA A GAS SJ GRIS 4H 20 ","COCINA-GRIS-SJ","COCINA-GRIS-SJ"],
            ["COCINA A GAS SJ-ELECTRONIC BLANCO 4H 20","COCINA-BLANCO-SJ","SJ-20B4H"],
            ["COCINA A GAS SJ-ELECTRONIC NEGRA 5H","COCINA-NEGRO-SJ","SJ-30N5H"],
            ["COCINA BAITI DE MESA 2H A GAS CON TAPA","GSE-2-4",""],
            ["COCINA BAITI DE MESA 3H A GAS","GST-T30",""],
            ["COCINA BAITI DE MESA 3H A GAS","GST-T23",""],
            ["COCINA BAITI DE MESA 3H A GAS","GST-T22",""],
            ["COCINA BAITI DE MESA 3H A GAS","GST-T26",""],
            ["COCINA BAITI DE MESA 3H A GAS CON TAPA","GSE-3-3",""],
            ["COCINA BAITI DE MESA 4H A GAS CON TAPA","GSE-4-3",""],
            ["COCINA CON HORNO 4H VENE HOGAR","VH-522W","VH-522W"],
            ["COCINA DE GAS 5H ROYAL REAL ","RR-29",""],
            ["COCINA DE GAS 5H ROYAL REAL LUJOSA","RR-23",""],
            ["COCINA DE HORNO GPLUS 6H","GP-C0C30",""],
            ["COCINA DE MESA 3H MILEXUS","MLEG-3Q9900","MLEG-3Q9900"],
            ["COCINA DE MESA 3H ROYAL ","GSE-3-6",""],
            ["COCINA DE MESA A GAS 2H KR","COCINA-2H-KR",""],
            ["COCINA DE MESA A GAS 2H ROYAL REAL","COCINA-ROYAL-2H","RRE-2-6"],
            ["COCINA DE MESA A GAS 3H ROYAL REAL","COCINA-ROYAL-3H","RRE-3-3"],
            ["COCINA DE MESA A GAS 4H ROYAL REAL","COCINA-4H-ROYALREAL","RRE-4-3"],
            ["COCINA DE MESA DE 2HORNILLAS STARLUX","5261220360021",""],
            ["COCINA DE MESA DE 4H BLANCA OMEGA ","7452022619044",""],
            ["COCINA ELECTRICA 1H JAGUAR 1000W ROJO-AZUL","COCINA-ELECTRICA-1H-JAGUAR",""],
            ["COCINA ELECTRICA 1H JAGUAR 1500W GRIS","COCINA-ELECTRICA-1H-1500W",""],
            ["COCINA ELECTRICA 2H JAGUAR 2000W ROJO-AZUL","COCINA-ELECTRICA-2H-2000W",""],
            ["COCINA ELECTRICA 2H JAGUAR 2500W GRIS","COCINA-ELECTRICA-2H-1500W",""],
            ["COCINA ELECTRICA COWPLANDT 2H 2000W ","CE2HCP-2000W","CE2HCP-2000W"],
            ["COCINA ELECTRICA DOBLE HORNILLA 2000W WATTS.","COCINA-ELEC-WATTS","SY-HY6243"],
            ["COCINA ELECTRICA KR PLUS 2H","COCINA-2H-KRPLUS","COCINA-2H-KRPLUS"],
            ["COCINA G DELUXE 20 TAPA DE VIDRIO 4H NEGRA","GDXCO20N","GDELUXE"],
            ["COCINA G DELUXE 20 TAPA LUXURY 4H GRIS","GDXC02OS","GDELUXE"],
            ["COCINA GAS 4H DE MESA OMEGA CON RESPALDO TOPE/ACERO BLANCA OEG-4MRW","OEG-4MW",""],
            ["COCINA GAS 4H DE MESA OMEGA CON RESPALDO TOPE/ACERO GRIS OEG-4MRG ","OEG-4MRG",""],
            ["COCINA GAS 4H DE MESA OMEGA CON RESPALDO TOPE/ACERO NEGRA OEG-4MRB ","OEG-4MB",""],
            ["COCINA GPLUS 4H SILVER","GPCC1003523",""],
            ["COCINA KR 3H A GAS ","KR-3H",""],
            ["COCINA KR 4H A GAS ","KR-4H",""],
            ["COCINA MYSTIC A GAS 6H PARRILLA GRUESA CHAMPAGNE ","MY-ST6QZ700",""],
            ["COCINA PREMIER A GAS CON&nbsp; SOPORTE","EF-7742G","EF-7742G"],
            ["COCINA TOPE A GAS VENE HOGAR 5H","VH-9001GB-C12","VH-9001GB-C12"],
            ["CODO A/N 2","CODO-A/N-2","CODO-A/N-2"],
            ["CODO A/N 3","CODO-A/N-3","CODO-A/N-3"],
            ["CODO A/N 4 ","CODO-A/N-4","CODO-A/N-4"],
            ["CODO CACHIMBO GALVANIZADO 1/2","CODO-CACHIMBO 1/2","CODO-CACHIMBO 1/2"],
            ["CODO CACHIMBO GALVANIZADO ENTRADA HEMBRA SALIDA MACHO 1/2","CODO-G-CACHIMBO-1/2",""],
            ["CODO CACHIMBO GALVANIZADO ENTRADA HEMBRA SALIDA MACHO 3/4","CODO-G-HM-3/4","CODO-G-HM-3/4"],
            ["CODO DE FUMIGADORA DE CAÑON 12L/15L/20L","CODO-CAÑON","CODO-CAÑON"],
            ["CODO GALVANIZADO 1","CODO-G-1","CODO-G-1"],
            ["CODO GALVANIZADO 1 1/4","CODO-G-1-14",""],
            ["CODO GALVANIZADO 1 X 1/2","CODO-G-1X12",""],
            ["CODO GALVANIZADO 1 X3/4","CODO-G-1X34",""],
            ["CODO GALVANIZADO 1-1/2","CODO-G-1-1/2","CODO-G-1-1/2"],
            ["CODO GALVANIZADO 1-3/4","CODO-G-1-3/4","CODO-G-1-3/4"],
            ["CODO GALVANIZADO 1/2","CODO-G-1/2","CODO-G-1/2"],
            ["CODO GALVANIZADO 2","CODO-G-2","CODO-G-2"],
            ["CODO GALVANIZADO 2 X 1 1/2","CODO-G-2x1-12","CODO-G-2-11/2"],
            ["CODO GALVANIZADO 2x1 ","CODO-G-2-1","CODO-G-2-1"],
            ["CODO GALVANIZADO 3/4","CODO-G-3/4","CODO-G-3/4"],
            ["CODO GALVANIZADO 3/4X1/2","CODO-G-3/4-1/2","CODO-G-3/4-1/2"],
            ["CODO PAVCO 1","CODO-PAVCO-1","CODO-PAVCO-1"],
            ["CODO PAVCO 1/2","CODO-PAVCO-1/2","CODO-PAVCO-1/2"],
            ["CODO PAVCO 2","CODO-PAVCO2",""],
            ["CODO PAVCO 3/4","CODO-PAVCO-3/4","CODO-PAVCO-3/4"],
            ["CODO PLASTICO CON ROSCA 1","CODO-PVC-1","CODO-PVC-1"],
            ["CODO PLASTICO CON ROSCA 1 1/2","CODO-PVC-11/2","CODO-PVC-11/2"],
            ["CODO PLASTICO CON ROSCA 1/2","CODO-PVC-1/2","CODO-PVC-1/2"],
            ["CODO PLASTICO CON ROSCA 2","CODO-PVC-2","CODO-PVC-2"],
            ["CODO PLASTICO CON ROSCA 3/4","CODO-PVC-3/4","CODO-PVC-3/4"],
            ["CODO PLASTICO CON ROSCA 3/4","7453038433518","GV-PVCEFA-3-4"],
            ["CODO PVC PEGABLE 90/34P","6234007890607","CPG-34"],
            ["CODO REDUCTOR 90 GALVANIZADO 1X3/4","CODO REDUCTOR 90° GALVANIZADO 1X3/4","501-65"],
            ["CONGELADOR 100 LTS BLANCO GPLUS","GP-05M FRE","GP-05M FRE"],
            ["CONGELADOR 100 LTS GRIS GPLUS","GP-05M FRE-1032","GP-05M FRE-1032"],
            ["CONGELADOR 142LTS OMEGA","CONG-142-OMEGA-A","OCH-142W"],
            ["CONGELADOR 150L MYSTIC","CONG-150L-MYSTIC","JMY-FZ150L"],
            ["CONGELADOR 150LTS ARTIC","CONG-150-ARTIC",""],
            ["CONGELADOR 150LTS GPLUS","CONG-150-GPLUS","GP-07M"],
            ["CONGELADOR 150LTS GRIS GPLUS","GP-07C FRE","GP-07C FRE"],
            ["CONGELADOR 200LTS MYSTIC MY-FZ200L","CONG-200-MYSTIC","MY-FZ-200L"],
            ["CONGELADOR 249LTS OMEGA","CONG-249-OMEGA","OCH-249W"],
            ["CONGELADOR 250LTS MILEXUS","MILEXUS-250LTS","ML-CF-250"],
            ["CONGELADOR 293L OMEGA","CONGELADOR-293L-OMEGA","OCH-293W"],
            ["CONGELADOR 300 LITROS GRIS VIVAMAX","VM-CF-300-GRIS","VM-CF-300-GRIS"],
            ["CONGELADOR 300 LITROS NEGRO VIVAMAX","VM-CF-300-NEGRO","VM-CF-300-NEGRO"],
            ["CONGELADOR 559LTS MILEXUS","559LTS-MILEXUS","ML-CF-559"],
            ["CONGELADOR 600LTS LANIX","CONG-600-LANIX","CONGELADOR"],
            ["CONGELADOR AK DE 100LTS","CONG-AK-100LTS","COGAKEBL001"],
            ["CONGELADOR AK DE 160LTS","CONG-160-AK","COGAKEBL002"],
            ["CONGELADOR AK DE 260LTS","CONG-260-AK","COGAKEBL003"],
            ["CONGELADOR CONDESA CON PROTECTOR 220LTS","CONG-220-CONDESA",""],
            ["CONGELADOR DORAL198LS BLANCO","CONG-198-DORAL","DOHS-258CN"],
            ["CONGELADOR DUAL 110L SJ ELECTRONICS","CONG-110-SJ-DUAL","BD-100"],
            ["CONGELADOR DUAL 188L SJ ELECTRONICS","CONG-188-SJ","BD-188Q"],
            ["CONGELADOR DUAL 508LTS OMEGA","CONGELADOR-508LTS-OMEGA","OCH-508W"],
            ["CONGELADOR DUAL SJ 110LTS","CONG-110-SJ","BD-100Q"],
            ["CONGELADOR EDMIRA 100LTS","CONG-100-EDMIRA","BD1-101"],
            ["CONGELADOR EDMIRA 145LTS","CONG-145-EDMIRA","BD1-145"],
            ["CONGELADOR EDMIRA 200LTS","CONG-200LTS-EDMIRA","CONG-03"],
            ["CONGELADOR EDMIRA 251LTS","CONG-251-EDMIRA","BD1-251"],
            ["CONGELADOR EXHIBIDOR 315 LTS JAGUAR","CONGELADOR-315LTS-JAGUAR","CONGELADOR-315LTS-JAGUAR"],
            ["CONGELADOR GAMA ELECTRIC 100LTS","CONG-100-GAMA-ELECTRIC","2022080800"],
            ["CONGELADOR GPLUS 100LTS","CONG-100-GPLUS","GP-05M"],
            ["CONGELADOR GPLUS 200L","200L-GPLUS",""],
            ["CONGELADOR JAGUAR 100LT GRIS/BLANCO","CONG.-100LTS-JAGUAR-B-G","100LGR/LBR"],
            ["CONGELADOR JAGUAR 145LTS GRIS","145LTS-JAGUAR","145LTS-JAGUAR"],
            ["CONGELADOR JAGUAR 150LTS BLANCO","CONG-150-JAGUAR-BLANCO","150-LBR"],
            ["CONGELADOR JAGUAR 150LTS GRIS","CONG-150-JAGUAR-GRIS","150-LGR"],
            ["CONGELADOR JAGUAR 200LTS BLANCO","CONG-200-JAGUAR-BLANCO","200-LBR"],
            ["CONGELADOR JAGUAR 200LTS GRIS","CONG-200-JAGUAR-GRIS","200-LGR"],
            ["CONGELADOR JAGUAR 205LT BLANCO","CONG-205-JAGUAR",""],
            ["CONGELADOR JAGUAR 305LT BLANCO","CONG-305-JAGUAR-BLANCO","305-LB"],
            ["CONGELADOR JAGUAR 305LT GRIS","CONG-305-JAGUAR-GRIS","305-LG"],
            ["CONGELADOR JAGUAR 518LTS BLANCO","CONG-518-JAGUAR-BLANCO","518-LB"],
            ["CONGELADOR JAGUAR 518LTS GRIS","CONG-518-JAGUAR-GRIS","518-LG"],
            ["CONGELADOR MILEXUS 100L","CONG-100-MILEXUS",""],
            ["CONGELADOR MILEXUS 169LTS","CONG-169-MILEXUS","ML-CF-169-110V"],
            ["CONGELADOR MYSTIC 100LTS","CONG-100-MYSTIC","MY-FZ105L"],
            ["CONGELADOR MYSTIC DE 292LTS","CONG-292-MYSTIC","MY-FZ300L"],
            ["CONGELADOR OMEGA 100LTS","CONG-100-OMEGA","OCH-99W"],
            ["CONGELADOR OMEGA 198LTS","CONG-198-OMEGA","OCH-198W"],
            ["CONGELADOR SJ 100LT","CONG-100-SJ","CONGELADOR-SJ-100LT"],
            ["CONGELADOR VIVAMAX 100LTS GRIS","0001110","VM-CF-100L-GRIS"],
            ["CONGELADOR VIVAMAX 100LTS NEGRO","0001132","VM-CF-100L-NEGRO"],
            ["CONGELADOR VIVAMAX 170 LT GRIS","0001111","VM-CF-100L-GRIS"],
            ["CONGELADOR VIVAMAX 170 LT NEGRO","0001131","VM-CF-170L-NEGRO"],
            ["CONGELADOR VIVAMAX 200 LT GRIS","0001112","VM-CF-200L-GRIS"],
            ["CONGELADOR VIVAMAX 200 LT NEGRO","0001130","VM-CF-200L-NEGRO"],
            ["CUCHILLA 1 PASE 2X100 AMP 110-600V FERMETAL","7593826012768","CUC-65"],
            ["CUCHILLA 1 PASE 2X100 AMP TRIC","679231555179","T-2100AF-SINGLE"],
            ["CUCHILLA 1 PASE 2X60 AMP TRIC","679231555155","T-2P60A-F"],
            ["CUCHILLA 1 PASE 3X100 AMP 110-600V FERMETAL","7593826012799","CUC-80"],
            ["CUCHILLA 2 POLOS/2 FASES 60A TROEN","TR-KS2P60A","TR-KD2P60A"],
            ["CUCHILLA 2X100 TROEN","7453038497206",""],
            ["CUCHILLA 2X30 TROEN","7453038407823",""],
            ["CUCHILLA 3X30 TROEN","7453038407830",""],
            ["CUCHILLA 6PZA EMTOP ESNKT6128","6941556202675",""],
            ["CUCHILLA 9101 EMTOP","6941556202583",""],
            ["CUCHILLA DE GUARAÑA BELLOTA PULIDA","7702956016946","351625P"],
            ["CUCHILLA DE GUARAÑA BELLOTA ROJA LIVIANA","7702956016762","351625"],
            ["CUCHILLA DE GUARAÑA BELLOTA ROJA PESADA","7702956016793","CUCHILLA-DESMALEZADORA-BELLOTA"],
            ["CUCHILLA DE LICUADORA","5621556746461","5621556746461"],
            ["CUCHILLA DE LICUADORA OSTER 4 HOJILLAS","034264025677-4H","HM-01.BD4"],
            ["CUCHILLA DE LICUADORA OSTER 6 HOJILLAS","0342640025677-6H","004964-013-000"],
            ["CUCHILLA DE LICUADORA ROYAL REAL","CU002",""],
            ["CUCHILLA ELECTRICA 2 PASE 2X30 VITRON","205000009782","03-009-023"],
            ["CUCHILLA ELECTRICA 2X100A TROEN","7453010088903","TR-KD2P100A"],
            ["CUCHILLA ELECTRICA 2X20 TROEN ","7453038416078","TR-KS2P20A"],
            ["CUCHILLA ELECTRICA 2X30AMP VITRON","205000008719","03-009-019"],
            ["CUCHILLA ELECTRICA 2X60 TROEN","7453010090876","TR-KS2P60A"],
            ["CUCHILLA ELECTRICA VITRON 2X60 AMP ","205000008721","03-009-020"],
            ["CUCHILLA EMTOP","6941556202613","ESNK18102"],
            ["CUCHILLA KNIFE SWITCH 2X60","CUCHILLA-2X60","CUS-02"],
            ["CUCHILLA KNIFE SWITCH 3X30","CUCHILLA-3X30","3X30-CUCHILLA"],
            ["CUCHILLA KNIFE SWITCH 3X60 DOBLE PASO","3X60-CUCHILLA","3X60-CUCHILLA"],
            ["CUCHILLA KNIFE SWITCH PARA LUZ 2X100","CUCHILLA-2X100","CUS-03"],
            ["CUCHILLA KNIFE SWITCH PARA LUZ 2X30","CUCHILLA-2X30","2X30-CUCHILLA"],
            ["CUCHILLA KNIFE SWITCH PARA LUZ 3X100","CUCHILLA-3X100","3X100-CUCHILLA"],
            ["CUCHILLA KNIFE SWITCH PARA LUZ 3X100","3x100-CUCHILLA","CUS-09"],
            ["CUCHILLA KNIFE SWITCH PARA LUZ 3X60","CUCHILLA-3X60","3X60-CUCHILLA"],
            ["CUCHILLA METALICA. MANGO ERGONOMICO RUN","7591996008116",""],
            ["CUCHILLA PARA CEPILLO DE MADERA INGCO","6928073661352","EPB820121"],
            ["CUCHILLA PARA CEPILLO DE MADERA TOTAL","6925582173567","TAC618201"],
            ["CUCHILLA PARA LICUADORA ORIGINAL","6900031220238",""],
            ["CUCHILLA PLEGABLE EMTOP","6941556202736",""],
            ["CUCHILLA UN PASE 3x30A TROEN","7453038415613","TR-KS3P30A"],
            ["CUCHILLA UN PASE 3X60 TROEN","7453010020309","TR-KS3P60A"],
            ["DESAGUE CON BAJANTE PARA FREGADERO GRIVEN","7453078501826","9A145-P10072-S"],
            ["DESAGUE CON SIFON FLEXIBLE PARA LAVAMANOS 1 1/4","7453038484497","A367-HA122"],
            ["DESAGUE CROMADA ANTICUCARACHA GRIVEN","7453038443234","A367-ZD-4"],
            ["DESAGUE DE ACERO INOXIDABLE 4X4 GRIVEN","7453010009984","A367-SD-10A1"],
            ["DESAGUE DE FREGADERO PLASTICO 1-1/2 LF","205000009476","04-005-040"],
            ["DESAGUE DE LAVAMANOS PLASTICO 1-1/4 LF","205000009478","04-005-041"],
            ["DESAGUE DE PISO 12CM X 12CM GIRATORIO GRIVEN","7453038485067","A367-SD-12S"],
            ["DESAGUE DE PISO 2 COLOR GRIS METALICO GRIVEN GUN METAL","7453038436861","GV-SD-10A1Q"],
            ["DESAGUE DE PISO 2 GRIS METALICO GRIVEN GUN METAL","7453038430197","GV-SD-10P1Q"],
            ["DESAGUE DE PISO 2 GRISS METALICO GRIVEN","7453038430005","GV-RSD-10P1Q"],
            ["DESAGUE DE PISO 3 DE ALUMINIO GRIVEN","7453038463287","A367-ZD-3"],
            ["DESAGUE DE PISO 3 REDONDO ACERO INOXIDABLE GRIVEN","7453010064204","A367-RSD-10A1"],
            ["DESAGUE DE PISO 4 TAPA CIEGA GRIVEN","7453038455596","A367-RSD-10B"],
            ["DESAGUE DE PISO 4 X 4 ACERO INOXIDABLE CON TAPA GRIVEN","7453010075125","A367-SD-10P"],
            ["DESAGUE DE PISO 4 X 4 ACERO INOXIDABLE GRIVEN","7453038499927","A367-SD-10P1"],
            ["DESAGUE DE PISO 4X4 GRIVEN","7453038422383","A367-RSD-10P"],
            ["DESAGUE METALES ALEADOS PARA LAVAMANOS 1 1/4 ","DESAGUE",""],
            ["DESAGUE METALES ALEADOS PARA LAVAMANOS 1 1/4 PLASTICO","DESAGUE-11/4",""],
            ["DESAGUE O INODORO DE 2 PLASTICO","7594005706010",""],
            ["DESAGUE P/FREGADERO 1-1/2 ABS/CROMADO RUN","734896112515","DF03"],
            ["DESAGUE P/FREGADERO 1-1/2 ACERO INOXIDABLE RUN","734896112478","DF01"],
            ["DESAGÜE PARA BATEA 1 1/2 PLASTICO METALES ALIADOS","07-024-011","07-024-011"],
            ["DESAGUE PARA FREGADERO 1-1/2","6901234567892","DESAGUE"],
            ["DESAGUE PARA FREGADERO 4X4 GRIVEN","7453038432832","9A145-P10072"],
            ["DESAGUE PARA FREGADERO AQUAFINA","7453050048257-A","A-YM3019"],
            ["DESAGÜE PARA FREGADERO AQUAFINA ","7453050048257","A-YM3019"],
            ["DESAGUE PARA FREGADERO FERMETAL","7592032002051","DES-02"],
            ["DESAGUE PARA FREGADERO FLEXIBLE 1-1/4 LF","205000010588","04-005-044"],
            ["DESAGUE PARA FREGADERO HUMMER","7453100258964","HUM-1171"],
            ["DESAGUE PARA FREGADERO METALES ALEADOS CON TUBO PLASTICO","DESAGUE-METALES",""],
            ["DESAGUE PARA LAVAMANO METALICO DE 1 1/4 CON SIFON FLEXIBLE MA","7592978400331","SIF-0220"],
            ["DESAGUE PARA LAVAMANO PLASTICO CON REJILLA FERMETAL","7592032002129","DES-15"],
            ["DESAGUE PARA LAVAMANOS 1 1/4 X 8 PVC GRIVEN","7453038454872","A367-HSD8A"],
            ["DESAGUE PARA LAVAMANOS 1-1/4","DESAGUE-1 1/4","DESAGUE-1 1/4"],
            ["DESAGUE PARA LAVAMANOS 1-1/4X8 RUN","734896112522","DF04"],
            ["DESARMADOR 6 EN 1 EXXEL","2000000510606","00-015115"],
            ["DESMALEZADORA 5200C WASA TOOLS","DMWASATOOLS",""],
            ["DESMALEZADORA 52CC OHM ELEKTRO","DESMALEZADORA-ELEKTRO",""],
            ["DESMALEZADORA A GASOLINA 5200 RUN","734896113598","DMZ001"],
            ["DESMALEZADORA A GASOLINA KOBATEX","JL-113","JL-113"],
            ["DESMALEZADORA ATOUAN 52Cc","DESMALEZADORA-ATOUAN-52CC",""],
            ["DESMALEZADORA CG520 MAGPOWER 520","DESM-MAGPOWER 5200","MAG-5200"],
            ["DESMALEZADORA CHESTERWOOD ","DESMALEZADORA-CHESTERWOOD",""],
            ["DESMALEZADORA DOMOSA A GASOLINA BC-5200 ","DESMALEZADORA-BC520",""],
            ["DESMALEZADORA DOMOSA BC-4300","DESMALEZADORA-BC4300","BC-430"],
            ["DESMALEZADORA DOMOSA D45","83-D45",""],
            ["DESMALEZADORA GENPAR 2T GBC-052-2T ","GBC-052-2T",""],
            ["DESMALEZADORA INALAMBRICA 20V SIN BATERIA COVO","7453078503400","CV-TRIMMER-20V"],
            ["DESMALEZADORA INALAMBRICA 40V EMTOP","6941556216641",""],
            ["DESMALEZADORA INGCO 45CC ","GPC45441",""],
            ["DESMALEZADORA JANA POWER 5200C","JP-BC520C",""],
            ["DESMALEZADORA MAG430 MAG POWER","MAG-430",""],
            ["DESMALEZADORA MAG45 MAG POWER","MAG-45",""],
            ["DESMALEZADORA MAG5200 52CC MAGPOWER","MAG-5200","BC-5200"],
            ["DESMALEZADORA SOLPOWER 430 SPD430G","DESM-SOLPOWER430","EDZ-1004"],
            ["DESMALEZADORA SOLPOWER 520 ","DESM-SOLPOWER520",""],
            ["DESMALEZADORA STIHL FS 160 ","4119-204-0014-FS160",""],
            ["DESMALEZADORA STIHL FS 280 ","419-200-0017-FS280",""],
            ["DESMALEZADORA TITANIO CG520","CG520","CG520"],
            ["DESMALEZADORA TOYAMA 43CC ","TG430-B","7590043086220"],
            ["DISCO ABRASIVO 41/2 X 7/8 GRANO 180 COVO","7453038454896","CV-FSP-15180"],
            ["DISCO ABRASIVO 6 X 1/2 GRINDING WHEEL LGM","GRINDING WHEEL","GRINDING WHEEL"],
            ["DISCO ABRASIVO DE CORTE METAL 14X1/8X1 WADFOW","6941786802270","WAC1314"],
            ["DISCO C/CONCRETO CONTINUO 7","7453010059125","CV-DCW-180W"],
            ["DISCO CEPILLO CONICO DE ALAMBRE TRENZADO 5 COVO","7453010079925","CV-WB2-0105"],
            ["DISCO CEPILLO COPA DE ALAMBRE 2 X 1/4 COVO","7453038483384","CV-9WB-0350"],
            ["DISCO CEPILLO COPA DE ALAMBRE 3X1/4COVO","7453038457118","CV-9WB-0375"],
            ["DISCO CEPILLO COPA DE ALAMBRE 4 5/8 COVO","7453038452489","A145-KC02-4"],
            ["DISCO CEPILLO COPA DE ALAMBRE 5 X 5/8 COVO","7453038486705","CV-WB-0105"],
            ["DISCO CEPILLO COPA DE ALAMBRE 5X 5/8 COVO","7453010064679",""],
            ["DISCO CEPILLO DE ALAMBRE 5 COVO","7453010036799","CV-WB-P05"],
            ["DISCO CEPILLO DE COPA ALAMBRE 3PULG SECURITY","7453038423267","A145-KC01-3B"],
            ["DISCO CERAM/CONCRETO 4 1/2 DEWAL ","DIS-4 1/2","DIS-108-DEWAL"],
            ["DISCO COPA DE ALAMBRE 4 X 5/8 COVO","7453038483605","CV-WB-0104"],
            ["DISCO COPA DESBASTE 4 1/2 TIPO FLAP INGCO","6928073674871","CGW011151"],
            ["DISCO CORTE 4 PUNTA DIAMANTE EMTOP","6941556211448","(EDDC011151)"],
            ["DISCO CORTE DE METAL 7*1/6*7/8 STANLEY","7896525088448",""],
            ["DISCO CORTE DE METAL EXTRAFINO 4 1/2 COVO","7453078512716","CV-CW-1512B"],
            ["DISCO CORTE DE METAL EXTRAFINO 4 1/2 COVO","7453038443821","CV-CW-1518"],
            ["DISCO CORTE DE METAL EXTRAFINO 4-1/2 COVO ","7453038412476","CV-CW-1518B"],
            ["DISCO CORTE DE METAL EXTRAFINO 7X1-16 COVO","7453038430616","CPV-CW-1816"],
            ["DISCO CORTE MADERA SILK 4 1/2","DISCO-SILK-4-1/2","DISC-SILK"],
            ["DISCO CORTE MADERA SILK 7PULG","DISCO-SILK-7","DISC-SILK"],
            ["DISCO CORTE METAL 180X1.6MM EMTOP","6941556220495","EACD161802"],
            ["DISCO CORTE METAL 230X1.9MM EMTOP","6972951247640","EACD302302"],
            ["DISCO CORTE METAL 4 1/2 EXTRAFINO EMTOP","6941556204044",""],
            ["DISCO CORTE ULTRAFINO 7 PUNTA DIAMANTE EMTOP","6941556215309",""],
            ["DISCO CORTE ULTRAFINO 7 PUNTA DIAMANTE EMTOP EDDCH31801","6972951242393",""],
            ["DISCO DE 7 COVO SEMICORTE","7453038442695",""],
            ["DISCO DE C/CONCRETO CONTINUO 4 1/2 COVO","7453010088194","CV-DCW-115W"],
            ["DISCO DE CONCRETO 14 METCO","8425628202148","010503"],
            ["DISCO DE CONCRETO 4 1/2 DEWALT","4502887403135-4","DISCO-DEWALT-4"],
            ["DISCO DE CONCRETO 7 DEWAL ","DISC-7 ","DIS-109-DEWAL"],
            ["DISCO DE CONCRETO 7 DEWALT ","4502887403135-7","DISCO-DEWALT-7"],
            ["DISCO DE CONCRETO METCO 7 ","8425628202704",""],
            ["DISCO DE CONCRETO SEGMENT 4 1/2 COVO","7453010093839","7A136-G115-CV"],
            ["DISCO DE CORTE 14 PARA TRONZADORA INGCO","6941640186065","MCD253551"],
            ["DISCO DE CORTE 4 1/2 CENTRO ELEVADO METCO","8425628303418","8425628303418"],
            ["DISCO DE CORTE 4 1/2 EMTOP","DISCO-CORTE","DISCO-CORTE"],
            ["DISCO DE CORTE 4-1/2 STANLEY","7896525088431",""],
            ["DISCO DE CORTE 7 CENTRO ELEVADO METCO","8425628303715","010130"],
            ["DISCO DE CORTE 7 DEWALT LGM","028877513577","DW44601"],
            ["DISCO DE CORTE 7 HUMMER","7453100257462","HUM-1021"],
            ["DISCO DE CORTE 7 METCO","8425628301728",""],
            ["DISCO DE CORTE 7 PRETUL","7506240624639",""],
            ["DISCO DE CORTE 7 X 1/16 EMTOP","6972951247633","EACD301802"],
            ["DISCO DE CORTE 7 X1/8X7/8","8425628301735","010111"],
            ["DISCO DE CORTE CONCRETO CONTINUO 4.5 KOBATEX","6985857112059","KBT-DTD-1205"],
            ["DISCO DE CORTE CONCRETO CONTINUO 7 KOBATEX","6985857112066","KBT-DTD-1206"],
            ["DISCO DE CORTE CONVENCIONAL 4/2 LYNOX","6109669104122","DIS-002"],
            ["DISCO DE CORTE DE DIENTE DE MOTOSIERRA 9^ FITS","110129","110129"],
            ["DISCO DE CORTE DE METAL 4 1/2 COVO","7453038473651","CV-CW-1512"],
            ["DISCO DE CORTE DE METAL 4 1/2 DEWALT","028877535630","028874948204"],
            ["DISCO DE CORTE DE METAL 4 1/2X3/64X7/8 CENTRO PLANO JADEVER","6942210210005","JDAC1345"],
            ["DISCO DE CORTE DE METAL 4-1/2 1.2MM INGCO","6925582106282","MCD121151"],
            ["DISCO DE CORTE DE METAL 7 INGCO","6925582104073","MCD311802"],
            ["DISCO DE CORTE DE METAL 7X1/ 16X7/8 CENTRO PLANO JADEVER","6942210219145","JDAC1371"],
            ["DISCO DE CORTE DE METAL 7X16MM WADFOW","6941786802324","WAC1371"],
            ["DISCO DE CORTE DE METAL PARA TROZADORA 14X3/32X1 JADEVER","6942210205865","JDAC1314"],
            ["DISCO DE CORTE DE RETOÑO BRUSH CUTTER BLADE 10 FITS","DISCO-DE.CORTE-RETOÑO",""],
            ["DISCO DE CORTE DIAMANTADO 4 1/12 INGCO","6941640154538","DMD081151HT"],
            ["DISCO DE CORTE DIAMANTADO 4 1/2 HUMMER","7453100257394","HUM-1014"],
            ["DISCO DE CORTE DIAMANTADO 4 1/2 ZASC","679231573319","Z-D115"],
            ["DISCO DE CORTE DIAMANTADO 7 HUMMER","7453100257417","HUM-1016"],
            ["DISCO DE CORTE DIAMANTADO CONTINUO 4 1/2 INGCO","6925582124194","DMD021152M"],
            ["DISCO DE CORTE DIAMANTADO CONTINUO 4 1/2X7/8 JADEVER","6942210214959","JDDC2K02"],
            ["DISCO DE CORTE DIAMANTADO CONTINUO 7X7/8 JADEVER","6942210213730","JDDC2K04"],
            ["DISCO DE CORTE DIAMANTADO LISO 4 1/2. ZASC","679231573333","Z-W115"],
            ["DISCO DE CORTE DIAMANTADO SEGMENTADO 4 1/2 INGCO","6925582123753","DMD011152M"],
            ["DISCO DE CORTE DIAMANTADO SEGMENTADO 4 1/2 X22,2MM INGCO","6925582104974","DMD011152"],
            ["DISCO DE CORTE DIAMANTADO SEGMENTADO 7X7/8 JADEVER","6942210215048","JDDC1K04"],
            ["DISCO DE CORTE DIAMANTADO TURBO4 /12X22,2MM INGCO","6925582100259","DMD031152"],
            ["DISCO DE CORTE DIAMANTADO ULTRAFINO 4 1/2 INGCO","6941640141118","DMD031152HT"],
            ["DISCO DE CORTE DIAMANTE ULTRAFINO EMTOP 4 1/2","6941556224455",""],
            ["DISCO DE CORTE EXTRA FINO 4/12 LYNOX","0109669104129","DIS-001"],
            ["DISCO DE CORTE EXTRAFINO 7 COVO","7453010064860",""],
            ["DISCO DE CORTE EXTRAFINO 7 COVO","7453078527062","180122-B-CV"],
            ["DISCO DE CORTE EXTRAFINO 7 METCO","8425628301711",""],
            ["DISCO DE CORTE EXTRAFINO 7 X 1/16 LYNOX","6109669103146","DIS-006"],
            ["DISCO DE CORTE METAL 4.5 ULTRA FINO 4 1/2 KOBATEX","9401817009827","KBTX400"],
            ["DISCO DE CORTE METAL 5 EMTOP","6972951247626",""],
            ["DISCO DE CORTE METAL 7 X 1/16 ULTRA FINO KOBATEX ","5012345678900","KBTX700"],
            ["DISCO DE CORTE METAL 9 EMTOP","6972951247657",""],
            ["DISCO DE CORTE METCO 4 1/2","8425628301421","8425628301414"],
            ["DISCO DE CORTE P/ MADERA 10 X 1/4 24D METCO","8425628204128","CA-8204-10 1/4-24D"],
            ["DISCO DE CORTE P/ MADERA 10 X 1/4 40D METCO","8425628204142","CA-8204-10 1/4-40D"],
            ["DISCO DE CORTE P/ MADERA 7 1/4 24D METCO","8425628204722","CA-8204-7 1/4-24D"],
            ["DISCO DE CORTE P/ MADERA 7 1/4 40D METCO","8425628204746","CA-8204-7 1/4-40D"],
            ["DISCO DE CORTE RETOÑO FITS 10","DISCO-RETOÑO",""],
            ["DISCO DE CORTE SEGMENTADO 7 KOBATEX","6985857112028","KDT-DTD-1202"],
            ["DISCO DE CORTE SEGMENTADO DE 4.5 KOBATEX","6985857112011","KBT-DTD-1201"],
            ["DISCO DE CORTE ULTRAFINO 4 1/2 RUN","7591996014988",""],
            ["DISCO DE CORTE ULTRAFINO CENTRO HUNDIDO 4 1/2 RUN","7591996015008","DSC08"],
            ["DISCO DE CORTE ULTRAFINO CENTRO HUNDIDO 7 RUN","7591996018993",""],
            ["DISCO DE CORTE ULTRAFINO PLANO 7 RUN","7591996014995",""],
            ["DISCO DE CORTE/METAL 4 1/2 WADFOW","6941786801174","WAC1345"],
            ["DISCO DE CORTEDIAMANTADO TURBO 7 HUMEDO WADFOW","6941786811456","WDC2K04"],
            ["DISCO DE DESBASTE 4 1/2 BLACK DECKER","A24R",""],
            ["DISCO DE DESBASTE 4 1/2 DE ACERO INOXIDABLE COVO","7453038469463","CV-GW2-1156B"],
            ["DISCO DE DESBASTE 4 1/2 TOTAL","6925582160857","TAC2231151"],
            ["DISCO DE DESBASTE 4/12 METCO","8425628304415","010201"],
            ["DISCO DE DESBASTE 7 6.0MM INGCO","6925582106749","MGD601801"],
            ["DISCO DE DESBASTE 7 COVO","7453038466691","CV-GW-1806B"],
            ["DISCO DE DESBASTE 7 KOBATEX","9780201371864","KBTX780"],
            ["DISCO DE DESBASTE 7 METCO","8425628304712","010202"],
            ["DISCO DE DESBASTE DE METAL 4 1/2 RUN","7591996006228",""],
            ["DISCO DE DESBASTE DE METAL 7X1/4X7/8 RUN","7591996006235","DSC03"],
            ["DISCO DE DESMALEZADORA COVO 4-179 ","7453038455077","CV-BCB02-1-6"],
            ["DISCO DE DIAMANTE NEGRO 4-1/2 EXXEL","2000000320540","09-007-035"],
            ["DISCO DE ESMERIL DE BANCO COVO","7453078505411","CV-GRST-5-3-4-36"],
            ["DISCO DE LIJA 100 ABRAMAGIC ","7592022565610",""],
            ["DISCO DE LIJA 4 1/2 GRANO 60 COVO","7453038485685","CV-SD-1560"],
            ["DISCO DE LIJA 4 1/2 X 7/8 GRANO 100 COVO","7453038465892","CV-SD-15100"],
            ["DISCO DE LIJA 4 1/2 X 7/8 GRANO 36 COVO ","7453038485524","CV-SD-1536"],
            ["DISCO DE LIJA 4 N80 COVO","7453038492669","CV-SD-1580"],
            ["DISCO DE LIJA 600 ABRAMAGIC ","7592022565689",""],
            ["DISCO DE LIJA 7 GRANO 60 COVO","7453038495936","CV-SD-1860"],
            ["DISCO DE LIJA 7 N16 COVO","7453038479004","CV-SD-1816"],
            ["DISCO DE LIJA 7 N24 COVO","7453038470490","CV-SD-1824"],
            ["DISCO DE LIJA 7 N36 COVO","7453038497640","CV-SD-1836"],
            ["DISCO DE LIJA 7 N80 COVO","7453010045296","CV-SD-1880"],
            ["DISCO DE LIJA 80 ABRAMAGIC ","759202565696",""],
            ["DISCO DE LIJA COVO N7 G100","7453038498654","CV-SD-18100"],
            ["DISCO DE PULIR-DESBASTE 4 1/2 CV-GW-1156B","7453038466707",""],
            ["DISCO DE RESPALDO 5PZAS 4 1/2 COVO","7453038428200","CV-FBP-156P"],
            ["DISCO DE RETOÑO 40T 430/520 MAGPOWER","DISCO-RETOÑO-40T","DISCO-RETOÑO-40T"],
            ["DISCO DE RETOÑO PARA DESMALEZADORA C/ CADENA 7","2023101900688",""],
            ["DISCO DE SEMICORTE 4 1/2 BLACK-DECKER ","DISCO-BLACK-DECKER","ISO-9001-4-1/2"],
            ["DISCO DE SIERRA 4 1/2 PARA MADERA COVO 30","7453038445856","A367-S9610-CV"],
            ["DISCO DE SIERRA 7 1/4 PARA MADERA COVO","7453038458009","A367-S9612-CV"],
            ["DISCO DE SIERRA 7 1/4 PARA MADERA COVO","7453038474184","CV-CW-3"],
            ["DISCO DE SIERRA 7-1/4 185mm 40T JADEVER","6942210204929","JDTC1K05"],
            ["DISCO DE SIERRA CIRCULAR 4 1/2 40T INGCO","6928073711330",""],
            ["DISCO DE SIERRA CIRCULAR 7 1/4 ZASC","679231573401","Z-W185-18T"],
            ["DISCO DE SIERRA CIRCULAR 7-1/4 30T DIENTES WADFOW","6942431483882","WTC1K02"],
            ["DISCO DE SIERRA PARA ALUMINIO 7 COVO","7453038403344","CV-CW-2"],
            ["DISCO DE SIERRA PARA MADERA 4 1/2 18D COVO","7453038428477","CV-CW-1"],
            ["DISCO DE SIERRA PARA MADERA 4 1/2 40D COVO","7453038445863","A367-S9609-CV"],
            ["DISCO DE SIERRA PARA RETOÑO TIPO CINCEL COVO","7453038475273","CV-BCB02-2"],
            ["DISCO DE TRONZADORA 14 COVO","7453038422864","CV-CW-3532"],
            ["DISCO DE TRONZADORA 14 METCO","14X1/8X1",""],
            ["DISCO DE TRONZADORA 14X1/8X1 METCO","8425628301148","TRONZADORA"],
            ["DISCO DESBASTE 4/ 1/2 115M X 6 MM INGCO","6925582106275","MGD601151"],
            ["DISCO DETRONZADORA EMTOP ","6972951247664","DISCO-TRONZ-EMTOP"],
            ["DISCO DIAMANADO 4-1/2 115MM HUMMER","7453100257424","HUM-1017"],
            ["DISCO DIAMANTADO 4 1/2 INGCO","6941640182289","DMD0111513"],
            ["DISCO DIAMANTADO 4.5 SEGMENTADO","8425628105456","CA-8105"],
            ["DISCO DIAMANTADO 7 CORTE TURBO COVO","7453010059132","DS180WAD-CV"],
            ["DISCO DIAMANTADO DE CORTE 7 HUMMER","7453100257400","HUM-1015"],
            ["DISCO DIAMANTE 9 ULTRAFINO EMTOP","6941556224646",""],
            ["DISCO DIAMANTE AZUL 7 EXXEL","2000000320144","32-014"],
            ["DISCO DIAMANTE ULTRAFINO 5 BORDE DE MALLA","6941556205065",""],
            ["DISCO DIAMANTE ULTRAFINO 5 EMTOP","6941556209322",""],
            ["DISCO DIAMANTE ULTRAFINO 9 EMTOP ","6941556222154",""],
            ["DISCO EXTRA FINO 4 1/2 DE CORTE DE COPA COVO","7453010057480","CV-CW-1514B"],
            ["DISCO FLAP 36 4 1/2 X 7/8 HUMMER","7453100257486","HUM-1023"],
            ["DISCO FLAP 4 1/2 DE 100 COVO","7453038480925","CV-FD115-100"],
            ["DISCO FLAP 4 1/2 DE 120 COVO","7453038437776","CV-FD115-120"],
            ["DISCO FLAP 4 1/2 DE 40","7453010076412",""],
            ["DISCO FLAP 4 1/2 DE 60","7453038481182",""],
            ["DISCO FLAP 4 1/2 DE 80 COVO","7453038460415","CV-FD115-80"],
            ["DISCO FLAP 4.5 N°100 METCO","8425628309410","CA-8309-100#-4 1/2"],
            ["DISCO FLAP 4.5 N°120 METCO","8425628309427","CA-8309-120#-4 1/2"],
            ["DISCO FLAP 4.5 N°120 METCO","14256288","CA-8309"],
            ["DISCO FLAP 4.5 N°150 METCO","8425628309458","CA-8309-150#-4 1/2"],
            ["DISCO FLAP 4.5 N°60 METCO","8425628309465","CA-8309-60#-4-1/2"],
            ["DISCO FLAP 4.5 N°80 METCO","8425628309489","CA-8309-80#-4 1/2"],
            ["DISCO FLAP 60 4 1/2X 7/8 HUMMER","7453100257493","HUM-1024"],
            ["DISCO FLAP 7 GRANO 100 COVO","7453038474252","CV-FD180-100"],
            ["DISCO FLAP 7 N° 150","8425628309755","CA-8309-150#"],
            ["DISCO FLAP 7 N°100 METCO","8425628309717","CA-8309-100#"],
            ["DISCO FLAP 7 N°120","8425628309724","CA-8309-120#"],
            ["DISCO FLAP 7 N°60 METCO","8425628309762","CA-8309-60#"],
            ["DISCO FLAP 7 N°80 METCO","8425628309786","CA-8309-80#"],
            ["DISCO FLAP COVO 7 GRANO 80","7453010048051","CV-FD180-120"],
            ["DISCO FLAP GRANO 40 INGCO","6928073670460","FD1151"],
            ["DISCO FLAP GRANO 60 INGCO","6941640155801","FDZ1152"],
            ["DISCO FLAP GRANO 80 INGCO","6941640160928","FDZ1153"],
            ["DISCO P/ MADERA 7 1/4 24T JADEVER","6942210211996","JDTC1K04"],
            ["DISCO P/ SIERRA CIRCULAR 10 60D COVO","7453038490689","CV-CW-6"],
            ["DISCO P/SIERRA CIRCULAR 7-1-1/4 40D ","7453038458252","CV-CW-4"],
            ["DISCO PARA CONCRETO 4.5 METCO","8425628101458","CA-8101"],
            ["DISCO PARA CORTE 4 1/2 HUMMER","7453100257448","HUM-1019"],
            ["DISCO PARA CORTE 4 1/2 HUMMER","7453100257455","HUM-1020"],
            ["DISCO PARA CORTE EXTRA FINO 4 1/2 COVO","7453038406444","CV-CW-1514"],
            ["DISCO PARA ESMERILAR 4 1/2 FERCO","6922818115605","FAD-E4"],
            ["DISCO PARA SIERRA CIRCULAR 10 60 DIENTES ZASC","679231573449","Z-W254-60T"],
            ["DISCO PARA TRONZADORA 14 COVO","7453038436946","CV-CW-3532"],
            ["DISCO PARA TRONZADORA 14 KOBATEX","9771473968012","KBTX14000"],
            ["DISCO PARA TRONZADORA 14 METCO","DISCO-14-METCO",""],
            ["DISCO PARA TRONZADORA 14 RUN","7591996015015",""],
            ["DISCO SEGMENTADO CORTE DE CONCRETO 7 COVO","7453038423786","DS180WAD-CV"],
            ["DISCO SEGMENTADO DE 4-1/2 PARA CONCRETO METCO TURBO","8425628202452",""],
            ["DISCO SIERA CIRCULAR 8 1/4 EMTOP ETCT121022","6941556219093",""],
            ["DISCO SIERRA CIRCULAR 6 1/2 PARA MADERA 24T INGCO","6941640152794","TSB116511"],
            ["DISCOS PARA LIJADORA ROTO-ORBITAL 4 1/2 COVO ","7453038406024","CV-FSP-15120"],
            ["ELECTOBOMBA CINTRIFUGA 1.5HP DOMOSA DC-150","7590024000306",""],
            ["ELECTROBOMBA 1 HP LYNOX","ELECTROBOMBA-1HPLYNOX",""],
            ["ELECTROBOMBA 1.5HP CENTRIFUGA GRIVEN","7453010053888","CP-1-5HP-VEN"],
            ["ELECTROBOMBA 1.5HP LEO AJDM-110/4H","6946687602677",""],
            ["ELECTROBOMBA 1/2 COBY","COBY-1/2","COBY-1/2"],
            ["ELECTROBOMBA 1/2 HP ROCCIA SQB60","SQB60",""],
            ["ELECTROBOMBA 1/2 HP UNIVERSAL ROYAL","DB-60-G",""],
            ["ELECTROBOMBA 2HP CENTRIFUGA GRIVEN","7453010076252","CP-2HP-VEN"],
            ["ELECTROBOMBA 3HP AUTOC JET PROMO 2X2 DOMOSA","9-8CCE-PROMO","9-8CCE-PROMO"],
            ["ELECTROBOMBA 4 MAG ELECTRIC 4HP/220EAC40","EBA-3020",""],
            ["ELECTROBOMBA AUTOCEBANTE 2HP INGCO","JP15008",""],
            ["ELECTROBOMBA AUTOCEBANTE DE ALTA PRESION 1HP GRIVEN","JET-1-VEN","JET-1-VEN"],
            ["ELECTROBOMBA AUTOCEBANTE DOMOSA 2X2 3HP 9-8CCE-PROMO","7590024000405","PROMO-3HP"],
            ["ELECTROBOMBA AUTOCEBANTE JET 1HP INGCO","UJP07508",""],
            ["ELECTROBOMBA AUTOCEBANTE MAG-ELECTRIC 2HP 1.5 220V JET200 ","EBA-3019","JET200"],
            ["ELECTROBOMBA CENTRIFUGA 1HP 100 COBRE DAEWOO","BOMBA-1HP-DAEWOO","DAECPM158"],
            ["ELECTROBOMBA CENTRIFUGA 1HP DOMOSA DC-100","DC-100",""],
            ["ELECTROBOMBA CENTRIFUGA 1HP GENPAR 110V","GENPAR-1HP","GBJ-100-S"],
            ["ELECTROBOMBA CENTRIFUGA 2HP DOMOSA DC-200","7590024000313",""],
            ["ELECTROBOMBA CENTRIFUGA DOMOSA 3HP DC-300","7590024000320",""],
            ["ELECTROBOMBA DOMOSA DPX-65 0.5 HP","7590024017366",""],
            ["ELECTROBOMBA DOMOSA DPX-70 0.5 HP 100COBRE","DPX-70",""],
            ["ELECTROBOMBA GENPAR 1/2 HP GBP-050-A","GBP-050-A",""],
            ["ELECTROBOMBA GENPAR 1HP","GBP-100A","GEN-1"],
            ["ELECTROBOMBA GENPAR 1HP GBP-100-A PERIFERICA","GBP-100-A",""],
            ["ELECTROBOMBA HUMMER 1 HP","7453100259213","HUM-1196"],
            ["ELECTROBOMBA HUMMER 1/2 HP","7453100259206","HUM-1195"],
            ["ELECTROBOMBA LEO 1.5HP CAUDAL Y PRESION ACM110 220V","ACM110","ACM110"],
            ["ELECTROBOMBA LEO 1HP 110V CENTRIFUGA AJM75","AJM75","6946687685168"],
            ["ELECTROBOMBA LEO 1HP AJM75 110V","6946687604862",""],
            ["ELECTROBOMBA LEO 1HP CINTRIFUGA ACM75PRO 110V-220V","ACM75","ACM75-PROM"],
            ["ELECTROBOMBA LEO 2ACM75 180-240V","6946687695426",""],
            ["ELECTROBOMBA LEO 2HP 220V TIPO JET AJM150","AJM150","6946687685168"],
            ["ELECTROBOMBA LEO 2HP CAUDAL Y PRESION ACM150 220V","ACM150","ACM150"],
            ["ELECTROBOMBA LEO AUTOCEBANTE 2HP XHSM2000 220V","XHSM2000","XHSM2000"],
            ["ELECTROBOMBA LEO CENTRIFUGA 2HP ALTO CAUDAL ACM150B2 220V","ACM150B2","ACM150B2"],
            ["ELECTROBOMBA LEO CENTRIFUGA 5.5HP ALTO CAUDAL Y PRESION ACM400CH2 220V","ACM400CH2","ACM400CH2"],
            ["ELECTROBOMBA MAG ELECTRIC / 1HP AUTOCEBANTE 1 110V JET100","JET100",""],
            ["ELECTROBOMBA MAG ELECTRIC /0.5HP PERIFERICA 1 110V EP60","EBA-3012",""],
            ["ELECTROBOMBA MAG ELECTRIC /1.5HP CENTRIFUGA 1 220V EC150","EBA-4004",""],
            ["ELECTROBOMBA MAG ELECTRIC /1HP PERIFERICA 1 110V EP80","EBA-3013",""],
            ["ELECTROBOMBA MAG ELECTRIC /2HP CENTRIFUGA 1X1 220V EC200","EBA-4005",""],
            ["ELECTROBOMBA MAG ELECTRIC /2HP PERIFERICA 1 220V JET150","EBA-3018",""],
            ["ELECTROBOMBA MAG ELECTRIC /3HP AUTOCEBANTE 3 220V EA6AM","EBA-3015",""],
            ["ELECTROBOMBA MAG ELECTRIC /3HP CENTRIFUGA 1.5X1 220V EC300","EBA-4006",""],
            ["ELECTROBOMBA MAG ELECTRIC /4HP AUTOCEBANTE 4 220V EA7BR","EAB-3016",""],
            ["ELECTROBOMBA MAG ELECTRIC 3HP EAC20","EAC20",""],
            ["ELECTROBOMBA MAG ELECTRIC 4HP EAC30","EBA-3001",""],
            ["ELECTROBOMBA PEDROLLO","BOMBA-PEDROLLO","PKM60"],
            ["ELECTROBOMBA PERIFERICA 0.5HP INGCO","UVPM37028",""],
            ["ELECTROBOMBA PERIFERICA 1/2 GRIVEN","7453038480673","QB-60-VEN"],
            ["ELECTROBOMBA PERIFERICA 1/2 LYNOX","ELECTRO-1/2LYNOX","ELECTRO"],
            ["ELECTROBOMBA PERIFERICA 1/2HP 100 COBRE GRIVEN","7453038435215","QB-60-VEN-CP"],
            ["ELECTROBOMBA PERIFERICA 12HP+TANQUE 24LT INGCO","UVPM3708A-24",""],
            ["ELECTROBOMBA PERIFERICA 1HP INGCO","UVPM7508",""],
            ["ELECTROBOMBA ROCCIA 1HP SQB80","SQB80",""],
            ["ELECTROBOMBA ROCCIA B70","CQB70",""],
            ["ELECTRODO 1/8 ACERO PQT 51 METCO","8425620316324","ELECTRODO-ACERO-1/8-METCO"],
            ["ELECTRODO 3/32 ACERO PQT 95 METCO","8425620309258","ELECTRODO-ACERO-3/32-METCO"],
            ["ELECTRODO 3/32 ZOE","ELECTRODO-3/32-ZOE",""],
            ["ELECTRODO 6013 1/8 KOBATEX","ELECTRODO-KOBATEX-1/8",""],
            ["ELECTRODO 6013 3/32 KOBATEX","ELECTRODO-KOBATEX-3/32",""],
            ["ELECTRODO ATOUAN 6013 3/32 ","3/32-ATOUAN",""],
            ["ELECTRODO DE ACERO INOXIDABLE HOFFMAN 1/8","ELECTRODO-ACERO-1/8",""],
            ["ELECTRODO DE ACERO INOXIDABLE HOFFMAN 3/32","ELECTRODO-ACERO-3/32",""],
            ["ELECTRODO DE ACERO INOXIDABLE LINCOLN 1/8","9110392",""],
            ["ELECTRODO DE ACERO INOXIDABLE LINCOLN 3/32","9110395",""],
            ["ELECTRODO DE ACERO INOXIDABLE METCO 1/8","ELECTRODO-ACERO-1/8-METCO",""],
            ["ELECTRODO DE ACERO INOXIDABLE METCO 3/32","ECTRODO-ACERO-3/32-METCO",""],
            ["ELECTRODO DE ACERO INOXIDABLE METCO DELGADO 3/32","ACERO-3/32-METCO",""],
            ["ELECTRODO DE ACERO INOXIDABLE METCO GRUESO 1/8 ","8425620308329",""],
            ["ELECTRODO DE HIERRO COLADO HOFFMAN 1/8","HIERRO-COLADO-1/8",""],
            ["ELECTRODO DE HIERRO COLADO HOFFMAN 3/32","HIERRO-COLADO-3/32",""],
            ["ELECTRODO DELGADO 3/32 6013 ALFACERO HUAL","ELECTRODO-ALFACEROHU","ELECTRODO-ALFACEROHU"],
            ["ELECTRODO GRUESO 1/8 LESSO PLUS","ELECTRODO","ELECTRODO"],
            ["ELECTRODO HOFFMAN 6013 1/8","1/8-HOFFMAN",""],
            ["ELECTRODO HOFFMAN 6013 3/32","3/32-HOFFMAN",""],
            ["ELECTRODO HOFFMAN 7018 1/8","7018-HOFFMAN",""],
            ["ELECTRODO LESSO PLUS 6013 3/32","3/32-LESSOPLUS",""],
            ["ELECTRODO LINCOLN 6013 1/8","91100409",""],
            ["ELECTRODO LINCOLN 6013 3/32 ","LINCON-3/32","LINCON-3/32"],
            ["ELECTRODO LINCOLN 7018 5/32","9110037",""],
            ["ELECTRODO LINCOLN 7018-1/8","91100422",""],
            ["ELECTRODO LINCOLN GRIDUR 600","91100210",""],
            ["ELECTRODO METCO 6013 1/8 ","1/8-METCO",""],
            ["ELECTRODO METCO 6013 3/32 ","3/32-METCO",""],
            ["ELECTRODO RUN 6013 1/8","1/8-RUN",""],
            ["ELECTRODO RUN 6013 3/32","3/32-RUN",""],
            ["ELECTRODO SINNVOLL 7018","ELECTRODO-7018",""],
            ["ELECTRODO VERDE 6013 1/8","7453010062071",""],
            ["ELECTRODO VERDE 6013 3/32","3/32-VERDE",""],
            ["ENCHUFE 110V TROEN","7453038442763","ST-3"],
            ["ENCHUFE ADAPTADOR TROEN SIN TIERRA","7453010011093",""],
            ["ENCHUFE CLASSIC LUX 1/2 VUELTA INDIVIDUAL 3P 20AMP","EBN-20","EBN-20"],
            ["ENCHUFE CON TIERRA 15AMP 110V FERMETAL","7593826013031","ENC-09"],
            ["ENCHUFE DE GOMA 110V TROEN","DQ-024","DQ-024"],
            ["ENCHUFE DE METAL CLASSIC LUX 2/P CON TIERRA 125V","EET-05","EET-05"],
            ["ENCHUFE DE METAL CLASSIC LUX 2/P SIN TIERRA","EET-03","EET-03"],
            ["ENCHUFE DE VINIL POLARIZADO 15AMP 110-130C MOD 4 FERMETAL","7593826013079","ENC-15"],
            ["ENCHUFE ELECTRICO SIN TIERRA TROEN","7453010009045","DQ-025"],
            ["ENCHUFE EUROPEO BLANCO AS","EUROPEO-BLANCO","ENCHUFE-EUROPEO"],
            ["ENCHUFE L5-30P 30AMP CLASSIC LUX","004466852113","EBN-01"],
            ["ENCHUFE MACHO 220 VERT","9184751995981","JEH-012"],
            ["ENCHUFE MACHO DE GOMA CHINO VERT","9184751994694","JEH-008"],
            ["ENCHUFE MACHO HORIZONTAL CLASSI LUX 15A 250V","EET-08","EET-08"],
            ["ENCHUFE MACHO INDUSTRIAL 15A-125V KOBATEX ","ENC-M12","U007-1"],
            ["ENCHUFE MACHO METAL 110V/15A","ENCHUFE-MACHO",""],
            ["ENCHUFE MACHO TIPO CHINO VERT","9780336379629","JEH-013"],
            ["ENCHUFE MACHO TRIPLE HUMMER","7453100257585","HUM-1033"],
            ["ENCHUFE MACHO TROEN CON TIERRA 220V","7453078539539","A136-3P40"],
            ["ENCHUFE METALICO 110V HUMMER","7453100257592","HUM-1034"],
            ["ENCHUFE METALICO CON TIERRA METALES ALIADOS","7592978392216","MAE-610"],
            ["ENCHUFE MULTIFUNCIONAL 6 TOMAS VERT","6900050116703","KME6"],
            ["ENCHUFE PLASTICO NEGRO TROEN ","7453038437301","DQ-025-O"],
            ["ENCHUFE PURPURA MULTI-POSISIONES VERT","ENCHUFE-180","EDG-180"],
            ["ENCHUFE REFORZADO SIN TIERRA VITRON","205000007707","03-010-041"],
            ["ENCHUFE TRIFASICO 4 PINES 30A RUN","736373171558","ENCH15"],
            ["ENCHUFE TRIPLE BENJAMIN","TEE-TRIPLE-GRIS","ETT-32"],
            ["ENCHUFE VINIL-GOMA C- TIERRA RUN","736373170131","ENCH03"],
            ["ENCHUFE VINIL-GOMA RUN","736373170155","ENCH05"],
            ["ENCHUFE VINIL-METAL C- TIERRA RUN","736373170117","ENCH01"],
            ["ESMERIL 4 1/2 X 750W COVO ","7453038498012","CV-ESME-45-750W"],
            ["ESMERIL ANGULAR 1500W EMTOP","6941556222321","ULAGR15053"],
            ["ESMERIL ANGULAR 2000W EMTOP ","6941556212100","ULAGR20073"],
            ["ESMERIL ANGULAR 2000W WADFOW","6942123006313","UWAG852001"],
            ["ESMERIL ANGULAR 2200W EMTOP ","6972951241006","ULAGR22093"],
            ["ESMERIL ANGULAR 2400W EMTOP","6941556219451","ULAGR24093"],
            ["ESMERIL ANGULAR 4 1/2 710W WADFOW","6976057337304",""],
            ["ESMERIL ANGULAR 4 1/2 750W INGCO","6941640124982","UAG75028"],
            ["ESMERIL ANGULAR 4 1/2 820W C/ESTUCHE Y ACCESORIOS BLACK-DECKER","028877429960","ESM-720"],
            ["ESMERIL ANGULAR 4 1/2 850W COVO","7453038420334","CV-ESME-45-850W"],
            ["ESMERIL ANGULAR 4 1/2 PRETUL","7501206646632",""],
            ["ESMERIL ANGULAR 4-1/2 710W INGCO","6976051783961","UAG7118"],
            ["ESMERIL ANGULAR 4-1/2 710W JADEVER","6942210211538","UJDAG15711"],
            ["ESMERIL ANGULAR 4-1/2 850W JADEVER","6942210218247","UJDAG15851"],
            ["ESMERIL ANGULAR 4-1/2 BLACK+DECKER 650W","885911528184","G650-B3"],
            ["ESMERIL ANGULAR 4-1/2 HUMMER","7453100258193","500W"],
            ["ESMERIL ANGULAR 4/12 950W INGCO","6925582112511","UAG8508"],
            ["ESMERIL ANGULAR 5 900W INGCO","6941640135339","UAG90028"],
            ["ESMERIL ANGULAR 7 1800W INGCO","6976051783497","UAG18008"],
            ["ESMERIL ANGULAR 7 1800W JADEVER","6942210219640","UJDAG851801"],
            ["ESMERIL ANGULAR 7 2000W INGCO","6941640125323","UAG200018"],
            ["ESMERIL ANGULAR 7 2200W COVO","7453038436298","CV-ESME-7-200W"],
            ["ESMERIL ANGULAR 7 2800W/ ROSCA 5/8 RUN","734896113529","ES005"],
            ["ESMERIL ANGULAR 750W CHESTERWOOD","7592346016607","CHEQ1005792"],
            ["ESMERIL ANGULAR 750W EMTOP","6941556206239",""],
            ["ESMERIL ANGULAR 750W EMTOP ","6941556208790","ULAGR07581"],
            ["ESMERIL ANGULAR 9 2200W INGCO","6941640125682","UAG220018"],
            ["ESMERIL ANGULAR 9 5/8 2000W JADEVER","6942210215536","UJDAG852001"],
            ["ESMERIL ANGULAR 9 5/8 2400W JADEVER","6942210209412","UJDAG852401"],
            ["ESMERIL ANGULAR 900W EMTOP ","6941556208820","ULAGRS09051"],
            ["ESMERIL ANGULAR DE 4 1/2 750W DEWALT","885911351911","DWE4010"],
            ["ESMERIL ANGULAR DE 9 2200W STANLEY","885911704991","SL229"],
            ["ESMERIL ANGULAR INALAMBRICO 20V EMTOP","6941556220532","ULAG201158"],
            ["ESMERIL DE BANCO 150W EMTOP","6941556216665",""],
            ["ESMERIL DE BANCO 150W EMTOP ","694155621665","ULBGR61501"],
            ["ESMERIL DE BANCO 6 150W INGCO","6928073601495","UBG61502"],
            ["ESMERIL INALAMBRICO 20V- 3000/9000 RPM -C/BATERIA Y CARGADOR JADEVER","6942210211972","UJDLAPM21"],
            ["ESMERIL P/TALADRO/SIERRA CALADORA, TIPO ISKRA PERLES/SKIL FITS","3202212060460","M065"],
            ["ESMERILADORA ANGULAR 5 900W INGCO","6941640138842","UAG900285"],
            ["ESTAÑO 1.0MM 40/60 ZASC","679231563433","ESTAÑO-ZASC"],
            ["ESTAÑO 30G HUMMER","7453100258247","HUM-1099"],
            ["ESTAÑO COVO","7453038418515","JD-100PB"],
            ["ESTAÑO EXXEL","2000000531359","53-135"],
            ["FUMIGADORA A PRESION 5L EMTOP ","6941556228910","ESPP30502"],
            ["FUMIGADORA ASPERJADORA 2L 2.5BAR","6941786814709","WRS1820"],
            ["FUMIGADORA CAÑON MAGPOWER 20LTS","MAG-CAÑON-20A","MAG-CAÑON-20A"],
            ["FUMIGADORA DE CAÑON DOMOSA 33- DFP-12-2T","FUMIGADORA-CAÑON-DOMOSA","F-CAÑON"],
            ["FUMIGADORA DE CAÑON STIHL SR 420","4203-011-2611-SR420",""],
            ["FUMIGADORA DE CAÑON STIHL SR 450","4244-011-2663-SR450",""],
            ["FUMIGADORA DE ESPALDA 20LTS MUZIN","850010443195",""],
            ["FUMIGADORA DE ESPALDA EXXEL 20LIT","205000009124",""],
            ["FUMIGADORA DE MANO 1.5LTS ROJA BLANCO","6950002401500","PH-H150"],
            ["FUMIGADORA DE MANO 1LTS ZASC","7453118003112","Z-DS1L"],
            ["FUMIGADORA DE MANO 2LTS ZASC","7453118003136","Z-DS2L"],
            ["FUMIGADORA DE MOTOR A CAÑON MAG POWER 20LT ","MAG-CAÑON",""],
            ["FUMIGADORA DE MOTOR A CAÑON MUZIN ","XGS2050",""],
            ["FUMIGADORA DE VARILLA 16L EMTOP","6941556206901","EKSP2001"],
            ["FUMIGADORA DE VARILLA 25L DOMOSA","FUMIGADORA-VARILLA-DOMOSA","F-VARILLA"],
            ["FUMIGADORA DE VARILLA 25L MAG POWER ","MAG-900",""],
            ["FUMIGADORA DE VARILLA MANUAL MAG POWER TIPO YACTO ","MAG-20L",""],
            ["FUMIGADORA ELECTRICA 20LTS DOMOSA","7590024033687","33-DFE-20L"],
            ["FUMIGADORA ESTACIONARIA 20-20 FITS","FUMIGADORA-ESTACIONARIA","FUMIGADORA-ESTACIONARIA"],
            ["FUMIGADORA ESTACIONARIA 2020F DOMOSA","FUMIGADORA-ESTA","FUMIGADORA-ESTA"],
            ["FUMIGADORA ESTACIONARIA SOLPOWER","FUMIGADORA-ESTACIONA",""],
            ["FUMIGADORA MANUAL DOMOSA 8LIT","7590024010275",""],
            ["FUMIGADORA MANUAL EMTOP 2L","FUMIGADORA-MANUAL","6941556209247"],
            ["FUMIGADORA MANUAL SPRAYER 16LIT AZUL","FJ01",""],
            ["FUMIGADORA MANUAL&nbsp;","6941556209247","EPRS1051"],
            ["FUMIGADORA MOCHILA 20LTS. SPRAYER","FUMIGADORA-SPRAYER","PH-HT014"],
            ["GANCHO PARA TECHO 3X1 CORTO 130MM 100UND","7453010052195",""],
            ["GANCHO PARA TECHO 3X1-1/2 ","GANCHO-3X1-1/2",""],
            ["GANCHO PARA TECHO CORTO 1-1/2X5 100UND","GANCHO-1-1/2",""],
            ["GANCHO PARA TECHO CORTO 2X1 100UND","GANCHO-2X1",""],
            ["GANCHO PARA TECHO CORTO 2X1 50 UNIDADES","GANCHO-CORTO-2X1","GANCHO-CORTO-2X1"],
            ["GRIFERIA CENTRAL PARA LAVAMANOS CUELLO TIPO BAR GRIVEN","7453038412483","GVB-P8507-C"],
            ["GRIFERIA DE ABS P/LAVAMANOS 4","734896112201","GDL04"],
            ["GRIFERIA DE ABS P/LAVAMANOS 4 CHICAGO RUN","734896112188",""],
            ["GRIFERIA DE BRONCE P/LAVAMANOS 4 LOS ANGELES RUN","4722171718",""],
            ["GRIFERIA DE BRONCE P/LAVAMANOS 4 NEW YORK RUN","734896112171",""],
            ["GRIFERIA DE PARED PARA FREGADERO METALICA METALES ALIADOS","7592978397549","MET-407"],
            ["GRIFERIA FREGADERO A/F PICO FLEXIBLE DOBLE FUNCION MA","7592978397532","MET-406"],
            ["GRIFERIA INDIVIDUAL ACERO INOX ACQUABELA","7451304214830","GRI-3383AC"],
            ["GRIFERIA INDIVIDUAL ACERO INOX ACQUABELA","7451304214847","GRI-7855AC"],
            ["GRIFERIA INDIVIDUAL PARA FREGADERO AGUA FRIA/CROMADO ACQUABELA","7451304214939","GRI-1161CR"],
            ["GRIFERIA INDIVIDUAL PARA FREGADERO CUELLO FLEXIBLE GRIVEN","7453038414289","GV-SSF-03"],
            ["GRIFERIA INDIVIDUAL/AGUA FRIA CROMADO ACQUABELA","7451304214922","GRI-9568CR"],
            ["GRIFERIA P/FREGADERO 8 CUELLO METALICO PICO ALTO GRIMAX","6900000002025","GRI-SD202"],
            ["GRIFERIA P/FREGADERO A/F PICO DIRECCIONAL METALES ALEADOS","7592978397709","MET-442"],
            ["GRIFERIA P/FREGADERO MONOM CROMADO C-MAXX","0203070205002","CMX-406"],
            ["GRIFERIA P/FREGADERO PICO DE 8 MANILLA CROMADA FERMETAL","7592032006059","GRI-17"],
            ["GRIFERIA P/FREGADERO PICO SEVEN C-MAXX","803070205005","CMX-405"],
            ["GRIFERIA PARA FREGADERO 7.9 MANIJAS D CRUZ GRIVEN","7453038432351","A367-P8501"],
            ["GRIFERIA PARA FREGADERO 8 CON DOBLE MANIJAS GRIVEN BASIC","7453038433112","GVB-P8507-K3"],
            ["GRIFERIA PARA FREGADERO 8 CUELLO ALTO GRIVEN","7453010088644","A367-WF802"],
            ["GRIFERIA PARA FREGADERO 8 CUELLO DE CISNE GRIVEN","7453010047832","A367-15061-K2"],
            ["GRIFERIA PARA FREGADERO 8 DOBLE MANIJA GRIVEN","7453038431064",""],
            ["GRIFERIA PARA FREGADERO A/F MANILLA TIPO CRUCETA MA","7592978398058","MET-554"],
            ["GRIFERIA PARA FREGADERO A/F PICO FLEXIBLE DOBLE FUNCION MA","7592978397853","MET-427"],
            ["GRIFERIA PARA FREGADERO CUELLO CISNE MANILLA DROP FERMETAL","7592032020574","GRI-37"],
            ["GRIFERIA PARA FREGADERO CUELLO CISNE MANILLA KING II FERMETAL","7592032005472","GRI-27"],
            ["GRIFERIA PARA FREGADERO DOBLE CON MANIJA GRIVEN","7453038427463",""],
            ["GRIFERIA PARA FREGADERO DOBLE MANILLA 8 GRIVEN","7453038412414","A367-15061B-K2"],
            ["GRIFERIA PARA FREGADERO INDIVIDUAL GRIVEN ","7453078543147","A367-KX81043W"],
            ["GRIFERIA PARA FREGADERO INDIVIDUAL NEGRO GRIVEN","7453038495875","A367-SH87095-BK"],
            ["GRIFERIA PARA FREGADERO MONOMANDO FLEXIBLE NEGRO GRIVEN","7453038423274","A367-SH700B-BK"],
            ["GRIFERIA PARA FREGADERO MONOMANDO FLEXIBLE ROJO GRIVEN","7453038468329","A367-SH700-RO"],
            ["GRIFERIA PARA FREGADERO PICO FLEXIBLE BOBLE FUNCION M/A","7592978397570","MET-410"],
            ["GRIFERIA PARA LAVAMANOS 4 GRIVEN","7453038498081","A367-P8512"],
            ["GRIFERIA PARA LAVAMANOS 4 MANILLA PLASTICA CROMADA FERMETAL","7592032006219","GRI-21"],
            ["GRIFERIA PARA LAVAMANOS A/F EN ABS ALTURA 35CM MA","7592978398003","MET-424M"],
            ["GRIFERIA PARA LAVAMANOS INDIVIDUAL BLANCO GRIVEN","7453038495493","A367-ZF322"],
            ["GRIFERIA PARA LAVAMANOS INDIVIDUAL GRIVEN","7453038459730","A145-BIB002"],
            ["GRIFO BLANCO SENCILLO PARA FREGADERO HUMMER","7453100259114","HUM-1186"],
            ["GRIFO CENTRAL PARA LAVAMANO 4 GRIVEN","7453038434959",""],
            ["GRIFO DE COBRE 120G HUMMER","7453100259015","HUM-1176"],
            ["GRIFO DE LAVAMANO SENCILLO KOBATEX","230053846766","KB-G04"],
            ["GRIFO DE LAVAMANO TIPO CHORRO KOBATEX","230053846532","KB-G03"],
            ["GRIFO INDIVIDUAL PARA FREGADERO GRIVEN","7453038457835","A367-SH8700B"],
            ["GRIFO MONOMANDO FLEXIBLE PARA FREGADERO GRIVEN","7453038470100","A367-SH800B"],
            ["GRIFO PARA FREGADERO 8 DOBLE GRIVEN","7453010057008",""],
            ["GRIFO PARA FREGADERO DOBLE MANIJA CUELLO TIPO CISNE GRIVEN","7453038436472",""],
            ["GRIFO PARA LAVAMANO DEEP BLUE","740-0201",""],
            ["GRIFO PARA LAVAMANOS KOBATEX","230053846535","KB-GL02"],
            ["GRIFO PARA LAVAPLATOS KOBATEX ","230053846624","KB-HC01"],
            ["GRIFO SENCILLO CROMADO PARA LAVAMANOS HUMMER","7453100259077","HUM-1182"],
            ["HERRAJE C/FLOTADOR COMPACTO GRIVEN","7453038421270","GV-MP018"],
            ["HERRAJE CON FLOTANTE METCO","6941571274411","MTL127441"],
            ["HERRAJE DE MANILLA P/POCETA 1/2 UNIVERSAL KOBATEX ","6924323200197","HRJ-PP-2"],
            ["HERRAJE DE POCETA DE BOTON GRIVEN","7453038460934","A367-MP005"],
            ["HERRAJE DE POCETA DE MANILLA AGUA NUOVA","0932073000000",""],
            ["HERRAJE DE POCETA DE MANILLA GRIVEN","7453078509372","A367-MP"],
            ["HERRAJE DE POCETA METALES ALEADOS","7592978009565","LX-1001WT"],
            ["HERRAJE DE POCETA METALES ALIADOS","7592978396450","BPK-0001"],
            ["HERRAJE DE POCETA UNIVERSAL MANILLA GRIVEN","7453010048280","A367-MP015"],
            ["HERRAJE PARA INODORO BOTON DOBLE GRIVEN","7453038495820","A367-MP010"],
            ["HERRAJE PARA INODORO HUMMER","7453100258858","HUM-1160"],
            ["HERRAJE PARA W.C AQUAFINA","7453050045829","A-ZDD104"],
            ["HERRAJE PARA W.C BOTON SUPERIOR GRIMAX","GR-ST5600",""],
            ["HERRAJE STANDAR W.C LF","205000008559","04-010-014"],
            ["HERRAJE UNIVERSAL PARA POCETA CON TORNILLOS","734896113949","HERP02"],
            ["HIDROJET 110V 1200W 1305PSI JADEVER","6942210207043","UJDHP1A11"],
            ["HIDROJET 110V 1200W 1305PSI JADEVER","6942210207104","UJDHP3A12"],
            ["HIDROJET 110V 1400W 1595PSI JADEVER","6942210207142","UJDHP3A14"],
            ["HIDROJET 110V 1800W 1585PSI JADEVER","6942210207203","UJDHP3A18"],
            ["HIDROJET 110V 2200W 2320PSI JADEVER","6942210207289","UJDHP3A22"],
            ["HIDROJET 1200W 1305PSI WADFOW","6976057337106","UWHP3A12"],
            ["HIDROJET 1400W 1500 PSI BLACK & DECKER","885911741224","HID-702"],
            ["HIDROJET 1400W 1595PSI WADFOW","6976057337113","UWHP3A14"],
            ["HIDROJET 1400W CHESTERWOOD","7592346017505","CHEQ1000073"],
            ["HIDROJET 1400W EMTOP ","6941556221584","ULHPW1401"],
            ["HIDROJET 1500W INGCO","HIDRO-1500",""],
            ["HIDROJET 1600PSI DOMOSA ","HIDROJET-1600-D",""],
            ["HIDROJET 1600W BLACK-DECKER ","885911782869","BEPW1600"],
            ["HIDROJET 1800W 2200 PSI EMTOP ","6941556211684","ULHPW1801"],
            ["HIDROJET DE ALTA PRESION 1200W INGCO","6941640169662","UHPWR12008"],
            ["HIDROJET DE ALTA PRESION 1400W INGCO","6925582125771","UHPWR14008"],
            ["HIDROJET DE ALTA PRESION 1500W INGCO","6941640154552","UHPWR15028"],
            ["HIDROJET DE ALTA PRESION 1800 W INGCO","UHPWR18008","UHPWR18008"],
            ["HIDROJET DE ALTA PRESION A GASOLINA DE 0.7HP INGCO","GHPW2103",""],
            ["HIDROJET HL-1400 DOMOSA ","HIDROJET-1400-D",""],
            ["HIDROLAVADORA DE ALTA PRESION 1600W 2000PS RUN","734896113604","HID03"],
            ["HIDROLAVADORA DE ALTA PRESION 1900W 220PSI RUN","734896113611","HID04"],
            ["HOJA DE SEGUETA BELLOTA 12 ","7702956200017","4601-18X100"],
            ["HOJA DE SEGUETA BIMETAL 12 INGCO","HSBB22326",""],
            ["HOJA DE SEGUETA BIMETAL 24T JADEVER","26942210219606","JDHB2H24"],
            ["HOJA DE SEGUETA COVO","7453038476423","A145-9HS24"],
            ["HOJA DE SEGUETA COVO 18 DIENTES","7453038493680","CV-SB-0218"],
            ["HOJA DE SEGUETA COVO 24 DIENTES","7453038428064","CV-SB-0424"],
            ["HOJA DE SEGUETA DIESEL TOOLS","7453012377081","DT-222076"],
            ["HOJA DE SEGUETA INGCO BIMETAL 24T 18T","6941640159564",""],
            ["HOJA DE SEGUETA LENOX 12 ","082472201161","HOJ-703"],
            ["INTERRUCTOR CLASSIC LUX SUPERFICIAL","INT-20","INT-20"],
            ["INTERRUCTOR DOBLE 3V LUZICA PU1201","162886",""],
            ["INTERRUCTOR DOBLE GRIS P/EMPOTRAR TROEN","7453038483964-D","A136-ETP-2K"],
            ["INTERRUCTOR DOBLE METALIZADO 1 WAY 10A 110V-220V RUN","736373171756","MET05"],
            ["INTERRUCTOR DOBLE NEGRO TROEN","7453010085193","A136-ETB-2K"],
            ["INTERRUCTOR SENCILLO LEGRAND BLANCO APAGADOR&nbsp; 0078","0078",""],
            ["INTERRUCTOR SENCILLO TROEN","7453038439824","9A136-ZA123-1"],
            ["INTERRUCTOR+TOMA 2P+T LUZICA PU1224","163005",""],
            ["INTERRUPTOR 32 BLANCO 602B","8012199814827","602B"],
            ["INTERRUPTOR 32 GRIS 602G","8012199814841",""],
            ["INTERRUPTOR APAGADOR DOBLE ELEGANTE BORDE CURVO NEGRO VERT","6234566690328","A80-N03"],
            ["INTERRUPTOR APAGADOR DOBLE ELEGANTE PLANO BORDE BLANCO VERT","6234566690700","AZC-B03"],
            ["INTERRUPTOR APAGADOR ELEGANTE BORDE CURVO NEGRO VERT","6234566690267","A80-N01"],
            ["INTERRUPTOR APAGADOR SENCILLO 2 WAY MODERNO BLANCO CROMO VERT","1690201300697","S3-ASS02"],
            ["INTERRUPTOR APAGADOR SENCILLO 3 WAY PLATEADO VERT","6900050116505","KAA-001"],
            ["INTERRUPTOR APAGADOR TRIPLE 3 WAY MODERNO BLANCO MATE VERT","6900050116628","KAP-003"],
            ["INTERRUPTOR BEIGE TROEN","7453038409780","TR-107AW"],
            ["INTERRUPTOR BIPOLAR DE 20 A 32 AMP 110-130V MOD 60 FERMETAL","7593826013567","INT-77"],
            ["INTERRUPTOR CON TOMA SENCILLA","0679231560692","LA202"],
            ["INTERRUPTOR CON TOMACORRIENTE BLANCO KOBATEX ","00125346000005","KB105"],
            ["INTERRUPTOR CON TOMACORRIENTE DE ALUMINIO KOBATEX ","00125346000025","KPA05"],
            ["INTERRUPTOR CON TOMACORRIENTE GRIS KOBATEX ","00125346000055","KPP05"],
            ["INTERRUPTOR CONMUTABLE SENCILLO OLIVO 6876210B","7702089688829",""],
            ["INTERRUPTOR DE PARED 380V VITRON","205000008725","03-016-032"],
            ["INTERRUPTOR DE PARED FUSIBLE VERT","9184751978557","JEH-015"],
            ["INTERRUPTOR DIMMER GRIS TROEN","7453038408479","A136-ETP-DM"],
            ["INTERRUPTOR DOBLE 2 WAY BLANC/CROMO VERT","1460201300467","S2N-ADW03"],
            ["INTERRUPTOR DOBLE 2 WAY MARRON VERT","1130201300131","SS1-ADN03"],
            ["INTERRUPTOR DOBLE 2 WAY MODERNO BLANCO CROMO VERT","9780201376715","AJP-B05"],
            ["INTERRUPTOR DOBLE 3 WAY BLANCO VERT","6900050116611","KAP-002"],
            ["INTERRUPTOR DOBLE 3 WAY PLATEADO VERT","6900050116512","KAA-002"],
            ["INTERRUPTOR DOBLE BLANCO KOBATEX ","00125346000004","KB104"],
            ["INTERRUPTOR DOBLE BLANCO MODERNO VERT","9780306997785","JEH-065"],
            ["INTERRUPTOR DOBLE BLANCO TROEN","7453038497688","A136-ETW-2K"],
            ["INTERRUPTOR DOBLE CATIANA RUN","736373169999","INT14"],
            ["INTERRUPTOR DOBLE CITRON","205000008523","03-014-159"],
            ["INTERRUPTOR DOBLE CLASSIC LUX","4574663139715","LUM-82"],
            ["INTERRUPTOR DOBLE CTRIWAY DE ACERO GRIS PLOMO VERT ","9780201376821","AJP-P05"],
            ["INTERRUPTOR DOBLE CTRIWAY MODERNO NEGRO VERT","9780201374759","AJP-N05"],
            ["INTERRUPTOR DOBLE DE ACERO 10A UNA VIA 127/250V GRIS HUMMER","7453100283577","HUM-SW802-GR"],
            ["INTERRUPTOR DOBLE DE ACERO GRIS HUMMER","7453100283669","HUM-DW802-GR"],
            ["INTERRUPTOR DOBLE DE ALUMINIO KOBATEX ","00125346000024","KPA04"],
            ["INTERRUPTOR DOBLE DUALE","7891154140925","NOM253"],
            ["INTERRUPTOR DOBLE ELECGANTE BORDE CURVO GRIS VERT","6234566690311","A80-G03"],
            ["INTERRUPTOR DOBLE ELEGANTE DORADO VERT","6234566690922","A31-D02"],
            ["INTERRUPTOR DOBLE GRIS KOBATEX ","00125346000054","KPP04"],
            ["INTERRUPTOR DOBLE LUMISTAR","7591996003920",""],
            ["INTERRUPTOR DOBLE LUZICA PU1200","7702089162848",""],
            ["INTERRUPTOR DOBLE NEPAL RUN","736373169913","INT06"],
            ["INTERRUPTOR DOBLE OLIVO 687616OB","610820",""],
            ["INTERRUPTOR DOBLE SENCILLO DE ACERO 10A UNA VIA 127/250V BLANCO HUMMER","7453100283430","HUM-SW802-WH"],
            ["INTERRUPTOR ELEGANTE BORDE CURVO GRIS VERT","6234566690250","A80-G01"],
            ["INTERRUPTOR ELEGANTE DORADO VERT","6234566690861","A31-D01"],
            ["INTERRUPTOR ELEGANTE GRIS PLATA VERT","6234566690540","A16-A01"],
            ["INTERRUPTOR GRANDE BLANCO KOBATEX ","00125346000003","KB103"],
            ["INTERRUPTOR GRANDE DE ALUMINIO KOBATEX ","00125346000023","KPA03"],
            ["INTERRUPTOR GRANDE GRIS KOBATEX ","00125346000053","KPP03"],
            ["INTERRUPTOR LITE SENCILLO VITRON","205000008526","03-014-158"],
            ["INTERRUPTOR MODERNO BLANCO VERT","6900050116635","KAP-004"],
            ["INTERRUPTOR MONOFASICO TICINO RUN ","736373171664","INT601"],
            ["INTERRUPTOR SENCILLO + TOMA AMERICANA SWITCH SOCKET","7594320516325","040405"],
            ["INTERRUPTOR SENCILLO + TOMA CORRIENTE BLANCO TROEN ","7453038493826","TR-HYD-1K1C"],
            ["INTERRUPTOR SENCILLO 3 WAY MODERNO BLANCO MATE VERT","6900050116604","KAP-001"],
            ["INTERRUPTOR SENCILLO 3 WAY TROEN","7453010063245","A136-HYA-1KY-3W"],
            ["INTERRUPTOR SENCILLO 3V LUZICA PU1101","7702089162541",""],
            ["INTERRUPTOR SENCILLO BLANCO C/LUZ REFLECTIVA FOX","745656811814","118Z-01"],
            ["INTERRUPTOR SENCILLO BLANCO TROEN","7453038402132","A136-ETW-1K"],
            ["INTERRUPTOR SENCILLO BLANCO TROEN ","7453010039882","TR-HYD-1K"],
            ["INTERRUPTOR SENCILLO BLANCO VERT","9780201376944","AJV-B03"],
            ["INTERRUPTOR SENCILLO BLANCO VERT ","9780306997778","JEH-064"],
            ["INTERRUPTOR SENCILLO CATANIA RUN","736373169982","INT13"],
            ["INTERRUPTOR SENCILLO CLASSIC LUX","3471284510108","LUM-81"],
            ["INTERRUPTOR SENCILLO CTRIWAY DE ACERO GRIS PLOMO VERT","9780201376791","AJP-P03"],
            ["INTERRUPTOR SENCILLO CTRIWAY MODERNO BLANCO CROMO VERT","9780201376692","AJP-B03"],
            ["INTERRUPTOR SENCILLO CTRIWAY MODERNO NEGRO VERT","9780201374735","AJP-N03"],
            ["INTERRUPTOR SENCILLO DE ACERO 10A UNA VIA 127V/250V GRIS HUMMER","7453100283560","HUM-SM801-GR"],
            ["INTERRUPTOR SENCILLO DE ACERO BLANCO HUMMER","7453100283515","HUM-DW801-WH"],
            ["INTERRUPTOR SENCILLO DE ACERO GRIS HUMMER","7453100283652","HUM-DW801-GR"],
            ["INTERRUPTOR SENCILLO DE UNA VIA BLANCO HUMMER","7453100283423","HUM-SW801-WH"],
            ["INTERRUPTOR SENCILLO FERMETAL","7592032053558","INT-55"],
            ["INTERRUPTOR SENCILLO LUZICA PU1100","7702089162503",""],
            ["INTERRUPTOR SENCILLO METALIZADO 1 WAY 110V-220V RUN","736373171732","MET03"],
            ["INTERRUPTOR SENCILLO NEGRO VERT","9780201374827","AJV-N02"],
            ["INTERRUPTOR SENCILLO NEPAL RUN","736373169906","INT05"],
            ["INTERRUPTOR SENCILLO OLIVO 687611OB","769443",""],
            ["INTERRUPTOR SENCILLO SANTIAGO RUN","736373169869","INT01"],
            ["INTERRUPTOR SENCILLO SUPERFICIAL OVAL TRIC","7453118010264","T-EA52"],
            ["INTERRUPTOR SENCILLO SWITCH SOCKET","7594320516301","040403"],
            ["INTERRUPTOR SENCILLO VENECIA RUN","736373169944","INT09"],
            ["INTERRUPTOR SENCILLO+TOMA TROEN","7453078546414","A136-HYA1K1C"],
            ["INTERRUPTOR SIMPLE P/SOBREPO 10A VITRON","2050000009851","03-014-176"],
            ["INTERRUPTOR SUPERFICIAL MARFIL METALES ALIADOS","7592978394074","MAE-52M"],
            ["INTERRUPTOR SUPERFICIAL RUN","736373170063","INT21"],
            ["INTERRUPTOR TIPLE DE ACERO 10A UNA VIA 127/250V GRIS HUMMER","7453100283584","HUM-SW803-GR"],
            ["INTERRUPTOR TRIPLE 3V LUZICA PU1301","7702089163180",""],
            ["INTERRUPTOR TRIPLE ACERO INOX TROEN","7453078505107","TR-ETSS-3K"],
            ["INTERRUPTOR TRIPLE BLANCO KOBATEX ","00125346000007","KB107"],
            ["INTERRUPTOR TRIPLE BLANCO TROEN","7453038474078","TR-HYD-3K"],
            ["INTERRUPTOR TRIPLE BLANCO VERT","9780306997792","JEH-066"],
            ["INTERRUPTOR TRIPLE CLASSIC LUX","976631316331","LUM-84"],
            ["INTERRUPTOR TRIPLE DE ACERO GRIS HUMMER","7453100283676","HUM-DW803-GR"],
            ["INTERRUPTOR TRIPLE DE ACERO UNA VIA BLANCO HUMMER","7453100283447","HUM-SW803-WH"],
            ["INTERRUPTOR TRIPLE DE ALUMINIO KOBATEX ","00125346000027","KPA07"],
            ["INTERRUPTOR TRIPLE DUALE","7891154140956","NOM253"],
            ["INTERRUPTOR TRIPLE GRIS KOBATEX ","00125346000057","KPP07"],
            ["INTERRUPTOR TRIPLE LUZICA PU1300","5702289163142",""],
            ["INTERRUPTOR TRIPLE NEPAL RUN","736373169920","INT07"],
            ["INTERRUPTOR TRIPLE OLIVO 6876170B","7702089787423",""],
            ["INTERRUPTOR TRIPLE RUN","736373170001","INT15"],
            ["INTERRUPTOR TROEN TIPO TIZINO CAJA VERDE","7453078539553","7453078539553"],
            ["INTERRUPTOR UNA VIA BLANCO HUMMER","7453100283485","HUM-11819-WH"],
            ["INTERRUPTOR UNA VIA GRIS HUMMER","7453100283621","HUM-11819-GR"],
            ["INTERRUPTOR UNA VIA TV+PC BLANCO HUMMER","7453100283508","HUM-11879-WH"],
            ["INTERRUPTOR Y TOMACORRIENTE DE ACERO GRIS HUMMER","7453100283683","HUM-DW807-GR"],
            ["INTERRUPTOR Y TOMACORRIENTE DE ACERO GRIS HUMMER","7453100283690","HUM-DW809-GR"],
            ["INTERRUPTOR+TOMACORRIENTE DE ACERO UNA VIA 15A/10 BLANCO HUMMER","7453100283454","HUM-SW807-WH"],
            ["INTERRUPTOR+TOMACORRIENTE DE ACERO UNA VIA 15A/10A BLANCO HUMMER","7453100283478","HUM-SW809-WH"],
            ["INTERRUPTOR+TOMACORRIENTE DE ACERO UNA VIA 15A/10A GRIS HUMMER","7453100283591","HUM-SW807-GR"],
            ["INTERRUPTOR+TOMACORRIENTE DE ACERO UNA VIA 15A/10A GRIS HUMMER","7453100283614","HUM-SW809-GR"],
            ["INTERUPTOR DOBLE 16A INGCO","6925582143584","HESSSH18121"],
            ["JUEGO DE RODILLO Y BROCHA PRO PAINT","7453078528977","A145-XEPT-1"],
            ["KIT DE BANDEJA RODILLO 9 6 PCS COVO","7453038411028","CV-PSET-6PCS"],
            ["LAMINA DE HIERRO PULIDA 2.4X1.20 C20","LAMINA-HIERRO",""],
            ["LAMINA DE HIERRO PULIDA 2X1 C20","LAMINA-PULIDA-2X1-C20",""],
            ["LAMINA DE HIERRO PULIDA 2X1 C22","LAMINA-PULIDA-2X1-C22",""],
            ["LAMINA DE PARED M01 MARMOL BLANCO 2.44MTS X 1.22MTS UNITEC","PARED-M01-244","PARED-M01-244"],
            ["LAMINA DE PARED M03 MARMOL NEGRO 2.44MTS X 1.22MTS UNITEC","PARED-M03-244","PARED-M03-244"],
            ["LAMINA DE PARED M04 MARMOL VETEADO 2.44MTS X 1.22MTS UNITEC","PARED-M04-244","PARED-M04-244"],
            ["LAMINA DE PARED M05 MARMOL CAFE PIEDRA 2.44MTS X 1.22MTS UNITEC","PARED-M05-244","PARED-M05-244"],
            ["LAMINA DE PARED M06 MARMOL LUNA 2.44MTS X 1.22MTS UNITEC","PARED-M06-244","PARED-M06-244"],
            ["LAMINA DE PARED M07 MARMOL VETA PIACERE 2.44MTS X 1.22MTS UNITEC","PARED-M07-244","PARED-M07-244"],
            ["LAMINA DE PARED M10 SAHARA 2.44MTS X1.22MTS UNITEC","PARED-M10-244","PARED-M10-244"],
            ["LAMINA DE PARED PVC ESPEJO SILVER 2.44MTSX1.22MTS UNITEC","ESPEJO-SILVER","ESPEJO-SILVER"],
            ["LAMINA DE ZINC 2.44MTS","LAMINA-ZINC-2.44","LAMINA-ZINC-2.44"],
            ["LAMINA DE ZINC 3.60MTS CALIBRE 0.17MM","LAMINA-ZINC-A-0.17","LAMINA-ZINC-A-0.17"],
            ["LAMINA DE ZINC 3.66MTS CALIBRE 0.17MM","LAMINA-ZINC-A-3.60MTS-0.17","LAMINA-ZINC-A-3.66-0.17"],
            ["LAMINA DE ZINC 3.66MTS CALIBRE 0.18MM","LAMINA-ZINC-0.18","LAMINA-ZINC-0.18"],
            ["LAMINA DE ZINC 3.66MTS CALIBRE 0.20MM ","LAMINA-ZINC-0.20","LAMINA-ZINC-0.20"],
            ["LAMINA DIODO 200W","DWD200W","DWD200W"],
            ["LAMINA LED 100W 85/265V CLASSIC LUX","537458100052",""],
            ["LAMINA LED 100W AXUM 040317","7594320516967",""],
            ["LAMINA LED 100W ECO VERT","9780123379627","LE-100W"],
            ["LAMINA LED 100W SDWD-100W RIOMAX","85-265V","SDWD-100W"],
            ["LAMINA LED 100W SUPER LIGHT","7453010073718",""],
            ["LAMINA LED 200W AXUM","7594320516974",""],
            ["LAMINA LED 200W SDWD-200W-RIOMAX","SDWD-200W","SDWD-200W"],
            ["LAMINA LED 50W 110-120V D-LED LITE","205000008470","03-015-083"],
            ["LAMINA LED 50W 85/265V ","792364127952",""],
            ["LAMINA LED 50W AXUM","7594320516950","040316"],
            ["LAMINA LED 50W ECO4","6780201396518","LE-5012"],
            ["LAMINA LED 50W RECTANGULAR VERT","RLA-50W",""],
            ["LAMINA LED 50W RUN","736373170575","LAIOTUN03"],
            ["LAMINA LED 50W SDWD-50W RIOMAX","SDWD-50W","SDWD-50W"],
            ["LAMINA LED CONTI MULTIVOLTAJE 100W","CONTI-100W",""],
            ["LAMINA LED CONTI MULTIVOLTAJE 50W","CONTI-50W",""],
            ["LAMINA LED DIODO 400W","DWD400W","DWD400W"],
            ["LAMINA LED REIK 100W","REIK-LAMI-100W",""],
            ["LAMINA LED REIK 100W 12V ","LAMINA-LED-12W-100W",""],
            ["LAMINA LED REIK 200W","REIK-LAMINA-200W",""],
            ["LAMINA LED REIK 40W + SOCATE","LAMINA-LED-SOCATE",""],
            ["LAMINA LED REIK 50W","REIK-LAMINA-50W",""],
            ["LAMINA LED REIK 50W 12V","LAMINA-LED-12W-50W",""],
            ["LAMP LED PLANA SUP 72W 65K 2.4MTS CLASSIC LUX","3742845116955","TFL-LED7"],
            ["LAMP LED PLANA SUPERFICIAL 18W CLASSIC LUX ","4375214800125","TFL-LED5"],
            ["LAMP LED PLANA SUPERFICIAL 36W CLASSIC LUX ","1286325252013","TFL-LED6"],
            ["LAMPARA CIRCULAR EMPOTRABLE 18W AXUM ","7594320517179",""],
            ["LAMPARA CIRCULAR EMPOTRABLE 24W AXUM 295X13MM PAL-24W ","7594320516912",""],
            ["LAMPARA CIRCULAR EMPOTRABLE 3W AXUM ","7594320517001",""],
            ["LAMPARA CIRCULAR EMPOTRABLE 6W AXUM ","7594320517032","000040211"],
            ["LAMPARA CIRCULAR EMPOTRABLE12W AXUM ","7594320517117",""],
            ["LAMPARA CIRCULAR SUPEFICIAL COLMENA 24W AXUM 170X170MM STR-24W B7 ","7594320517261",""],
            ["LAMPARA CIRCULAR SUPERFICIAL 12W AXUM ","7594320517124",""],
            ["LAMPARA CIRCULAR SUPERFICIAL 18W AXUM ","7594320517186",""],
            ["LAMPARA CIRCULAR SUPERFICIAL 24W AXUM ","7594320516936",""],
            ["LAMPARA CIRCULAR SUPERFICIAL 6W AXUM ","7594320517049",""],
            ["LAMPARA CL-181 ","6956369601515","CL-181"],
            ["LAMPARA CL-860 ","CL-860",""],
            ["LAMPARA CUADRADA EMPOTRABLE 12W AXUM ","7594320516028",""],
            ["LAMPARA CUADRADA EMPOTRABLE 18W AXUM ","7594320516035",""],
            ["LAMPARA CUADRADA EMPOTRABLE 24W AXUM ","7594320516929",""],
            ["LAMPARA CUADRADA EMPOTRABLE 3W AXUM ","7594320515991",""],
            ["LAMPARA CUADRADA EMPOTRABLE 6W AXUM ","7594320516004",""],
            ["LAMPARA CUADRADA EMPOTRABLE 9W AXUM ","7594320516011",""],
            ["LAMPARA CUADRADA LED 12W VERT PPARED PLASTICA NEGRO ","9780201110104","ALV-003"],
            ["LAMPARA CUADRADA SUPERFICIAL 12W AXUM ","7594320516059",""],
            ["LAMPARA CUADRADA SUPERFICIAL 18W AXUM ","7594320516066",""],
            ["LAMPARA CUADRADA SUPERFICIAL 24W AXUM ","7594320516943",""],
            ["LAMPARA CUADRADA SUPERFICIAL 6W AXUM ","7594320516042",""],
            ["LAMPARA DE ALUMBRADO PUBLICO 60W AXUM CON PANEL 2 FOCOS ","7594320516882",""],
            ["LAMPARA DE ALUMBRADO PUBLICO 60W AXUM CON PANEL 4 FOCOS ","7594320516868",""],
            ["LAMPARA DE ALUMBRADO PUBLICO 90W AXUM CON PANEL 6 FOCOS ","7594320516875",""],
            ["LAMPARA DE ALUMBRADO PUBLICO 90W CON PANEL 3 FOCOS ","7594320516899",""],
            ["LAMPARA DE EMERGENCIA 15W RUN ","736373171220",""],
            ["LAMPARA DE ESTACIONAMIENTO 18W AXUM","7594320516202",""],
            ["LAMPARA DE ESTACIONAMIENTO 28W AXUM ","7594320516189",""],
            ["LAMPARA DE ESTACIONAMIENTO 36W AXUM","7594320516219",""],
            ["LAMPARA DE ESTACIONAMIENTO 54W AXUM","7594320516196",""],
            ["LAMPARA DE MESA Y OFICINA VERT LED RGB TACTIL RECARGABLE ","6780201002013","LML-T04"],
            ["LAMPARA DE UÑA SUNY6","SUNY6","SUNY6"],
            ["LAMPARA ELECTRONICA DE PLAFON 22W CUADRADA C/BOMBILLO FERMETAL","759382601494","LAM-71"],
            ["LAMPARA ELECTRONICA DE PLAFON CUADRADA CON BOMBILLO FERMETAL","7593826014724","LAM-49"],
            ["LAMPARA INCANDESCENTE 7W DE PARED TROEN ","7453078546667",""],
            ["LAMPARA LED 12W CIRCULAR EMPOTRABLE CHESTERWOOD","7592346015464",""],
            ["LAMPARA LED 12W CIRCULAR SUPERFICIAL CHESTERWOOD","7592346015549",""],
            ["LAMPARA LED 12W CUADRADA EMPOTRABLE CHERTERWOOD","7592346015501",""],
            ["LAMPARA LED 12W CUADRADA SUPERFICIAL CHESTERWOOD","7592346015587",""],
            ["LAMPARA LED 18W CIRCULAR EMPOTRABLE CHESERWOOD","7592346015471",""],
            ["LAMPARA LED 18W CIRCULAR SUPERFICIAL CHESTERWOD","7592346015556",""],
            ["LAMPARA LED 18W CUADRADA EMPOTRABLE CHESTERWOOD","7592346015518",""],
            ["LAMPARA LED 18W CUADRADA SUPERFICIAL CHESTERWOOD","7592346015594",""],
            ["LAMPARA LED 18W EXXEL ","205000007421","03-004-129"],
            ["LAMPARA LED 24W CIRCULAR EMPOTRABLE CHESTERWOOD","7592346015488","CHMA1003606"],
            ["LAMPARA LED 24W CIRCULAR SIN BORDE FERCO","7592070254993","FLPL-PISR-24W"],
            ["LAMPARA LED 24W CIRCULAR SUPERFICIAL CHESTERWOOD","7592346015563",""],
            ["LAMPARA LED 24W CIRCULAR SUPERFICIAL FERCO ","7592070250452","FLPL-PSR-B24W"],
            ["LAMPARA LED 24W CUADRADA EMPOTRABLE CHESTERWOOD","7592346015525",""],
            ["LAMPARA LED 24W CUADRADA SUPERFICIAL CHESTERWOOD","7592346015600",""],
            ["LAMPARA LED 24W EXXEL ","205000007423","03-004-130"],
            ["LAMPARA LED 30W LUMILED","6987021126329","DT15-MLED2B"],
            ["LAMPARA LED 36W EXXEL 03-004-131 ","205000007425",""],
            ["LAMPARA LED 50W ECO","6110201079607","LE-C506"],
            ["LAMPARA LED 50W TIPO COBRA 100-277V ANGEL LIGHT ","ATW0463","AL-COBRA-50W"],
            ["LAMPARA LED 6W CIRCULAR EMPOTRABLE CHESTERWOOD","7592346015457",""],
            ["LAMPARA LED 6W CIRCULAR SUPERFICIAL CHESTERWOOD","7592346015532",""],
            ["LAMPARA LED 6W CUADRADA EMPOTRABLE CHESTERWOD","7592346015495",""],
            ["LAMPARA LED 6W CUADRADA SUPERFICIAL CHESTERWOD","7592346015570",""],
            ["LAMPARA LED CIRCULAR PARA EMPOTRAR 6W EXXEL","205000008926","03-015-112"],
            ["LAMPARA LED CUADRADA P/EMPOTRAR BLANCA 12W","6974632086166","AI-2041R-DL"],
            ["LAMPARA LED CUADRADA P/EMPOTRAR BLANCA 18W","6974632088399","AI-2042R-DL"],
            ["LAMPARA LED CUADRADA P/EMPOTRAR BLANCA 24W","6974632089099","AI-2043R-DL"],
            ["LAMPARA LED CUADRADA P/EMPOTRAR BLANCA 3W","6974632086685","AI-2045R-DL"],
            ["LAMPARA LED CUADRADA P/EMPOTRAR BLANCA 6W ARTIG LIGHT","6974632088955","AI-2040R-DL"],
            ["LAMPARA LED CUADRADA PARA EMPOTRAR 12W EXXEL","205000008936","03-015-117"],
            ["LAMPARA LED CUADRADA PARA EMPOTRAR 18W EXXEL","205000008938","03-015-118"],
            ["LAMPARA LED CUADRADA PARA EMPOTRAR 24W EXXEL","205000008940","03-015-119"],
            ["LAMPARA LED CUADRADA PARA EMPOTRAR 6W EXXEL","205000008934","03-015-116"],
            ["LAMPARA LED CUADRADA SOBREPONER 6W EXXEL","205000008950","03-015-124"],
            ["LAMPARA LED CUADRADA SUPERFICIAL 12W EXXEL","205000008952","03-015-125"],
            ["LAMPARA LED CUADRADA SUPERFICIAL 18W EXXEL","205000008954","03-015-126"],
            ["LAMPARA LED CUADRADA SUPERFICIAL 24W EXXEL","205000008956","03-015-127"],
            ["LAMPARA LED CUADRADA SUPERFICIAL BLANCA 12W","6974632080065","AI-2051S-DL"],
            ["LAMPARA LED CUADRADA SUPERFICIAL BLANCA 18W","6974632080072","AI-2052S-DL"],
            ["LAMPARA LED CUADRADA SUPERFICIAL BLANCA 24W","6974632080089","AI-2053S-DL"],
            ["LAMPARA LED CUADRADA SUPERFICIAL BLANCA 6W","6974632080058","AI-2050S-DL"],
            ["LAMPARA LED DE ALUMBRADO PUBLICO 150W TIPO COBRA 3 FOCOS AXUM","7594320403199","ALUMB-COBRA-150W"],
            ["LAMPARA LED DE EMERGENCIA 30W RUN ","736373171237",""],
            ["LAMPARA LED DE EMERGENCIA 50W RECARGA SOLAR RUN","736373171398","LAMLESOL01"],
            ["LAMPARA LED DE EMERGENCIA CON SENSOR 240MM RUN ","736373169562",""],
            ["LAMPARA LED DE EMERGENCIA REDONDA CON SENSOR 1W","736373171107","LLN01"],
            ["LAMPARA LED DICROICO E27 7W LUZ CALIDA RUN ","736373171015",""],
            ["LAMPARA LED DICROICO E27 7W RUN","736373170490",""],
            ["LAMPARA LED DICROICO GU10 7W RUN ","736373170469",""],
            ["LAMPARA LED DICROICO MR16 5W RUN ","736373170476",""],
            ["LAMPARA LED DICROICO MR16 7W RUN ","736373170483",""],
            ["LAMPARA LED EMPOTRABLE LUZ CALIDAAMARILLA ANGEL LIGHT","7453010014179","A105-PB001D-12W"],
            ["LAMPARA LED NOCTURNA TROEN ","7453010049959","A136-LD11P"],
            ["LAMPARA LED OJO DE BUEY 12W CUADRADA VERT","6684233379047","SSC-126"],
            ["LAMPARA LED OJO DE BUEY 12W REDONDO VERT","6582345676309","SPOT-22W6"],
            ["LAMPARA LED OJO DE BUEY 3W CUADRADO ANGEL LIGHT","7453010086251","DZ101S-3W"],
            ["LAMPARA LED OJO DE BUEY 5W CUADRADO ANGEL LIGHT","7453038435130","DZ104S-5W"],
            ["LAMPARA LED OJO DE BUEY 5W VERT","6680233379049","SPOT-5W3"],
            ["LAMPARA LED OJO DE BUEY 9W CUADRADA VERT","6673233379041","SSC-96"],
            ["LAMPARA LED OJO DE BUEY 9W REDONDO VERT","6660233379041","SPOT-9W6"],
            ["LAMPARA LED OJO DE BUEY CUADRADO 5W SAMSUNG LED VERT ","6672233379044","SSC-53"],
            ["LAMPARA LED OJO DE BUEY REDONDO SAMSUNG 7W VERT","1969091154277","SPS-026"],
            ["LAMPARA LED PARA EMPOTRAR CUADRADA 18W ANGEL LIGHT ","7453010002268","A105-PB003M-18W"],
            ["LAMPARA LED PARA EMPOTRAR LUZ CALIDA AMARILLA ANGEL LIGHT","7453010074012","A105-PB001D-24W"],
            ["LAMPARA LED PARA EMPOTRAR REDONDA 18W ANGEL LIGHT","7453010073701","A105-PB001D-18W"],
            ["LAMPARA LED PARA EMPOTRAR REDONDA 18W NIQUELADA LUZ BLANCA ANGEL LIGHT ","7453038487375","A105-PB001-18W-N"],
            ["LAMPARA LED PARA EMPOTRAR REDONDA 6W 4.7 ANGEL LIGHT ","7453010008468","A105-PB001D-6W"],
            ["LAMPARA LED PARA EXTERIOR 24W ANGEL LIGHT","7453038497404","A105-FCS02-24W"],
            ["LAMPARA LED PARA EXTERIOR 24W ANGEL LIGHT","7453038488792","A105-FCS03-24W"],
            ["LAMPARA LED PARA EXTERIOR 24W ANGEL LIGHT ","7453038487818","A105-FCS04-24W"],
            ["LAMPARA LED PLEGABLE 2 HOJAS 30W RUN ","734896111396",""],
            ["LAMPARA LED PLEGABLE 3 HOJAS 50W RUN ","734896111402",""],
            ["LAMPARA LED PLEGABLE 4 HOJAS 70W RUN ","734896111419",""],
            ["LAMPARA LED PLEGABLE 4/1 HOJAS 80W RUN ","734896111426",""],
            ["LAMPARA LED RECARGABLE 40W EXXEL","205000009929","03-015-142"],
            ["LAMPARA LED REDONDA 18W RUN","736373171206",""],
            ["LAMPARA LED REDONDA 24W EMP LUZ BLANCA ANGEL LIGHT ","7453078529127","A105-PB001-24W"],
            ["LAMPARA LED REDONDA 24W RUN","736373171213",""],
            ["LAMPARA LED REDONDA P/EMPOTRAR BLANCA 12W","6974632080119","AI-3041R-DL"],
            ["LAMPARA LED REDONDA P/EMPOTRAR BLANCA 18W","6974632080126","AI-3042R-DL"],
            ["LAMPARA LED REDONDA P/EMPOTRAR BLANCA 24W","6974632080133","AI-3043R-DL"],
            ["LAMPARA LED REDONDA P/EMPOTRAR BLANCA 3W","6974632080096","AI-3045R-DL"],
            ["LAMPARA LED REDONDA P/EMPOTRAR BLANCA 6W","6974632080102","AI-3040R-DL"],
            ["LAMPARA LED REDONDA PARA EMPOTRAR 12W EXXEL","205000008928","03-015-113"],
            ["LAMPARA LED REDONDA SUPERFICIAL 12W EXXEL","205000008944","03-015-121"],
            ["LAMPARA LED REDONDA SUPERFICIAL 18W EXXEL","205000008946","03-015-122"],
            ["LAMPARA LED REDONDA SUPERFICIAL 6W EXXEL","205000008942","03-015-120"],
            ["LAMPARA LED REDONDA SUPERFICIAL BLANCA 12W","6974632080027","AI-3051S-DL"],
            ["LAMPARA LED REDONDA SUPERFICIAL BLANCA 18W","6974632080034","AI-3052S-DL"],
            ["LAMPARA LED REDONDA SUPERFICIAL BLANCA 24W","6974632080041","AI-3053S-DL"],
            ["LAMPARA LED REDONDA SUPERFICIAL BLANCA 6W","6974632080010","AI-3050S-DL"],
            ["LAMPARA LED SOLAR C/CONTROL Y SENSOR/MOV SIN SOPORTE 200W STREET LIGHT","679231560173","SL-SSP-200W-STAR LIGHT"],
            ["LAMPARA LED SOLAR PARA EXTERIORES 60W ACCESORIOS RUN","734896111556","LEDSOL02-60W"],
            ["LAMPARA LED SOLAR PARA EXTERIORES 80W CON ACCESORIOS RUN","734896111563",""],
            ["LAMPARA LED SOLAR PARA EXTERIORES CONTROL REMOTO-ACCESORIOS DE INSTALACION RUN ","734896111532","20W-1 FOCO"],
            ["LAMPARA LED SOLAR RECARGABLE 1 FOCO 30W CLASSIC LUX ","462751983246","LUV-35"],
            ["LAMPARA LED SOLAR RECARGABLE 120W CLASSIC LUX","137000217001","LUV-40"],
            ["LAMPARA LED SOLAR RECARGABLE 180W CLASSIC LUX","7542185563329",""],
            ["LAMPARA LED SOLAR RECARGABLE 3 FOCOS 90W CLASSIC LUX","220002145791","LUV-39"],
            ["LAMPARA LED SOLAR RECARGABLE 300W CLASSIC LUX","7863254100239",""],
            ["LAMPARA LED SOLAR RECARGABLE 60W CLASSIC LUX","300045789130","LUV-37"],
            ["LAMPARA LED SUPERFICIAL 12W CLASSIC LUX ","8546632574110",""],
            ["LAMPARA LED SUPERFICIAL BLANCA CIRCULAR CLASSICI LUX","6630012547985",""],
            ["LAMPARA LED SUPERFICIAL CUADRADA 6W 4.3 ANGEL LIGHT ","7453038498609","A105-PB0025-6W"],
            ["LAMPARA LED SUPERFICIAL REDONDA 18W 3 TONOS ANGEL LIGHT ","7453038443203","AL-PBRCCT-18W"],
            ["LAMPARA LED SUPERFICIAL REDONDA 18W ANGEL LIGTH","7453010074746","A105-PB002R-18W"],
            ["LAMPARA LED SUPERFICIAL REDONDA 24W 3 TONOS ANGEL LIGHT ","7453038491426","AL-PBRCCT-24W"],
            ["LAMPARA LED TIPO UFO 18W RUN ","736373171251","LAMPLA01"],
            ["LAMPARA LED TIPO UFO 36W RUN ","736373171275",""],
            ["LAMPARA LED TIPO UFO 50W RUN ","736373171282",""],
            ["LAMPARA LINEAL BLANCA SOBREPONER 9W 30CM VERT","6180201379038","LAP-753"],
            ["LAMPARA MODERNA LED 4W VERT EN PLASTICO NEGRO ","9780201110128","ALV-007"],
            ["LAMPARA MODERNA LED 6W VERT PLASTICO BLANCO","9780201110180","ALV-013"],
            ["LAMPARA MODERNA LED 6W VERT PLASTICO NEGRO ","9780201110166","ALV-011"],
            ["LAMPARA MODERNA LED 8W VERT EN PLASTICO NEGRO ","9780201110210","ALV-016"],
            ["LAMPARA PARA UÑAS SUN-Y6","6901234987652","SUN-Y6"],
            ["LAMPARA PLEGABLE 1 MAS 3 HOJAS 15W RUN ","734896112133",""],
            ["LAMPARA PLEGABLE 3 HOJAS 15W-1300 RUN ","734896112911","LEDPLE09"],
            ["LAMPARA PLEGABLE 3 HOJAS 15W-1400 RUN ","734896112126",""],
            ["LAMPARA PLEGABLE 3+1 HOJAS 20W RUN ","734896112102",""],
            ["LAMPARA REDONDA PARA EMPOTRAR 18W EXXE ","205000008930","03-015-114"],
            ["LAMPARA REDONDA PARA EMPOTRAR 24W EXXEL","205000008932","03-015-115"],
            ["LAMPARA REDONDA SUPERFICIAL 24W EXXEL","205000008948","03-015-123"],
            ["LAMPARA REFLECTOR LED ANGEL LIGHT 30W","7453038486163","AL-MKFL-30W"],
            ["LAMPARA SOLAR 9W EMERGENCIA ","7365212541127",""],
            ["LAMPARA SOLAR CON SENSOR","7594320516905","040311"],
            ["LAMPARA SOLAR P/EXTERIORES 40W CONTROL REMOTO Y ACCESORIOS RUN","734896111549","LEDSOL02-40W"],
            ["LAMPARA TUBULAR LED PPARED NEGRO 12W VERT","9780201369625","ALV-002"],
            ["LAMPARA VAPOLETA 18W NEGRA EXXEL","205000009594","03-015-134"],
            ["LAMPARA VAPOLETA 24W BLANCA REDONDA EXXEL","205000008769","03-015-091"],
            ["LAMPARA VAPOLETA 24W NEGRA EXXEL","205000009596","03-015-135"],
            ["LAMPARA VAPOLETA LED OVALADA 24W RUN","736373171190",""],
            ["LAMPARA VAPOLETA LED OVALADA NEGRA 12W VERT ","1680201379076","LVP-126N"],
            ["LAMPARA VAPOLETA SAMSUNG LED 18W VERT ","6110201371305","LOS-186N"],
            ["LAVADORA 12KG SEMI-AUTOMATICA OMEGA","LAVADORA-12KG-OMEGA","OWS-12PS"],
            ["LAVADORA 16KG CON BOMBA JAGUAR","XPB160-2018CB","XPB160-2018CB"],
            ["LAVADORA 16KG MILEXUS","LAVA-16KG-MILEXUS","ML-WM-16KGS"],
            ["LAVADORA 20KG MILEXUS","LAVA-20KG-MILEXUS","ML-WM-20KGS"],
            ["LAVADORA 7KG KHALED","LAVA-7KG-KHALED","XPB70-1776"],
            ["LAVADORA 7KG SEMI C/BOMBA EDMIRA","LAVADORA-7KG-EDMIRA","LAV-02"],
            ["LAVADORA 8KG CON BOMBA JAGUAR","LAVADORA-CON-8K-JAGUAR","XPB80-107CB-8K"],
            ["LAVADORA 8KG SIN BOMBA JAGUAR","LAVADORA-SIN-8K-JAGUAR","XPB80-107SB-8K"],
            ["LAVADORA 9KG MILEXUS","LAVA-9KG-MILEXUS","ML-WM-9KGS"],
            ["LAVADORA A PRESION DE GASOLINA EMTOP","EHPWG6001",""],
            ["LAVADORA AUTOMATICA 10KG SJ ELECTRONICS","LAV.AUTOMATICA-SJ-10AUT-10KG","LAV.AUTOMATICA-SJ-10AUT-10KG"],
            ["LAVADORA AUTOMATICA 12KG SJ ELECTRONICS","LAVA-12KG-SJ","LAVADORA-AUTOMATICA13KG-SJ"],
            ["LAVADORA AUTOMATICA 13KG SJ ELECTRONICS","LAV.AUTOMATICA-SJ-13AUT-13KG","LAV.AUTOMATICA-SJ-13AUT-13KG"],
            ["LAVADORA AUTOMATICA 16KG SJ ELECTRONICS","LAV.AUTOMATICA-SJ-16AUT-SJ-16KG","LAV.AUTOMATICA-SJ-16AUT-SJ-16KG"],
            ["LAVADORA AUTOMATICA 8KG SJ-ELECTRONICS","LAV.AUTOMATICA-SJ-08AUT-8K","LAV.AUTOMATICA-SJ-08AUT-8K"],
            ["LAVADORA COMFEE 12KG ","LAVA-12KG-COMFEE",""],
            ["LAVADORA CONDESA SEMI AUTOMATICA 13KG ","LAVA-13KG-CONDESA","COND-WMB10-20"],
            ["LAVADORA CONDESA SEMI AUTOMATICA 8KG ","LAVA-8KG-CONDESA","COND-WMRT0-20"],
            ["LAVADORA DE 15KG JAGUAR ","LAVA-15KG-JAGUAR","LAVADORA-JAGUAR"],
            ["LAVADORA DE PRESION A GASOLINA INGCO","GHPW2003","GHPW2003"],
            ["LAVADORA DOBLE TINA 15KG SJ ELECTRONICS ","LAVA-15-KG-SJ","LAVADORA-15 SJ"],
            ["LAVADORA DOBLE TINA 6KG SJ-ELECTRONICS ","LAVA-6KG-SJ","7591910000158"],
            ["LAVADORA DOBLE TINA 7KG C/BOMBA BLANCA GPLUS","LAVA-7KG-GPLUS","GPLA1002862"],
            ["LAVADORA DOBLE TINA 7KG SJ-ELECTRONICS","LAVA-7KG-SJ","LAV701SJ"],
            ["LAVADORA DOBLE TINA 8.5KG SJ ELECTRONICS","LAVA-8KG-SJ","LAVADORA-AUTOMATICA8KG-SJ"],
            ["LAVADORA DOBLE TINA 8.5KG SJ-ELECTRONICS","LAVA-8.5KG-SJ","LAV805SJ"],
            ["LAVADORA EDMIRA 10KG","LAVA-10KG-EDMIRA",""],
            ["LAVADORA EDMIRA 12KG","LAVA-12KG-EDMIRA","LAVA-EDMIRA-12KG"],
            ["LAVADORA EDMIRA 15KG","LAVA-15KG-EDMIRA",""],
            ["LAVADORA JAGUAR DOBLE TINA 5.5KG","LAVA-5.5KG-JAGUAR","XPB45-4588SC"],
            ["LAVADORA KHALED 6.5KG","KHL65-E01-KHALED","KHL65-E01-KHALED"],
            ["LAVADORA LEES 12KG","LAVA-12KG-LEES",""],
            ["LAVADORA MABE 13KG","LAVA-13KG-MABE","LMDX3123"],
            ["LAVADORA MABE SEMI AUTOMATICA 11KG","LAVA-11KG-MABE",""],
            ["LAVADORA MILEXUS 7KG","LAVA-7KG-MILEXUS","ML-WM7KG"],
            ["LAVADORA OMEGA 7KG SEMI AUTOMATICA C/ TAPA TRANSPARENTE DOBLE TINA OWS-7TP","LAVADORA-7KG-OMEGA","OWS-7TP"],
            ["LAVADORA OMEGA 7KG SEMI AUTOMATICA CON VISOR DOBLE TINA OWS-7TB","LAVA-7KG-OMEGA","OWS-7TB"],
            ["LAVADORA OMEGA SEMI AUTOMATICA 11KG C/TAPA TRANSPARENTE","LAVA-11KG-OMEGA","OWS-11P3"],
            ["LAVADORA OMEGA SEMI AUTOMATICA CON TAPA TRANSPARENTE 8.5KG OWS-85P3","LAVADORA-8.5KG-OMEGA","OWS-85P3"],
            ["LAVADORA OMEGA SEMI AUTOMATICA CON VISOR 8.5KG OWS-85TP","LAVA-8.5KG-OMEGA","OWS-85TP"],
            ["LAVADORA OMEGA SEMI-AUTOMATICA 8KG CON TAPA AZUL","8KG OMEGA","OWS-8TA"],
            ["LAVADORA PREMIER 6.5KG ","LAVA-6.5KG-PREMIER","LVA65/"],
            ["LAVADORA PREMIER 9KG ","LAVA-9KG-PREMIER","LAVADORA-PREMIER-9KG"],
            ["LAVADORA SEMI AUTOMATICA 11KG DOBLE TINA JAGUAR CON BOMBA","LAVA-11KG-JAGUAR","XPB110-36CB"],
            ["LAVADORA SEMI AUTOMATICA 8KG DOBLE TINA JAGUAR ","LAVA-8KG-JAGUAR","8K-JAGUAR"],
            ["LAVADORA SEMI AUTOMATICA DOBLE TINA 10KG SJ-ELECTRONICS","LAVA-10KG-SJ","LAV101SJ"],
            ["LAVADORA SEMI-AUTOMATICA 8KG C/TAPA BLANCA","OWS-8TB","OWS-8TB"],
            ["LAVADORA VIVAMAX 7KG ","LAVA-7KG-VIVAMAX",""],
            ["LICUADORA 1.5LTS ROYAL REAL","ED-4822PL-1.5L","ROYAL-REAL"],
            ["LICUADORA 2 EN 1 KACOSA ","7453047301280","LE-999"],
            ["LICUADORA 400W 1.5LTS 4 VLC HOMEVER","101002","HBB-2816G"],
            ["LICUADORA 500W 1,5L DECAKILA ","6941556209964","KUJB002W"],
            ["LICUADORA 500W 1.5L DECAKILA KUJB007B","6941556297824",""],
            ["LICUADORA 550W HOMEVER","101001","HBB-1676P"],
            ["LICUADORA 600W 1.5L DECAKILA ","6941556295295","KUJB006M"],
            ["LICUADORA AZUL 3 VLC GTRONIC","46551030","GTRSX-4655BLUE"],
            ["LICUADORA BLACK+DECKER ","BLBD202PWLUX",""],
            ["LICUADORA BLACK+DECKER NEGRA","BLBD210PV",""],
            ["LICUADORA BLANCA WESTINGHOUSE","48950913019924895091301992","WKBEHS4609"],
            ["LICUADORA BOTON NEGRO VASO VIDRIO TRK HOME","TRK-4628B","TRK-4628B"],
            ["LICUADORA BOTON ROJA VASO VIDRIO TRK HOME","TRK-4699R","TRK-4699R"],
            ["LICUADORA BOTON VASO PLASTICO BIGBEN","LICUADORA-BIGBN","LICUADORA-BIGBN"],
            ["LICUADORA CLASICA METALICA 3V AK ","LICAKECR03",""],
            ["LICUADORA CON JARRA DE PLASTICO 1.5LTS DAEWOO","4895218331598","DEBL2210BKLUX"],
            ["LICUADORA CROMADA 3 VELOCIDADES KUCCE","7598734174508","BLKC001-SS"],
            ["LICUADORA CROMADA KR ","8600993225446","KR688"],
            ["LICUADORA DAEWOO 10 VELOCIDADES ","ED-4822PL-10V",""],
            ["LICUADORA DECAKILA 2 VELOCIDADES 1.5L KUJB001W","6941556209896",""],
            ["LICUADORA ELECTRO 4V 550W JARRA DE VIDRIO AZUL BOTON","7483224944412","BLEGJ-PB"],
            ["LICUADORA ELECTRO 4V 550W JARRA DE VIDRIO AZUL PERILLA","7483224944580A/P","BLEGJ-RD"],
            ["LICUADORA ELECTRO 4V 550W JARRA DE VIDRIO BLANCO BOTON","7483224944412B/B","BLEGJ-PB"],
            ["LICUADORA ELECTRO 4V 550W JARRA DE VIDRIO BLANCO PERILLA","7483224944580B/P","BLEGJ-RD"],
            ["LICUADORA ELECTRO 4V 550W JARRA DE VIDRIO NEGRO BOTON","7483224944412N/B","BLEGJ-PB"],
            ["LICUADORA ELECTRO 4V 550W JARRA DE VIDRIO NEGRO PERILLA","7483224944580/N/P","BLEGJ-RD"],
            ["LICUADORA ELECTRO 4V 550W JARRA DE VIDRIO ROJO BOTON","7483224944412R/B","BLEGJ-PB"],
            ["LICUADORA ELECTRO 4V 550W JARRA DE VIDRIO ROJO PERILLA","7483224944580","BLEGJ-RD"],
            ["LICUADORA FRIGILUX ","0012075","LFR-1190N"],
            ["LICUADORA KITCHEN AID K400 27CU ","883049525297","KSB4027CU"],
            ["LICUADORA MILEXUS 550W 1.5L VASO DE VIDRIO","LICUADORA-MILEX-550W","LICUADORA-MILEX-550W"],
            ["LICUADORA MYO VASO PLASTICO 300W ","734896674488",""],
            ["LICUADORA NEGRA 3 VLC GTRONIC","46551023","GRSX-4655B"],
            ["LICUADORA NEGRA KUCCE","7598734750160","BLKCP02BL"],
            ["LICUADORA NEGRA WESTINGHOUSE","4895091302012","WKBEHS4609BK"],
            ["LICUADORA NZ 600W","NZ-999",""],
            ["LICUADORA OSTER 12V CROMADA CON ACCESORIO","034264491533",""],
            ["LICUADORA OSTER 12V NARANJA","034264478008","BLSTEG7805N-013"],
            ["LICUADORA OSTER 2 VLC AZUL C/BONTONES VASO DE VIDRIO","053891150880","BLSTKAG-LPB-013"],
            ["LICUADORA OSTER 2 VLC BLANCA DE PERILLA VASO DE VIDRIO","053891138376","BLSTKAG-WRD-013"],
            ["LICUADORA OSTER 2 VLC KIWI C/BOTONES VASO DE VIDRIO","053891163361","BLSTKAG-KPB-013"],
            ["LICUADORA OSTER 2 VLC NEGRO DE PERILLA VASO DE VIDRIO","053891138352","BLSTKAG-BRD-013"],
            ["LICUADORA OSTER 2 VLC ROJA C/PERILLA VASO DE VIDRIO","053891138369","BLSTKAG-RRD-013"],
            ["LICUADORA OSTER 3V CROMADA","053891140294","004655-013-000"],
            ["LICUADORA OSTER 3V NEGRA DE BOTONES","096116935824","BLSTPYG1209B-013"],
            ["LICUADORA OSTER 4 VLC BLANCA C/BOTONES VASO DE VIDRIO","053891138321","BLSTKAG-WPB"],
            ["LICUADORA OSTER BLANCA 2V CON BOTONES V/PLASTICO","LICUADORA-OSTER-2VBOTON-B","VASO-PLASTICO"],
            ["LICUADORA OSTER BLANCA 2V CON BOTONES V/VIDRIO ","LICUADORA-OSTER-BLANCA-2VBOTON","BLSTKAP-WPB-013"],
            ["LICUADORA OSTER BLANCA 2V CON PERILLA V/PLASTICO","053891138413","LIC-11"],
            ["LICUADORA OSTER CROMADA DZG-BGO","DZGBGO","DZG-BGO"],
            ["LICUADORA OSTER J/V ROJA","034264458772","004126-R3S-013"],
            ["LICUADORA OSTER NEGRA 2V CON PERILLA V/PLASTICO","053891138390","LIC-16"],
            ["LICUADORA OSTER V/V 2V CROMA","034264432970","BEST02-E01-814"],
            ["LICUADORA OSTER XPERT SERIES DE LUJO","053891138857","BLSTTDT-N00-013"],
            ["LICUADORA OSTERIZER CROMADA 2V DE PERILLA OSTER ","034264025554","465-15"],
            ["LICUADORA PERILLA CROMADA VASO VIDRIO TRK HOME","TRK-4655CHR","TRK-4655CHR"],
            ["LICUADORA PERILLA NEGRO VASO VIDRIO TRK HOME","TRK-4688B","TRK-4688B"],
            ["LICUADORA PERILLA NEGRO VASO VIDRIO TRK HOME","TRK-4629B","TRK-4629B"],
            ["LICUADORA PLASTICA 1.5L KR","KR999",""],
            ["LICUADORA PORTATIL MINI RECARGABLE 380ML","8917297878909","PH-NM-03"],
            ["LICUADORA ROJA 3 VLC GTRONIC","46551047","GTRSX-4655R"],
            ["LICUADORA ROJA VASO VIDRIO BOTON TRK HOME","TRK-4698R","TRK-4698R"],
            ["LICUADORA SILVER PLUS PLASTICA 4V","SILVER-4V",""],
            ["LICUADORA SX006 BLACK GTRONIC","8436545096871","GTRSX-006B"],
            ["LICUADORA SX4690 BLACK GTRONIC","5680206799042","GTRSX-4690BLACK"],
            ["LIMA CHATA PARA METALES 20CM INGCO","6941640118288","HSFF088"],
            ["LIMA CIRCULAR 8 COVO","7453038487139","CV-FL-018R"],
            ["LIMA DE MOTOSIERRA 3/16 BELLOTA","LIMA-BELLOTA-3/16",""],
            ["LIMA DE MOTOSIERRA 3/16 FITS","LIMA DE MOTOSIERRA","LIMA DE MOTOSIERRA"],
            ["LIMA DE MOTOSIERRA 3/16 GAVILAN","LIMA-3/16-GAVILAN",""],
            ["LIMA DE MOTOSIERRA 3/16 MAGPOWER","LIMA-3/16-MAGPOWER","ACM-1040"],
            ["LIMA DE MOTOSIERRA 3/16 OREGON","LIMA-3/16 OREGON",""],
            ["LIMA DE MOTOSIERRA 3/16 Y 7/32 COVO","7453038475297","CV-RFL-7-32"],
            ["LIMA DE MOTOSIERRA 5/32 BELLOTA","LIMA-BELLOTA-5/32",""],
            ["LIMA DE MOTOSIERRA 7/32 BELLOTA","LIMA-BELLOTA-7/32",""],
            ["LIMA DE MOTOSIERRA 7/32 FITS","LIMA-7/32-FITS","LIMA DE MOTOSIERRA"],
            ["LIMA DE MOTOSIERRA 7/32 MAGPOWER","LIMA-MOTOSIERRA-7/32","LIMA-MOTOSIERRA-7/32"],
            ["LIMA DE MOTOSIERRA 7/32 OREGON","LIMA-7/32-OREGON",""],
            ["LIMA DE MOTOSIERRA VALLORBE 7/32 404","LIMA-VALLORBE","LIMA-VALLORBE"],
            ["LIMA ESCORFINA MEDIA CAÑA 8 COVO","7453038413336","CV-FL-0308H"],
            ["LIMA PARA CADENA DE MOTOSIERRA 3/16 COVO","7453038493895","CV-RFL-3-16"],
            ["LIMA PARA MOTOSIERRA 3/16 C/MANGO GAVILAN ","LIMA-3/16",""],
            ["LIMA PARA MOTOSIERRA 7/32 C/MANGO GAVILAN ","LIMA-7/32",""],
            ["LIMA PARA MOTOSIERRA 7/32X8 EXXEL","51-157","00-022-030"],
            ["LIMA PLANA BASTARDA COVO","7453038401449",""],
            ["LIMA PLANA COVO 8","7453038487115","CV-FL-018M"],
            ["LIMA TRIANGULAR 6 GAVILAN","LIMA-TRIANGULAR","LIMA-TRIANGULAR"],
            ["LIMA TRIANGULAR 8 COVO","7453038487122","CV-FL-018T"],
            ["LIMA TRIANGULAR BELLOTA","LIMA-TRIANGULAR-BELL",""],
            ["LIMA TRIANGULAR GAVILAN CACHA NEGRA","LIMA-TRIANGULAR-NEGRA",""],
            ["LLAVE DE CHORRO 1/2","7591996003548",""],
            ["LLAVE DE CHORRO 1/2 METALES ALEADOS BRONCE SIN PICO","7592978005307","MET-010"],
            ["LLAVE DE CHORRO 1/2 X 3/4 METALICA C/PICO","734896112812","LDC09"],
            ["LLAVE DE CHORRO 1/2X3/4 CON PICO DE MANGUERA","734896112829","LDCAB01"],
            ["LLAVE DE CHORRO 1/2X34 BRONCE","734896112430","LDC02"],
            ["LLAVE DE CHORRO 3/4 BLANCA GRIVEN","7453029106421","A367-LLA-81-3-4"],
            ["LLAVE DE CHORRO AQUA BLUE","7453011746413","P-10019"],
            ["LLAVE DE CHORRO BLANCA PLASTICA 1/2 HUMMER","7453100259060","HUM-1181"],
            ["LLAVE DE CHORRO BRONCE 1/2 GRIVEN","7453038415460","HC-B120G-3-4"],
            ["LLAVE DE CHORRO BRONCE 1/2 KOBATEX","6952362412950-B","T001-11"],
            ["LLAVE DE CHORRO BRONCE KOBATEX 1/2X3/4","6907909497303","LL-BRON1/2-B"],
            ["LLAVE DE CHORRO DE BRONCE 1/2","7453038460675","BVD-150-1-2"],
            ["LLAVE DE CHORRO PASO RAPIDO 1/2 HUMMER","7453100259053","HUM-1180"],
            ["LLAVE DE CHORRO PASO RAPIDO AQUAFINA","7453050046666","A-KD313"],
            ["LLAVE DE CHORRO PASO RAPIDO ECONOMICA MANGO ROJO","PH-190051",""],
            ["LLAVE DE CHORRO PLASTICA 1/2 CRUZ","LLAVE-CHORRO-PLASTIC",""],
            ["LLAVE DE CHORRO PLASTICA 1/2 KOBATEX","6952362412950-P","P002-1"],
            ["LLAVE DE CHORRO PLASTICA 1/2 X 3/4 HUMMER","7453100259046","HUM-1179"],
            ["LLAVE DE CHORRO PLASTICA METALES ALIADOS DE 1/2 CON PICO","7592978393480","MET-060"],
            ["LLAVE DE CHORRO PVC CUELLO CORTO 14 C/PICO","GRIF-004","GRIF-004"],
            ["LLAVE DE CHORRO PVC PASE RAPIDO MANGO NARANJA","GRIF-006","GRIF-006"],
            ["LLAVE DE CHORRO PVC PLASTICA 1/2&nbsp;","7453050049834",""],
            ["LLAVE DE DUCHA DOBLE MOD: 1261","42602903",""],
            ["LLAVE DE DUCHA GRIVEN","7453038491211","7453038491211"],
            ["LLAVE DE FREGADERO 8 METALICO MANILLA PALANCA MA","592978086078","BMF-8607M"],
            ["LLAVE DE FREGADERO DE LUJO MOD. MURCIA METALES ALEADOS","7592978006045","PRI-005"],
            ["LLAVE DE FREGADERO DOBLE CROMADO DE METAL 8 LF","205000009436","04-008-067"],
            ["LLAVE DE FREGADERO DOBLE CUELLO FLEXIBLE 8 GRIVEN","7453038446587","A367-15054-B8"],
            ["LLAVE DE FREGADERO DOBLE CUELLO FLEXIBLE 8 GRIVEN","7453078519319","A367-15047"],
            ["LLAVE DE FREGADERO DOBLE CUELLO TIPO COBRA GRIVEN","7453038400787","GVB-P8503-K1"],
            ["LLAVE DE FREGADERO DOBLE GRIFO CROMADO GRIVEN","7453038463447","A367-P8506"],
            ["LLAVE DE FREGADERO DOBLE METAL 8 LF","205000009428","04-008-063"],
            ["LLAVE DE FREGADERO DOBLE METAL 8 LF","205000009430","04-008-064"],
            ["LLAVE DE FREGADERO EN BRONCE MANILLA DE PALANCA. MA FAUCET","7592978369317","BMF-8601M"],
            ["LLAVE DE FREGADERO INDIVIDUAL BLANCA GRIVEN","7453038445443","A367-SH900-WH"],
            ["LLAVE DE FREGADERO INDIVIDUAL CUELLO FLEXIBLE GRIVEN","7453078519302","A367-15046"],
            ["LLAVE DE FREGADERO INDIVIDUAL SENCILLA GRIVEN","7453078542959","A367-15050"],
            ["LLAVE DE FREGADERO METALICA FLEXIFLE AQUAFINA","7453050052087","A-GSZ806-C"],
            ["LLAVE DE FREGADERO PLASTICA","7453078543116",""],
            ["LLAVE DE LAVAMANO GRIVEN","7453038428163","A367-ZF321A"],
            ["LLAVE DE LAVAMANO TUBULAR DE LUJO METALES ALIADOS","7592978006120","PRI-013"],
            ["LLAVE DE LAVAMANOS 7 MOD. ALICANTE METALES ALIADOS","7592978006069","PRI-007"],
            ["LLAVE DE LAVAMANOS DOBLE 4 ACABADO CROMADO LF","205000009410","04-008-054"],
            ["LLAVE DE LAVAMANOS DOBLE DE METAL 4 LF","205000009420","04-008-059"],
            ["LLAVE DE LAVAMANOS DOBLE DE METAL CON ACABADO CROMADO 4 LF","205000009424","04-008-061"],
            ["LLAVE DE LAVAMANOS DOBLE PLASTICA CROMADA GRIVEN","7453038438841","GVB-P8503-B"],
            ["LLAVE DE LAVAMANOS DOBLE PLASTICA CROMADA GRIVEN","7453038464659","GVB-P8507"],
            ["LLAVE DE LAVAMANOS INDIVIDUAL BASICA GRIVEN","7453010094317","GVB-ZF142"],
            ["LLAVE DE LAVAMANOS INDIVIDUAL POMO ACRILICO GRIVEN","7453038426350","A367-D8523-002PH"],
            ["LLAVE DE LAVAMANOS PLASTICO CROMADO METALES ALEADOS","7592978368877","BPL-2D03"],
            ["LLAVE DE LAVAMANOS VINDEX","VINDEX",""],
            ["LLAVE DE MANGUERA PASO RAPIDO GRIVEN","7453038418997",""],
            ["LLAVE DE PASO 1 1/2 BRONCE WOLGGANG ITALY","LLAVE-PASO-11/2",""],
            ["LLAVE DE PASO 1 BRONCE BRASSCO","BBV22501A","BBV22501A"],
            ["LLAVE DE PASO 1/2 BRONCE AQUAFINA","7453050046208","A-B100B-1-2"],
            ["LLAVE DE PASO 1/2 CROMADA GRIVEN","7453010083243","BV-145G-1-2-CF"],
            ["LLAVE DE PASO 1/2 PLASTICA METALES ALEADOS SIN ROSCA MANGO ROJO","LLAVE-PASO-1/2-MA",""],
            ["LLAVE DE PASO 1/2 RUN","734896112317","LB03"],
            ["LLAVE DE PASO 1/2 X 1/2 DE BRONCE RUN","734896112294","LB01"],
            ["LLAVE DE PASO 1/2 ZINC.AQUAFINA","7453050046420","A-B103C-1/2"],
            ["LLAVE DE PASO 2 BRONCE GRIVEN","LLAVE-PASO-2","LLAVE-PASO-2"],
            ["LLAVE DE PASO 3/4 BRONCE GRIVEN","7453038469289","BV-205G-3-4-VEN"],
            ["LLAVE DE PASO 3/4 CON ROCA GRIVEN","7453038494021","VA-180G-3-4-VEN"],
            ["LLAVE DE PASO 3/4 CROMADA GRIVEN","7453038496636","LLAVE-3/4"],
            ["LLAVE DE PASO 3/4 PLASTICA METALES ALEADOS SIN ROSCA MANGO ROJO","LLAVE-PASO-3/4-MA",""],
            ["LLAVE DE PASO 3/4 RUN","734896112324","LB04"],
            ["LLAVE DE PASO BOLA 1-1/4 METALES ALEADOS","MET-030","MET-030"],
            ["LLAVE DE PASO BRONCE 1 GRIVEN","7453038495448","BV-300G-1-VEN"],
            ["LLAVE DE PASO BRONCE 1 KOBATEX","LLAVE-PASO1-KOBATEX",""],
            ["LLAVE DE PASO BRONCE 1/2 GRIVEN","7453038405713","BV-145G-1-2-BR"],
            ["LLAVE DE PASO C/ROSCA PVC 1 RUN","734896112584","LB07"],
            ["LLAVE DE PASO C/ROSCA PVC 3/4 RUN","734896112577","LB06"],
            ["LLAVE DE PASO CARBURADOR FUMIGADORA CAÑON FITS","LLAVE DE PASO-CARBURADOR","LLAVE DE PASO-CARBURADOR"],
            ["LLAVE DE PASO COMPRESOR 1/4 FITS","LLAVE-COMPRESOR","LLAVE-COMPRESOR"],
            ["LLAVE DE PASO CON ROSCA 3/4","7592032021212","VAL-31"],
            ["LLAVE DE PASO D/COMPUERTA 1/2 LGM","COMPUERTA-1/2","LLA-105"],
            ["LLAVE DE PASO DE BOLA PESADA 1/2 METALES ALIADOS","7592978009459","MET-062"],
            ["LLAVE DE PASO DE BOLA PESADA 3/4 METALES ALIADOS","7592978009466","MET-063"],
            ["LLAVE DE PASO DE FUMIGADORA ESTACIONARIA","LLAVE-ESTACIONARIA","LLAVE-ESTACIONARIA"],
            ["LLAVE DE PASO MARIPOSA GRIVEN 1/2 BRONCE","7453038438308","VA-115G-1-2-VEN"],
            ["LLAVE DE PASO PARA COMPRESOR","COMPRE-LLAVE",""],
            ["LLAVE DE PASO PAVCO 1/2 JADEVER","6942210212122","JDVV1822"],
            ["LLAVE DE PASO PAVCO 1/2 KOBATEX","6974620242185","LLAVE-1/2"],
            ["LLAVE DE PASO PLASTICA CON ROSCA 1/2","GV-PVCV-1-2-R","GV-PVCV-1-2-R"],
            ["LLAVE DE PASO PLASTICA CON ROSCA 3/4","GV-PVCV-3-4-R","GV-PVCV-3-4-R"],
            ["LLAVE DE PASO PVC PLASTICA 1/2 ROJO","LLAVE-PVC-1/2","LLAVE-PVC-1/2"],
            ["LLAVE DE PASO PVC PLASTICA 3/4","7453038478403","GV-PVCV-3-4-S"],
            ["LLAVE DE PASO PVC S/ROSCA PLASTICA 1","7453078505299",""],
            ["LLAVE DE PASO ROSCA 1 FERMETAL","7592032021229","VAL-32"],
            ["LLAVE DE PASO ROSCA 1/2","7592032021205","VAL-30"],
            ["LLAVE DE PASO S/ROSCA 3/4 AQUAPLUS","7453012362186","AQP-1748"],
            ["LLAVE DOBLE CUELLO FLEXIBLE 4 GRIVEN","7453038436618","A367-15055-B4"],
            ["LLAVE DOBLE PARA FREGADERO CROMADA 8 GRIVEN","7453038424240","A367-D8002A"],
            ["LLAVE DOBLE PARA LAVAMANO ACABADO CROMADO. TOYO","7450097114952","04-FR-21501"],
            ["LLAVE DOBLE PARA LAVAMANOS CUELLO FLEXIBLE 4 GRIVEN","7453038437950","A367-15054-B4"],
            ["LLAVE DUCHA MONOMANDO E-9205S LF","205000009466","04-014-016"],
            ["LLAVE DUCHA MONOMANDO E-9205S LF","04-014-016","04-014-016"],
            ["LLAVE FREGADERO DOBLE 8 METAL S-8010 LF","205000009442",""],
            ["LLAVE FREGADERO DOBLE 8 METAL S-8035B LF","205000009444",""],
            ["LLAVE FREGADERO IND METAL S-1018G LF","205000009446","04-008-072"],
            ["LLAVE FREGADERO IND METAL S-1018H LF","205000009448","04-008-073"],
            ["LLAVE FREGADERO TUBULAR CADIZ MA","7592978006113","PRI-012"],
            ["LLAVE INDIVIDUAL P/FREGADERO ROJO CUELLO FLEXIBLE GRIVEN","7453038473552","GV-SH87095-RD"],
            ["LLAVE INDIVIDUAL PARA DUCHA GRIVEN","7453038484886",""],
            ["LLAVE INDIVIDUAL PARA FREGADERO BRILLANTE GRIVEN","7453038473996","A367-SH008A"],
            ["LLAVE INDIVIDUAL PARA FREGADERO GRIVEN","7453010049348","A367-SH008B"],
            ["LLAVE INDIVIDUAL PARA FREGADERO GRIVEN","7453078543130","A367-ZF306D"],
            ["LLAVE INDIVIDUAL PARA FREGADERO TIPO CISNE GRIVEN","7453078543123","A367-ZF305D"],
            ["LLAVE INDIVIDUAL PARA LAVAMANO CROMADA GRIVEN","7453038451925","A367-Z225"],
            ["LLAVE INDIVIDUAL PARA LAVAMANO GRIMAX","6940181700082","GRIX-SM0003"],
            ["LLAVE INDIVIDUAL PARA LAVAMANOS 1/2 CROMADO ACQUABELA","7451304214977","GRI-4453CR"],
            ["LLAVE INDIVIDUAL PARA LAVAMANOS CROMADO GRIMAX","6954912830168","PH-SRB1902"],
            ["LLAVE INDIVIDUAL PARA LAVAMANOS GRIVEN","7453078543109","A367-ZF322A"],
            ["LLAVE INDIVIDUAL PARA LAVAMANOS KOBATEX","7329823116","GRIF-GB100/C"],
            ["LLAVE INDIVIDUAL PARA LAVAMANOS PLASTICA BLANCO GRIVEN","7453078543093","A367-ZF321"],
            ["LLAVE INDIVIDUAL PARA LAVAMANOS POMO ACRILICO GRIVEN","7453078536033","A145-BIB002PH"],
            ["LLAVE INDIVIDUAL PARA PURIFICADOR GRIVEN","7453038428590","GV-WP-Z1"],
            ["LLAVE LAVAMANO IND METAL S-1051F LF ","2050000094569","04-008-077"],
            ["LLAVE LAVAMANOS IND METAL S-1076Q LF","2050000094583","04-008-078"],
            ["LLAVE LAVAPLATOS MONOMANDO NEGRA UNITEC","7706829394605","LLAVE-UNITEC"],
            ["LLAVE MANOMANDO METALES ALEADOS PARA LAVAMANOS ABS","7592978368938","BPL-2801"],
            ["LLAVE MECANICA COMBINADA 10MM JADEVER","6942210203663","JDSA1110"],
            ["LLAVE MECANICA COMBINADA 11MM JADEVER","6942210203670","JDSA1111"],
            ["LLAVE MECANICA COMBINADA 12MM JADEVER","6942210203687","JDSA1112"],
            ["LLAVE MECANICA COMBINADA 8MM JADEVER","6942210203649","JDSA1108"],
            ["LLAVE MECANICO 22MM BEST VALUE","7453001155195","LLAVE-M-22"],
            ["LLAVE P/ FREGADERO METALICA FLEXIBLE AQUAFINA","7453050052100","A-GSZ808-C"],
            ["LLAVE P/FREGADERO PICO ALTO FLEXIBLE. AQUAFINA","7453118003570","A-5228A"],
            ["LLAVE P/LAVAMANO 4 CROMADA FLEXIBLE AQUAFINA","7453050050120","A-GSZ4463FN"],
            ["LLAVE P/LAVAMANO METALES ALEADOS 1/2 MANILLA LUNA BLANCA","7592978004485","BPL-2L05B"],
            ["LLAVE P/LAVAMANOS 1/2 MA FAUCET","7592978008162","BPL-2304-S"],
            ["LLAVE PARA CORREA DE CINTURON 190MM-820MM JADEVER","6942431491474","JDAW3311"],
            ["LLAVE PARA DUCHA 1/2 C-PVC GRIVEN","7453038433839","GV-PV-1A"],
            ["LLAVE PARA DUCHA 1/2 CROMADO DE ACERO INOXIDABLE GRIVEN","7453010049201","GVS-WA-4"],
            ["LLAVE PARA DUCHA 1/2 GRIVEN","7453038485609","A367-WA016A"],
            ["LLAVE PARA DUCHA 1/2 INDIVIDUAL GRIVEN","7453038458191","GV-WA-2"],
            ["LLAVE PARA DUCHA 1/2 INDIVIDUAL GRIVEN","7453038447416","A367-WA009"],
            ["LLAVE PARA DUCHA 1/2 INDIVIDUAL GRIVEN BLACK","7453038458030","GV-BV-1"],
            ["LLAVE PARA DUCHA 1/2 POMO ACRILICO GRIVEN","7453038484893","A367-WA003"],
            ["LLAVE PARA DUCHA DE 1/2 INDIVIDUAL GRIVEN","7453038429108","A367-WA021-H"],
            ["LLAVE PARA DUCHA DOBLE","SUFEIN",""],
            ["LLAVE PARA DUCHA GRIVEN INDIVIDUAL 1/2","7453038452854","A367-WA106"],
            ["LLAVE PARA DUCHA GRIVEN POMO ACRILICO 1/2","7453038463591","A367-WA105"],
            ["LLAVE PARA DUCHA INDIVIDUAL 1/2 MANILLA METALICA FERMETAL","7592032020727","LLA-72"],
            ["LLAVE PARA DUCHA INDIVIDUAL GRIVEN 1/2","7453010084080","A367-WA024"],
            ["LLAVE PARA FILTROS DE ACEITE TRUPER","7501206641859",""],
            ["LLAVE PARA FREGADERO ACERO INOXIDABLE BLANCA MA","7592978403691","MET-611"],
            ["LLAVE PARA FREGADERO ACERO INOXIDABLE NEGRO MA","7592978403684","MET-610"],
            ["LLAVE PARA FREGADERO ACERO INOXIDABLE ROJO MA","7592978403721","MET-614"],
            ["LLAVE PARA FREGADERO AGUA FRIA PVC BLUE DREAMS","7592978006946","BLU-0002"],
            ["LLAVE PARA FREGADERO CUELLO FLEXIBLE CON REGADERA MA","7592978004621","BMF-8608"],
            ["LLAVE PARA FREGADERO EN BRONCE MANILLA CURVA MA","7592978369393","BMF-8605"],
            ["LLAVE PARA FREGADERO INDIVIDUAL ABS/CROMADA POMO REDONDO TOYO","7450097114853","04-FR-21490"],
            ["LLAVE PARA FREGADERO INDIVIDUAL AZUL ","7453038430432","GV-SH87095-NBU"],
            ["LLAVE PARA FREGADERO INDIVIDUAL METALES ALEADOS","7592978403165","BPL-2L03A"],
            ["LLAVE PARA FREGADERO KOBATEX","230053846536-01","KB-GC01"],
            ["LLAVE PARA FREGADERO KOBATEX","230053846536-04","KB-GC04"],
            ["LLAVE PARA FREGADERO METALES ALEADOS 1/2 ABS","7592978368860","BPF-2D02"],
            ["LLAVE PARA LAVAMANO DOBLE 4 METAL LF","205000009422","04-008-060"],
            ["LLAVE PARA LAVAMANOS","7591631100120",""],
            ["LLAVE PARA LAVAMANOS 1/2 ABS BLANCA METALES ALIADOS","7592978004508","BPL-2305B"],
            ["LLAVE PARA LAVAMANOS 1/2 ABS CUELLO DE CISNE METALES ALIADOS","7592978008193","BPL-2303-S"],
            ["LLAVE PARA LAVAMANOS 1/2 ABS MANILLA CRUCETA NEGRA METALES ALEADOS","7592978004515",""],
            ["LLAVE PARA LAVAMANOS 1/2 MANILLA TIPO CRUCETA","7592978368921",""],
            ["LLAVE PARA LAVAMANOS 1/2 MANILLA TIPO LUNA","7592978368846",""],
            ["LLAVE PARA LAVAMANOS 1/2 METALES ALEADOS EN ABS","7592978396955","BPL-2D04"],
            ["LLAVE PARA LAVAMANOS 1/2. MA FAUCET","7592978008179","BPL-2L04-S"],
            ["LLAVE PARA LAVAMANOS 1/4 DE VUELTA","7592978002979","MET-223"],
            ["LLAVE PARA LAVAMANOS 1/4 DE VUELTA","7592978002955","MET-097"],
            ["LLAVE PARA LAVAMANOS 4 L-14","7591996005573",""],
            ["LLAVE PARA LAVAMANOS 4 L-52","7591996013790",""],
            ["LLAVE PARA LAVAMANOS 4 L01-PD","7591996000134",""],
            ["LLAVE PARA LAVAMANOS 4 L02-PD","7591996000028",""],
            ["LLAVE PARA LAVAMANOS 4 METALES ALIADOS","7592978393039","BPL-1041"],
            ["LLAVE PARA LAVAMANOS 4 METALES ALIADOS","7592978393022","BPL-1040"],
            ["LLAVE PARA LAVAMANOS 4 METALES ALIADOS","7592978393046","BPL-1042"],
            ["LLAVE PARA LAVAMANOS 4 METALES ALIADOS","7592978004720","BPL-1044"],
            ["LLAVE PARA LAVAMANOS ABS NEGRA METALES ALIADOS","7592978004492","BPL-2L05N"],
            ["LLAVE PARA LAVAMANOS DE MONOMANDO","7592978006144","MET-027"],
            ["LLAVE PARA LAVAMANOS DOBLE CUELLO CURVO GRIVEN","7453010089450","GVB-P8507-B"],
            ["LLAVE PARA LAVAMANOS EVEREST","7592978391431","MET-200"],
            ["LLAVE PARA LAVAMANOS INDIVIDUAL 1/2 DE MANILLA FERMETAL","7592032007902","LLA-32"],
            ["LLAVE PARA LAVAMANOS KUALA LUMPUR","7591996005153","L39"],
            ["LLAVE PARA LAVAMANOS METALES ALEADOS 1/4 ROMA","7592978002962","MET-100"],
            ["LLAVE PARA LAVAMANOS METALES ALIADOS ABS CUELLO DE CISNE ","7592978008186","BPL-2L03-S"],
            ["LLAVE PARA LAVAMANOS METALICA METALES ALIADOS","7592978398089","MET-557"],
            ["MACHETE 22 ROZADOR EXXEL","205000009790","02-016-051"],
            ["MACHETE BELLOTA ROZADOR 22 CACHA DE PLASTICO","7702956003106",""],
            ["MACHETE COLORADA CHAPARRO 22 CACHA","MACHETE22CHAPARRO",""],
            ["MACHETE COLORAO 706 CACHA FUNDIDA 24","7706912659574",""],
            ["MACHETE COVO ROZADOR 22 MANGA PLASTICA","7453010080761","CV-MT-0222"],
            ["MACHETE DURAWOOD 22 GAVILAN+LIMA TRIANGULAR","7706912050531","85328822"],
            ["MACHETE EL INDIO ROZADOR ","1300072000007",""],
            ["MACHETE GAVILAN 104 ROZADOR 22 CACHA PLASTICA","ROZADOR-CACHA-PLASTI",""],
            ["MACHETE GAVILAN CACHA DE MADERA CON LIMA INCOLMA ","MACHETE-GAVILAN","7706912650281"],
            ["MACHETE GAVILAN ROZADOR 22 CACHA MADERA","ROZADOR-CACHA-MADERA",""],
            ["MACHETE LINIERO 22 NEGRO","205000009778","02-016-045"],
            ["MACHETE LINIERO 3 CANALES CACHA DE GOMA 22 VERT","6780135370028","MGL-22B"],
            ["MACHETE LINIERO 3 CANALES CACHA DE MADERA 20 VERT","6780135650045","MML-20B"],
            ["MACHETE MANGO MADERA 20 HERRAGRO","7706335002636","14170020"],
            ["MACHETE PEINILLA 18 C/CACHA MADERA VERT","6780135650052","MME-18B"],
            ["MACHETE ROZADOR 20 COVO","7453038458177","CV-MT-0220"],
            ["MACHETE ROZADOR 3 CANALES 22 VERT","6159753655396","MGC-22B"],
            ["MACHETE ROZADOR CACHA DE MADERA GAVILAN 22","MACHETE-INCOLMA",""],
            ["MACHETE RUTEL","KIT-MACHETE-RUTEL",""],
            ["MANTO ASFALTICO 3.15MM X10MTS IPA","MANTO-ASFALTICO-IPA",""],
            ["MANTO ASFALTICO 3.20MM X10MTS BITUPLAS","MANTO-ASFALTICO-BITUPLAS",""],
            ["MANTO ASFALTICO 3.2MMX10M IPA","MANTO ASFALTICO","MANTO"],
            ["MANTO FLEX IMPERMIABILIZANTE SUPER A AZUL 1G","M-FLEX-1G-AZUL","01400101002004"],
            ["MANTO FLEX IMPERMIABILIZANTE SUPER A AZUL 5G","M-FLEX-5G-AZUL","01400101001004"],
            ["MANTO FLEX IMPERMIABILIZANTE SUPER A BLANCO 1G","M-FLEX-1G-BLANCO","01400101002006"],
            ["MANTO FLEX IMPERMIABILIZANTE SUPER A BLANCO 5G","M-FLEX-5G-BLANCO","01400101001006"],
            ["MANTO FLEX IMPERMIABILIZANTE SUPER A GRIS 1G","M-FLEX-1G-GRIS","01400101002008"],
            ["MANTO FLEX IMPERMIABILIZANTE SUPER A GRIS 5G","M-FLEX-5G-GRIS","01400101001008"],
            ["MANTO FLEX IMPERMIABILIZANTE SUPER A NEGRO 1G","M-FLEX-1G-NEGRO","01400101002013"],
            ["MANTO FLEX IMPERMIABILIZANTE SUPER A NEGRO 5G","M-FLEX-5G-NEGRO","01400101001013"],
            ["MANTO FLEX IMPERMIABILIZANTE SUPER A ROJO 1G","M-FLEX-1G-ROJO","01400101002015"],
            ["MANTO FLEX IMPERMIABILIZANTE SUPER A ROJO 5G","M-FLEX-5G-ROJO","01400101001015"],
            ["MANTO FLEX IMPERMIABILIZANTE SUPER A VERDE 1G","M-FLEX-1G-VERDE","01400101002016"],
            ["MANTO FLEX IMPERMIABILIZANTE SUPER A VERDE 5G","M-FLEX-5G-VERDE","01400101001016"],
            ["MAQUINA DE SOLDAR 160AMP 110-60HZ DAEWOO","DABX1-160B","DABX1-160B"],
            ["MAQUINA DE SOLDAR 160AMP COVO","7453010077693","CV-FBW-160"],
            ["MAQUINA DE SOLDAR 200A EMTOP ","6941556219123","ULWDEM2031"],
            ["MAQUINA DE SOLDAR 200AMP COVO","7453010076993","CV-FBW-200"],
            ["MAQUINA DE SOLDAR CHESTERWOOD 5-160A","MAQUINA-CHESTER","MAQUINA"],
            ["MAQUINA DE SOLDAR INVERTE MINI 110AMP ATOUAN","SOL-001",""],
            ["MAQUINA DE SOLDAR INVERTER 110-120V 10V 130A SUPER SELECT INGCO","6941640157119","ING-UMMA1301"],
            ["MAQUINA DE SOLDAR INVERTER 110-240V 76V 130A INGCO","ING-UMMA1304","ING-UMMA1304"],
            ["MAQUINA DE SOLDAR INVERTER 110/220V ATOUAN ","7596347795417","MAQUINA-160AMP"],
            ["MAQUINA DE SOLDAR INVERTER 200A WADFOW","6942123010662","UWWD32001"],
            ["MAQUINA DE SOLDAR INVERTER 75V 160AMP INGCO","6925582140200","ING-UMMA1605"],
            ["MAQUINA DE SOLDAR INVERTER 85V 200AMP INGCO","6941640167712","ING-UMMA2006"],
            ["MAQUINA DE SOLDAR INVERTER MAGMIGMMATIG LIFT 110-220V 85V 160A INGCO","ING-UMGT1601","ING-UMGT1601"],
            ["MAQUINA DE SOLDAR LASSEN DC 160AMP HOFFMAN","759190712018","401-151"],
            ["MAQUINA DE SOLDAR MEGA FORCE 175","0590041",""],
            ["MAQUINA DE SOLDAR ROCKET 200 SPARKEN","MAQUINA-200",""],
            ["MECATE 1/2 SISALARA","1/2-SISALARA",""],
            ["MECATE COVO 1/2 KG","7453038443661",""],
            ["MECATE COVO 1/4 KG ","7453038443685",""],
            ["MECATE COVO 3/16 KG ","7453038424875",""],
            ["MECATE COVO 3/8 KG","7453038415095",""],
            ["MECATE COVO 5/16 KG ","7453010090692",""],
            ["MECATE COVO 7/16 KG","7453038414739",""],
            ["MECATE DE COLORES 1/2 POR ROLLO ELEFANTE","MECATE-1/2COLOR",""],
            ["MECATE DE COLORES 3/8 POR ROLLO ELEFANTE","MECATE-3/8COLOR",""],
            ["MECATE DE COLORES 5/16 POR ROLLO ELEFANTE","MECATE-5/16COLOR",""],
            ["MECATE DE COLORES 7/16 POR ROLLO ELEFANTE","MECATE-7/16COLOR",""],
            ["MECATE ELEFANTE 1/2 KG","MECATE-1/2-ELE",""],
            ["MECATE ELEFANTE 1/4 KG","MECATE-1/4-ELE",""],
            ["MECATE ELEFANTE 3/16 KG","MECATE-3/16-ELE",""],
            ["MECATE ELEFANTE 3/8 KG","MECATE-3/8-ELE",""],
            ["MECATE ELEFANTE 5/16 KG","MECATE-5/16-ELE",""],
            ["MECATE ELEFANTE 7/16 KG","MECATE-7/16-ELE",""],
            ["MECATE EXXEL 1/2 KG","MECATE-1/2-EXXEL","07-015-063"],
            ["MECATE EXXEL 1/4 KG","MECATE-1/4-EXXEL","07-015-059"],
            ["MECATE EXXEL 3/16 KG","MECATE-3/16-EXXEL","07-015-064"],
            ["MECATE EXXEL 3/8 KG","MECATE-3/8-EXXEL","07-015-061"],
            ["MECATE EXXEL 5/16 KG","MECATE-5/16-EXXEL","07-015-060"],
            ["MECATE EXXEL 7/16 KG","MECATE-7/16-EXXEL","07-015-062"],
            ["MOTOSIERRA 46CC EMTOP ","6941556221690","EGCS18451"],
            ["MOTOSIERRA 62 CC ALTERMAN ","ALTERMAN-XCS62-1","ALTERMAN-XCS62-1"],
            ["MOTOSIERRA A GASOLINA INGCO 18","MOTOSIERRA-18","GCS5451811"],
            ["MOTOSIERRA DE GASOLINA CHESTERWOOD","MOTOSIERRA-CHESTERWOOD",""],
            ["MOTOSIERRA DOMOSA PS52","PS52",""],
            ["MOTOSIERRA DOMOSA PS58","7590024033335","PS58"],
            ["MOTOSIERRA ELECTRICA GASOLINA 24 EMTOP`","6941556219727","EGCS24621"],
            ["MOTOSIERRA HUSQVARNA 061","HUSQ-061",""],
            ["MOTOSIERRA HUSQVARNA 288","HUSQVARNA-288","HUSQVARNA-288"],
            ["MOTOSIERRA HUSQVARNA 288","HUSQ-288","HUSQ-288"],
            ["MOTOSIERRA INGCO A GASOLINA 24","6941640100719","GCS5602411"],
            ["MOTOSIERRA MAGPOWER 7200 MSG-720","MSG-720",""],
            ["MOTOSIERRA MAGPOWER 9100 BARRA 30","MAG-910","MSG-910"],
            ["MOTOSIERRA MSG-580 BARRA 24 CADENA 3/8 MAGPOWER","MSG-580",""],
            ["MOTOSIERRA PROFESIONAL PS61-24 DOMOSA MOD-HUSQ061","PS61-24",""],
            ["MOTOSIERRA PROFESIONAL PS660-36 DOMOSA MOD-STHIL","PS660-36",""],
            ["MOTOSIERRA SOLPOWER 7200","SPM7200",""],
            ["MOTOSIERRA SOLPOWER 9100","SPM9100G",""],
            ["MOTOSIERRA TITANIO 58TI","58TI","58TI"],
            ["MOTOSIERRA WASA TOOLS","MOTOSIERRA-WASA",""],
            ["NEVERA 2 PUERTAS PLATEADA OMEGA","NEVERA-490L-OMEGA","ORS-490VB"],
            ["NEVERA 219L KHALED","NEVERA-219L-KHALED","NKHL-219"],
            ["NEVERA 251 LTS 110V GPLUS","GP-NEV10","GP-NEV10"],
            ["NEVERA C/ESCARCHA 150LTS 1 PUERTA OMEGA NEGRO","NEVERA-150LTS-OM","ORT-150B"],
            ["NEVERA C/ESCARCHA 150LTS 1 PUERTA OMEGA PLATEADO","NEVERA-150LTS-OMEGA","ORT-150V"],
            ["NEVERA C/ESCARCHA 241LTS OMEGA NEGRO","NEVERA-241LTS-OMEGA","ORT-241B"],
            ["NEVERA CONDESA 2 PUERTAS","NEVERA-2P-CONDESA","NEVERA-2 PUERTAS"],
            ["NEVERA CONDESA 219LTS BLANCA0","CR220W15",""],
            ["NEVERA CONDESA GRIS 220LTS ","CR-220W15",""],
            ["NEVERA EJECUTIVA 76L ACERO JAGUAR ","XLS-85SS",""],
            ["NEVERA EJECUTIVA 76L NEGRO JAGUAR ","XLS-85",""],
            ["NEVERA EJECUTIVA 93L MILEXUS","MLRF-93L",""],
            ["NEVERA EJECUTIVA 96L NEGRA JAGUAR","XLS-105",""],
            ["NEVERA EXHIBIDORA 2 PUERTAS AZUL 700L JAGUAR","700L","2-PV-700L"],
            ["NEVERA EXHIBIDORA 3 PUERTAS JAGUAR","1110L","3-PV-1100"],
            ["NEVERA EXHIBIDORA JAGUAR 1 PUERTA 410LTS GRAFITY","NEVERA-EXHIBIDORA-410L-GRAFITY","1P-410LPH"],
            ["NEVERA EXHIBIDORA JAGUAR 1 PUERTA 410LTS NEGRO","NEVERA-EXHIBIDORA-410L-NEGRO","1P-410LN"],
            ["NEVERA EXHIBIDORA JAGUAR 2 PUERTAS 715LTS GRAFITY","NEVERA-EXHIBIDORA-715L-GRAFITY","2P-715LPH"],
            ["NEVERA EXHIBIDORA JAGUAR 2 PUERTAS 715LTS NEGRO","NEVERA-EXHIBIDORA-715L-NEGRO","2P-715LN"],
            ["NEVERA EXHIBIDORA JAGUAR BLANCA/GRIS 2P 528L ","LC-600",""],
            ["NEVERA EXHIBIDORA JAGUAR BLANCO/G 280L","NEVERA-EXHIBIDORA-280L",""],
            ["NEVERA EXHIBIDORA JAGUAR BLANCO/G 360L","NEVERA-EXHIBIDORA-360L-BLANCO",""],
            ["NEVERA EXHIBIDORA VERTICAL 2 PUERTAS 560LTS MILEXUS","560L-MILEXUS","ML-ED-560L-110V"],
            ["NEVERA GALANZ","GLR76T",""],
            ["NEVERA GAMA ELETRIC 172LTS","NEVERA-172LT-GAMA-ELECTRIC","GE-172W"],
            ["NEVERA MILEXUS","NEVERA-MILEXUS","RF7CUFT"],
            ["NEVERA MILEXUS MOD-ML-RF-INOX-6.5CUFT-110V 2 PUERTAS","NEVERA-MILEX-110V","NEVERA-MILEX-110V"],
            ["NEVERA MILEXUS RF9.1CUFT","VIL-RF9.1CUF",""],
            ["NEVERA MYSTIC GRIS 207 LTS 2 PUERTA","NEVERA-207LSS-MYSTIC","RF-207LSS"],
            ["NEVERA MYSTIC GRIS 218 LTS 1 PUERTA.","NEVERA-218LSS-MYSTIC","RF-218LSS"],
            ["NEVERA PUERTA DE ACERO /NEGRA JAGUAR 288LTS","NESS-288LTS","NESS-288LTS"],
            ["NEVERA PUERTA DE ACERO/CUERPO NEGRO 92LTS JAGUAR","NEJ-92LTS","NEJ-92LTS"],
            ["NEVERA ROYAL 8.5 PIE ","RF855",""],
            ["NEVERA SAMSUNG GRIS","RT29K500JS8","NEVERA-SAMSUNG-GRIS"],
            ["NEVERA SJ ELECTRONIC 4PUERTAS 400LTS ","NEVERA-400L-4P","SJ-520SP"],
            ["NEVERA SJ ELECTRONIC EJECUTIVA NAVY 92LTRS","NEVERA-92L-NAVY","SJ-620SD"],
            ["NEVERA SJ ELECTRONIC EJECUTIVA NEGRO 92LTRS","NEVERA-92L-NEGRO","SJ-620SD"],
            ["NEVERA SJ ELECTRONIC EJECUTIVA SILVER 92LTRS","NEVERA-92L-SILVER","SJ-620SD"],
            ["NEVERA SJ ELECTRONIC GRIS 420L ","NEVERA-420L","SJ-400SD"],
            ["NEVERA SJ ELECTRONIC SEMI/ESC. NAVY 208L ","NEVERA-NAVY-208L","SJ-208SD"],
            ["NEVERA SJ ELECTRONIC SEMI/ESC. NEGRA 208LTS ","NEVERA-NEGRA-208L","SJ-208SD"],
            ["NEVERA SJ ELECTRONIC SEMI/ESC. SILVER 208L","NEVERA-SILVER-208L","SJ-208SD"],
            ["NEVERA SYDE 19 PIES WHIRPOOL ","WR151AKTWW","WR151AKTWW"],
            ["PALA CONUQUERA BELLOTA","PALA-CONUQUERA",""],
            ["PALA CONUQUERA COLIMA","7706912680370","3567"],
            ["PALA CONUQUERA HERRAGRO","PALA-SANTANDER-HERRAGRO",""],
            ["PALA CONUQUERA RUTEL","PALA-CONUQUERA-RUTEL",""],
            ["PALA CUADRADA 19CM CABO DE MADERA COVO","7453038475587",""],
            ["PALA CUADRADA ATOUAN ","PALA-CUADRADA",""],
            ["PALA CUADRADA C/CABO EXXEL","7826624130021","02-021-001"],
            ["PALA CUADRADA HERRAGRO","7706335000625","7706335000625"],
            ["PALA CUADRADA M/MADERA 5502-7 BELLOTA ","7702956026433","PALA-5502-7MAP"],
            ["PALA CUADRADA M/MADERA, LARGOM 1.08MTS STRUGGER","759192010013","8006000"],
            ["PALA CUADRADA MANIJA PLASTICA HERRAGRO","PALA-MANIJA-PLASTICA",""],
            ["PALA CUADRADA RUTEL","PALA-CUADRADA-RUTEL",""],
            ["PALA DRAGA BELLOTA","PALA-DRAGA",""],
            ["PALA DRAGA COLIMA","7706912680400","CAVADOR"],
            ["PALA DRAGA HERRAGRO","PALA-DRAGA-HERRAGRO",""],
            ["PALA DRAGA RUTEL","PALA-DRAGA-RUTEL",""],
            ["PALA REDONDA 27CM CABO DE MADERA COVO","7453038465151","CV-SB-0513"],
            ["PALA REDONDA CABO CORTO EXXEL USO AGRICOLA","205000009794","02-021-029"],
            ["PALA REDONDA CABO DE MADERA 21CM COVO","7453038465144","CV-SV-0311"],
            ["PALA REDONDA DORADA BELLOTA","7702956028963","859176"],
            ["PALA REDONDA M/MADERA LARGO 1.08MTS STRUGGER","759192010020","8006001"],
            ["PALA REDONDA MAN/LARGO EXXEL","205000009796","02-021-030"],
            ["PALA REDONDA MANGO CORTO PUÑO RUTEL","PALA-REDONDA-M/CORTO",""],
            ["PALA REDONDA MANIJA PLASTICA HERRAGRO","PALA-REDONDA-HERRAGRO",""],
            ["PALA REDONDA NEGRA CASCABEL","PALA-CASCABEL","PALA-CASCABEL"],
            ["PANEL CSAMSUNG LED 12W REDONDO PARA EMPOTRAR VERT ","1990311273012","SPS-009"],
            ["PANEL CSAMSUNG LED 18W CUADRADO EMPOTRABLE VERT","1990311272018-E","SPS-014"],
            ["PANEL CSAMSUNG LED 24W CUADRADO SUPERFICIAL VERT","1990311272024","SPS-024"],
            ["PANEL CSAMSUNG LED 30W CUADRADO SUPERFICIAL VERT","1990311272030","SPS-025"],
            ["PANEL CSAMSUNG LED 30W REDONDO SUPERFICIAL VERT","2009089456330","SPS-017"],
            ["PANEL CSAMSUNG LED 6W REDONDO PARA EMPOTRAR VERT ","1990311273006","SPS-003"],
            ["PANEL CSAMSUNG LED CUADRADO EMPOTRABLE 6W VERT ","1990311272006","SPS-004"],
            ["PANEL CUADRADO SUPERFICIAL 6W VERT","6236567860199","PCS-66"],
            ["PANEL DE PARED ACANALADA 06 MADERA GRIS 2.95 x 0.16 UNITEC","WPC-INT-MG-6","WPC-INT-MG-6"],
            ["PANEL DE PARED ACANALADA 06 MADERA NEGRO 2.95 x 0.16 UNITEC","WPC-INT-MN-13","WPC-INT-MN-13"],
            ["PANEL DE PARED ACANALADA 06 MADERA OSCURA 2.95 x 0.16 UNITEC","WPC-INT-MO-4","WPC-INT-MO-4"],
            ["PANEL DE PARED ACANALADA 06 MADERA ROBLE 2.95 x 0.16 UNITEC","WPC-INT-RO-10","WPC-INT-RO-10"],
            ["PANEL DE PARED ACANALADA 18 GRIS CONCRETO 2.95X0.16 UNITEC","WPC-INT-GC-18","WPC-INT-GC-18"],
            ["PANEL LED 24W PARA EMPOTRAR BORDE INFINITO VERT","223455856112316","DSR-246"],
            ["PANEL LED CSAMSUNG 18W CUADRADO SUPERFICIAL VERT","1990311272018-S","SPS-016"],
            ["PANEL LED CSAMSUNG CUADRADO SUPERFICIAL 6W VERT ","1990311271006","SPS-006"],
            ["PANEL LED CUADRADADO SUPERFICIAL 12W VERT","970200126342","EPCS-12634"],
            ["PANEL LED CUADRADO 10W PARA EMPOTRAR BORDE INFINITO VERT ","223455856112310","DSC-106"],
            ["PANEL LED CUADRADO 18W PARA EMPOTRAR BORDE INFINITO VERT ","223455856112311","DSC-186"],
            ["PANEL LED CUADRADO 24W PARA EMPOTRAR BORDE INFINITO VERT ","223455856112312","DSC-246"],
            ["PANEL LED CUADRADO EMPOTRABLE 12W VERT","6236567860113","PCE-126"],
            ["PANEL LED CUADRADO EMPOTRABLE BORDE INFINITO 36W RUN ","734896111754","BI-PEMCD36"],
            ["PANEL LED CUADRADO EMPOTRABLE BORDE INFINITO 36W VERT ","223455856112313","DSC-366"],
            ["PANEL LED CUADRADO EMPOTRABLE BORDE INFINITO18W RUN ","734896111730","BI-PEMCD18"],
            ["PANEL LED CUADRADO EMPOTRABLE DE 18W VERT ","9781701979598","HPCE-186"],
            ["PANEL LED CUADRADO EMPOTRABLE DE 24W VERT ","9781701979628","HPCE-246"],
            ["PANEL LED CUADRADO EMPOTRAR 12W CLASSIC LUX ","4006000284000","LIB-70-2"],
            ["PANEL LED CUADRADO EMPOTRAR 15W CLASSIC LUX ","5623500072107",""],
            ["PANEL LED CUADRADO EMPOTRAR 18W CLASSIC LUX ","127800003978","LIB-71-1"],
            ["PANEL LED CUADRADO EMPOTRAR 24W CLASSIC LUX","367415984000","LIB-71-2"],
            ["PANEL LED CUADRADO EMPOTRAR 4W CLASSIC LUX","56486465217","LIB-61"],
            ["PANEL LED CUADRADO EMPOTRAR 6W CLASSIC LUX ","587469100000",""],
            ["PANEL LED CUADRADO EMPOTRAR 9W CLASSIC LUX ","500364000120",""],
            ["PANEL LED CUADRADO P/ EMPOTRAR 12W VERT","2019112000012","SPS-010"],
            ["PANEL LED CUADRADO PARA EMPOTRAR 3W VERT ","6698564710295","PCE-36"],
            ["PANEL LED CUADRADO PARA EMPOTRAR 6W VERT ","6698564710264","PCE-66"],
            ["PANEL LED CUADRADO SOBREPONER 18W VERT ","6236567860205","PCS-186"],
            ["PANEL LED CUADRADO SUPERFICIAL 12W CLASSIC LUX ","580040003700",""],
            ["PANEL LED CUADRADO SUPERFICIAL 18W CLASSIC LUX ","450036400280",""],
            ["PANEL LED CUADRADO SUPERFICIAL 18W VERT","9710201379621","PSS-C18"],
            ["PANEL LED CUADRADO SUPERFICIAL 24W ANGEL LIHT","7453038448499",""],
            ["PANEL LED CUADRADO SUPERFICIAL 24W CLASSIC LUX ","3479855841125",""],
            ["PANEL LED CUADRADO SUPERFICIAL 6W 35K CLASSIC LUX ","1279658821001","LIB-71-3-1"],
            ["PANEL LED CUADRADO SUPERFICIAL 6W CLASSIC LUX","457200046100",""],
            ["PANEL LED CUADRADO SUPERFICIAL BORDE INFINITO 18W RUN ","734896111792",""],
            ["PANEL LED CUADRADO SUPERFICIAL BORDE INFINITO 24W RUN","734896111808",""],
            ["PANEL LED CUADRADO SUPERFICIAL BORDE INFINITO 36W RUN","734896111815",""],
            ["PANEL LED CUADRADRO EMPOTRABLE BORDE INFINITO 24W RUN ","734896111747","BI-PEMCD24"],
            ["PANEL LED DOBLE COLOR C/BORDE INFINITO 5W VERT","6591371280169","PDA-E55"],
            ["PANEL LED EMPOTRABLE CUADRADO 5W RUN ","736373169012",""],
            ["PANEL LED EMPOTRABLE CUADRADO 9W RUN ","736373169029",""],
            ["PANEL LED EMPOTRABLE REDODNO 24W RUN","736373170766",""],
            ["PANEL LED EMPOTRABLE REDONDO 12/4W ROSADO RUN","734896111341",""],
            ["PANEL LED EMPOTRABLE REDONDO 12W RUN ","736373168992",""],
            ["PANEL LED EMPOTRABLE REDONDO 15W RUN","736373169005",""],
            ["PANEL LED EMPOTRABLE REDONDO 3+3W PURPURA RUN ","0734896111167","PERPU01"],
            ["PANEL LED EMPOTRABLE REDONDO 5W RUN ","736373168978",""],
            ["PANEL LED EMPOTRABLE REDONDO 6/3W ROSADO RUN","734896111334",""],
            ["PANEL LED EMPOTRABLE REDONDO 9W 120MM RUN ","736373168985",""],
            ["PANEL LED EMPOTRAR COB CHIP 15W +LED AMARILLO 7W VERT","6236767890811","PE-C15Y"],
            ["PANEL LED EMPOTRAR CUADRADA 6W LUZ BLANCA ANGEL LIGHT","7453010004682","A105-PB003-6W"],
            ["PANEL LED OJO DE AGUILA 12W VERT","6534560092129","SPS-022"],
            ["PANEL LED OJO DE AGUILA 50W VERT","1724661126402","SPE-R35"],
            ["PANEL LED P/EMPOTRAR 3W REDONDA CLASSIC LUX ","287000369400-3W",""],
            ["PANEL LED REDONDA EMP LUZ CALIDA 18W ANGEL LIGHT ","7453038451239","A105-PB001M-18W"],
            ["PANEL LED REDONDO 10W PARA EMPOTRAR BORDE INFINITO VERT ","223455856112314","DSR-106"],
            ["PANEL LED REDONDO 12W SUPERFICIAL VERT ","6582345981236","PRS-12346"],
            ["PANEL LED REDONDO 18W PARA EMPOTAR VERT ","6534560092181","SPS-023"],
            ["PANEL LED REDONDO 18W PARA EMPOTRAR BORDE INFINITO VERT","6110201371220","PRW-318"],
            ["PANEL LED REDONDO 18W PARA EMPOTRAR BORDE INFINITO VERT ","223455856112315","DSR-186"],
            ["PANEL LED REDONDO 18W SUPERFICIAL VERT","6582345981830","PRS-18346"],
            ["PANEL LED REDONDO 36W PARA EMPOTRAR BORDE INFINITO VERT ","223455856112317","DSR-366"],
            ["PANEL LED REDONDO 3W PARA EMPOTRAR VERT SAMSUNG LED ","6534560090187","SPS-020"],
            ["PANEL LED REDONDO 6W PARA EMPOTRAR VERT ","6534560092167","SPS-021"],
            ["PANEL LED REDONDO BORDE INFINITO 36W SOBREPONER VERT","6527364964624","PSA-R364"],
            ["PANEL LED REDONDO EMP LUZ CALIDA 12W ANGEL LINGHT ","7453038446457","A105-PB0011M-12W"],
            ["PANEL LED REDONDO EMPOTRABLE BORDE INFINITO 18W RUN ","734896111693",""],
            ["PANEL LED REDONDO EMPOTRABLE BORDE INFINITO 24W RUN ","734896111709","BI-PEMRD24"],
            ["PANEL LED REDONDO EMPOTRABLE BORDE INFINITO 36W RUN","734896111716","BI-PEMRD36"],
            ["PANEL LED REDONDO EMPOTRABLE TIPO AVEJA STR-18W","7594320517254","str-18w"],
            ["PANEL LED REDONDO EMPOTRAR 12W CLASSIC LUX","783033051000","LIB-63"],
            ["PANEL LED REDONDO EMPOTRAR 15W CLASSIC LUX","15W","LIB-64"],
            ["PANEL LED REDONDO EMPOTRAR 15W CLASSIC LUX","PANEL-15W","5623500072107"],
            ["PANEL LED REDONDO EMPOTRAR 18W CLASSIC LUX","18W","LIB-64-1"],
            ["PANEL LED REDONDO EMPOTRAR 24W CLASSIC LUX ","963694000001","LIB-65"],
            ["PANEL LED REDONDO EMPOTRAR 3W CLASSIC LUX ","56486465216",""],
            ["PANEL LED REDONDO EMPOTRAR 6W CLASSIC LUX ","8545516523265",""],
            ["PANEL LED REDONDO EMPOTRAR 9W CLASSIC LUX ","457100025900",""],
            ["PANEL LED REDONDO P/EMPOTRAR 15W CLASSIC LUX ","56486465220","LIB-64"],
            ["PANEL LED REDONDO P/EMPOTRAR 6W CLASSIC LUX","56486465218","LIB-61"],
            ["PANEL LED REDONDO PARA EMBUTIR 18W CLASSIC LUX ","759153759153","LIB-64-1"],
            ["PANEL LED REDONDO SOBREPONER 12W VERT ","6583267925780","EPRS-126"],
            ["PANEL LED REDONDO SOBREPONER 18W VERT","9780201010626","EPRS-18634"],
            ["PANEL LED REDONDO SUPERFICIAL 12W CLASSIC LUX","000423111100",""],
            ["PANEL LED REDONDO SUPERFICIAL 12W CLASSIC LUX ","460001789411","LIB-66-1"],
            ["PANEL LED REDONDO SUPERFICIAL 12W VERT","2103481127412","SPS-019"],
            ["PANEL LED REDONDO SUPERFICIAL 18W CLASSIC LUX ","137540000008","LIB-67-1"],
            ["PANEL LED REDONDO SUPERFICIAL 24W","6583267978564","EPRS-246"],
            ["PANEL LED REDONDO SUPERFICIAL 24W CLASSIC LUX ","4000524160017","LIB-68"],
            ["PANEL LED REDONDO SUPERFICIAL 24W RUN","736373169067","PSPRD02"],
            ["PANEL LED REDONDO SUPERFICIAL 6W CLASSIC LUX","400281000030",""],
            ["PANEL LED REDONDO SUPERFICIAL 6W CLASSIC LUX ","3414587741251","LIB-65-2"],
            ["PANEL LED REDONDO SUPERFICIAL BORDE INFINITO 18W RUN ","734896111761",""],
            ["PANEL LED REDONDO SUPERFICIAL BORDE INFINITO 24W RUN","734896111778",""],
            ["PANEL LED REDONDO SUPERFICIAL BORDE INFINITO 36W RUN","734896111785",""],
            ["PANEL LED REDONDO VERT DOBLE COLOR BORDE INFINITO EMPOTRAR 12+4W ","6591371280176","PDP-E12"],
            ["PANEL LED SUPERFICIAL CUADRADO 24W RUN","736373169081",""],
            ["PANEL LED SUPERFICIAL CUADRADO 30W RUN ","736373170780","PSPCD04"],
            ["PANEL LED SUPERFICIAL CUADRADO 6W RUN ","736373170353",""],
            ["PANEL LED SUPERFICIAL REDONDO 15W RUN ","736373169050",""],
            ["PANEL LED SUPERFICIAL REDONDO 6W RUN ","736373170346",""],
            ["PANEL LED VERT DOBLE COLOR BORDE INFIN. EMP 12+4W ","6591371280138","PDA-E12"],
            ["PANEL SUPERFICIAL REDONDO 30W RUN ","736373170773","PSPRD04"],
            ["PEGA BLANCA 60G","6934518600060","HY-B060"],
            ["PEGA BLANCA PVA ABRAPEG 500 GR","PEGA-COLA-ABRAPEG","COLA BLANCA-ABRAPEG"],
            ["PEGA EPOXY 5MIN JADEVER","6942210206978","JDGX1K31"],
            ["PEGA LOKA 3 ","7591664000046",""],
            ["PEGA LOKA 3 CAJA 42PCS","PEGA-LOKA",""],
            ["PEGA LOKA INGCO","6928073712665",""],
            ["PEGA LOKA SUPER GLUE VERDE 4GRS EVER ","7453050024732",""],
            ["PEGA O COLA COVO P/CARPINTERIA 125G","7453038489768","CV-125"],
            ["PEGA O COLA COVO P/CARPINTERIA 250G","7453038443074","CV-250"],
            ["PEGA PARA MADERA 1/4 GAL COVO","7453038495363","CV-WG-1L"],
            ["PEGA PVC 125ML HUMMER","7453100258582","HUM-1133"],
            ["PEGA PVC 125ML HUMMER","7453100258599","HUM-1134"],
            ["PEGA PVC 16OZ GRIVEN","7453038452106","A367-PCG-16OZ"],
            ["PEGA PVC 230ML HUMMER","7453100258605","HUM-1135"],
            ["PEGA PVC 32OZ GRIVEN","7453038487078",""],
            ["PEGA PVC GRIVEN 0-118 OZ","7453038469715","A367-PCG-40Z"],
            ["PEGA PVC/CPVC ULTRA PEGATANKE 118ML","7862117300809",""],
            ["PEGA PVC/CPVC ULTRA PEGATANKE 240ML","7862117300106",""],
            ["PEGA PVC/CPVC ULTRA PEGATANKE 25ML","7862117300984",""],
            ["PEGA PVC/CPVC ULTRA PEGATANKE 475ML","7862117300823",""],
            ["PEGA PVC/CPVC ULTRA PEGATANKE 80ML","7862117301059",""],
            ["PEGA SOLDAR PVC SUPER FUERTE GRIVEN","7453010093631","GV-CPCG-4OZ"],
            ["PEGA TANQUE","7594011993992",""],
            ["PEGA-SOLD 300 PVC 70CC","7592203300597","PEGA-SOLD-300"],
            ["PEGO GRIS 14KG","PEGO-GRIS-14KG","PEGO-GRIS-14KG"],
            ["PEINILLA 3 PUNTOS ANARANJADO GAVILAN","7706912651851",""],
            ["PEINILLA BELLOTA RULA","7702956013549",""],
            ["PEINILLA BELLOTA RULA 24+ LIMA TRIANGULAR ","7702956013693","PEINILLA-RULA"],
            ["PEINILLA COLIMA RULA AMARILLA","7706912055215",""],
            ["PEINILLA EL INDIO CACHA ROJA","1300074000005",""],
            ["PEINILLA LINERO 22 COVO","7453038436427","CV-MT-0322"],
            ["PEINILLA MANGO PLASTICO 22 HERRAGRO","7706335002353","14170022"],
            ["PLAFON DE PLASTICO BEIGE TROEN","7453010009663","DQ-267"],
            ["PLAFON DE PORCELANA CHINO E-27","PLAFON-PORCELANA",""],
            ["PLAFON GRANDE 4.5 ELECFULL","8089132436","P0004BF"],
            ["PLAFON GRANDE ELECFULL","690426735861100",""],
            ["PLAFON PEQUEÑO ELECFULL","PLAFON-PEQUEÑO-ELECFULL","P0002PR"],
            ["PLAFON PLASTICO TIPO OVAL TRIC","679231555315",""],
            ["PLAFON PLASTICO TROEN 3.5 TROEN","7453038463706",""],
            ["PROTECTOR AIRE ACONDICIONADO Y REFRIGE 110V PROTEKTOR","7594004060144","PARDA110"],
            ["PROTECTOR AIRE ACONDICIONADO Y REFRIGE 110V PROTEKTOR","7594004060045","PARE110"],
            ["PROTECTOR AIRE ACONDICIONADO Y REFRIGE 220V PROTEKTOR","7594004060137","PARDA220"],
            ["PROTECTOR AIRE Y REFRIGERACION PROTEKTOR 110V","7594004060038","PAR-110"],
            ["PROTECTOR BREAKERMATIC 110V","7591178000129","PANTPV3"],
            ["PROTECTOR BREAKERMATIC AJUSTABLE 220V","7591178000563-30000BTU","PV220-BM"],
            ["PROTECTOR BREAKERMATIC ULTRA 220","7591178000563-220","PMP220-BD"],
            ["PROTECTOR BREAKERMATIC ULTRA 220V","7591178000167","PAN220V3-B"],
            ["PROTECTOR BREAKERMATIC ULTRA-42000BTU","7591178001102","PMP220"],
            ["PROTECTOR CLASSIC LUX C/REGULADOR DE VOLTAJE 36000BTU 220V","56788412310007272172333",""],
            ["PROTECTOR CONTRA ALTO Y BAJO VOLTAJES A/A 220V EXCELINE","7591919003761","GSMR220B3"],
            ["PROTECTOR DE AIRE 120V DE CABLE COWPLANDT","1000007206","PCHM-R120"],
            ["PROTECTOR DE AIRE ACONDICONADO PROTEKTOR220V","7594004060052","PAR-220"],
            ["PROTECTOR DE AIRE ACONDICONADO Y REFRIGERACION PROTEKTOR 220V","7594004060069","PARE-220"],
            ["PROTECTOR DE CADENA 5200/5800 MAGPOWER","PROTECTOR-CADENA-5800/5200","PROTECTOR-CADENA-5800/5200"],
            ["PROTECTOR DE CILINDRO TIPO PERA ALARGADO","PROTECTOR-C-A",""],
            ["PROTECTOR DE CILINDRO TIPO PERA REDONDO","PROTECTOR-C-R",""],
            ["PROTECTOR DE EQUIPO ELECTRONICOS 110V 3/T LGM","PROTE-3 TOMAS","PRO-102"],
            ["PROTECTOR DE REFRIGERACION 220 PROTEKTOR","7594004060106","PARTE-220"],
            ["PROTECTOR DE VOLTAGE 110V 3 TOMAS TROEN","7453010081058","A136-BV08"],
            ["PROTECTOR DE VOLTAGE 220V PARA A/A TROEN","7453038436106","TR-VP10-220V"],
            ["PROTECTOR DE VOLTAJE 110V-15AMP","736373169418","PRV04"],
            ["PROTECTOR DE VOLTAJE 110V-20AMP","736373169388","PRV01"],
            ["PROTECTOR DE VOLTAJE 120V PARA EQUIPOS ELCTRONICOS TROEN","7453010053338","TR-VP12-120V-Y"],
            ["PROTECTOR DE VOLTAJE 125V PARA ELECTRODOMESTICOS TROEN","7453038452632","TR-VP12-120V-R"],
            ["PROTECTOR DE VOLTAJE 125V TROEN","7453010056797","A006-YY-3C2GA"],
            ["PROTECTOR DE VOLTAJE 220V MONOFASICO TROEN","7453038440066","TR-VP6"],
            ["PROTECTOR DE VOLTAJE 220V P/AIRE SPECTRUM ","7591460000110","0110"],
            ["PROTECTOR DE VOLTAJE 220V PE220V-004 HTC","6932022810142",""],
            ["PROTECTOR DE VOLTAJE 220V TROEN","7453038486446","TR-VP15-220"],
            ["PROTECTOR DE VOLTAJE 220V-20AMP","736373169401","PRV03"],
            ["PROTECTOR DE VOLTAJE DIGITAL 120V TROEN ","7453010085506","TR-VP11"],
            ["PROTECTOR DE VOLTAJE EQUIPOS REFRIGERACION 120V EXCELINE","7591919003754","GSMR120B3"],
            ["PROTECTOR DE VOLTAJE MULTIPLUS AJUSTABLE CLASSIC LUX","6312549876557907","P-V099-D"],
            ["PROTECTOR DE VOLTAJE MULTIPROPOSITO 120V 15A TROEN ","7453038426817","TR-VP15-120V"],
            ["PROTECTOR DE VOLTAJE NEVERA CONGELADORES 110V NC-001","2022050524440",""],
            ["PROTECTOR DE VOLTAJE PARA EQUIPOS ELECTRONICOS TRIC","0679231568315","T-V099D-120V"],
            ["PROTECTOR DE VOLTAJE PARA NEVERA 110V EXCELINE","7591919000036","GSMN120"],
            ["PROTECTOR DE VOLTAJE PARA NEVERA 110V- BREAKERMATIC","7591178000013","ISO9001"],
            ["PROTECTOR DEL TANQUE DESMALEZADORA 430","PROTECTOR-430","PROTECTOR-430"],
            ["PROTECTOR ELÉCTRICO MULTI-USO 3 TOMAS CLASSIC LUX","6552632643566872152300","P-V008-E"],
            ["PROTECTOR ELECTRONICO MULTIPROPOSITO 110V EXCELINE","7591919000319","GSMMP120"],
            ["PROTECTOR GTRONIC 220V DIG ALAM","6958046980159","GT-V206-D"],
            ["PROTECTOR GTRONIC 220V TRIFASICO","6908152680238","T-V086"],
            ["PROTECTOR MONOFASICO ENCHUFE POWER PLUG 120V EXCELINE ","7591919003723","GSMRE120A"],
            ["PROTECTOR MONOFASICO P/PLUG 220V EXCELINE ","7591919003730","GSM-RE220M-3"],
            ["PROTECTOR MULTIPLUS CLASSIC LUX PARA AIRES ACONDICIONADOS 20A 220V","451111254360","P-V201"],
            ["PROTECTOR P/AIRE ACONDIONADO 220V ECOVOLT","7593240000068","VLTAB220"],
            ["PROTECTOR P/EQUIPOS ELECTRONICOS 120V ECOVOLT","7593240000020","VLTTV120"],
            ["PROTECTOR P/EQUIPOS ELECTRONICOS DE TRE EXCELINE","7591919000043","GSM-E"],
            ["PROTECTOR PARA AIRE ACONDICIONADO 110V ECOVOLT","7593240000051","EVB005"],
            ["PROTECTOR PARA AIRE ACONDICIONADO 120V ECOVOLT","7593240000037","VLTAB120"],
            ["PROTECTOR PARA AIRE ENCHUFABLE 220 CLASSIC LUX","3795825871009","P-V010-220"],
            ["PROTECTOR PARA AIRE ENCHUFABLE CLASSIC LUX 110V","2210101057805","P-V010-110"],
            ["PROTECTOR PARA AIRES ACONDICIONADOS Y REFRIGERACION 120V COWPLANDT","1000008545","PCHM-RE120"],
            ["PROTECTOR PARA EQUIPOS ELECTRONICOS PROTEKTOR 110V","7594004060021","PEE-110"],
            ["PROTECTOR PARA FAX BREAKERMATIC","7591178000068","7591178000068"],
            ["PROTECTOR PARA NEVERA 120V ECOVOLT","ECOVOLT-120","LTNE120"],
            ["PROTECTOR PARA NEVERA CLASSIC LUX","887512363965200003","P-V009-N"],
            ["PROTECTOR PARA NEVERA PROTEKTOR 110V","7594004060014","PRD-110"],
            ["PROTECTOR PARA REFRIGERACION 110W PROTEKTOR","7594004060090","PARTE-110"],
            ["PROTECTOR SECURITY PLUS 220V","6938946817830","VA060"],
            ["PROTECTOR STARLUX D/VPLTAJE P/NEVERAS LGM","7453006046610","SX-V009"],
            ["PROTECTOR TROEN DE NEVERA","7453046828405","TR-VP10-120V"],
            ["RODILLO 9 COVO ","7453038417495","CV-RB-938H"],
            ["RODILLO 9 ESQUELETO METCO","9780201380224","MET9375"],
            ["RODILLO 9 HUMMER","7453100258759","HUM-1150"],
            ["RODILLO C/FELPA DE PINTAR 4 HUMMER","7453100258797","HUM-1164"],
            ["RODILLO CON BANDEJA MASTER","RODILLO-MASTER","KIT-MASTER"],
            ["RODILLO CON FUNDA 9 PRO PAINT ","7453078503974","A145-HL1414"],
            ["RODILLO CON FUNDA PARA PINTAR 9 INGCO","6941640163905","HRHT282302"],
            ["RODILLO KOBATEX","6950120210787",""],
            ["RODILLO KOBATEX ","6950181200338",""],
            ["RODILLO MANGO EXTENDIBLE INGCO","6925582122954","HRHT442302T"],
            ["RODILLO MICRO-FIBRA 12 EMTOP ","6972951249606",""],
            ["RODILLO MICRO-FIBRA 4 EMTOP","6972951248258","ECBH061001"],
            ["RODILLO MICRO-FIBRA EMTOP ","6972951249545","ECBH092551"],
            ["RODILLO MINI P/ PINTAR 4/ 100MM JADEVER","6942210210432","JDCB1904"],
            ["RODILLO P/ PINTAR 9 230MM JADEVER","6942210210456","JDCB1909"],
            ["RODILLO PARA PINTAR 9 ESQUELETO HUMMER","7453100258773","HUM-1152"],
            ["RODILLO PARA PINTAR ACRICLICO 9 WADFOW","6975085807865","WCB1909"],
            ["RODILLO PARA PINTAR ESQUELETO RADIANT","7594002510344",""],
            ["RODILLO SIN FUNDA 9PULGADAS COVO","7453010025205","CV-RB-09H"],
            ["RODILLO SIN FUNDA PARA PARED INGCO","6941640164148","HCBB28092"],
            ["SEGUETA 12 HUMMER","7453100258377","HUM-1112"],
            ["SEGUETA 300MM EMTOP","6941556202521",""],
            ["SEGUETA ESCOLAR 6/12 CON HOJAS BEST VALUE","7453001153313","H420708"],
            ["SEGUETA MINI HUMMER","7453100258360","HUM-1111"],
            ["SET DE 3 BOCHAS PARA PINTAR 1,2,3 WADFOW","6942123004159","WPB1931"],
            ["SET DE BANDEJA CON RODILLO","7592927000322","KIT-004"],
            ["SET DE BANDEJA PARA PINTURA PRO PAINT","7453078521176","A145-9GL904"],
            ["SET DE BROCHAS 9PZA MANGO DE MADERA INGCO","6925582115734","CHPTB0114091"],
            ["SET DE BROCHAS DE 3 PZA INGCO","6941640158444","CHPTB7860301"],
            ["SET DE BROCHAS PARA PINTAR 3PZAS 1,2,3 INGCO","6976051788171","CHPTB7850302"],
            ["SIFON A/N 2","PLO-SAN-2","SIFON-A/N-2"],
            ["SIFON A/N 2","SIFON A/N 2","SIFON A/N 2"],
            ["SIFON A/N 3","SIFON A/N 3",""],
            ["SIFON A/N 4","SIFON-PVC-4",""],
            ["SIFON AJUSTABLE 1 1/2 P/LAVAPLATOS KOBATEX","7329833116-1 1/2","SIFON-1 1/2"],
            ["SIFON AJUSTABLE 1 1/4 P/LAVAMANOS KOBATEX ","7329833116-1 1/4","SIFON-1 1/4"],
            ["SIFON DE 1/ 1/2 CON ADAPTADOR PLASTICO","7592032010162","SIF-07"],
            ["SIFON DOBLE BLANCO 1 1/2 KOBATEX","7450097112125","SIFON-DOB-11/2"],
            ["SIFON DOBLE DE FREGADERO 1 1/2 FERMETAL","7592032001443","COL-04"],
            ["SIFON DOBLE FLEXIBLE C/BOTELLA GRIVEN","7453010003005","A367-HDA151-140"],
            ["SIFON DOBLE PARA FREGADERO 1 1/2 GRIVEN","7453078501611","A367-H1403"],
            ["SIFON DOBLE PARA FREGADERO 1 1/2 SF","SINFON-DOBLE",""],
            ["SIFON EXTENSIBLE 1 1/2 GRIVEN","7453010090630","A367-MP003-1-2"],
            ["SIFON EXTENSIBLE 1 1/4 GRIVEN","7453010074999","A367-MP003"],
            ["SIFON EXTENSIBLE 1-1/2 - 1-1/4 GRIVEN","7453038494625","A367-MP009"],
            ["SIFON EXTENSIBLE 1-1/2 CON REDUCTOR/ADAPTADOR 1 FERMETAL","7592032010049","SIF-08N"],
            ["SIFON FLEXIBLE CON DESAGUE PUSH GRANDE 1 1/4 GRIVEN ","7453078515175","GV-FPL221A"],
            ["SIFON FLEXIBLE DOBLE 1 1/2 METALES ALIADOS","7592978395194","SIF0110"],
            ["SIFON FLEXIBLE DOBLE C/DESAGUE 1 1/2 GJSJ LGM","SIFON-GJSJ-1-1/2","SIF-106"],
            ["SIFON FLEXIBLE METALES ALEADOS DE 1 1/2 ","7592978392704","SIF0113"],
            ["SIFON FLEXIBLE PLASTICO CROMO 1 1/2 P/FREGADERO C/DESAGUE LGM","SIF-109","SIF-109"],
            ["SIFON FLEXIBLE PLASTICO CROMO 1 1/4 P/LAVAMANO C/DESAGUE LGM","SIF-108","SIF-108"],
            ["SIFON FLEXIBLE UNIVERSAL 1, 1-1/4, 1-1/2 HUMMER","7453100262749","HUM-1203"],
            ["SIFON O COLECTOR DOBLE GRIVEN 4-270","7453078501628","A367-D010"],
            ["SIFON PARA FREGADERO 1 1/2 X 4 GRIVEN","7453038448475","A367-HDB114"],
            ["SIFON PARA FREGADERO FLEXIBLE 1 1/2 GRIVEN","7453038490924","GV-HAD-153"],
            ["SIFON PARA FREGADERO O LAVAMANOS 1 1/2-1 1/4 GRIVEN","7453038456159","A367-D028-1.5"],
            ["SIFON PARA FREGADERO Y LAVAMANOS 1 1/2-1 1/4 GRIVEN","7453038429238","GV-HDA-151W"],
            ["SIFON PARA LAVAMANOS 1 1/4 SENCILLO PVC GRIVEN","7453078501604","A367-D028"],
            ["SIFON PLASTICO 1 1/2 METALES ALIADOS","7592978396801","SIF-0103"],
            ["SIFON PLASTICO 1 1/4 METALES ALIADOS","7592978396818","SIF-0104"],
            ["SIFON PLASTICO CON TRAMPA 1 1/2 METALES ALIADOS","7592978003358","SIF-0102"],
            ["SIFON PLASTICO CON TRAMPA DE 1 1/4 METALES ALIADOS","7592978003341","SIF-0115"],
            ["SILICON ABRILLANTADOR 120CC CHAMPION","7591273199605",""],
            ["SILICON AZUL RTV 85G COVO","7453038412759","CV-RTV-BU"],
            ["SILICON BLANCO 300ML INGCO","6928073675991","HASS01"],
            ["SILICON BLANCO 300ML WADFOW","6976057336994","WGQ2T30"],
            ["SILICON BLANCO DE CARTUCHO EMTOP","6941556216580","EASS3001"],
            ["SILICON BLANCO PARA BAÑOS","6291100717330",""],
            ["SILICON BLANCO RTV 300G COVO","7453038416214","CV-RTVG280-WT"],
            ["SILICON DE 20CM 6PZAS EMTOP","6972951247350","EGGS20601"],
            ["SILICON EN BARRA 11X150mm 7pcs JADEVER","6942210206893","JDGJ5515"],
            ["SILICON GP BLANCO PARA VIDRIOS","6291100717224",""],
            ["SILICON GP NEGRO PARA VIDRIOS","6291100717576",""],
            ["SILICON GP TRANSPARENTE PARA VIDRIOS","6291100717231",""],
            ["SILICON GRIS ALTA TEMPERATURA AUTO PARTS 85G","4M-RTV85GR","4M-RTV85GR"],
            ["SILICON GRIS PARA ALTA TEMPERATURA EXXEL","205000009834","09-017-234"],
            ["SILICON MEGA GREY 85G","078727999396",""],
            ["SILICON NEGRO 280ML HUMMER","7453100258544","HUM-1129"],
            ["SILICON NEGRO 300ML INGCO","6928073679609","HASS03"],
            ["SILICON NEGRO PARA BAÑOS","6291100717439",""],
            ["SILICON TRANSPARENTE 280ML HUMMER","7453100258520","HUM-1127"],
            ["SILICON TRANSPARENTE 300GR EXXEL","205000009121","09-017-232"],
            ["SILICON TRANSPARENTE 85GR EXXEL","205000009837","09-017-233"],
            ["SILICON TRANSPARENTE DE 85ML","6291108399712",""],
            ["SILICON TRANSPARENTE LIQUIDO 100ML ZASC","0679231573555","Z-10012"],
            ["SILICON TRANSPARENTE PARA BAÑOS","6291100717347",""],
            ["SILICONE SELLANTE TRANSPARENTE 300ML EMTOP EASS3002","6941556219253",""],
            ["SOCATE BAQUELITA CLASSIC LUX BLANCO","5731598252414","PLAFON-CLASSIC"],
            ["SOCATE BENJAMIN CON CADENITA TROEN","7453038452410","DQ-036-IV"],
            ["SOCATE CLASSIC LUX DE PORCELANA 4 BLANCO","SOC-30","SOC-30"],
            ["SOCATE CON VENTILADOR 6 FAN LIGHT U-SHAPED","6913545138128","FSD-60W"],
            ["SOCATE DE GOMA - PORCELANA RUN","0736373170315","SOC07"],
            ["SOCATE DE GOMA / PORCELANA E27 CLASSIC LUX","SOC-57","SOC-57"],
            ["SOCATE DE GOMA NEGRO VITRON","03017039",""],
            ["SOCATE DE GOMA PORCELANA 250W E-24, 110-130V FERMETAL","SOC-03","SOC-03"],
            ["SOCATE DE GOMA- PORCELANA","7453038451765","A136-FB4104"],
            ["SOCATE DE PLASTICO 2.25 TROEN","7453038463737",""],
            ["SOCATE DE PLASTICO TIPO PLAFON VERT","9184751910021","JEH-019"],
            ["SOCATE DE PORCELANA 250V - 660V 4-1/2 HUMMER","7453100257691","HUM-1044"],
            ["SOCATE DE PORCELANA 4 1/2 VITRON","205000008708","03-017-045"],
            ["SOCATE DE PORCELANA CLASSIC LUX","SOC-30-PORCELANA","SOC-30-PORCELANA"],
            ["SOCATE DE PORCELANA METCO","SOCATE-P-METCO","SOCATE-P-METCO"],
            ["SOCATE DE PORCELANA TIPO PLAFON 4A-250V KOBATEX ","6906450790833","SOCATE-P-KOBATEX"],
            ["SOCATE DE TUBO LED C/BASE CLASSIC LUX","SOCATE-TUBOLED-CLASSIC LUX","SOC-05"],
            ["SOCATE DOBLE E27 C/CADENA NEGRO TROEN","7453010015176","DQ-036-BR"],
            ["SOCATE E27 CON ENCHUFE VITRON","205000010251","03-017-052"],
            ["SOCATE E27 PORCELANA","03-017-044","03-017-044"],
            ["SOCATE PLAFON BAKELITA RUN","736373170285","SOC04"],
            ["SOCATE PLAFON PLASTICO E27 VERT","9184751910007","JEH-017"],
            ["SOCATE PLASTICO BLANCO TROEN","7453010008024","DQ-031-W"],
            ["SOCATE PLASTICO TIPO PLAFON AXUM","7594320516516","040424"],
            ["SOCATE PVC RUN","736373170339","SOC09"],
            ["SOCATE RECEPTACULO 4 1/2 E-27 4 AMP-250V RUN","734896113178","SOC11"],
            ["SOCATE TIPO BAQUELITA HUMMER","7453100257707","HUM-1045"],
            ["SOCATE TIPO PLAFON PARA BOMBILLO CLASSIC LUZ","SOCATE-TIPO PLAFON-CLASSIC LUX","SOC-62"],
            ["SOCATE TROEN PROBADOR DE BOMBILLO ROSCA","7453010009427","ST-6"],
            ["SOCATES DE GOMA METALES LEADOS PORCELANA E27","SOCATE-GOMA-MA",""],
            ["SOLDADURA DE ESTAÑO 40/60 12G HUMMER","7453100258254","HUM-1100"],
            ["SOLDADURA EPOXICA MULTIUSO 57GRS SECADO RAPIDO ZASC","7453118002870","Z-EA57G"],
            ["SOLDADURA PVC 90 118CM BELL POWER","7594000461587","7V0373"],
            ["SOLDADURA PVC CONDICIONES HUMEDAS 1/128GL GRIVEN","7453038482899","A367-PCG29"],
            ["SOLDADURA PVC PARA AGUA CALIENTE 1ONZ GRIVEN","7453010039677","GV-CPCG29"],
            ["TALADRO 1/2 400W RUN","TALADRO-1/2-RUN",""],
            ["TALADRO 3/8 400W RUN","7591996002275","TL001"],
            ["TALADRO ATORNILLADOR INALAMBRICO 20V EMTOP","6941556211745","ULCIDL620012"],
            ["TALADRO DAEWOO 750W IMPACT DRILL","7798125042311",""],
            ["TALADRO DE BANCO 350W INGCO","UDP133505","UDP133505"],
            ["TALADRO DE IMPACTO 1100W EMTOP","6941556206413","ULMDL1101"],
            ["TALADRO DE IMPACTO 12 1100W INGCO","6928073605141","UID11008"],
            ["TALADRO DE IMPACTO 12 680W INGCO","6941640128102","UID6808"],
            ["TALADRO DE IMPACTO 12V INGCO","6925582142327","UCIDLI1232"],
            ["TALADRO DE IMPACTO 650W INGCO","6928073608555","UID6508"],
            ["TALADRO DE IMPACTO 680W EMTOP","6941556211455","ULMDL0681"],
            ["TALADRO DE IMPACTO 850W EMTOP","6972951240887",""],
            ["TALADRO DE IMPACTO 88 PZAS EMTOP","6941556216658","ULEDK08801"],
            ["TALADRO DE IMPACTO DRILL 1/2 950W RUN","0734896113505","TL011"],
            ["TALADRO DE IMPACTO INALAMBRICO 35MM WADFOW","6941786814488","UWCDP5222"],
            ["TALADRO DE IMPACTO INALAMBRICO 38 20V INGCO","6976051782971","UCDLI200528"],
            ["TALADRO DE IMPACTO SET 122 PZAS EMTOP","6941556206420",""],
            ["TALADRO DE INALAMBRICO EMTOP ","6941556221621","ULCDL620012"],
            ["TALADRO DE PERCUSION 650W WADFOW","6942123010624","UWMD15651"],
            ["TALADRO INALAMBRICO 1.5A 12V EMTOP ","6941556222178","ULCIDL12622"],
            ["TALADRO INALAMBRICO 12V 3/8 RUN","734896113444","TL009"],
            ["TALADRO INALAMBRICO 12V EMTOP","6972951240429","ULCDL12511"],
            ["TALADRO INALAMBRICO 12V TIPO C INGCO","6976051781189","CDLI12428"],
            ["TALADRO INALAMBRICO 20V 3/8 RUN","734896113437","TL008"],
            ["TALADRO INALAMBRICO 20V INGCO","6941640179043","UCDLI20051"],
            ["TALADRO INALAMBRICO 20V WADFOW","6976057336161","UWCDP511"],
            ["TALADRO INALAMBRICO DE IMPACTO 12V, 20NM-VELOCIDAD 1500MP JADEVER","6942210215666","JDCDS540"],
            ["TALADRO INALAMBRICO DE IMPACTO 20V-1500RPM JADEVER","6942210218780","JDCDP521"],
            ["TALADRO PERCUTOR 12 850W INGCO","6928073605158","UID8508"],
            ["TALADRO PERCUTOR 550W 1/2 13MM BLACK+DECKER","885911453400","HD555-B3"],
            ["TALADRO PERCUTOR 550W 3/8 10MM BLACK-DECKER","885911463201","TP-550-B3"],
            ["TALADRO PERCUTOR 750W COVO","7453010086770","CV-TAL-750"],
            ["TALADRO PERCUTOR 810W INGCO","6941640160089","UID8108"],
            ["TALADRO ROTACIONAL 500W EMTOP","6941556204983","ULEDL501"],
            ["TALADRO ROTATIVO 38 500W INGCO","6925582132298","UED50028"],
            ["TALADRO ROTOMARTILLO DEMOLEDOR INDUSTRIAL 1500W INGCO","6925582134230","URH1500281"],
            ["TALADRO ROTOMARTILLO PERFORADOR 800W MAS MANDRIL INGCO","6925582145922","URGH9028-2"],
            ["TALADRO ROTOMARTILO DEMOLEDOR 1050W INGCO","6941640167767","URH10506"],
            ["TALADRO Y ATORNILLADOR DE IMPACTO 20V EMTOP","6941556206383",""],
            ["TEFLON 1/2 PEQUEÑO AQUA BLUE","7453011722646",""],
            ["TEFLON 1/2 X 12MT GRIVEN","7453010020880","GV-TF-1-2-12M"],
            ["TEFLON 15MT AQUA BLUE","7453042871016",""],
            ["TEFLON 19MM 15MT HUMMER","7453100259190","HUM-1194"],
            ["TEFLON 3/4 FERMETAL","7592032010346","TEF-05"],
            ["TEFLON AZUL ECONOMICO 3/4 x 10MTS","6924209801685","PH-SLD1902"],
            ["TEFLON HUMMER 1/2 X 10MTS","7453100259183","HUM-1193"],
            ["TEFLON METCO AMARILLO","6976369153012","AVE-MU301"],
            ["TEFLON NASTRO PROFESIONAL 19MM","TEFLON-NASTRO",""],
            ["TEFLON PROFESIONAL 3/4 KOBATEX ","6987021102781","TEFLON-KOB-3/4"],
            ["TEFLON PROFESIONAL 3/4 SECURITY","7453010041076","52-CH35"],
            ["TEFLON PROFESIONAL AMARILLO 3/4X15M ","TEFLON-AMARILLO","7592032010315"],
            ["TEIPE 0.15MM 19MM X 5M TROEN","7453038422109","TR-ETP-1516-DP"],
            ["TEIPE 3/4 X 18M TRUPER","7501206644911","M-33"],
            ["TEIPE 3/4X18M METCO","8425623162140",""],
            ["TEIPE 3/4X18M PEGATANKE","7862117300830",""],
            ["TEIPE 3M ECONOMICO","7591233413109","TEIPE-3M"],
            ["TEIPE 3M ORIGINAL AZUL FERMETAL","076308925680","TEIPE-3N-ORIGINAL"],
            ["TEIPE 3M VERDE","638060414798",""],
            ["TEIPE CINTA AISLANTE 3/4 18MTS EXXEL","205000008511",""],
            ["TEIPE COBRA 10M ORIGINAL","7592032010520","TEI-21"],
            ["TEIPE COBRA 3/4X18M ","7592032010353",""],
            ["TEIPE COBRA ORIGINAL 3/4-18MTS","7592043122571","TEI-01"],
            ["TEIPE COBRA PEQUEÑO","7592043121468",""],
            ["TEIPE ELECTRICO 10MTS HUMMER","7453100257547","HUM-1029"],
            ["TEIPE ELÉCTRICO 20.1M CINTA AISLANTE TROEN","7453038480833","TR-ETP-1566"],
            ["TEIPE ELECTRICO 9.1MTS SECURITY ","7453010014056","MS-013X10YDS"],
            ["TEIPE ELECTRODO 3/4 X 9MT METCO","3290803060210","060210"],
            ["TEIPE EXXEL 10MTS 09018036","205000008508",""],
            ["TEIPE NEGRO 20M HUMMER","7453100257530","HUM-1028"],
            ["TEIPE NEGRO INGCO","6925582119466","HPET1101"],
            ["TELEFONO CANTV ROYAL REAL","6928276030146","KX-TS500MX"],
            ["TELEFONO FIJO INALAMBRICO D1005","D10005","D1005"],
            ["TELEVISOR 32 SMARTV ROYAL REAL","TV-32-ROYAL-SMART","K32"],
            ["TELEVISOR 43 SMARTV ROYAL REAL","TV-43-ROYAL-SMART","K43"],
            ["TELEVISOR 50 SMARTV ROYAL REAL","TV-50-ROYAL-SMART","K50"],
            ["TELEVISOR ANDROID SMARTV 32 SJ ELECTRONICS","TV-32-SJ-SMARTV","SJ-32-SJMARTV"],
            ["TELEVISOR DARIN 32 SMART ","TV-32-DARIN-SMART","DTL325T-20"],
            ["TELEVISOR EDMIRA 32 LED ","TV-32-EDMIRA-BASICO","TELEVISOR-EDMIRA-32LED"],
            ["TELEVISOR EDMIRA 43 SMART","TV-43-EDMIRA.SMART","TV-EDMIRA-43-SMART"],
            ["TELEVISOR LED 32 HD GDELUXE","TV-32-GDELUXE-BASICO","GDX32LED"],
            ["TELEVISOR LED 32 OMEGA ","TV-32-OMEGA-BASICO","TV-32-OMEGA-LED"],
            ["TELEVISOR LED CLX 32 ","TV-32-CLX-BASICO","TV-VIZZION-32"],
            ["TELEVISOR LED JAV LANDSCAPE 42","TV-42.JAV-BASICO","TV-JAV-42''"],
            ["TELEVISOR LED SJ ELECTRONICS 32 ","TV-32-SJ-BASICO","SJ-32-LED"],
            ["TELEVISOR LED SJ ELECTRONICS 42","TV-42-SJ-BASICO","SJ-42-LED"],
            ["TELEVISOR LED SMARTV 32 OMEGA","TV-SMARTV-32-OMEGA","OTL-32SB"],
            ["TELEVISOR LED SMARTV 40 OMEGA","TV-SMARTV-40-OMEGA","OTL-40SB"],
            ["TELEVISOR LED SYON 20","TV-20-SYON","BHL20B"],
            ["TELEVISOR OMEGA LED 42 SMART TV CON NETFLIX.YOUTUBE.ANDROID 12.0 .1G+8G MEMORIA.BLUETOOH 4.2HDMI","TV-42FS-OMEGA","OTL-42FS"],
            ["TELEVISOR RCA SMARTV 43","TV-43-RCA-SMART","TV-SMARTV-RCA-43"],
            ["TELEVISOR SMAR TV 32 OMEGA","TL-32SB","TL-32SB"],
            ["TELEVISOR SMART 32 MYO","TV-32-MYO-SMART","MY-S32800"],
            ["TELEVISOR SMART 32 OMEGA","OTL-32FS","OTL-32FS"],
            ["TELEVISOR SMARTV 32 OMEGA","TV-32-OMEGA-SMART","TV-SMARTV-32''-OMEGA"],
            ["TELEVISOR SMARTV 32 RCA","TV-32-RCA-SMART","ROKU-TV"],
            ["TELEVISOR SMARTV 40 WESTINGHOUSE ","TV-40-WESTINGHOUSE-SMART","W40P22SSM"],
            ["TELEVISOR SMARTV 42 OMEGA","TV-42-OMEGA-SMART","TV-SMARTV-42-OMEGA"],
            ["TELEVISOR SMARTV 43 OMEGA","TV-43-OMEGA-SMARTV",""],
            ["TELEVISOR SMARTV 50 HISENSE","6942147490976","50A6K"],
            ["TELEVISOR SMARTV 50 VIZZION","TV-50-VIZZION",""],
            ["TELEVISOR SMARTV 60 + TABLET LCD WESTINGHOUSE","7453021621144","W60A23SNXSM"],
            ["TELEVISOR SMARTV CLX 42 ","TV-42-CLX-SMART","CLX-42"],
            ["TELEVISOR SMARTV JVC 55","TV-55-JVC-SMART","LT55KB527"],
            ["TELEVISOR SMARTV MAGNAVOX 32","TV-32-MAGNAVOX-SMART","32MEZ412/M1"],
            ["TELEVISOR SMARTV MAGNAVOX 43 ","TV-43-MAGNAVOX-SMART","TV-SMARTV-43-MAGNAVOX"],
            ["TELEVISOR SMARTV MAGNAVOX 50 4K","7450074498617","TV-50-MAGNAVOX-SMART"],
            ["TELEVISOR SMARTV MAGNAVOX 55 4K","7450074498624","TV-55-MAGNAVOX-SMART"],
            ["TELEVISOR SMARTV MYSTIC 40","TV-40-MYSTIC-SMART","MY-SG40104T"],
            ["TELEVISOR SMARTV SJ ELECTRONIC 32","TV-32-SJ-SMARTV",""],
            ["TELEVISOR SMARTV SJ ELECTRONIC 43","TV-43-SJ-SMART","TV-SMARTV-43-SJ"],
            ["TELEVISOR SMARTV TOSHIBA 55","TV-55-TOSHIBA-SMART","55C350KB"],
            ["TELEVISOR SMARTV WESTINGHOUSE 32","TV-32-WESTINGHOUSE-SMART","SMARTV32-WESTIN"],
            ["TELEVISOR SMARTV WESTINGHOUSE 43","TV-43-WESTINGHOUSE -SMART","W43A23SNXSM"],
            ["TELEVISOR SMARTV WESTINGHOUSE 50","TV-50-WESTINGHUSE-SMART","W50A23SNSXM"],
            ["TOMA 2P+T+INT LUZICA 6876310B","7702089025679",""],
            ["TOMA AEREA CLASSI LUX CON TIERRA 15AMP","EET-02","EET-02"],
            ["TOMA AEREA METAL SENCILLA 15AMP 110-1 FERMETAL","7593826016964","TOM-13"],
            ["TOMA AMERICANA SENCILLA+ 2 USB SWITCH SOCKET","7594320516349","040407"],
            ["TOMA CHINA 220V TROEN","7453038414142",""],
            ["TOMA CLASSIC LUX AEREA 1/2 VUELTA 4P 20AMP","EBN-35","EBN-35"],
            ["TOMA CLASSIC LUX COAXIAL PARA TV","67961313132323","LUM-97"],
            ["TOMA COAXIAL PARA CABLE DE TV NEGRO TROEN","7453010023522","A136-ETB-CX"],
            ["TOMA COAXIAL PARA CABLE DE TV PLATEADO TROEN","7453038445511","A136-ETP-CX"],
            ["TOMA CORRIENTE 110 15A DOBLE CON TIERRA PARA EMPOTRAR","TOMA-110V-15A",""],
            ["TOMA CORRIENTE CLASSIC LUX SUPERFICIAL DE METAL 15A","ETT-02","ETT-02"],
            ["TOMA CORRIENTE DOBLE BLANCO TRIC","679231560708",""],
            ["TOMA CORRIENTE DOBLE BLANCO TROEN ","7453038401005","TR-HYD-2C"],
            ["TOMA CORRIENTE DOBLE BLANCO TROEN ","7453038498333","A136-ETW-6C"],
            ["TOMA CORRIENTE DOBLE CON TIERRA TROEN","7453078546421","A136-HYA-2C"],
            ["TOMA CORRIENTE DOBLE PARA EMPOTRAR POLARIZADO TROEN","745307854642215886",""],
            ["TOMA CORRIENTE DOBLE SUPERFICIAL VERT","9780201371116","JEH-080"],
            ["TOMA CORRIENTE HEMBRA DE GOMA TIPO CHINO VERT","9184751964987","JEH-009"],
            ["TOMA CORRIENTE LITE DOBLE VITRON","205000008520","03-014-161"],
            ["TOMA CORRIENTE SENCILLO CLASSIC LUX P/T HORIZONTAL BEIGE","816V-TOMA","ETT-05"],
            ["TOMA CORRIENTE SENCILLO CLASSIC LUX P/T HORIZONTAL BLANCO","801320012310","ETT-06"],
            ["TOMA CORRIENTE SENCILLO KOBATEX ","00125346000028","KPA08"],
            ["TOMA CORRIENTE SUPERFICIAL P/T HORIZONTAL CLASSIC LUX 15A 250V","ETT-02-1","ETT-02-1"],
            ["TOMA CORRIENTE TRIFILAR 220V PARA EMPOTRAR","TOMA-220V",""],
            ["TOMA CORRIENTE TRIPLE SIN TIERRA TIPO PANELITA EXXEL","ELEC-001",""],
            ["TOMA DOBLE 2P+T LUZICA PU1228","7702089163029","PU1228"],
            ["TOMA DOBLE 2P+T OLIVO 687633OB","64898",""],
            ["TOMA DOBLE BLANCA SWITCH SOCKET","7594320516363","040409"],
            ["TOMA DOBLE C/TIERRA 127/250V 15AMP RUN","0736373169852","TM03 10429"],
            ["TOMA DOBLE CON TIERRA CATANIA RUN","736373170018","INT16"],
            ["TOMA DOBLE CON TIERRA CLASSIC LUX","66663643634","LUM-90"],
            ["TOMA DOBLE CON TIERRA NEPAL RUN","736373169937","INT08"],
            ["TOMA DOBLE CON TIERRA SUPERFICIAL RUN","736373169852","TM03"],
            ["TOMA DOBLE CON TIERRA VENECIA RUN","736373169975","INT12"],
            ["TOMA DOBLE LEGRAND 0079 superficial","0079",""],
            ["TOMA DOBLE POLARIZADA AMERICANA METALES ALIADOS","7592978005895","TOM0206"],
            ["TOMA DOBLE SUPERFICIAL RUN","736373171886","TM04"],
            ["TOMA DOBLE SUPERFICIAL TIPO PANELITA CLASSIC LUX 78V","ETT-22","ETT-22"],
            ["TOMA HEMBRA SWITCH SOCKET","7594320516431","040416"],
            ["TOMA HEMBRA VINIL GOMA RUN","736373170162","ENCH06"],
            ["TOMA HEMBRA VINIL METAL CON TIERRA RUN","736373170124",""],
            ["TOMA PANELA DOBLE SUPERFICIAL METALES ALIADOS","7592978392018","MAE004"],
            ["TOMA PARA CABLE DE RED RJ45 NEGRO TROEN","7453010015879","A136-ETB-RJ45"],
            ["TOMA PARA EMPOTRAR TIPO CHINO VERT NEGRO","1280201392923","T80-N12"],
            ["TOMA PLASTICA CHINA PANELITA","PANELITA-CHINA",""],
            ["TOMA SENCILLO 2P+T LUZICA PU1128","7702089162688",""],
            ["TOMA TELEFONO","7594320516332","VB-TEL"],
            ["TOMA VINIL - GOMA C- TIERRA RUN","736373170148","ENCH04"],
            ["TOMACIRRIENTE SUPERFICIAL AMARILLO SAPITO","SAPITO-AMARILLO",""],
            ["TOMACORRIENTE 110V HUMMER","7453100257769","HUM-1051"],
            ["TOMACORRIENTE 220V GRIS VERT","1380201393934",""],
            ["TOMACORRIENTE 220V TROEN","7453038419284","SCT-U33-C"],
            ["TOMACORRIENTE 6TOMAS HUMMER","7453100257554","HUM-1030"],
            ["TOMACORRIENTE ALUMINIO KOBATEX ","00125346000058","KPP08"],
            ["TOMACORRIENTE BLIND C/TIERRA VITRON ","205000007728","048PBP3"],
            ["TOMACORRIENTE BLINDADO VITRON","205000007722",""],
            ["TOMACORRIENTE CON 1 USB Y 1 TIPO C DE ACRILICO BLANCO","7453078509945","TR-ATW-6C1TC"],
            ["TOMACORRIENTE CON DOBLE PUERTO DE USB GRIS KOBATEX ","00125346000056","KPP06"],
            ["TOMACORRIENTE CON DOBLE PUERTO USB BLANCO KOBATEX ","00125346000006","KB106"],
            ["TOMACORRIENTE CON DOBLE PUERTO USB DE ALUMINIO KOBATEX ","0012534600002","KPA06"],
            ["TOMACORRIENTE CON DOBLE PUERTO USB KOBATEX","00125346000016","KB06"],
            ["TOMACORRIENTE CON INTERRUPTOR INGCO","6925582144642","HESST181111"],
            ["TOMACORRIENTE DE EMPOTRAR GRIS CON PUERTO USB","7453038487917","A136-ETP-6C2U"],
            ["TOMACORRIENTE DE SOBREPONER 125V TROEN","7453038412858","U05-W"],
            ["TOMACORRIENTE DOBLE 16A INGCO","6925582143553","HESST183201"],
            ["TOMACORRIENTE DOBLE AMERICANO ACRILICO NEGRO 10-15A 250V VERT","9780201374889","TJV-N01"],
            ["TOMACORRIENTE DOBLE AMERICANO BALNCO 2P+T 15A 125V VERT","9184751910137",""],
            ["TOMACORRIENTE DOBLE AMERICANO BLANCO MATE VERT","6900050116659","KTP-002"],
            ["TOMACORRIENTE DOBLE AMERICANO ELEGANTE BORDE CURVO GRIS VERT","6234566690403","T80-G06"],
            ["TOMACORRIENTE DOBLE AMERICANO ELEGANTE DORADO VERT","6234566690014","T31-D05"],
            ["TOMACORRIENTE DOBLE AMERICANO ELEGANTE GRIS PLATA 16A 250V VERT","6234566690595","T16-A06"],
            ["TOMACORRIENTE DOBLE AMERICANO ELEGANTE GRIS PLOMO VERT","6234566690205","T32-P06"],
            ["TOMACORRIENTE DOBLE AMERICANO ELEGANTE PLANO BLANCO BORDE BLANCO VERT","6234566690786","TZC-B07"],
            ["TOMACORRIENTE DOBLE AMERICANO ELEGANTE PLANO DORADO BORDE DORADO VERT","6234566690793","TZC-D07"],
            ["TOMACORRIENTE DOBLE AMERICANO MODERNO VERT","6112210111279","JX1-AT1"],
            ["TOMACORRIENTE DOBLE AMERICANO PLATEADO VERT","6900050116567","KTA-003"],
            ["TOMACORRIENTE DOBLE BLANCO 15A - 125V HUMMER","7453100257752","HUM-1050"],
            ["TOMACORRIENTE DOBLE BLANCO CON USB TROEN","7453038425223","A136-ETW-6C2U"],
            ["TOMACORRIENTE DOBLE BLANCO ORIX ELECTRONIC","6950077011321","PH-TG069"],
            ["TOMACORRIENTE DOBLE BLANCO TROEN","7453078513676","A136-ET118Z-44"],
            ["TOMACORRIENTE DOBLE BLANCO VERT","9780201374667","TJV-B01"],
            ["TOMACORRIENTE DOBLE C/TIERRA DE ACERO INOXIDABLE TROEN","7453038454544","TR-ETSS-6C-20A"],
            ["TOMACORRIENTE DOBLE CON CUBIERTA IMPERMEABLE 127/250 15A TROEN","7453038450966","TR-WP-6C"],
            ["TOMACORRIENTE DOBLE CON USB NEGRO TROEN","7453038494366","A136-ETB-6C2U"],
            ["TOMACORRIENTE DOBLE DE ACERO 15A UNA VIA 127/250V BLANCO HUMMER","7453100283461","HUM-SM8061-WH"],
            ["TOMACORRIENTE DOBLE DE ACERO 15A UNA VIA 127/250V GRIS HUMMER","7453100283607","HUM-SW806-GR"],
            ["TOMACORRIENTE DOBLE DE ACERO GRIS PLOMO VERT ","9780201376906","TJP-P01"],
            ["TOMACORRIENTE DOBLE GRIS BTICINO","7702089464898","6876330B"],
            ["TOMACORRIENTE DOBLE MODERNO BLANCO CROMO 10-15A VERT","1440201300445","S2N-TDW05"],
            ["TOMACORRIENTE DOBLE MODERNO BLANCO CROMO VERT","1660201300669","S3-TDS05"],
            ["TOMACORRIENTE DOBLE MODERNO BLANCO CROMO VERT ","9780201376746","TJP-B01"],
            ["TOMACORRIENTE DOBLE MODERNO DORADO VERT","1710201300715","S1-TDG05"],
            ["TOMACORRIENTE DOBLE MODERNO GRIS Y NEGRO","9780201374896","TJV-N02"],
            ["TOMACORRIENTE DOBLE MODERNO MARRON CROMO 10-15A 110-250V VERT","1100201300103","SS1-TEN06"],
            ["TOMACORRIENTE DOBLE MODERNO MARRON CROMO VERT","1110201300119","SS1-TDN05"],
            ["TOMACORRIENTE DOBLE MODERNO NEGRO VERT","9780201374780","TJP-N01"],
            ["TOMACORRIENTE DOBLE MODERNO PLATA 10-15A 110-250V VER","1330201300333","SS1-TD05"],
            ["TOMACORRIENTE DOBLE MODERNO PLATA 10-15A 110-250V VERT","1320201300327","SS1-TE06"],
            ["TOMACORRIENTE DOBLE MODERNO PLATA CROMO 110-250W VERT","1550201300557","S3-TDW05"],
            ["TOMACORRIENTE DOBLE NEGRO TROEN ","7453010079383","A136-ETB-6C"],
            ["TOMACORRIENTE DOBLE PLATEADO TROEN ","7453038453530","A136-ETP-6C"],
            ["TOMACORRIENTE DOBLE POLARIZADO 125V HUMMER","7453100257745","HUM-1049"],
            ["TOMACORRIENTE DOBLE SOBREP/BLANCO 15A VITRON","2050000009848","03-014-177"],
            ["TOMACORRIENTE DOBLE SUPERFICIAL BLANCO SENCILLO-PANELITA","6049461894968","Y1147M"],
            ["TOMACORRIENTE DOBLE SUPERFICIAL BLANCO TIPO EUROPEO","6049461894913","Y1147"],
            ["TOMACORRIENTE DOBLE SUPERFICIAL CLASSIC LUX","6101250021006","YL-40"],
            ["TOMACORRIENTE DOBLE SUPERFICIAL VITRON","2050000009871","03-014-179"],
            ["TOMACORRIENTE ELEGANTE DORADO VERT","2180201381813","T31-D10"],
            ["TOMACORRIENTE ELEGANTE PLANO BLANCO VERT","2480201384846","TZC-B11"],
            ["TOMACORRIENTE HEMBRA METALIZADO 110V VERT","9184751994984","JEH-011"],
            ["TOMACORRIENTE HEMBRA P/EXTENSION HUMMER","7453100257578","HUM-1032"],
            ["TOMACORRIENTE HEMBRA TIPO CHINO","9780336987626","JEH-014"],
            ["TOMACORRIENTE LITE DOBLE VITRON","03014161",""],
            ["TOMACORRIENTE MODERNO DORADO REDONDO VERT","1720201300721","S1-TEG06"],
            ["TOMACORRIENTE NEGRO VERT","6234566690038","T31-N05"],
            ["TOMACORRIENTE PATA DE GALLINA BLANCO VERT","9184751910120","JEH-028"],
            ["TOMACORRIENTE SENCILLO BLANCO FERMETAL","7592032083784","TOM-67"],
            ["TOMACORRIENTE SENCILLO SUPERFICIAL CLASSIC LUX","INT-22","INT-22"],
            ["TOMACORRIENTE SOBREPO MARFIL 15A VITRON","2050000009854","03-014-178"],
            ["TOMACORRIENTE SUPERFICIAL BLANCO TROEN","7453038489997","A136-P78"],
            ["TOMACORRIENTE SUPERFICIAL POLARIZADA 15AMP. 110-130V","TOMACORRIENTE-SUPERFICIAL",""],
            ["TOMACORRIENTE SUPERFICIAL SIN TIERRA 3 VIAS TROEN","7453038490719","A136-P79"],
            ["TOMACORRIENTE TIPO CHINO MODERNO PLETEADO CROMO VERT ","9780316997713","JEH-059"],
            ["TUBO DE HIERRO 2X1 6M","THP-2X1",""],
            ["TUBO DE HIERRO 2X2 6M","THP-2X2",""],
            ["TUBO DE HIERRO PULIDO 1/2X1/2 6M","THP1/2X1/2",""],
            ["TUBO DE HIERRO PULIDO 1X1 6M","THP-1X1",""],
            ["TUBO DE HIERRO PULIDO 1X1/2 6M","THP-1x1/2",""],
            ["TUBO DE HIERRO PULIDO 3/4X3/4 6M","THP-3/4X3/4",""],
            ["TUBO DE HIERRO PULIDO 3X1 6M","THP-3X1",""],
            ["TUBO DE HIERRO PULIDO 3X11/2 1.1MM 6M SEMI-ESTRUCTURAL","THP-3X11/2SE",""],
            ["TUBO DE HIERRO PULIDO 3X11/2 6M","THP-3X11/2",""],
            ["TUBO ELECTRICIDAD BLANCO 1","TUBO-ELE-1BLANCO",""],
            ["TUBO ELECTRICIDAD BLANCO 1/2 ","TUBO-ELE-1/2BLANCO",""],
            ["TUBO ELECTRICIDAD BLANCO 3/4 ","TUBO-ELE-3/4BLANCO",""],
            ["TUBO ELECTRICIDAD 1 1/2 ","TUBO-ELE-1 1/2",""],
            ["TUBO ELECTRICIDAD NEGRO 1/2","TUBO-ELE-1/2 NEGRO",""],
            ["TUBO ELECTRICIDAD NEGRO 3/4","TUBO-ELE-3/4 NEGRO",""],
            ["TUBO PAVCO 1 6MTS","TUBO-PAVCO-1","TUBO-PAVCO-1"],
            ["TUBO PAVCO 1/2 6MTS","TUBO-PAVCO-1/2","TUBO-PAVCO-1/2"],
            ["TUBO PAVCO 3/4 6MTS","TUBO-PAVCO-3/4","TUBO-PAVCO-3/4"],
            ["TUBO PLASTICO CON ROSCA AZUL 1","TUBO-AZUL-1","TUBO-AZUL-1"],
            ["TUBO PLASTICO CON ROSCA AZUL 1 1/2","TUBO-AZUL-11/2","TUBO-AZUL-11/2"],
            ["TUBO PLASTICO CON ROSCA AZUL 1/2","TUBO-AZUL-1/2","TUBO-AZUL-1/2"],
            ["TUBO PLASTICO CON ROSCA AZUL 2","TUBO-AZUL-2","TUBO-AZUL-2"],
            ["TUBO PLASTICO CON ROSCA AZUL 3/4","TUBO-AZUL-3/4","TUBO-AZUL-3/4"],
            ["TUBO PVC BLANCO 2","TUBO-PVC-2-B",""],
            ["TUBO PVC AMARILLO 2","TUBO-PVC-2-A",""],
            ["TUBO PVC AMARILLO 3","TUBO-PVC-3-A",""],
            ["TUBO PVC AMARILLO 4","TUBO-PVC-4-A",""],
            ["TUBO PVC NARANJA 3","TUBO-PVC-3-N",""],
            ["TUBO PVC NARANJA 4","TUBO-PVC-6-A",""],
            ["TUBO PVC NARANJA 6","TUBO-PVC-6-N",""],
            ["TUBO REDONDO 1","TUBO-REDONDO-1",""],
            ["TUBO REDONDO 1 1/2","TUBO-REDONDO-112",""],
            ["VALVULA CHEQUER 1 1/2 VERTICAL METALES ALEADOS","7592978369065","WL-480-1-1-2-CP"],
            ["VALVULA CHEQUER 1 BRONCE RUN","734896112379","VC03"],
            ["VALVULA CHEQUER 1-1/2 CON RESORTE GRIVEN","7453038433495",""],
            ["VALVULA CHEQUER 1/2 BRONCE CON FILTRO GRIVEN","7453038487467","WL-120-1-2-BR"],
            ["VALVULA CHEQUER 1/2 DE BRONCE RUN","734896112355","VC01"],
            ["VALVULA CHEQUER 1/2 HUMMER","7453100259145","HUM-1189"],
            ["VALVULA CHEQUER 1/2 METALES ALEADOS DE BRONCE","CHEQUER-1/2-MA",""],
            ["VALVULA CHEQUER 1/2 VERTICAL METALES ALEADOS","7592978369027","VALVULA"],
            ["VALVULA CHEQUER 3/4 BRONCE GRIVEN","7453038480468","WL-180-3-4-CP"],
            ["VALVULA CHEQUER 3/4 DE BRONCE RUN","734896112362","VC02"],
            ["VALVULA CHEQUER 3/4 DE ROSCA CON FILTRO PVC","PH-RTY34","PH-RTY34"],
            ["VALVULA CHEQUER BRONCE 1/2 GRIVEN","7453010032883","WL-120-1-2-CP"],
            ["VALVULA CHEQUER BRONCE 1/2 KOBATEX","VALVULA-CHECK1/2",""],
            ["VALVULA CHEQUER BRONCE 2 GRIVEN","7453038428323","WL-700-2-CP"],
            ["VALVULA CHEQUER BRONCE 3/4 ASIENTO GRIVEN","7453038472890","WL-180-3-4-BR1"],
            ["VALVULA CHEQUER BRONCE 3/4 KOBATEX","VALVULA-CHECK3/4",""],
            ["VALVULA CHEQUER VERTICAL 1 METALES ALEADOS","VALVULA 1","VALVULA-1"],
            ["VALVULA CHEQUER VERTICAL 1-1/4","VALVULA-1-1/4","MET-042"],
            ["VALVULA CHEQUER VERTICAL 3/4 METALES ALEADOS","VALVULA-3/4","VALVULA-3/4"],
            ["VENTILADOR 10 D-ROMAX","VENTILADOR-10-D-ROMAX","D-RIO-10"],
            ["VENTILADOR AK DE MESA 16 ","VENTILADOR-AK-M-16","VENTILADOR-AK-M-16"],
            ["VENTILADOR AK DE PEDESTAL 18 ","VENTILADOR-AK-P-18","VENTILADOR-AK-P-18"],
            ["VENTILADOR ARTIC CON PEDESTAL 10 ASPA ALUMINIO","VENTILADOR-ARTIC-10","VENTILADOR-ARTIC-10"],
            ["VENTILADOR ASPA DE ALUMINIO 18 OMEGA","OV3-18B","OV3-18B"],
            ["VENTILADOR AUTOCEBANTE 1.5HP MAGPOWER","VENTILADOR-AUTOCEBANTE-1.5HP","VENTILADOR-AUTOCEBANTE-1.5HP"],
            ["VENTILADOR BAKUS PLASTICO 18","FS-45A","FS-45A"],
            ["VENTILADOR DE COLORES","EDMIRA-COLORES","EDMIRA-COLORES"],
            ["VENTILADOR DECAKILA DE TORRE 32 ","6941556288945","KUFC033W"],
            ["VENTILADOR DECAKILA VERTICAL DE OFICINA ","6941556298715","KUFC002B"],
            ["VENTILADOR EDMIRA 18 DE PARED","VENTILADOR-18","VEN-13"],
            ["VENTILADOR EDMIRA DE 20 PISO CUADRADO","873478347834","VEN-11"],
            ["VENTILADOR EMTOP INALAMBRICO ","6941556219208","ELFN2001"],
            ["VENTILADOR FM 12 ","VENTILADOR-FM-12","VENTILADOR-FM-12"],
            ["VENTILADOR FM DE MESA 16","VENTILADOR-FM-P","VENTILADOR-FM-P"],
            ["VENTILADOR FM DE PEDESTAL 18 ","VENTILADOR-FM-P-18","VENTILADOR-FM-P-18"],
            ["VENTILADOR FOR PLAST 18 125W","VENTFORPLAST125W",""],
            ["VENTILADOR JAGUAR ELECTRISC 18 ","VENTILADOR-JAGUAR",""],
            ["VENTILADOR METAL 18* VENE HOGAR VH","VH18-3HE","VH18-3HE"],
            ["VENTILADOR MYSTIC PLASTICO 10","MY-1009B","MY-1009B"],
            ["VENTILADOR OMEGA 10 PEDESTAL 45W ","OVP-10S",""],
            ["VENTILADOR OMEGA DE PEDESTAL 18 5 ASPA","OVP-18",""],
            ["VENTILADOR PARA BOMBA DE 1/2HP MAGPOWER","VENTILADOR-BOMBA-1/2HP","VENTILADOR-BOMBA-1/2HP"],
            ["VENTILADOR PARA M/EC-150 1.5HP CENTRIFUGA MAGPOWER","EC-150-VENTILADOR","EC-150-VETILADOR"],
            ["VENTILADOR PLASTICO 18* VENE HOGAR VH","VH18-5HE","VH18-5HE"],
            ["VENTILADOR RECARGABLE 6 CON LAMPARA LED RUN","734896114397","VENTEL01"],
            ["VENTILADOR RIOMAX 3 ASPAS","VENTILADOR-RIOMAX-3",""],
            ["VENTILADOR RIOMAX 6 ASPAS","VEN-RIO-18","VEN-RIO-18"],
            ["VENTILADOR ROYAL DE METAL 18","VENTILADOR-ROYAL-18","VENTILADOR-ROYAL-18"],
            ["VENTILADOR SJ ELECTRONIC CON PEDESTAL 18","7177768947625","SJ-1801P"],
            ["VENTILADOR SJ ELECTRONIC DE PARED 18 ","VENTILADOR-SJ-18","VENTILADOR-SJ-18"],
            ["VENTILADOR SJ ELECTRONIC DE PARED 18 1801W ","VENTILADOR--SJ-PARED-18","VENTILADOR--SJ-P-18"],
            ["VENTILADOR SJ ELECTRONIC DE PEDESTAL 18","VENTILADOR-SJ-P-18","VENTILADOR-SJ-P-18"],
            ["VENTILADOR SUPER MEGATURBO 18","MEGATURBO-18","MEGATURBO-18"],
            ["VENTILADOR TECZON 10","VEN-TECZON-18",""],
        ];
 
        /*$arr = [
            ["PINTURA HAKUNA BLANCO 1G","PIN-H-BLANCO-1G","PIN-H-BLANCO-1G"],
            ["PINTURA HAKUNA MARFIL 1G","PIN-H-MARFIL-1G","PIN-H-MARFIL-1G"],
            ["PINTURA HAKUNA AMARILLO 1G","PIN-H-AMARILLO-1G","PIN-H-AMARILLO-1G"],
            ["PINTURA HAKUNA VERDE 1G","PIN-H-VERDE-1G","PIN-H-VERDE-1G"],
            ["PINTURA HAKUNA AZUL 1G","PIN-H-AZUL-1G","PIN-H-AZUL-1G"],
            ["PINTURA HAKUNA NARANJA 1G","PIN-H-NARANJA-1G","PIN-H-NARANJA-1G"],
            ["PINTURA HAKUNA BLANCO 4G","PIN-H-BLANCO-4G","PIN-H-BLANCO-4G"],
            ["PINTURA HAKUNA FUCSIA 4G","PIN-H-FUCSIA-4G","PIN-H-FUCSIA-4G"],
            ["PINTURA HAKUNA AZUL 4G","PIN-H-AZUL-4G","PIN-H-AZUL-4G"],
            ["PINTURA HAKUNA OCRE 1G","PIN-H-OCRE-1G","PIN-H-OCRE-1G"],
            ["PINTURA HAKUNA OCRE 4G","PIN-H-OCRE-4G","PIN-H-OCRE-4G"],
            ["PINTURA HAKUNA GRIS 1G","PIN-H-GRIS-1G","PIN-H-GRIS-1G"],
            ["PINTURA HAKUNA FUCSIA 1G","PIN-H-FUCSIA-1G","PIN-H-FUCSIA-1G"],
            ["PINTURA HAKUNA TURQUESA 1G","PIN-H-TURQUESA-1G","PIN-H-TURQUESA-1G"],
        ];*/
        $sucursales = sucursal::where("id","<>",13)->get();

        foreach ($sucursales as $isuc => $suc) {
            $splitItems = array_chunk($arr,50);
            foreach ($splitItems as $i => $split50) {
                $lastid = pedidos::orderBy("id","desc")->first("id");
                if (!$lastid) {$lastid = 0;}else{$lastid = $lastid->id;}
                $newped = new pedidos;
                $newped->idinsucursal = ($lastid)+1;
                $newped->estado = 1;
                $newped->id_origen = 13;
                $newped->updated_at = "2024-12-24 12:59:59";
                $newped->id_destino = $suc->id;//id Destino
                if ($newped->save()) {
                    
                    $id_ped = $newped->id;
                    foreach ($split50 as $key => $e) {
                        $descripcion = $e[0];
                        $barras = $e[1];
                        $alterno = $e[2];
                        $id_producto_local = null;
            
                        $check_barras = inventario_sucursal::where("codigo_barras",$barras)->where("id_sucursal",13)->first();
                        if ($check_barras) {
                            $id_producto_local = $check_barras->id;
                        }else{
                            $crearProducto = inventario_sucursal::updateOrCreate([
                                "id" => null
                            ],[
                                "id_sucursal" => 13,
                                "codigo_barras" => $barras,
                                "codigo_proveedor" => $alterno,
                                "descripcion" => $descripcion,
                                "codigo_proveedor2" => null,
                                "id_deposito" => null,
                                "unidad" => null,
                                "iva" => null,
                                "porcentaje_ganancia" => null,
                                "precio_base" => 0,
                                "precio" => 0,
                                "precio1" => 0,
                                "precio2" => 0,
                                "precio3" => 0,
                                "bulto" => 0,
                                "cantidad" => 0,
                                "push" => null,
                                "id_vinculacion" => null,
                                "n1" => null,
                                "n2" => null,
                                "n3" => null,
                                "n4" => null,
                                "n5" => null,
                                "id_proveedor" => null,
                                "id_categoria" => null,
                                "id_catgeneral" => null,
                                "id_marca" => null,
                                "id_marca" => null,
                                "stockmin" => null,
                                "stockmax" => null,
                            ]); 
                            $id_producto_local = $crearProducto->id;
                        }
    
                        $vinccodigo_barras = $check_barras?$check_barras->codigo_barras:$barras;
                        $match_vinc = inventario_sucursal::where("codigo_barras",$vinccodigo_barras)->where("id_sucursal",$suc->id)->first();
                        if ($match_vinc) {
                            vinculossucursales::updateOrCreate([
                                "id_sucursal" => 13, //CENTRAL
                                "id_sucursal_fore" => $suc->id, //SUC
                                "id_producto_local" => $id_producto_local, //PROD CENTRAL
                            ],[
                                "idinsucursal_fore" => $match_vinc->idinsucursal, //PROD SUC
                                "idinsucursal" => null, // INSUCURSAl, SOLO REF
                            ]);
                        }
    
    
                        $newitem = new items_pedidos;
                        $newitem->id_producto = $id_producto_local;
                        $newitem->id_pedido = $id_ped;
                        $newitem->cantidad = 0;
                        $newitem->descuento = 0;
                        $newitem->monto = 0;
                        $newitem->ct_real = null;
                        $newitem->barras_real = null;
                        $newitem->alterno_real = null;
                        $newitem->descripcion_real = null;
                        $newitem->vinculo_real = null;
                        $newitem->save();
                    }
                }

                
            }


           



        }   
    }
}
