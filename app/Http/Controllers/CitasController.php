<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitasController extends Controller
{
    public function agendaEmpleado()
    {
        $empleado = Empleado::where('id_empleado', Auth::user()->empleado_id)->first();

        $citas = Cita::where('id_empresa', $empleado->id_empresa)
            ->where('id_empleado', Auth::user()->empleado_id)
            ->whereNull('deleted_at')
            ->get();

        $clientes = Cliente::whereIn('id_cliente', function ($query) use ($empleado) {
            $query->select('id_cliente')
                ->from('empresa_cliente')
                ->where('id_empresa', $empleado->id_empresa);
        })->get();

        $servicios = Servicio::where('id_empresa', $empleado->id_empresa)
            ->whereNull('deleted_at')
            ->orderBy('nombre', 'desc')
            ->paginate(5);

        return view('cita.agendaEmpleado', ['citas' => $citas, 'clientes' => $clientes, 'servicios' => $servicios]);
    }
}
