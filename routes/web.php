<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InventarioController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\FallasController;
use App\Http\Controllers\DepositoController;
use App\Http\Controllers\CtSucursalController;

use App\Http\Controllers\GastosController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\home;

use App\Http\Controllers\FacturasController;
use App\Http\Controllers\ItemsFacturasController;




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

Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('/', [home::class,"index"]);
Route::get('/today', [home::class,"today"]);
Route::get('/getSucursales', [SucursalController::class,"getSucursales"]);




Route::post('/setFalla', [FallasController::class,"setFallas"]);
Route::get('/getFallas', [FallasController::class,"getFallas"]);

Route::post('/setGastos', [GastosController::class,"setGastos"]);
Route::get('/getGastos', [GastosController::class,"getGastos"]);

Route::post('/setVentas', [VentasController::class,"setVentas"]);
Route::get('/getVentas', [VentasController::class,"getVentas"]);


Route::post('getinventario', [InventarioController::class,"index"]);
Route::post('guardarNuevoProducto', [InventarioController::class,"guardarNuevoProducto"]);
Route::post('delProducto', [InventarioController::class,"delProducto"]);


Route::post('setProveedor', [ProveedoresController::class,"setProveedor"]);
Route::post('delProveedor', [ProveedoresController::class,"delProveedor"]);
Route::post('getProveedores', [ProveedoresController::class,"getProveedores"]);

Route::post('getDepositos', [DepositoController::class,"getDepositos"]);

Route::post('getFacturas', [FacturasController::class,"getFacturas"]);
Route::post('setFactura', [FacturasController::class,"setFactura"]);
  

Route::post('delFactura', [FacturasController::class,"delFactura"]);
Route::post('delItemFact', [ItemsFacturasController::class,"delItemFact"]);


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