@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Horario Semanal
@endsection

@section('linkScript')
    <script>
        var dias_semana = [
            'Domingo',
            'Lunes',
            'Martes',
            'Miércoles',
            'Jueves',
            'Viernes',
            'Sábado',
        ];

        $(document).ready(function() {

            //Este evento se activa al abrir el modal de añadir y se encarga de cargar los datos del dia.

            $('#añadirModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var dispo = button.data('dispo');
                $('#añadir-dia_semana').text(dias_semana[dispo]);
                $('#programar-horario-form').submit(function() {
                    var url =
                        "{{ route('programarHorario', ['id' => ':idempleado', 'dia' => ':iddia']) }}";
                    url = url.replace(':idempleado', {{ $empleado->id_empleado }});
                    url = url.replace(':iddia', dispo);
                    $('#programar-horario-form').attr('action', url);
                });
            });

            $('#borrarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var dispo = button.data('dispo');
                console.log(dispo);
                $('#borrar-dia_semana').text(dias_semana[dispo.dia_semana]);
                $('#borrar-dia').text(dias_semana[dispo.dia_semana]);
                $('#borrar-inicio').text(dispo.hora_inicio);
                $('#borrar-fin').text(dispo.hora_fin);
                $('#borrar-horario-form').submit(function() {
                    var url = "{{ route('borrarHorario', ['id' => ':iddisponibilidad']) }}";
                    url = url.replace(':iddisponibilidad', dispo.id_disponibilidad);
                    $('#borrar-horario-form').attr('action', url);
                });
            });

        });

        function modificar(dispo) {
            $('#modificar-dia_semana').text(dias_semana[dispo.dia_semana]);
            $('#hora_inicio_mod').val(dispo.hora_inicio);
            $('#hora_fin_mod').val(dispo.hora_fin);
            $('#modificar-horario-form').submit(function() {
                var url =
                    "{{ route('modificarHorario', ['id' => ':iddisponibilidad']) }}";
                url = url.replace(':iddisponibilidad', dispo.id_disponibilidad);
                $('#modificar-horario-form').attr('action', url);
            });
        }
    </script>

    <!-- Si existe una sesion de crear mostramos el modal nada mas cargar la pagina -->

    @if (session()->get('crear') && !session()->has('error'))
        <script>
            $(document).ready(function() {
                $('#añadirModal').modal('show');
                //Limpiar los mensajes de error al cerrar el modal
                $('#añadirModal').on('hidden.bs.modal', function() {
                    // Limpiar los mensajes de error
                    $('.alert-danger').hide();
                });
            });
        </script>
    @endif

    <!-- Si existe una sesion de modificar mostramos el modal nada mas cargar la pagina -->

    @if (session()->get('modificar'))
        <script>
            $(document).ready(function() {
                $('#modificarModal').modal('show');
                $('#modificar-horario-form').submit(function() {
                    var url =
                        "{{ route('modificarHorario', ['id' => ':iddisponibilidad']) }}";
                    url = url.replace(':iddisponibilidad',
                        {{ old('id_disponibilidad', session('id_disponibilidad')) }});
                    $('#modificar-horario-form').attr('action', url);
                });
                //Limpiar los mensajes de error al cerrar el modal
                $('#modificarModal').on('hidden.bs.modal', function() {
                    // Limpiar los mensajes de error
                    $('.alert-danger').hide();
                });
            });
        </script>
    @endif

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
                    <th scope="col">Opciones</th>
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
                                <td>
                                    <div class="btn-group btn-group-md gap-1">
                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Modificar"
                                            onclick="modificar({{ $dispo }})">
                                            <a class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#modificarModal">
                                                <i class="bi bi-gear-fill"></i></a></span>
                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar">
                                            <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#borrarModal"
                                                data-dispo="{{ $dispo }}">
                                                <i class="bi bi-trash-fill"></i></a></span>
                                    </div>
                                </td>
                                @php
                                    $dispo_encontrado = true;
                                    break;
                                @endphp
                            @endif
                        @endforeach
                        @if (!$dispo_encontrado)
                            <td></td>
                            <td></td>
                            <td>
                                <div class="btn-group btn-group-md gap-1">
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Programar">
                                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#añadirModal"
                                            data-dispo="{{ ($index + 1) % 7 }}">
                                            <i class="bi bi-hourglass"></i></a></span>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('modals')

    <!-- Modal añadir horario -->

    <div class="modal fade" id="añadirModal" tabindex="-1" aria-labelledby="añadirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="añadirModalLabel"><b>Programar horario <span
                                id="añadir-dia_semana"></span></b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="programar-horario-form">
                        @csrf

                        <div class="row mb-3">

                            <div class="col">
                                <label class="form-label">Hora de inicio:</label>
                                <input type="time" class="form-control border border-primary" name="hora_inicio"
                                    @if (old('hora_inicio') && session()->get('crear')) value="{{ old('hora_inicio') }}" @endif>
                                @if ($errors->has('hora_inicio') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('hora_inicio', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">Hora de fin:</label>
                                <input type="time" class="form-control border border-primary" name="hora_fin"
                                    @if (old('hora_fin') && session()->get('crear')) value="{{ old('hora_fin') }}" @endif>
                                @if ($errors->has('hora_fin') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('hora_fin', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Programar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal modificar horario -->

    <div class="modal fade" id="modificarModal" tabindex="-1" aria-labelledby="modificarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="añadirModalLabel"><b>Modificar horario <span
                                id="modificar-dia_semana"></span></b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="modificar-horario-form">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">

                            <div class="col">
                                <label class="form-label">Hora de inicio:</label>
                                <input type="time" class="form-control border border-primary" name="hora_inicio"
                                    id="hora_inicio_mod"
                                    @if (old('hora_inicio') && session()->get('modificar')) value="{{ old('hora_inicio') }}" @endif>
                                @if ($errors->has('hora_inicio') && session()->get('crmodificarear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('hora_inicio', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">Hora de fin:</label>
                                <input type="time" class="form-control border border-primary" name="hora_fin"
                                    id="hora_fin_mod"
                                    @if (old('hora_fin') && session()->get('modificar')) value="{{ old('hora_fin') }}" @endif>
                                @if ($errors->has('hora_fin') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('hora_fin', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Modificar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal borrar Horario -->

    <div class="modal fade" id="borrarModal" tabindex="-1" aria-labelledby="borrarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="borrarModalLabel"><b>Confirmar baja de horario <span
                                id="borrar-dia_semana"></span></b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas borrar este horario?</p>
                    <table class="table">
                        <tr>
                            <th scope="row" class="bg-dark text-light">Dia de la semana</th>
                            <td class="bg-light"><span id="borrar-dia"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Hora de inicio</th>
                            <td><span id="borrar-inicio"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Hora de fin</th>
                            <td><span id="borrar-fin"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" id="borrar-horario-form" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
