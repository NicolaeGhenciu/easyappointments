<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicioController extends Controller
{
    public function listar()
    {
        $servicios = Servicio::where('id_empresa', Auth::user()->empresa_id)
            ->whereNull('deleted_at')
            ->orderBy('nombre', 'desc')
            ->paginate(5);

        return view('servicio.lista', ['servicios' => $servicios]);
    }

    public function crear()
    {

        session()->flash('crear');

        $cod = request()->input('cod');

        // Verificar si el código ya existe en la base de datos para esta empresa
        if (Servicio::where('cod', $cod)
            ->where('id_empresa', Auth::user()->empresa_id)
            ->exists()
        ) {
            return back()->withErrors(['cod' => 'El código ya esta asociado a un servicio.']);
        }

        $datos = request()->validate([
            'id_empresa' => '',
            'cod' => 'required|min:3|max:100',
            'nombre' => 'required|min:3|max:100',
            'descripcion' => 'required|min:3|max:100',
            'precio' => 'required|numeric',
            'duracion' => 'required|numeric',
        ]);

        $datos['id_empresa'] = Auth::user()->empresa_id;

        Servicio::create($datos);

        session()->forget('crear');

        session()->flash('message', 'Servicio dado de alta correctamente.');

        return back();
    }

    public function modificar($id)
    {
        $servicio = Servicio::where('id_servicio', $id);
        $sercicioDatos = Servicio::where('id_servicio', $id)->first();

        if ($sercicioDatos->id_empresa != Auth::user()->empresa_id) {
            session()->flash('error', 'No tienes permiso para modificar este servicio.');
            return redirect()->route('listarServicios');
        }

        session()->flash('modificar');

        session(['id_servicio' => $sercicioDatos->id_servicio]);

        $cod = request()->input('cod');

        if (Servicio::where('cod', $cod)
            ->where('id_empresa', Auth::user()->empresa_id)
            ->where('id_servicio', '!=', $id)
            ->exists()
        ) {
            return back()->withErrors(['cod' => 'El código ya está asociado a otro servicio.']);
        }

        $datos = request()->validate([
            'id_empresa' => '',
            'cod' => 'required|min:3|max:100',
            'nombre' => 'required|min:3|max:100',
            'descripcion' => 'required|min:3|max:100',
            'precio' => 'required|numeric',
            'duracion' => 'required|numeric',
        ]);

        $servicio->update($datos);

        session()->forget('modificar');

        session()->flash('message', $datos['cod']  . " ha sido modificado correctamente.");

        return redirect()->route('listarServicios');
    }

    public function borrar($id)
    {
        $servicio = Servicio::where('id_servicio', $id);
        $sercicioDatos = Servicio::where('id_servicio', $id)->first();

        if ($sercicioDatos->id_empresa != Auth::user()->empresa_id) {
            session()->flash('error', 'No tienes permiso para eliminar este servicio.');
            return redirect()->route('listarServicios');
        }

        $servicio->delete();
        session()->flash('message', "$sercicioDatos->cod ha sido dado de baja correctamente.");
        return redirect()->route('listarServicios');
    }
}
