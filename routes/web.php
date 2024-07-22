<?php

use App\Http\Controllers\AlquileresController;
use App\Http\Controllers\BancosController;
use App\Http\Controllers\BancosListController;
use App\Http\Controllers\CajasAprobacionController;
use App\Http\Controllers\CajasController;
use App\Http\Controllers\CatcajasController;
use App\Http\Controllers\CreditoAprobacionController;
use App\Http\Controllers\CuentasporpagarController;
use App\Http\Controllers\CuentasporpagarFisicasController;
use App\Http\Controllers\CuentasporpagarItemsController;
use App\Http\Controllers\InventarioSucursalEstadisticasController;
use App\Http\Controllers\NovedadInventarioAprobacionController;
use App\Http\Controllers\PuntosybiopagosController;
use App\Http\Controllers\TransferenciaAprobacionController;
use App\Http\Controllers\UltimainformacioncargadaController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProductoxproveedorController;

use App\Http\Controllers\SucursalController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\DepositoController;
use App\Http\Controllers\CtSucursalController;

use App\Http\Controllers\VentasController;
use App\Http\Controllers\home;

use App\Http\Controllers\FacturasController;
use App\Http\Controllers\ItemsFacturasController;
use App\Http\Controllers\ItemsPedidosController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\MonedaController;
use App\Http\Controllers\LocalsVersionController;
use App\Http\Controllers\MarcasController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\CatGeneralsController;
use App\Http\Controllers\sockets;
use App\Http\Controllers\TareasController;

use App\Http\Controllers\GastosController;
use App\Http\Controllers\GarantiasController;
use App\Http\Controllers\FallasController;

use App\Http\Controllers\InventarioSucursalController;
use App\Http\Controllers\CierresController;

use App\Http\Controllers\NominacargosController;
use App\Http\Controllers\NominaController;
use App\Http\Controllers\NominapagosController;
use App\Http\Controllers\ComovamosController;

use App\Models\puntosybiopagos;
use App\Models\catcajas;














/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('login', [home::class,"login"]);

Route::get('logout', [home::class,"logout"]);

Route::post('verificarLogin', [home::class,"verificarLogin"]);
Route::get('/hora', function () {
    return date("Y-m-d H:i:s");
});


Route::get('/usuarioconsulta', function () {
    DB::table("usuarios")->insert([
        [
        "nombre" => "CONSULTA",
        "usuario" => "consulta",
        "clave" => Hash::make("2685AAZZ$$%%"),
        "tipo_usuario" => "2",
        "area" => "TI",
        ]
    ]);
});



Route::get('getMoneda', [MonedaController::class,"getMoneda"]);
Route::get('getVersionRemote', [LocalsVersionController::class,"getVersion"]);

Route::get('', [home::class,"index"]);
Route::get('today', [home::class,"today"]);
Route::get('getSucursales', [SucursalController::class,"getSucursales"]);
Route::post('setEstadisticas', [InventarioSucursalController::class,"setEstadisticas"]);


Route::post('getNomina', [NominaController::class,"getNomina"]);
Route::post('getAlquileresSucursal', [AlquileresController::class,"getAlquileresSucursal"]);

Route::get('getCatCajas', [CatcajasController::class,"getCatCajas"]);
Route::post('getAuditoriaEfec', [CajasController::class,"getAuditoriaEfec"]);


Route::get('getFallas', [FallasController::class,"getFallas"]);

Route::post('getGastos', [PuntosybiopagosController::class,"getGastos"]);
Route::post('getGastosDistribucion', [PuntosybiopagosController::class,"getGastosDistribucion"]);

Route::post('delGasto', [PuntosybiopagosController::class,"delGasto"]);
Route::post('saveNewGasto', [PuntosybiopagosController::class,"saveNewGasto"]);


Route::post('setVentas', [VentasController::class,"setVentas"]);
Route::get('getVentas', [VentasController::class,"getVentas"]);


Route::post('sendGastos', [GastosController::class,"sendGastos"]);

Route::get('getLast', [UltimainformacioncargadaController::class,"getLast"]);

/* Route::post('sendInventarioCt', [InventarioSucursalController::class,"sendInventarioCt"]);
Route::post('sendGarantias', [GarantiasController::class,"sendGarantias"]);
Route::post('sendFallas', [FallasController::class,"sendFallas"]);
Route::post('setCierreFromSucursalToCentral', [CierresController::class,"setCierreFromSucursalToCentral"]);
Route::post('setEfecFromSucursalToCentral', [CajasController::class,"setEfecFromSucursalToCentral"]); */


 Route::get('deuda',  function() {
    $deuda = [
        ["42","9","2024-01-30","2024-02-29","21213","1182.59"],
        ["48","12","2024-01-30","2024-02-29","08342","2500.00"],
        ["48","11","2024-01-30","2024-02-29","08263","1632.00"],
        ["12","6","2024-01-26","2024-02-25","90169938","984.09"],
        ["12","6","2024-01-26","2024-02-25","90170025","984.09"],
        ["48","11","2024-01-30","2024-02-29","08341","5000.00"],
        ["22","12","2024-01-31","2024-03-16","5312","1204.00"],
        ["12","2","2024-01-26","2024-02-25","90170026","590.45"],
        ["48","8","2024-01-30","2024-02-29","08343","3750.00"],
        ["39","12","2024-01-31","2024-01-31","6647219","808.79"],
        ["76","9","2024-01-22","2024-01-22","8529","153.97"],
        ["76","9","2024-01-22","2024-01-22","8525","1310.77"],
        ["76","9","2024-01-22","2024-01-22","8527","614.14"],
        ["76","9","2024-01-22","2024-01-22","8518","1210.07"],
        ["76","9","2024-01-22","2024-01-22","8054","181.56"],
        ["76","9","2024-01-22","2024-01-22","8517","2010.93"],
        ["76","3","2024-01-22","2024-01-22","8513","1826.87"],
        ["76","3","2024-01-22","2024-01-22","8051","1006.57"],
        ["76","3","2024-01-22","2024-01-22","8514","754.57"],
        ["76","3","2024-01-22","2024-01-22","8512","2744.50"],
        ["39","5","2024-01-19","2024-01-19","NOSEVEE","115.75"],
        ["39","5","2024-01-19","2024-01-19","6645134","323.51"],
        ["76","5","2024-01-22","2024-01-22","8044","274.82"],
    ];

    foreach ($deuda as $i => $e) {
        $arrinsert = [
            "id_proveedor" => $e[0],
            "tipo" => 1, //COMPRAS
            "frecuencia" => 0,
            "id_sucursal" => $e[1],

            "idinsucursal" => time().$i,
            "numfact" => $e[4],
            "numnota" => "",
            "descripcion" => $e[4],

            "subtotal" => 0,
            "monto" => $e[5]*-1,
            "fechaemision" => $e[2],
            "fecharecepcion" => $e[2],
            "fechavencimiento" => $e[3],
            "nota" => "",
            "metodo" => null,
            "aprobado" => 1,
        ];
        $search = [
            "id" => 0
        ];
        App\Models\cuentasporpagar::updateOrCreate($search,$arrinsert);
    }
});


Route::get('metodos',  function() {
    $metodos = [
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
    }
});



Route::get('importnagazaki',  [InventarioSucursalController::class,"importnagazaki"]);

Route::get('categoriasgastos',  function() {
    
    $data = [["1","CAJA CHICA: EFECTIVO ADICIONAL","0","9","4"],
    ["2","CAJA CHICA: CENA NOCTURNA","0","2","0"],
    ["3","CAJA CHICA: TORTA DE CUMPLEAÑOS","0","2","0"],
    ["4","CAJA CHICA: ALMUERZO DE TRABAJADOR","0","2","0"],
    ["5","CAJA CHICA: EXAMENES MEDICOS","0","2","0"],
    ["6","CAJA CHICA: LIMPIEZA: CLORO, JABON, AROMATIZANTE, CERA","0","2","0"],
    ["7","CAJA CHICA: LIMPIEZA: COLETO, CEPILLOS, PALAS, TRAPOS, PAPEL HIGIENICO","0","2","0"],
    ["8","CAJA CHICA: VASOS PARA CAFE","0","2","0"],
    ["9","CAJA CHICA: VASOS PARA PANELADA","0","2","0"],
    ["10","CAJA CHICA: BOLSAS","0","2","0"],
    ["11","CAJA CHICA: AZUCAR","0","2","0"],
    ["12","CAJA CHICA: CAFE","0","2","0"],
    ["13","CAJA CHICA: PANELADA O JUGO","0","2","0"],
    ["14","CAJA CHICA: PAPELERIA: HOJAS, CARTULINA, FOTOCOPIAS, MARCADORES","0","2","0"],
    ["15","CAJA CHICA: AGUA: BOTELLON","0","2","0"],
    ["16","CAJA CHICA: AGUA: HIELO","0","2","0"],
    ["17","CAJA CHICA: AGUA: CISTERNA","0","2","0"],
    ["18","CAJA CHICA: SUMINISTROS CASA IMPORTADOS","0","2","0"],
    ["19","CAJA CHICA: CALETEROS","0","2","0"],
    ["20","CAJA CHICA: COLABORACION SUCURSAL","0","2","0"],
    ["21","CAJA CHICA: REPARACIONES Y MANTENIMIENTO","0","2","0"],
    ["22","CAJA CHICA: TRANSPORTE: TAXI Y MOTOTAXI","0","2","0"],
    ["23","CAJA CHICA: TRANSPORTE: COMBUSTIBLE","0","2","0"],
    ["24","CAJA CHICA: TRANSPORTE: REPARACION DE VEHICULOS","0","2","0"],
    ["25","CAJA CHICA: TRASPASO A CAJA FUERTE","0","5","2"],
    ["26","INGRESO DESDE CIERRE","1","1","1"],
    ["27","CAJA FUERTE: EFECTIVO ADICIONAL","1","8","4"],
    ["28","CAJA FUERTE: NOMINA ABONO","1","8","4"],
    ["29","CAJA FUERTE: NOMINA QUINCENA","1","2","0"],
    ["30","CAJA FUERTE: NOMINA PRESTAMO","1","2","0"],
    ["31","CAJA FUERTE: SERVICIOS: ELECTRICIDAD","1","2","0"],
    ["32","CAJA FUERTE: SERVICIOS: AGUA","1","2","0"],
    ["33","CAJA FUERTE: SERVICIOS: INTERNET","1","2","0"],
    ["34","CAJA FUERTE: ALQUILER","1","2","0"],
    ["35","CAJA FUERTE: TALONARIOS, SELLOS, ETC","1","2","0"],
    ["36","CAJA FUERTE: COLABORACIONES GENERAL (TODAS SUCURSALES)","1","3","0"],
    ["37","CAJA FUERTE: TRANSPORTE: COMBUSTIBLE (TODAS SUCURSALES)","1","3","0"],
    ["38","CAJA FUERTE: TRANSPORTE: REPARACION DE VEHICULOS (TODAS SUCURSALES)","1","3","0"],
    ["39","CAJA FUERTE: TRANSPORTE: VIATICOS Y PEAJES (TODAS SUCURSALES)","1","3","0"],
    ["40","CAJA FUERTE: PAGO PROVEEDOR","1","0","0"],
    ["41","CAJA FUERTE: FDI","1","7","3"],
    ["42","CAJA FUERTE: TRASPASO A CAJA MATRIZ (RAID RETIRA)","1","6","3"],
    ["43","CAJA FUERTE: TRANSFERENCIA TRABAJADOR","1","4","2"],
    ["44","CAJA FUERTE: TRASPASO A CAJA CHICA","1","5","2"],
    ["45","CAJA FUERTE: EGRESO TRANSFERENCIA SUCURSAL","1","10","2"],
    ["46","CAJA FUERTE: INGRESO TRANSFERENCIA SUCURSAL","1","10","2"],

    ["47","CAJA MATRIZ: ARANCELES MUNICIPALES","1","2","0"],
    ["48","CAJA MATRIZ: SENIAT","1","2","0"],
    ["49","CAJA MATRIZ: CREDITO BANCARIO","1","2","0"],
    ["50","CAJA MATRIZ: COMISION PUNTO DE VENTA","1","2","0"],
    ["51","CAJA MATRIZ: COMISION TRANSFERENCIA INTERBANCARIA O PAGO MOVIL","1","2","0"],
];
    DB::table("catcajas")->truncate();

    foreach ($data as $i => $e) {
        DB::table("catcajas")->insert([
            [
                "id" => $e[0],
                "nombre" => $e[1],
                "tipo" => $e[2],
                "catgeneral" => $e[3],
                "ingreso_egreso" => $e[4],
            ],
        ]); 
    }
});





Route::post('setAll', [CierresController::class,"setAll"]);
Route::post('setPermisoCajas', [CajasAprobacionController::class,"setPermisoCajas"]);
Route::post('checkDelMovCajaCentral', [CajasAprobacionController::class,"checkDelMovCajaCentral"]);

Route::post('aprobarMovCajaFuerte', [CajasAprobacionController::class,"aprobarMovCajaFuerte"]);
Route::post('verificarMovPenControlEfec', [CajasAprobacionController::class,"verificarMovPenControlEfec"]);
Route::post('verificarMovPenControlEfecTRANFTRABAJADOR', [CajasAprobacionController::class,"verificarMovPenControlEfecTRANFTRABAJADOR"]);

Route::post('aprobarRecepcionCaja', [CajasAprobacionController::class,"aprobarRecepcionCaja"]);

Route::post('delCuentaPorPagar', [CuentasporpagarController::class,"delCuentaPorPagar"]);


Route::post('conciliarCuenta', [CuentasporpagarController::class,"conciliarCuenta"]);
Route::post('delFilescxp', [CuentasporpagarFisicasController::class,"delFilescxp"]);
Route::post('getFilescxp', [CuentasporpagarFisicasController::class,"getFilescxp"]);
Route::post('showFilescxp', [CuentasporpagarFisicasController::class,"showFilescxp"]);
Route::post('sendComprasFats', [CuentasporpagarFisicasController::class,"sendComprasFats"]);


Route::post('sendMovimientoBanco', [PuntosybiopagosController::class,"sendMovimientoBanco"]);
Route::post('sendDescuentoGeneralFats', [CuentasporpagarController::class,"sendDescuentoGeneralFats"]);
Route::post('liquidarMov', [PuntosybiopagosController::class,"liquidarMov"]);
Route::post('reportarMov', [PuntosybiopagosController::class,"reportarMov"]);

Route::post('autoliquidarTransferencia', [PuntosybiopagosController::class,"autoliquidarTransferencia"]);

Route::post('changeBank', [PuntosybiopagosController::class,"changeBank"]);
Route::post('changeSucursal', [CuentasporpagarController::class,"changeSucursal"]);
Route::post('saveFacturaLote', [CuentasporpagarController::class,"saveFacturaLote"]);
Route::post('sendlistdistribucionselect', [CuentasporpagarController::class,"sendlistdistribucionselect"]);

//ALQUILERES
Route::post('getAlquileres', [AlquileresController::class,"getAlquileres"]);
Route::post('setNewAlquiler', [AlquileresController::class,"setNewAlquiler"]);
Route::post('delAlquiler', [AlquileresController::class,"delAlquiler"]);

/* Route::get('pos', function() {

    $arr = [
        ["0108","2024-07-03","2298.98"],
        ["0108","2024-07-04","2291.59"],
        ["0108","2024-07-05","1549.46"],
        ["0108","2024-07-06","1515.03"],
        ["0108","2024-07-07","1381.84"],
        ["0108","2024-07-08","496.69"],
        ["0108","2024-07-09","1977.39"],
        ["0108","2024-07-10","2368.07"],
        ["0108","2024-07-11","2036.58"],
        ["0108","2024-07-12","2971.42"],
        ["0108","2024-07-13","2465.50"],
        ["0108","2024-07-14","2506.21"],
        ["0134","2024-07-03","5383.34"],
        ["0134","2024-07-04","6328.69"],
        ["0134","2024-07-05","5330.79"],
        ["0134","2024-07-06","5181.15"],
        ["0134","2024-07-07","4975.54"],
        ["0134","2024-07-08","1514.43"],
        ["0134","2024-07-09","4862.32"],
        ["0134","2024-07-10","4614.29"],
        ["0134","2024-07-11","5689.24"],
        ["0134","2024-07-12","7669.71"],
        ["0134","2024-07-13","7341.86"],
        ["0134","2024-07-14","6906.65"],
        ["0151","2024-07-03","982.31"],
        ["0151","2024-07-04","254.17"],
        ["0151","2024-07-05","1059.01"],
        ["0151","2024-07-06","557.65"],
        ["0151","2024-07-07","223.32"],
        ["0151","2024-07-08","427.24"],
        ["0151","2024-07-09","176.29"],
        ["0151","2024-07-10","166.47"],
        ["0151","2024-07-11","209.03"],
        ["0151","2024-07-12","519.69"],
        ["0151","2024-07-13","389.52"],
        ["0151","2024-07-14","1084.87"],
        ["0191","2024-07-03","747.16"],
        ["0191","2024-07-04","328.43"],
        ["0191","2024-07-05","733.95"],
        ["0191","2024-07-06","929.19"],
        ["0191","2024-07-07","693.75"],
        ["0191","2024-07-08","131.02"],
        ["0191","2024-07-09","616.61"],
        ["0191","2024-07-10","594.17"],
        ["0191","2024-07-11","683.89"],
        ["0191","2024-07-12","1322.86"],
        ["0191","2024-07-13","1040.05"],
        ["0191","2024-07-14","1060.34"],
    ];
    $catcompos = catcajas::where("nombre","CAJA MATRIZ: COMISION PUNTO DE VENTA")->first();
    foreach ($arr as $key => $val) {
        # code...
        $comision_monto = abs($val[2])*-1;
    
        $com = puntosybiopagos::updateOrCreate([
            "id" => null
        ],[
            "loteserial" => "TOTAL $val[1] $val[0] COMISION POS",
            "banco" => $val[0],
            "fecha" => $val[1],
            "fecha_liquidacion" => $val[1],
            "monto" => $comision_monto,
            "monto_liquidado" => $comision_monto,
            
            "tipo" => "Transferencia",
            "debito_credito" => "DEBITO",
            "id_usuario" => 1,
            "id_sucursal" => 13,
            "origen" => 2,
        
            "categoria" => $catcompos->id
        ]);
    }
    
});
 */

///END ALQUILERES

Route::post('sendsaldoactualbancofecha', [BancosController::class,"sendsaldoactualbancofecha"]);
Route::post('reverserLiquidar', [PuntosybiopagosController::class,"reverserLiquidar"]);

Route::post('sendFacturaCentral', [CuentasporpagarController::class,"sendFacturaCentral"]);
Route::post('getAllProveedores', [ProveedoresController::class,"getAllProveedores"]);
Route::match(array('GET', 'POST'),'selectCuentaPorPagarProveedorDetalles', [CuentasporpagarController::class,"selectCuentaPorPagarProveedorDetalles"]);
Route::get('showImageFact', [CuentasporpagarController::class,"showImageFact"]);
Route::post('getDisponibleEfectivoSucursal', [CajasController::class,"getDisponibleEfectivoSucursal"]);
Route::post('getCajaMatriz', [CajasController::class,"getCajaMatriz"]);
Route::post('depositarmatrizalbanco', [CajasController::class,"depositarmatrizalbanco"]);


Route::post('getControlEfec', [CajasController::class,"getControlEfec"]);
Route::post('delCaja', [CajasController::class,"delCaja"]);
/* Route::post('verificarMovPenControlEfecTRANFTRABAJADOR', [CajasController::class,"verificarMovPenControlEfecTRANFTRABAJADOR"]);
Route::post('verificarMovPenControlEfec', [CajasController::class,"verificarMovPenControlEfec"]); */
//Route::post('aprobarRecepcionCaja', [CajasController::class,"aprobarRecepcionCaja"]);
Route::post('reversarMovPendientes', [CajasController::class,"reversarMovPendientes"]);
Route::post('setControlEfec', [CajasController::class,"setControlEfec"]);


Route::get('getMetodosPago', [BancosListController::class,"getMetodosPago"]);
Route::get('getBancosData', [BancosController::class,"getBancosData"]);
Route::post('getMovBancos', [PuntosybiopagosController::class,"getMovBancos"]);
Route::post('saveNewmovnoreportado', [PuntosybiopagosController::class,"saveNewmovnoreportado"]);




Route::post('saveNewFact', [CuentasporpagarController::class,"saveNewFact"]);
Route::post('changeAprobarFact', [CuentasporpagarController::class,"changeAprobarFact"]);

Route::post('selectPrecioxProveedorSave', [ProductoxproveedorController::class,"selectPrecioxProveedorSave"]);
Route::post('getPrecioxProveedor', [ProductoxproveedorController::class,"getPrecioxProveedor"]);


Route::post('setCarrito', [PedidosController::class,"setCarrito"]);

Route::post('getPedidosList', [PedidosController::class,"getPedidosList"]);

Route::post('getPedidos', [PedidosController::class,"getPedidos"]);
Route::post('delPedido', [PedidosController::class,"delPedido"]);
Route::post('getPedido', [PedidosController::class,"getPedido"]);
Route::post('setConfirmFacturas', [PedidosController::class,"setConfirmFacturas"]);

Route::post('setCtCarrito', [PedidosController::class,"setCtCarrito"]);
Route::post('setDelCarrito', [PedidosController::class,"setDelCarrito"]);

Route::post('sendPedidoSucursal', [PedidosController::class,"sendPedidoSucursal"]);
Route::post('aprobarRevisionPedido', [PedidosController::class,"aprobarRevisionPedido"]);

Route::get('showPedidoBarras', [PedidosController::class,"showPedidoBarras"]);

Route::post('getPedidoPendSucursal', [PedidosController::class,"getPedidoPendSucursal"]);
Route::post('extraerPedidoPendSucursal', [PedidosController::class,"extraerPedidoPendSucursal"]);

Route::post('sendInventario', [InventarioController::class,"sendInventario"]);

Route::post('sendItemsPedidosChecked', [ItemsPedidosController::class,"sendItemsPedidosChecked"]);

Route::post('guardarNuevoProductoLote', [InventarioSucursalController::class,"guardarNuevoProductoLote"]);
Route::post('guardarmodificarInventarioDici', [InventarioSucursalController::class,"guardarmodificarInventarioDici"]);

Route::post('getinventario', [InventarioSucursalController::class,"index"]);
Route::post('getInventarioGeneral',  [InventarioSucursalController::class,"getInventarioGeneral"]);
Route::post('getBarrasCargaItems',  [InventarioSucursalController::class,"getBarrasCargaItems"]);
Route::get('delduplicateItemsEstadisticas',  [InventarioSucursalEstadisticasController::class,"delduplicateItemsEstadisticas"]);

Route::post('buscarNombres',  [InventarioController::class,"buscarNombres"]);
Route::post('modNombres',  [InventarioController::class,"modNombres"]);
Route::post('newNombres',  [InventarioController::class,"newNombres"]);


Route::get('removeDuplicatesCXP',  [CuentasporpagarController::class,"removeDuplicatesCXP"]);
Route::get('removeDuplicatesItemsEstadisticas',  [InventarioSucursalEstadisticasController::class,"removeDuplicatesItemsEstadisticas"]);



Route::post('getInventarioNovedades', [NovedadInventarioAprobacionController::class,"getInventarioNovedades"]);
Route::post('resolveInventarioNovedades', [NovedadInventarioAprobacionController::class,"resolveInventarioNovedades"]);
Route::post('resolveNovedadCentralCheck', [NovedadInventarioAprobacionController::class,"resolveNovedadCentralCheck"]);
Route::post('delInventarioNovedades', [NovedadInventarioAprobacionController::class,"delInventarioNovedades"]);



Route::post('guardarNuevoProducto', [InventarioController::class,"guardarNuevoProducto"]);
Route::post('sendNovedadCentral', [InventarioController::class,"sendNovedadCentral"]);

Route::post('delProducto', [InventarioController::class,"delProducto"]);
Route::post('getFallas', [InventarioController::class,"getFallas"]);
Route::post('setFalla', [InventarioController::class,"setFalla"]);
Route::post('delFalla', [InventarioController::class,"delFalla"]);
Route::post('getEstaInventario', [InventarioController::class,"getEstaInventario"]);
Route::get('reporteInventario', [InventarioController::class,"reporteInventario"]);
Route::get('reporteFalla', [InventarioController::class,"reporteFalla"]);

Route::post('setProveedor', [ProveedoresController::class,"setProveedor"]);
Route::post('getProveedores', [ProveedoresController::class,"getProveedores"]);
Route::post('delProveedor', [ProveedoresController::class,"delProveedor"]);

Route::post('getDepositos', [DepositoController::class,"getDepositos"]);

Route::post('getFacturas', [FacturasController::class,"getFacturas"]);
Route::post('setFactura', [FacturasController::class,"setFactura"]);
Route::post('delFactura', [FacturasController::class,"delFactura"]);
Route::post('saveMontoFactura', [FacturasController::class,"saveMontoFactura"]);
Route::get('verFactura', [FacturasController::class,"verFactura"]);

Route::post('delItemFact', [CuentasporpagarItemsController::class,"delItemFact"]);
Route::post('modItemFact', [CuentasporpagarItemsController::class,"modItemFact"]);

Route::get('setestatus', [CuentasporpagarController::class,"setEstatusAll"]);

/* Route::post('setPagoProveedor', [PagoFacturasController::class,"setPagoProveedor"]);
Route::post('getPagoProveedor', [PagoFacturasController::class,"getPagoProveedor"]); */

Route::get('getCategorias', [CategoriasController::class,"getCategorias"]);
Route::post('delCategoria', [CategoriasController::class,"delCategoria"]);
Route::post('setCategorias', [CategoriasController::class,"setCategorias"]);

Route::get('getCatGenerals', [CatGeneralsController::class,"getCatGenerals"]);
Route::get('importarusers', [UsuariosController::class,"importarusers"]);

Route::post('delCatGeneral', [CatGeneralsController::class,"delCatGeneral"]);
Route::post('setCatGenerals', [CatGeneralsController::class,"setCatGenerals"]);

Route::get('getMarcas', [MarcasController::class,"getMarcas"]);
Route::post('delMarca', [MarcasController::class,"delMarca"]);
Route::post('setMarcas', [MarcasController::class,"setMarcas"]);
Route::post('changeLiquidacionPagoElec', [PuntosybiopagosController::class,"changeLiquidacionPagoElec"]);
Route::post('sendPagoCuentaPorPagar', [CuentasporpagarController::class,"sendPagoCuentaPorPagar"]);


Route::post('aprobarCreditoFun', [CreditoAprobacionController::class,"aprobarCreditoFun"]);
Route::post('aprobarTransferenciaFun', [TransferenciaAprobacionController::class,"aprobarTransferenciaFun"]);

Route::post('createCreditoAprobacion', [CreditoAprobacionController::class,"createCreditoAprobacion"]);
Route::post('createTranferenciaAprobacion', [TransferenciaAprobacionController::class,"createTranferenciaAprobacion"]);


Route::get('unicoinventario', [InventarioController::class,"unicoinventario"]);
Route::get('vincularinventario', [InventarioController::class,"vincularinventario"]);
Route::get('estatusVinculacion', [InventarioController::class,"estatusVinculacion"]);



Route::get('getDistinctNs', [InventarioSucursalController::class,"getDistinctNs"]);

Route::get('setNombres1', [InventarioController::class,"setNombres1"]);
Route::post('getDatinputSelectVinculacion', [InventarioController::class,"getDatinputSelectVinculacion"]);
Route::post('saveCuatroNombres', [InventarioController::class,"saveCuatroNombres"]);
Route::post('addnewNombre', [InventarioController::class,"addnewNombre"]);
Route::get('delInventarioDuplicado', [InventarioController::class,"delInventarioDuplicado"]);
Route::get('delInventarioSucursalDuplicado', [InventarioController::class,"delInventarioSucursalDuplicado"]);

Route::get('setNombres1Inventario', [InventarioController::class,"setNombres1Inventario"]);


Route::post('setPedidoInCentralFromMasters', [PedidosController::class,"setPedidoInCentralFromMasters"]);
Route::post('respedidos', [PedidosController::class,"respedidos"]);
Route::post('changeExtraidoEstadoPed', [PedidosController::class,"changeExtraidoEstadoPed"]);
Route::post('setComovamos', [ComovamosController::class,"setComovamos"]);


///eventscentral
Route::post('setNuevaTareaCentral', [sockets::class,"setNuevaTareaCentral"]);
Route::post('setInventarioFromSucursal', [InventarioSucursalController::class,"setInventarioFromSucursal"]);
Route::post('getInventarioSucursalFromCentral', [InventarioSucursalController::class,"getInventarioSucursalFromCentral"]);
Route::post('setInventarioSucursalFromCentral', [InventarioSucursalController::class,"setInventarioSucursalFromCentral"]);
Route::get('autovincular', [InventarioSucursalController::class,"autovincular"]);

Route::post('setCambiosInventarioSucursal', [InventarioSucursalController::class,"setCambiosInventarioSucursal"]);

Route::post('getInventarioFromSucursal', [InventarioSucursalController::class,"getInventarioFromSucursal"]);
Route::post('changeEstatusProductoProceced', [InventarioSucursalController::class,"changeEstatusProductoProceced"]);
Route::post('setnewtasainsucursal', [MonedaController::class,"setnewtasainsucursal"]);
Route::post('getMonedaSucursal', [MonedaController::class,"getMonedaSucursal"]);

Route::get('getTareasCentral', [TareasController::class,"getTareasCentral"]);

Route::post('resolveTareaCentral', [TareasController::class,"resolveTareaCentral"]);


Route::post('getsucursalListData', [CierresController::class,"getsucursalListData"]);
Route::post('getsucursalDetallesData', [CierresController::class,"getsucursalDetallesData"]);
Route::post('getBalanceGeneral', [CierresController::class,"getBalanceGeneral"]);
Route::get('getCuadreGeneral', [CierresController::class,"getCuadreGeneral"]);




Route::post('delPersonalNomina', [NominaController::class,"delPersonalNomina"]);
Route::post('getPersonalNomina', [NominaController::class,"getPersonalNomina"]);
Route::post('setPersonalNomina', [NominaController::class,"setPersonalNomina"]);
Route::post('activarPersonal', [NominaController::class,"activarPersonal"]);


Route::post('delPersonalCargos', [NominacargosController::class,"delPersonalCargos"]);
Route::post('getPersonalCargos', [NominacargosController::class,"getPersonalCargos"]);
Route::post('setPersonalCargos', [NominacargosController::class,"setPersonalCargos"]);
Route::get('configPagos', [NominapagosController::class,"configPagos"]);


Route::post('getUsuarios', [UsuariosController::class,"getUsuarios"]);
Route::post('setUsuario', [UsuariosController::class,"setUsuario"]);
Route::post('delUsuario', [UsuariosController::class,"delUsuario"]);









// Route::get('/cache', function () {
    //     $clearcache = Artisan::call('cache:clear');
    //     echo "Cache cleared<br>";
    
    //     $clearview = Artisan::call('view:clear');
    //     echo "View cleared<br>";
    
    //     $clearconfig = Artisan::call('config:cache');
//     echo "Config cleared<br>";

   
// });

// Route::get('/migrate', function () {
//     Artisan::call('migrate:fresh');
//     echo "Migrate cleared<br>";
// });

// Route::get('/key', function () {
//     Artisan::call('key:generate');
//     echo "key generated<br>";
// });