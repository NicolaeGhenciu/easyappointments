<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Municipio;
use App\Models\Provincia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function listar()
    {
        $provincias = Provincia::all();
        $municipios = Municipio::all();

        //$clientes = Cliente::all();
        if (Auth::user()->role == 'empresa') {
            $clientes = Cliente::whereIn('id_cliente', function ($query) {
                $query->select('id_cliente')
                    ->from('empresa_cliente')
                    ->where('id_empresa', Auth::user()->empresa_id);
            })->get();
        }

        if (Auth::user()->role == 'empleado') {

            $empleado = Empleado::where('id_empleado', Auth::user()->empleado_id)->first();

            $clientes = Cliente::whereIn('id_cliente', function ($query) use ($empleado) {
                $query->select('id_cliente')
                    ->from('citas')
                    ->where('id_empresa', $empleado->id_empresa);
            })->get();
        }

        return view('cliente.lista', ['clientes' => $clientes, 'provincias' => $provincias, 'municipios' => $municipios]);
    }
}
