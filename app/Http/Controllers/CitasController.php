<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Empleado;
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

        return view('cita.agendaEmpleado', ['citas' => $citas]);
    }
}
