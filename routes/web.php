<?php

use App\Http\Controllers\CajasController;
use App\Http\Controllers\CatcajasController;
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

Route::get('getMoneda', [MonedaController::class,"getMoneda"]);
Route::get('getVersionRemote', [LocalsVersionController::class,"getVersion"]);

Route::get('', [home::class,"index"]);
Route::get('today', [home::class,"today"]);
Route::get('getSucursales', [SucursalController::class,"getSucursales"]);
Route::post('setEstadisticas', [InventarioSucursalController::class,"setEstadisticas"]);


Route::post('getNomina', [NominaController::class,"getNomina"]);
Route::get('getCatCajas', [CatcajasController::class,"getCatCajas"]);


Route::get('getFallas', [FallasController::class,"getFallas"]);

Route::get('getGastos', [GastosController::class,"getGastos"]);

Route::post('setVentas', [VentasController::class,"setVentas"]);
Route::get('getVentas', [VentasController::class,"getVentas"]);


Route::post('sendInventarioCt', [InventarioSucursalController::class,"sendInventarioCt"]);
Route::post('sendGastos', [GastosController::class,"sendGastos"]);
Route::post('sendGarantias', [GarantiasController::class,"sendGarantias"]);
Route::post('sendFallas', [FallasController::class,"sendFallas"]);



Route::post('selectPrecioxProveedorSave', [ProductoxproveedorController::class,"selectPrecioxProveedorSave"]);
Route::post('getPrecioxProveedor', [ProductoxproveedorController::class,"getPrecioxProveedor"]);

Route::post('getinventario', [InventarioController::class,"index"]);

Route::post('setCarrito', [PedidosController::class,"setCarrito"]);

Route::post('getPedidosList', [PedidosController::class,"getPedidosList"]);

Route::post('getPedidos', [PedidosController::class,"getPedidos"]);
Route::post('delPedido', [PedidosController::class,"delPedido"]);
Route::post('getPedido', [PedidosController::class,"getPedido"]);
Route::post('setConfirmFacturas', [PedidosController::class,"setConfirmFacturas"]);

Route::post('setCtCarrito', [PedidosController::class,"setCtCarrito"]);
Route::post('setDelCarrito', [PedidosController::class,"setDelCarrito"]);

Route::post('sendPedidoSucursal', [PedidosController::class,"sendPedidoSucursal"]);
Route::get('showPedidoBarras', [PedidosController::class,"showPedidoBarras"]);

Route::post('getPedidoPendSucursal', [PedidosController::class,"getPedidoPendSucursal"]);
Route::post('extraerPedidoPendSucursal', [PedidosController::class,"extraerPedidoPendSucursal"]);

Route::post('sendInventario', [InventarioController::class,"sendInventario"]);



Route::post('getinventario', [InventarioController::class,"index"]);
Route::post('guardarNuevoProducto', [InventarioController::class,"guardarNuevoProducto"]);
Route::post('guardarNuevoProductoLote', [InventarioController::class,"guardarNuevoProductoLote"]);
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

Route::post('delItemFact', [ItemsFacturasController::class,"delItemFact"]);

/* Route::post('setPagoProveedor', [PagoFacturasController::class,"setPagoProveedor"]);
Route::post('getPagoProveedor', [PagoFacturasController::class,"getPagoProveedor"]); */

Route::get('getCategorias', [CategoriasController::class,"getCategorias"]);
Route::post('delCategoria', [CategoriasController::class,"delCategoria"]);
Route::post('setCategorias', [CategoriasController::class,"setCategorias"]);

Route::get('getCatGenerals', [CatGeneralsController::class,"getCatGenerals"]);
Route::post('delCatGeneral', [CatGeneralsController::class,"delCatGeneral"]);
Route::post('setCatGenerals', [CatGeneralsController::class,"setCatGenerals"]);

Route::get('getMarcas', [MarcasController::class,"getMarcas"]);
Route::post('delMarca', [MarcasController::class,"delMarca"]);
Route::post('setMarcas', [MarcasController::class,"setMarcas"]);




Route::post('setPedidoInCentralFromMasters', [PedidosController::class,"setPedidoInCentralFromMasters"]);
Route::post('respedidos', [PedidosController::class,"respedidos"]);
Route::post('changeExtraidoEstadoPed', [PedidosController::class,"changeExtraidoEstadoPed"]);
Route::post('setComovamos', [ComovamosController::class,"setComovamos"]);


///eventscentral
Route::post('setNuevaTareaCentral', [sockets::class,"setNuevaTareaCentral"]);
Route::post('setInventarioFromSucursal', [InventarioSucursalController::class,"setInventarioFromSucursal"]);
Route::post('getInventarioSucursalFromCentral', [InventarioSucursalController::class,"getInventarioSucursalFromCentral"]);
Route::post('setInventarioSucursalFromCentral', [InventarioSucursalController::class,"setInventarioSucursalFromCentral"]);
Route::post('setEfecFromSucursalToCentral', [CajasController::class,"setEfecFromSucursalToCentral"]);

Route::post('setCambiosInventarioSucursal', [InventarioSucursalController::class,"setCambiosInventarioSucursal"]);

Route::post('getInventarioFromSucursal', [InventarioSucursalController::class,"getInventarioFromSucursal"]);
Route::post('changeEstatusProductoProceced', [InventarioSucursalController::class,"changeEstatusProductoProceced"]);
Route::post('setnewtasainsucursal', [MonedaController::class,"setnewtasainsucursal"]);
Route::post('getMonedaSucursal', [MonedaController::class,"getMonedaSucursal"]);

Route::get('getTareasCentral', [TareasController::class,"getTareasCentral"]);

Route::post('resolveTareaCentral', [TareasController::class,"resolveTareaCentral"]);

Route::post('setCierreFromSucursalToCentral', [CierresController::class,"setCierreFromSucursalToCentral"]);

Route::post('getsucursalListData', [CierresController::class,"getsucursalListData"]);
Route::post('getsucursalDetallesData', [CierresController::class,"getsucursalDetallesData"]);


Route::post('delPersonalNomina', [NominaController::class,"delPersonalNomina"]);
Route::post('getPersonalNomina', [NominaController::class,"getPersonalNomina"]);
Route::post('setPersonalNomina', [NominaController::class,"setPersonalNomina"]);

Route::post('delPersonalCargos', [NominacargosController::class,"delPersonalCargos"]);
Route::post('getPersonalCargos', [NominacargosController::class,"getPersonalCargos"]);
Route::post('setPersonalCargos', [NominacargosController::class,"setPersonalCargos"]);

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