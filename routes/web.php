<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\ClienteController;
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

// Recuperar la contraseña de una cuenta
Route::post('recuperarContraseña', [UserController::class, 'recuperarContraseña'])->name('recuperarContraseña');

//Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Peticion JSON para los municipios
Route::get('/municipiosPorProvincia/{provincia_id}', [MunicipioController::class, 'municipiosPorProvincia']);

//-- Aqui no dejamos acceder a no ser que se haya logueado.
Route::middleware(['auth'])->group(function () {

    //Menu Principal
    Route::get('/easyappointments', function () {
        return view('easyappointments');
    })->name('easyappointments');

    //Rol Empresa
    Route::middleware(['checkRole:empresa'])->group(function () {

        // --- Empleado

        //Listar
        Route::get('/listarEmpleados', [EmpleadoController::class, 'listar'])->name('listarEmpleados');
        //Dar de alta un empleado
        Route::post('/crearUsuarioEmpleado', [UserController::class, 'crearUsuarioEmpleado'])->name('crearUsuarioEmpleado');
        //Modificar un empleado
        Route::put('/modificarEmpleado/{id}', [EmpleadoController::class, 'modificar'])->name('modificarEmpleado');
        //Borrar un empleado
        Route::delete('/borrarEmpleado/{id}', [EmpleadoController::class, 'borrar'])->name('borrarEmpleado');

        // --- Asociación servicios-empleado

        //Servicios que presta un empleado
        Route::get('/serviciosEmpleado/{id}', [EmpleadoController::class, 'servicios'])->name('serviciosEmpleado');
        //Asociar servicios a un empleado
        Route::post('/asociarServicio/{id}', [EmpleadoController::class, 'asociarServicio'])->name('asociarServicio');
        //Eliminar una asociacion-servicios de un empleado
        Route::delete('/desasociarServicio/{id}', [EmpleadoController::class, 'desasociarServicio'])->name('desasociarServicio');

        // --- Servicios

        //Listar
        Route::get('/listarServicios', [ServicioController::class, 'listar'])->name('listarServicios');
        //Dar de alta a un servicio
        Route::post('/crearServicio', [ServicioController::class, 'crear'])->name('crearServicio');
        //Modificar un servicio
        Route::put('/modificarServicio/{id}', [ServicioController::class, 'modificar'])->name('modificarServicio');
        //Borrar un servicio
        Route::delete('/borrarServicio/{id}', [ServicioController::class, 'borrar'])->name('borrarServicio');

        // --- Citas
        //Ver la agenda mensual, semanal y diaria del empleado
        Route::get('/agendaEmpleadoEmpresa/{id}', [CitasController::class, 'agendaEmpleadoEmpresa'])->name('agendaEmpleadoEmpresa');
        //Programar una nueva cita
        Route::post('/nuevaCitaE_Empresa/{id}', [CitasController::class, 'nuevaCitaE_Empresa'])->name('nuevaCitaE_Empresa');
        //Modificar una cita
        Route::post('/modificarCitaE_Empresa/{id}/{idEmpleado}', [CitasController::class, 'modificarCitaE_Empresa'])->name('modificarCitaE_Empresa');
    });

    //Rol Empresa y Empleado
    Route::middleware(['checkRole:empresa,empleado'])->group(function () {

        // --- Clientes

        //Listar
        Route::get('/listarClientes', [ClienteController::class, 'listar'])->name('listarClientes');
        //Dar de alta un cliente
        Route::post('/asociarCliente', [ClienteController::class, 'asociar'])->name('asociarCliente');
        //Modificar un cliente
        Route::put('/modificarCliente/{id}', [ClienteController::class, 'modificar'])->name('modificarCliente');
        //Desasociar un cliente
        Route::delete('/desasociarCliente/{id}', [ClienteController::class, 'borrar'])->name('desasociarCliente');
    });

    //Rol Empelado
    Route::middleware(['checkRole:empleado'])->group(function () {

        // --- Citas
        //Ver la agenda mensual, semanal y diaria del empleado
        Route::get('/agendaEmpleado', [CitasController::class, 'agendaEmpleado'])->name('agendaEmpleado');
        //Programar una nueva cita
        Route::post('/nuevaCitaE', [CitasController::class, 'nuevaCitaE'])->name('nuevaCitaE');
        //Modificar una cita
        Route::post('/modificarCitaE/{id}', [CitasController::class, 'modificarCitaE'])->name('modificarCitaE');
    });

    //Rol Empresa, Empleado y Cliente
    Route::middleware(['checkRole:empresa,empleado,cliente'])->group(function () {

        // --- Citas
        Route::get('/citaPDF/{id}', [CitasController::class, 'generarCitaPdf'])->name('citaPDF');

        // --- Mi cuenta
        Route::get('/miCuenta', [UserController::class, 'miCuenta'])->name('miCuenta');
        // --- Cambiar contraseña de un usuario
        Route::post('/cambiarPass', [UserController::class, 'cambiarPass'])->name('cambiarPass');
    });
});
