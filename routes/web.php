<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

//Login
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('crearUsuarioEmpresa', [UserController::class, 'crearUsuarioEmpresa'])->name('crearUsuarioEmpresa');

Route::post('crearUsuarioCliente', [UserController::class, 'crearUsuarioCliente'])->name('crearUsuarioCliente');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Peticion JSON
Route::get('/municipiosPorProvincia/{provincia_id}', [MunicipioController::class, 'municipiosPorProvincia']);

//Menu Principal
Route::get('/easyappointments', function () {
    return view('easyappointments');
})->name('easyappointments');

//Empleado
Route::get('/listarEmpleados', [EmpleadoController::class, 'listar'])->name('listarEmpleados');
Route::delete('/borrarEmpleado/{id}', [EmpleadoController::class, 'borrar'])->name('borrarEmpleado');