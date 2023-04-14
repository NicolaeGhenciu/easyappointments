<?php

use App\Http\Controllers\AuthController;
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

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('crearUsuarioEmpresa', [UserController::class, 'crearUsuarioEmpresa'])->name('crearUsuarioEmpresa');

Route::post('crearUsuarioCliente', [UserController::class, 'crearUsuarioCliente'])->name('crearUsuarioCliente');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/easyappointments', function () {
    return view('easyappointments');
})->name('easyappointments');


