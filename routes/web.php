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
Route::get('/', [InventarioController::class,"index"]);

Route::get('/getSucursales', [SucursalController::class,"getSucursales"]);

Route::post('/setFalla', [FallasController::class,"setFallas"]);
Route::get('/getFallas', [FallasController::class,"getFallas"]);


Route::post('/setGastos', [GastosController::class,"setGastos"]);
Route::get('/getGastos', [GastosController::class,"getGastos"]);

Route::post('/setVentas', [VentasController::class,"setVentas"]);
Route::get('/getVentas', [VentasController::class,"getVentas"]);


