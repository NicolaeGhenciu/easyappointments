<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Empresa_Cliente;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Models\User;
use App\Rules\DniRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Mockery\Undefined;

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
                    ->from('empresa_cliente')
                    ->where('id_empresa', $empleado->id_empresa);
            })->get();
        }

        return view('cliente.lista', ['clientes' => $clientes, 'provincias' => $provincias, 'municipios' => $municipios]);
    }

    public function asociar()
    {

        session()->flash('asociar');

        $datos = request()->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $datos['email'])
            ->whereNotNull('cliente_id')
            ->first();

        if ($user == null) {
            session()->flash('error', 'No hay ningun cliente con ese correo electronico.');
            return back();
        }

        $clienteId = $user->cliente_id;
        $empresaId = Auth::user()->role == 'empresa' ? Auth::user()->empresa_id : Empleado::where('id_empleado', Auth::user()->empleado_id)->first()->id_empresa;

        // Verificar si la asociación ya existe
        if (Empresa_Cliente::where('id_cliente', $clienteId)->where('id_empresa', $empresaId)->exists()) {
            session()->flash('error', 'La asociación entre el cliente y la empresa ya existe.');
            return back();
        }

        Empresa_Cliente::create([
            'id_cliente' => $clienteId,
            'id_empresa' => $empresaId,
        ]);

        session()->forget('asociar');

        session()->flash('message', 'El cliente ha sido asociado correctamente.');

        return redirect()->route('listarClientes');
    }

    public function borrar($id)
    {

        $empresaId = Auth::user()->role == 'empresa' ? Auth::user()->empresa_id : Empleado::where('id_empleado', Auth::user()->empleado_id)->first()->id_empresa;

        Empresa_Cliente::where('id_empresa', $empresaId)
            ->where('id_cliente', $id)
            ->delete();

        session()->flash('success', 'La asociación ha sido eliminada con éxito.');

        return redirect()->route('listarClientes');
    }

    public function modificar($id)
    {

        $cliente = Cliente::where('id_cliente', $id);
        $clienteDatos = Cliente::where('id_cliente', $id)->first();

        $empresaId = Auth::user()->role == 'empresa' ? Auth::user()->empresa_id : Empleado::where('id_empleado', Auth::user()->empleado_id)->first()->id_empresa;

        $relacionExistente = Empresa_Cliente::where('id_empresa', $empresaId)
            ->where('id_cliente', $id)
            ->first();

        if (!$relacionExistente) {
            session()->flash('error', 'No tienes permiso para modificar a este cliente.');
            return redirect()->route('listarClientes');
        }

        session()->flash('modificar');

        session(['id_cliente' => $clienteDatos->id_cliente]);

        $datos = request()->validate([
            'nif' => ['required', new DniRule],
            'nombre' => 'required|min:3|max:100',
            'apellidos' => 'required|min:3|max:100',
            'fecha_nacimiento' => 'required',
            'direccion' => 'required|min:6|max:100',
            'telefono' => 'required|regex:/^(?:(?:\+?[0-9]{2,4})?[ ]?[6789][0-9 ]{8,13})$/',
            'provincia_id' => 'required',
            'municipio_id' => 'required',
        ]);

        $cliente->update($datos);

        $user = User::where('empleado_id', $clienteDatos->id_cliente)->first();

        if ($user) {

            $user->nombre = $datos['nombre'] . ' ' . $datos['apellidos'];

            $user->save();
        }

        session()->forget('modificar');

        session()->flash('message', "Los datos de " . $datos['nombre'] . " " . $datos['apellidos'] . "han sido modificado correctamente.");

        return redirect()->route('listarClientes');
    }
}
