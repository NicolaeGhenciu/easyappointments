<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ServicioController;
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

//GET Login
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

//POST Login
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Dar de alta a una empresa
Route::post('crearUsuarioEmpresa', [UserController::class, 'crearUsuarioEmpresa'])->name('crearUsuarioEmpresa');

// Dar de alta a un cliente
Route::post('crearUsuarioCliente', [UserController::class, 'crearUsuarioCliente'])->name('crearUsuarioCliente');

//Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Peticion JSON
Route::get('/municipiosPorProvincia/{provincia_id}', [MunicipioController::class, 'municipiosPorProvincia']);

//-- Aqui no dejamos acceder a no ser que se haya logueado.
Route::middleware(['auth'])->group(function () {

    //Menu Principal
    Route::get('/easyappointments', function () {
        return view('easyappointments');
    })->name('easyappointments');

    //Rol Empresa
    Route::middleware(['empresa'])->group(function () {

        // --- Empleado

        //Listar
        Route::get('/listarEmpleados', [EmpleadoController::class, 'listar'])->name('listarEmpleados');
        //Dar de alta un empleado
        Route::post('/crearUsuarioEmpleado', [UserController::class, 'crearUsuarioEmpleado'])->name('crearUsuarioEmpleado');
        //Modificar un empleado
        Route::put('/modificarEmpleado/{id}', [EmpleadoController::class, 'modificar'])->name('modificarEmpleado');
        //Borrar un empleado
        Route::delete('/borrarEmpleado/{id}', [EmpleadoController::class, 'borrar'])->name('borrarEmpleado');

        // --- Servicios

        //Listar
        Route::get('/listarServicios', [ServicioController::class, 'listar'])->name('listarServicios');
        //Dar de alta a un servicio
        Route::post('/crearServicio', [ServicioController::class, 'crear'])->name('crearServicio');
        //Modificar un servicio
        Route::put('/modificarServicio/{id}', [ServicioController::class, 'modificar'])->name('modificarServicio');
        //Borrar un servicio
        Route::delete('/borrarServicio/{id}', [ServicioController::class, 'borrar'])->name('borrarServicio');
    });
});
