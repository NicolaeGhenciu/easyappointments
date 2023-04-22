<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Rules\CifRules;
use App\Rules\DniRule;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\Empleado;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function crearUsuarioEmpresa()
    {

        $datos = request()->merge(['role' => 'empresa'])->all();

        $datos = request()->validate([
            'nombre' => 'required|min:3|max:100',
            'cif' => ['required', new CifRules],
            'email' => 'required|email',
            'password' => 'required|min:6|max:15|regex:/^[^,]*$/',
            'telefono' => 'required|regex:/^(?:(?:\+?[0-9]{2,4})?[ ]?[6789][0-9 ]{8,13})$/',
            'direccion' => 'required|min:6|max:100',
            'provincia_id' => 'required',
            'municipio_id' => 'required',
            'role' => '',
        ]);

        $datos['password'] = Hash::make($datos['password']);

        $empresa = Empresa::create($datos);

        $datos['empresa_id'] = $empresa->id;

        User::create($datos);

        session()->flash('message', 'Empresa registrado correctamente.');

        return back();
    }

    public function crearUsuarioCliente()
    {

        $datos = request()->merge(['role' => 'cliente'])->all();

        $datos = request()->validate([
            'nif' => ['required', new DniRule],
            'nombre' => 'required|min:3|max:100',
            'apellidos' => 'required|min:3|max:100',
            'fecha_nacimiento' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|max:15|regex:/^[^,]*$/',
            'direccion' => 'required|min:6|max:100',
            'telefono' => 'required|regex:/^(?:(?:\+?[0-9]{2,4})?[ ]?[6789][0-9 ]{8,13})$/',
            'provincia_id' => 'required',
            'municipio_id' => 'required',
            'role' => '',
        ]);

        $datos['password'] = Hash::make($datos['password']);

        $cliente = Cliente::create($datos);

        $datos['cliente_id'] = $cliente->id;

        User::create($datos);

        session()->flash('message', 'Cliente registrado correctamente.');

        return back();
    }

    public function crearUsuarioEmpleado()
    {

        $datos = request()->merge(['role' => 'empleado'])->all();

        $datos = request()->validate([
            'nif' => ['required', new DniRule],
            'nombre' => 'required|min:3|max:100',
            'apellidos' => 'required|min:3|max:100',
            'cargo' => 'required|min:2|max:100',
            'fecha_nacimiento' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|max:15|regex:/^[^,]*$/',
            'direccion' => 'required|min:6|max:100',
            'telefono' => 'required|regex:/^(?:(?:\+?[0-9]{2,4})?[ ]?[6789][0-9 ]{8,13})$/',
            'provincia_id' => 'required',
            'municipio_id' => 'required',
            'role' => '',
        ]);

        $datos['password'] = Hash::make($datos['password']);

        $datos['id_empresa'] = Auth::user()->empresa_id;

        $empleado = Empleado::create($datos);

        $datos['empleado_id'] = $empleado->id;

        User::create($datos);

        session()->flash('message', 'Empleado dado de alta correctamente.');

        return back();
    }
}
