<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Rules\DniRule;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    public function listar()
    {
        $provincias = Provincia::all();
        $municipios = Municipio::all();

        $empleados = Empleado::where('id_empresa', Auth::user()->empresa_id)
            ->whereNull('deleted_at')
            ->orderBy('nombre', 'desc')
            ->paginate(5);

        return view('empleado.lista', ['empleados' => $empleados, 'provincias' => $provincias, 'municipios' => $municipios]);
    }

    public function modificar($id)
    {
        $empleado = Empleado::where('id_empleado', $id);
        $empleadoDatos = Empleado::where('id_empleado', $id)->first();

        if ($empleadoDatos->id_empresa != Auth::user()->empresa_id) {
            session()->flash('error', 'No tienes permiso para modificar a este empleado.');
            return redirect()->route('listarEmpleados');
        }

        session()->flash('modificar');

        $datos = request()->validate([
            'nif' => ['required', new DniRule],
            'nombre' => 'required|min:3|max:100',
            'apellidos' => 'required|min:3|max:100',
            'cargo' => 'required|min:2|max:100',
            'fecha_nacimiento' => 'required',
            'direccion' => 'required|min:6|max:100',
            'telefono' => 'required|regex:/^(?:(?:\+?[0-9]{2,4})?[ ]?[6789][0-9 ]{8,13})$/',
            'provincia_id' => 'required',
            'municipio_id' => 'required',
        ]);

        $empleado->update($datos);

        session()->forget('modificar');

        session()->flash('message', "Los datos de " . $datos['nombre'] . " " . $datos['apellidos'] . "han sido modificado correctamente.");

        return redirect()->route('listarEmpleados');
    }

    public function borrar($id)
    {
        $empleado = Empleado::where('id_empleado', $id);
        $empleadoDatos = Empleado::where('id_empleado', $id)->first();

        if ($empleadoDatos->id_empresa != Auth::user()->empresa_id) {
            session()->flash('error', 'No tienes permiso para eliminar a este empleado.');
            return redirect()->route('listarEmpleados');
        }

        $empleado->delete();
        session()->flash('message', "$empleadoDatos->nombre $empleadoDatos->apellidos ha sido dado de baja correctamente.");
        return redirect()->route('listarEmpleados');
    }
}
