<?php

namespace App\Http\Controllers;
use App\Models\cuentasporpagar;
use App\Models\novedad_inventario_aprobacion;
set_time_limit(300000);
use App\Models\marcas;
use App\Models\productonombre1;
use App\Models\productonombre2;
use App\Models\productonombre3;
use App\Models\productonombre4;

use App\Models\cuentasporpagar_items;
use App\Models\inventario;
use App\Models\sucursal;
use App\Models\inventario_sucursal;
use App\Models\moneda;
use App\Models\fallas;


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
    $producto = $req->producto;
    $codigo_origen = $req->codigo_origen;
    $idinsucursal = $producto["id"];
    $estado = 0;
    
    $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
    $id_sucursal = $id_ruta["id_origen"];

    novedad_inventario_aprobacion::updateOrCreate([
        "id_sucursal" => $id_sucursal,
        "idinsucursal" => $idinsucursal,
    ],[
        "id_sucursal" => $id_sucursal,
        "idinsucursal" => $idinsucursal,
        "estado" => $estado,
    ]);
    
    
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
    $inputselectvinculacionmarca = $req->inputselectvinculacionmarca;

    foreach ($selectIdVinculacion as $key => $e) {
        $isPermiso = true;
        /* if ($inputselectvinculacion1 && $inputselectvinculacion2 && $inputselectvinculacion3 && $inputselectvinculacion3) {
            $check = inventario::where("n1",$inputselectvinculacion1)->where("n2",$inputselectvinculacion2)->where("n3",$inputselectvinculacion3)
            # code...
        } */
        if ($isPermiso) {
            $query = inventario::find($e);
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
            if ($inputselectvinculacionmarca) {
                $query->marca = $inputselectvinculacionmarca;
            } 
            $query->save();
        }
    }

}
function getDatinputSelectVinculacion() {
    $datavinculacion1 = productonombre1::all();
    $datavinculacion2 = productonombre2::all();
    $datavinculacion3 = productonombre3::all();
    $datavinculacion4 = productonombre4::all();
    $datavinculacionmarca = marcas::all();
    return [
        "datavinculacion1" => $datavinculacion1,
        "datavinculacion2" => $datavinculacion2,
        "datavinculacion3" => $datavinculacion3,
        "datavinculacion4" => $datavinculacion4,
        "datavinculacionmarca" => $datavinculacionmarca,
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
}
