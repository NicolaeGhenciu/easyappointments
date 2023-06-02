<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Disponibilidad_Empleado;
use App\Models\Empleado;
use App\Models\Empresa;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CitasController extends Controller
{
    public function agendaEmpleado()
    {
        $empleado = Empleado::where('id_empleado', Auth::user()->empleado_id)->first();

        $citas = Cita::where('id_empresa', $empleado->id_empresa)
            ->where('id_empleado', Auth::user()->empleado_id)
            ->whereNull('deleted_at')
            ->get();

        $citasConfirmadas = Cita::where('id_empresa', $empleado->id_empresa)
            ->where('id_empleado', Auth::user()->empleado_id)
            ->where('status', 'Confirmada')
            ->whereNull('deleted_at')
            ->get();


        $clientes = Cliente::whereIn('id_cliente', function ($query) use ($empleado) {
            $query->select('id_cliente')
                ->from('empresa_cliente')
                ->where('id_empresa', $empleado->id_empresa);
        })->get();

        $servicios = Servicio::join('servicios_empleado', 'servicios.id_servicio', '=', 'servicios_empleado.id_servicio')
            ->where('servicios_empleado.id_empleado', Auth::user()->empleado_id)
            ->select('servicios.*')
            ->get();

        $disponibilidad = Disponibilidad_Empleado::where('id_empleado', $empleado->id_empleado)
            ->whereNull('deleted_at')
            ->get();

        return view('cita.agendaEmpleado', ['citas' => $citas, 'citasConfirmadas' => $citasConfirmadas, 'clientes' => $clientes, 'servicios' => $servicios, 'disponibilidad' => $disponibilidad]);
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
        $nuevoTimestamp = strtotime("+" . $servicio['duracion'] . " minutes", $timestamp);

        $timestampFin = date('Y-m-d H:i:s', $nuevoTimestamp);

        // Verificar si existe alguna otra cita no eliminada y confirmada en esa fecha y hora
        $existeCita = Cita::where('id_empleado', Auth::user()->empleado_id)
            ->where('id_empresa', $empleadoDatos->id_empresa)
            ->where('fecha_inicio', '<', $timestampFin)
            ->where('fecha_fin', '>', $timestampInicio)
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

        $horaCita = DateTime::createFromFormat('H:i', $datos['hora']);
        $horaInicio = DateTime::createFromFormat('H:i:s', $disponibilidadDia->hora_inicio);
        $horaFin = DateTime::createFromFormat('H:i:s', $disponibilidadDia->hora_fin);

        if (!$disponibilidadDia || $horaCita < $horaInicio || $horaCita > $horaFin) {
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

        $pdf = PDF::loadView('pdf.cita', compact('cita'));

        $pdf_content = $pdf->output();

        $cliente = Cliente::where('id_cliente', $datos['cliente_id'])
            ->first();

        $asunto = "Cita" . $servicio['cod'] . " - $cliente->nif";
        $email = "nicoadrianx42x@gmail.com";

        Mail::send('email.citaPDF', ['cita' => $cita, 'asunto' => $asunto, 'cliente' => $cliente], function ($message) use ($email, $pdf_content, $asunto) {
            $message->from('easyappointments@empresa.com', 'Easyappointments');
            $message->to($email)
                ->subject($asunto)
                ->attachData($pdf_content, "$asunto.pdf");
        });

        session()->flash('message', 'Cita programada correctamente.');

        return back();
    }

    public function generarCitaPdf($id)
    {
        $cita = Cita::where('id_cita', $id)
            ->whereNull('deleted_at') // Verificar que no esté borrada (soft delete)
            ->first();

        $pdf = PDF::loadView('pdf.cita', compact('cita'));

        return $pdf->download('Cita-' . $cita->servicio->cod . '-' . $cita->cliente->nif . '.pdf');
    }

    public function modificarCitaE($id)
    {
        $empleadoDatos = Empleado::where('id_empleado', Auth::user()->empleado_id)->first();

        session()->flash('modificar');

        $datos = request()->validate([
            'cliente_id' => 'required',
            'estado' => 'required',
            'servicio_obj' => 'required',
            'modificarFechayHora' => '',
        ]);

        $servicio = json_decode($datos['servicio_obj'], true);

        if ($datos['modificarFechayHora'] == 'si') {

            $datos = request()->validate([
                'cliente_id' => 'required',
                'estado' => 'required',
                'servicio_obj' => 'required',
                'fecha' => 'required',
                'hora' => 'required',
                'modificarFechayHora' => '',
            ]);

            // Crear un timestamp a partir de la fecha y hora
            $timestamp = strtotime($datos['fecha'] . ' ' . $datos['hora']);

            // Formatear el timestamp según tus necesidades
            $timestampInicio = date('Y-m-d H:i:s', $timestamp);

            // Sumar 40 minutos al timestamp
            $nuevoTimestamp = strtotime("+" . $servicio['duracion'] . " minutes", $timestamp);

            $timestampFin = date('Y-m-d H:i:s', $nuevoTimestamp);

            $cita = Cita::where('id_cita', $id)
                ->whereNull('deleted_at')
                ->first();


            // Verificar si existe alguna otra cita no eliminada y confirmada en esa fecha y hora
            $existeCita = Cita::where('id_empleado', Auth::user()->empleado_id)
                ->where('id_empresa', $empleadoDatos->id_empresa)
                ->where('fecha_inicio', '<', $timestampFin)
                ->where('fecha_fin', '>', $timestampInicio)
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

            $horaCita = DateTime::createFromFormat('H:i', $datos['hora']);
            $horaInicio = DateTime::createFromFormat('H:i:s', $disponibilidadDia->hora_inicio);
            $horaFin = DateTime::createFromFormat('H:i:s', $disponibilidadDia->hora_fin);

            if (!$disponibilidadDia || $horaCita < $horaInicio || $horaCita > $horaFin) {
                session()->flash('error', 'La hora de la cita está fuera del horario disponible del trabajador');
                return back();
            }

            $cita = Cita::where('id_cita', $id)
                ->whereNull('deleted_at'); // Verificar que no esté borrada (soft delete);

            $cita->update([
                'id_cliente' => $datos['cliente_id'],
                'id_empleado' => Auth::user()->empleado_id,
                'id_servicio' => data_get($servicio, 'id_servicio'),
                'fecha_inicio' => $timestampInicio,
                'fecha_fin' => $timestampFin,
                'status' => $datos['estado'],
            ]);
        } else {

            $cita = Cita::where('id_cita', $id)
                ->whereNull('deleted_at'); // Verificar que no esté borrada (soft delete);

            $cita->update([
                'id_cliente' => $datos['cliente_id'],
                'id_empleado' => Auth::user()->empleado_id,
                'id_servicio' => data_get($servicio, 'id_servicio'),
                'status' => $datos['estado'],
            ]);
        }

        $cita = Cita::where('id_cita', $id)
            ->whereNull('deleted_at')
            ->first();

        $pdf = PDF::loadView('pdf.cita', compact('cita'));

        $pdf_content = $pdf->output();

        $cliente = Cliente::where('id_cliente', $datos['cliente_id'])
            ->first();

        $asunto = "Cita" . $servicio['cod'] . " - $cliente->nif";
        $email = "nicoadrianx42x@gmail.com";

        Mail::send('email.citaModificadaPDF', ['cita' => $cita, 'asunto' => $asunto, 'cliente' => $cliente], function ($message) use ($email, $pdf_content, $asunto) {
            $message->from('easyappointments@empresa.com', 'Easyappointments');
            $message->to($email)
                ->subject($asunto)
                ->attachData($pdf_content, "$asunto.pdf");
        });

        session()->flash('message', "$asunto modificada correctamente.");

        return back();
    }

    public function agendaEmpleadoEmpresa($id)
    {
        $empleado = Empleado::where('id_empleado', $id)->first();

        $citas = Cita::where('id_empresa', $empleado->id_empresa)
            ->where('id_empleado', $id)
            ->whereNull('deleted_at')
            ->get();

        $citasConfirmadas = Cita::where('id_empresa', $empleado->id_empresa)
            ->where('id_empleado', $id)
            ->where('status', 'Confirmada')
            ->whereNull('deleted_at')
            ->get();


        $clientes = Cliente::whereIn('id_cliente', function ($query) use ($empleado) {
            $query->select('id_cliente')
                ->from('empresa_cliente')
                ->where('id_empresa', $empleado->id_empresa);
        })->get();

        $servicios = Servicio::join('servicios_empleado', 'servicios.id_servicio', '=', 'servicios_empleado.id_servicio')
            ->where('servicios_empleado.id_empleado', $id)
            ->select('servicios.*')
            ->get();

        $disponibilidad = Disponibilidad_Empleado::where('id_empleado', $empleado->id_empleado)
            ->whereNull('deleted_at')
            ->get();

        return view('cita.agendaEmpleadoEmpresa', ['citas' => $citas, 'citasConfirmadas' => $citasConfirmadas, 'clientes' => $clientes, 'servicios' => $servicios, 'disponibilidad' => $disponibilidad, 'id' => $id]);
    }

    public function nuevaCitaE_Empresa($id)
    {
        $empleadoDatos = Empleado::where('id_empleado', $id)->first();

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
        $nuevoTimestamp = strtotime("+" . $servicio['duracion'] . " minutes", $timestamp);

        $timestampFin = date('Y-m-d H:i:s', $nuevoTimestamp);

        // Verificar si existe alguna otra cita no eliminada y confirmada en esa fecha y hora
        $existeCita = Cita::where('id_empleado', $id)
            ->where('id_empresa', $empleadoDatos->id_empresa)
            ->where('fecha_inicio', '<', $timestampFin)
            ->where('fecha_fin', '>', $timestampInicio)
            ->whereNull('deleted_at') // Verificar que no esté borrada (soft delete)
            ->where('status', ['Confirmada', 'Pendiente']) // Verificar que esté confirmada
            ->exists();

        if ($existeCita) {
            session()->flash('error', 'No es posible confirmar la cita, ya que hay otra cita programada para ese horario.');
            return back();
        }

        $diaSemanaCita = date('w', $timestamp);

        $disponibilidadDia = Disponibilidad_Empleado::where('dia_semana', $diaSemanaCita)
            ->where('id_empleado', $id)
            ->first();

        $horaCita = DateTime::createFromFormat('H:i', $datos['hora']);
        $horaInicio = DateTime::createFromFormat('H:i:s', $disponibilidadDia->hora_inicio);
        $horaFin = DateTime::createFromFormat('H:i:s', $disponibilidadDia->hora_fin);

        if (!$disponibilidadDia || $horaCita < $horaInicio || $horaCita > $horaFin) {
            session()->flash('error', 'La hora de la cita está fuera del horario disponible del trabajador');
            return back();
        }

        $cita = Cita::create([
            'id_cliente' => $datos['cliente_id'],
            'id_empresa' => $empleadoDatos->id_empresa,
            'id_empleado' => $id,
            'id_servicio' => data_get($servicio, 'id_servicio'),
            'fecha_inicio' => $timestampInicio,
            'fecha_fin' => $timestampFin,
            'status' => "Confirmada",
        ]);

        $pdf = PDF::loadView('pdf.cita', compact('cita'));

        $pdf_content = $pdf->output();

        $cliente = Cliente::where('id_cliente', $datos['cliente_id'])
            ->first();

        $asunto = "Cita" . $servicio['cod'] . " - $cliente->nif";
        $email = "nicoadrianx42x@gmail.com";

        Mail::send('email.citaPDF', ['cita' => $cita, 'asunto' => $asunto, 'cliente' => $cliente], function ($message) use ($email, $pdf_content, $asunto) {
            $message->from('easyappointments@empresa.com', 'Easyappointments');
            $message->to($email)
                ->subject($asunto)
                ->attachData($pdf_content, "$asunto.pdf");
        });

        session()->flash('message', 'Cita programada correctamente.');

        return back();
    }

    public function modificarCitaE_Empresa($id, $idEmpleado)
    {
        $empleadoDatos = Empleado::where('id_empleado', $idEmpleado)->first();

        session()->flash('modificar');

        $datos = request()->validate([
            'cliente_id' => 'required',
            'estado' => 'required',
            'servicio_obj' => 'required',
            'modificarFechayHora' => '',
        ]);

        $servicio = json_decode($datos['servicio_obj'], true);

        if ($datos['modificarFechayHora'] == 'si') {

            $datos = request()->validate([
                'cliente_id' => 'required',
                'estado' => 'required',
                'servicio_obj' => 'required',
                'fecha' => 'required',
                'hora' => 'required',
                'modificarFechayHora' => '',
            ]);

            // Crear un timestamp a partir de la fecha y hora
            $timestamp = strtotime($datos['fecha'] . ' ' . $datos['hora']);

            // Formatear el timestamp según tus necesidades
            $timestampInicio = date('Y-m-d H:i:s', $timestamp);

            // Sumar 40 minutos al timestamp
            $nuevoTimestamp = strtotime("+" . $servicio['duracion'] . " minutes", $timestamp);

            $timestampFin = date('Y-m-d H:i:s', $nuevoTimestamp);

            $cita = Cita::where('id_cita', $id)
                ->whereNull('deleted_at')
                ->first();


            // Verificar si existe alguna otra cita no eliminada y confirmada en esa fecha y hora
            $existeCita = Cita::where('id_empleado', $idEmpleado)
                ->where('id_empresa', $empleadoDatos->id_empresa)
                ->where('fecha_inicio', '<', $timestampFin)
                ->where('fecha_fin', '>', $timestampInicio)
                ->whereNull('deleted_at') // Verificar que no esté borrada (soft delete)
                ->where('status', ['Confirmada', 'Pendiente']) // Verificar que esté confirmada
                ->exists();

            if ($existeCita) {
                session()->flash('error', 'No es posible confirmar la cita, ya que hay otra cita programada para ese horario.');
                return back();
            }

            $diaSemanaCita = date('w', $timestamp);

            $disponibilidadDia = Disponibilidad_Empleado::where('dia_semana', $diaSemanaCita)
                ->where('id_empleado', $idEmpleado)
                ->first();

            $horaCita = DateTime::createFromFormat('H:i', $datos['hora']);
            $horaInicio = DateTime::createFromFormat('H:i:s', $disponibilidadDia->hora_inicio);
            $horaFin = DateTime::createFromFormat('H:i:s', $disponibilidadDia->hora_fin);

            if (!$disponibilidadDia || $horaCita < $horaInicio || $horaCita > $horaFin) {
                session()->flash('error', 'La hora de la cita está fuera del horario disponible del trabajador');
                return back();
            }

            $cita = Cita::where('id_cita', $id)
                ->whereNull('deleted_at'); // Verificar que no esté borrada (soft delete);

            $cita->update([
                'id_cliente' => $datos['cliente_id'],
                'id_empleado' => $idEmpleado,
                'id_servicio' => data_get($servicio, 'id_servicio'),
                'fecha_inicio' => $timestampInicio,
                'fecha_fin' => $timestampFin,
                'status' => $datos['estado'],
            ]);
        } else {

            $cita = Cita::where('id_cita', $id)
                ->whereNull('deleted_at'); // Verificar que no esté borrada (soft delete);

            $cita->update([
                'id_cliente' => $datos['cliente_id'],
                'id_empleado' => $idEmpleado,
                'id_servicio' => data_get($servicio, 'id_servicio'),
                'status' => $datos['estado'],
            ]);
        }

        $cita = Cita::where('id_cita', $id)
            ->whereNull('deleted_at')
            ->first();

        $pdf = PDF::loadView('pdf.cita', compact('cita'));

        $pdf_content = $pdf->output();

        $cliente = Cliente::where('id_cliente', $datos['cliente_id'])
            ->first();

        $asunto = "Cita" . $servicio['cod'] . " - $cliente->nif";
        $email = "nicoadrianx42x@gmail.com";

        Mail::send('email.citaModificadaPDF', ['cita' => $cita, 'asunto' => $asunto, 'cliente' => $cliente], function ($message) use ($email, $pdf_content, $asunto) {
            $message->from('easyappointments@empresa.com', 'Easyappointments');
            $message->to($email)
                ->subject($asunto)
                ->attachData($pdf_content, "$asunto.pdf");
        });

        session()->flash('message', "$asunto modificada correctamente.");

        return back();
    }

    public function nuevaCita_Cliente()
    {

        session()->flash('crear');

        $datos = request()->validate([
            'empleado_id' => 'required',
            'servicio_id' => 'required',
            'fecha' => 'required',
            'hora' => 'required',
        ]);

        $empleadoDatos = Empleado::where('id_empleado', $datos['empleado_id'])->first();

        $servicio = Servicio::where('id_servicio', $datos['servicio_id'])->first();

        // Crear un timestamp a partir de la fecha y hora
        $timestamp = strtotime($datos['fecha'] . ' ' . $datos['hora']);

        // Formatear el timestamp según tus necesidades
        $timestampInicio = date('Y-m-d H:i:s', $timestamp);

        // Sumar 40 minutos al timestamp
        $nuevoTimestamp = strtotime("+" . $servicio->duracion . " minutes", $timestamp);

        $timestampFin = date('Y-m-d H:i:s', $nuevoTimestamp);

        // Verificar si existe alguna otra cita no eliminada y confirmada en esa fecha y hora
        $existeCita = Cita::where('id_empleado', $datos['empleado_id'])
            ->where('id_empresa', $empleadoDatos->id_empresa)
            ->where('fecha_inicio', '<', $timestampFin)
            ->where('fecha_fin', '>', $timestampInicio)
            ->whereNull('deleted_at') // Verificar que no esté borrada (soft delete)
            ->where('status', ['Confirmada', 'Pendiente']) // Verificar que esté confirmada
            ->exists();

        if ($existeCita) {
            session()->flash('error', 'No es posible confirmar la cita, ya que hay otra cita programada para ese horario.');
            return back();
        }

        $diaSemanaCita = date('w', $timestamp);

        $disponibilidadDia = Disponibilidad_Empleado::where('dia_semana', $diaSemanaCita)
            ->where('id_empleado', $datos['empleado_id'])
            ->first();

        $horaCita = DateTime::createFromFormat('H:i', $datos['hora']);
        $horaInicio = DateTime::createFromFormat('H:i:s', $disponibilidadDia->hora_inicio);
        $horaFin = DateTime::createFromFormat('H:i:s', $disponibilidadDia->hora_fin);

        if (!$disponibilidadDia || $horaCita < $horaInicio || $horaCita > $horaFin) {
            session()->flash('error', 'La hora de la cita está fuera del horario disponible del trabajador');
            return back();
        }

        $cita = Cita::create([
            'id_cliente' => Auth::user()->cliente_id,
            'id_empresa' => $empleadoDatos->id_empresa,
            'id_empleado' => $datos['empleado_id'],
            'id_servicio' => data_get($servicio, 'id_servicio'),
            'fecha_inicio' => $timestampInicio,
            'fecha_fin' => $timestampFin,
            'status' => "Confirmada",
        ]);

        $pdf = PDF::loadView('pdf.cita', compact('cita'));

        $pdf_content = $pdf->output();

        $cliente = Cliente::where('id_cliente', Auth::user()->cliente_id)
            ->first();

        $asunto = "Cita" . $servicio['cod'] . " - $cliente->nif";
        $email = "nicoadrianx42x@gmail.com";

        Mail::send('email.citaPDF', ['cita' => $cita, 'asunto' => $asunto, 'cliente' => $cliente], function ($message) use ($email, $pdf_content, $asunto) {
            $message->from('easyappointments@empresa.com', 'Easyappointments');
            $message->to($email)
                ->subject($asunto)
                ->attachData($pdf_content, "$asunto.pdf");
        });

        session()->flash('message', 'Cita programada correctamente.');

        return back();
    }

    public function listarCitasPasadas()
    {

        $cliente = Cliente::where('id_cliente', Auth::user()->cliente_id)->first();

        $fechaActual = Carbon::now(); // Obtener la fecha y hora actual

        $citas = Cita::where('id_cliente', Auth::user()->cliente_id)
            ->whereNull('deleted_at')
            ->whereDate('fecha_inicio', '<', $fechaActual->toDateString())
            ->orWhere(function ($query) use ($fechaActual) {
                $query->whereDate('fecha_inicio', '=', $fechaActual->toDateString())
                    ->whereTime('fecha_inicio', '<', $fechaActual->toTimeString());
            })
            ->orWhere('status', 'Cancelada')
            ->get();

        return view('cita.citasPasadas', ['$cliente' => $cliente, 'citas' => $citas]);
    }

    public function listarCitasPendientes()
    {

        $cliente = Cliente::where('id_cliente', Auth::user()->cliente_id)->first();

        $fechaActual = Carbon::now(); // Obtener la fecha y hora actual

        $citas = Cita::where('id_cliente', Auth::user()->cliente_id)
            ->where('status', 'Confirmada')
            ->whereNull('deleted_at')
            ->whereDate('fecha_inicio', '>', $fechaActual->toDateString())
            ->orWhere(function ($query) use ($fechaActual) {
                $query->whereDate('fecha_inicio', '=', $fechaActual->toDateString())
                    ->whereTime('fecha_inicio', '>', $fechaActual->toTimeString());
            })
            ->get();

        return view('cita.citasPendientes', ['$cliente' => $cliente, 'citas' => $citas]);
    }

    public function cancelarCita($id)
    {
        $cita = Cita::where('id_cita', $id)
            ->whereNull('deleted_at')
            ->first();

        $cita->update([
            'status' => "Cancelada",
        ]);

        session()->flash('message', 'Cita cancelada correctamente.');

        return back();
    }
}
