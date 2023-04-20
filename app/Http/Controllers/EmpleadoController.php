<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    public function listar()
    {
        $empleados = Empleado::where('id_empresa', Auth::user()->empresa_id)
            ->whereNull('deleted_at')
            ->orderBy('nombre', 'desc')
            ->paginate(5);

        return view('empleado.lista', compact('empleados'));
    }

    public function borrar($id_empleado)
    {
        $empleado = Empleado::where('id_empleado', $id_empleado);
        $empleado->delete();
        session()->flash('message', 'El empleado ha sido borrado correctamente.');
        return redirect()->route('listarEmpleados');
    }
}
