<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Municipio;
use App\Models\Provincia;
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
