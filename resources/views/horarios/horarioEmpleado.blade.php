@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Horario Semanal
@endsection

@section('linkScript')

    <style>
        #centrar {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #cuerpo {
            margin: 2em;
        }

        tr,
        td,
        th {
            text-align: center;
        }

        @media screen and (max-width: 767px) {

            #tabla-disponibilidad td:nth-child(3),
            #tabla-disponibilidad th:nth-child(3) {
                display: none;
            }
        }
    </style>

@endsection

@section('contenido')
    <div class="container">
        <div class="row align-items-center">
            <div class="col text-center">
                <h1>Horario semanal de {{ $empleado->nombre }} {{ $empleado->apellidos }} - {{ $empleado->nif }}</h1>
            </div>
        </div>
    </div>
    <hr>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    <div class="table-responsive" id="tabla-disponibilidad">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Dia de la semana</th>
                    <th scope="col">Hora de incio</th>
                    <th scope="col">Hora de fin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dias_semana as $index => $dia)
                    <tr>
                        <td>{{ $dia }}</td>
                        @php
                            $dispo_encontrado = false;
                        @endphp
                        @foreach ($disponibilidad as $dispo)
                            @if (isset($dispo->hora_inicio) && ($index + 1) % 7 == $dispo->dia_semana)
                                <td>{{ $dispo->hora_inicio }}</td>
                                <td>{{ $dispo->hora_fin }}</td>
                                @php
                                    $dispo_encontrado = true;
                                    break;
                                @endphp
                            @endif
                        @endforeach
                        @if (!$dispo_encontrado)
                            <td></td>
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('modals')
@endsection
