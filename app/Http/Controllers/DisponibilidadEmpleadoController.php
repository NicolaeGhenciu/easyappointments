<?php

namespace App\Http\Controllers;

use App\Models\Disponibilidad_Empleado;
use App\Models\Empleado;
use Illuminate\Support\Facades\Auth;

class DisponibilidadEmpleadoController extends Controller
{
    public function listar($id)
    {
        $dias_semana = [
            'Lunes',
            'Martes',
            'Miércoles',
            'Jueves',
            'Viernes',
            'Sábado',
            'Domingo',
        ];

        $disponibilidad = Disponibilidad_Empleado::where('id_empleado', $id)
            ->whereNull('deleted_at')
            ->get();

        $empleado = Empleado::where('id_empleado', $id)->first();

        return view('horarios.lista', ['disponibilidad' => $disponibilidad, 'dias_semana' => $dias_semana, 'empleado' => $empleado]);
    }

    public function listarEmpleado()
    {
        $dias_semana = [
            'Lunes',
            'Martes',
            'Miércoles',
            'Jueves',
            'Viernes',
            'Sábado',
            'Domingo',
        ];

        $disponibilidad = Disponibilidad_Empleado::where('id_empleado', Auth::user()->empleado_id)
            ->whereNull('deleted_at')
            ->get();

        $empleado = Empleado::where('id_empleado', Auth::user()->empleado_id)->first();

        return view('horarios.horarioEmpleado', ['disponibilidad' => $disponibilidad, 'dias_semana' => $dias_semana, 'empleado' => $empleado]);
    }

    public function programar($id, $dia)
    {
        $dias_semana = [
            'Domingo',
            'Lunes',
            'Martes',
            'Miércoles',
            'Jueves',
            'Viernes',
            'Sábado',
        ];

        session()->flash('crear');
        session(['id_disponibilidad' => $id]);
        session(['id_dia' => $dia]);

        $datos = request()->validate([
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
        ]);

        if ($datos['hora_inicio'] > $datos['hora_fin']) {
            session()->flash('error', 'La hora de fin no puede ser inferior a la hora de inicio.');
            return back();
        }

        $disponibilidad = Disponibilidad_Empleado::where('id_empleado', $id)
            ->where('dia_semana', $dia)
            ->whereNull('deleted_at')
            ->get();

        if (!$disponibilidad->isEmpty()) {
            session()->flash('error', 'No es posible crear otro horario para el ' . $dias_semana[$dia]);
            return back();
        }

        $horario = Disponibilidad_Empleado::create([
            'id_empleado' => $id,
            'dia_semana' => $dia,
            'hora_inicio' => $datos['hora_inicio'],
            'hora_fin' => $datos['hora_fin'],
        ]);

        session()->flash('message', 'Horario programado correctamente.');

        session()->forget('crear');

        return back();
    }

    public function modificar($id)
    {

        $dias_semana = [
            'Domingo',
            'Lunes',
            'Martes',
            'Miércoles',
            'Jueves',
            'Viernes',
            'Sábado',
        ];

        session()->flash('modificar');
        session(['id_disponibilidad' => $id]);

        $datos = request()->validate([
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
        ]);

        session()->forget('modificar');
        session()->forget('id_disponibilidad');

        if ($datos['hora_inicio'] > $datos['hora_fin']) {
            session()->flash('error', 'La hora de fin no puede ser inferior a la hora de inicio.');
            return back();
        }

        $disponibilidad = Disponibilidad_Empleado::where('id_disponibilidad', $id)
            ->whereNull('deleted_at')
            ->first();

        $disponibilidad->update($datos);

        session()->flash('message', 'Horario modificado correctamente.');

        return back();
    }

    public function borrar($id)
    {

        $disponibilidad = Disponibilidad_Empleado::where('id_disponibilidad', $id)
            ->whereNull('deleted_at')
            ->first();

        $disponibilidad->delete();

        session()->flash('message', 'Horario borrado correctamente.');

        return back();
    }
}
