<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Models\Servicio_Empleado;
use App\Models\User;
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

        session(['id_empleado' => $empleadoDatos->id_empleado]);

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

        $user = User::where('empleado_id', $empleadoDatos->id_empleado)->first();

        if ($user) {

            $user->nombre = $datos['nombre'] . ' ' . $datos['apellidos'];

            $user->save();
        }

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

    public function servicios($id)
    {

        $empleadoDatos = Empleado::where('id_empleado', $id)->first();

        if ($empleadoDatos->id_empresa != Auth::user()->empresa_id) {
            session()->flash('error', 'No tienes permiso sobre ese empleado.');
            return redirect()->route('listarEmpleados');
        }

        $allservicios = Servicio::where('id_empresa', Auth::user()->empresa_id)
            ->whereNull('deleted_at')
            ->get();

        $servicios = Servicio_Empleado::where('id_empleado', $id)
            ->whereHas('empleado', function ($query) {
                $query->where('id_empresa', Auth::user()->empresa_id);
            })
            ->whereHas('servicio', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->with('servicio')
            ->paginate(5);

        return view('empleado.servicios', ['servicios' => $servicios, 'empleado' => $empleadoDatos, 'allservicios' => $allservicios]);
    }

    public function asociarServicio($id)
    {
        $empleadoDatos = Empleado::where('id_empleado', $id)->first();

        if ($empleadoDatos->id_empresa != Auth::user()->empresa_id) {
            session()->flash('error', 'No tienes permisos sobre ese empleado.');
            return redirect()->route('listarEmpleados');
        }

        $datos = request()->validate([
            'servicio_id' => 'required',
        ]);

        // Comprobar si ya existe la asociación
        $servicioEmpleadoExistente = Servicio_Empleado::where('id_empleado', $id)
            ->where('id_servicio', $datos['servicio_id'])
            ->exists();

        if ($servicioEmpleadoExistente) {
            session()->flash('error', 'La asociación entre este empleado y el servicio ya existe.');
            return redirect()->back();
        }

        $servicioEmpleado = Servicio_Empleado::create([
            'id_empleado' => $id,
            'id_servicio' => $datos['servicio_id'],
        ]);

        return redirect()->route('serviciosEmpleado', $id);
    }

    public function desasociarServicio($id)
    {
        $servicioEmpleado = Servicio_Empleado::where('id_servicio_empleado', $id);

        $servicioEmpleadoDatos = Servicio_Empleado::where('id_servicio_empleado', $id)->first();

        $empleadoDatos = Empleado::where('id_empleado', $servicioEmpleadoDatos->id_empleado)->first();
        
        if ($empleadoDatos->id_empresa != Auth::user()->empresa_id) {
            session()->flash('error', 'No tienes permisos sobre ese empleado.');
            return redirect()->route('listarEmpleados');
        }
        
        if ($servicioEmpleado) {
            $servicioEmpleado->delete();
            session()->flash('success', 'La asociación ha sido eliminada con éxito.');
        } else {
            session()->flash('error', 'No se encontró la asociación especificada.');
        }

        return redirect()->route('serviciosEmpleado', $empleadoDatos->id_empleado);
    }
}
