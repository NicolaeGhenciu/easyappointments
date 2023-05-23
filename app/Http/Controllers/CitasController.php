<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Disponibilidad_Empleado;
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

        $disponibilidad = Disponibilidad_Empleado::where('id_empleado', $empleado->id_empleado)
            ->whereNull('deleted_at')
            ->get();

        return view('cita.agendaEmpleado', ['citas' => $citas, 'clientes' => $clientes, 'servicios' => $servicios, 'disponibilidad' => $disponibilidad]);
    }

    public function nuevaCitaE()
    {
        $empleadoDatos = Empleado::where('id_empleado', Auth::user()->empleado_id)->first();

        session()->flash('crear');

        $datos = request()->validate([
            'cliente_id' => 'required',
            'servicio_obj' => 'required',
            'fecha' => 'required',
            'hora' => 'required',
        ]);

        $servicio = json_decode($datos['servicio_obj'], true);

        // Crear un timestamp a partir de la fecha y hora
        $timestamp = strtotime($datos['fecha'] . ' ' . $datos['hora']);

        // Formatear el timestamp según tus necesidades
        $timestampInicio = date('Y-m-d H:i:s', $timestamp);

        // Sumar 40 minutos al timestamp
        $nuevoTimestamp = strtotime('+40 minutes', $timestamp);

        $timestampFin = date('Y-m-d H:i:s', $nuevoTimestamp);

        // Verificar si existe alguna otra cita no eliminada y confirmada en esa fecha y hora
        $existeCita = Cita::where('id_empleado', Auth::user()->empleado_id)
            ->where('id_empresa', $empleadoDatos->id_empresa)
            ->where('fecha_inicio', '<=', $timestampFin)
            ->where('fecha_fin', '>=', $timestampInicio)
            ->whereNull('deleted_at') // Verificar que no esté borrada (soft delete)
            ->where('status', ['Confirmada', 'Pendiente']) // Verificar que esté confirmada
            ->exists();

        if ($existeCita) {
            session()->flash('error', 'No es posible confirmar la cita, ya que hay otra cita programada para ese horario.');
            return back();
        }

        $diaSemanaCita = date('w', $timestamp);

        $disponibilidadDia = Disponibilidad_Empleado::where('dia_semana', $diaSemanaCita)
            ->where('id_empleado', Auth::user()->empleado_id)
            ->first();

        if (!$disponibilidadDia || $datos['hora'] < $disponibilidadDia->hora_inicio || $datos['hora'] > $disponibilidadDia->hora_fin) {
            session()->flash('error', 'La hora de la cita está fuera del horario disponible del trabajador');
            return back();
        }

        $cita = Cita::create([
            'id_cliente' => $datos['cliente_id'],
            'id_empresa' => $empleadoDatos->id_empresa,
            'id_empleado' => Auth::user()->empleado_id,
            'id_servicio' => data_get($servicio, 'id_servicio'),
            'fecha_inicio' => $timestampInicio,
            'fecha_fin' => $timestampFin,
            'status' => "Confirmada",
        ]);

        session()->flash('message', 'Cita programada correctamente.');

        return back();
    }
}
