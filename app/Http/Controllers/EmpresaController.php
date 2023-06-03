<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    public function estadisticas1()
    {
        $citas = Cita::where('id_empresa', Auth::user()->empresa_id)
            ->whereNull('deleted_at')
            ->get();

        return view('estadisticas.estadisticas1', ['citas' => $citas]);
    }
}
