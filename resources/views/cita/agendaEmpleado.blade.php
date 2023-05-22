@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Agenda
@endsection

@section('linkScript')

    <script src="{{ asset('js/fullcalendar@6.1.6.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                headerToolbar: {
                    left: 'prev,today,next',
                    center: 'title',
                    right: 'dayGridMonth,listDay,listWeek'
                },

                // customize the button names,
                // otherwise they'd all just say "list"
                views: {
                    listDay: {
                        buttonText: 'list day'
                    },
                    listWeek: {
                        buttonText: 'list week'
                    },
                    dayGridMonth: {
                        buttonText: 'month'
                    }
                },

                initialView: 'listDay',
                initialDate: new Date(new Date().getTime() - (new Date().getTimezoneOffset() * 60000))
                    .toISOString().slice(0, 10),
                navLinks: true,
                editable: true,
                dayMaxEvents: true,
                events: [
                    @foreach ($citas as $cita)
                        {
                            title: '#{{ $cita->servicio->nombre }} {{ $cita->cliente->apellidos }}',
                            start: '{{ $cita->fecha_inicio }}',
                            end: '{{ $cita->fecha_fin }}',
                            extendedProps: {
                                id: '{{ $cita->id_cita }}',
                                empresa: '{{ $cita->empresa }}',
                                empleado: '{{ $cita->empleado }}',
                                servicio: '{{ $cita->servicio }}',
                                cliente: '{{ $cita->cliente }}',
                                status: '{{ $cita->status }}',
                            },
                            color: @if ($cita->status == 'Confirmada')
                                'green'
                            @elseif ($cita->status == 'Cancelada')
                                'red'
                            @else
                                'gray'
                            @endif ,
                        },
                    @endforeach
                ],

                eventClick: function(info) {
                    // obtener la información de la cita
                    var empresa = obtenerPropiedadDeEvento(info.event, 'empresa');
                    var empleado = obtenerPropiedadDeEvento(info.event, 'empleado');
                    var servicio = obtenerPropiedadDeEvento(info.event, 'servicio');
                    var cliente = obtenerPropiedadDeEvento(info.event, 'cliente');
                    //aplicar
                    $('#nombre_empresa').text(empresa.nombre);
                    $('#cif').text(empresa.cif);
                    $('#telefono_empresa').text(empresa.telefono);
                    $('#nif_empleado').text(empleado.nif);
                    $('#nombre_empleado').text(empleado.nombre + " " + empleado.apellidos);
                    $('#cargo').text(empleado.cargo);
                    $('#cod').text(servicio.cod);
                    $('#nombre_servicio').text(servicio.nombre);
                    $('#precio').text(servicio.precio);
                    $('#nif_cliente').text(cliente.nif);
                    $('#nombre_cliente').text(cliente.nombre + " " + cliente.apellidos);
                    $('#fecha_inicio').text(obtenerFechaFormateada(info.event.start));
                    $('#fecha_fin').text(obtenerFechaFormateada(info.event.end));
                    $('#estado').text(info.event.extendedProps.status);

                    //mostrar
                    $('#detallesModal').modal('show');
                    $('.fc-popover').hide();
                }

            });

            calendar.render();
        });

        function obtenerPropiedadDeEvento(evento, propiedad) {
            var empresaString = $('<textarea />').html(evento.extendedProps[propiedad]).text();
            var objeto = JSON.parse(empresaString);
            return objeto;
        }

        function obtenerFechaFormateada(fecha) {
            return new Date(fecha).toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }
    </script>

    <style>
        #calendar {
            height: 75vh;
            /* el calendario ocupará el 60% de la altura de la pantalla */
        }

        .fc-daygrid-day-number {
            color: black;
            text-decoration: none;
            /* Este estilo desactiva el subrayado de texto que se utiliza para los enlaces hipervínculos */
        }

        .fc-col-header-cell-cushion {
            cursor: default;
            pointer-events: none;
            color: black;
            text-decoration: none;
            /* Este estilo desactiva el subrayado de texto que se utiliza para los enlaces hipervínculos */
        }
    </style>

@endsection

@section('contenido')

    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Programar una nueva cita">
                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#añadirModal">
                        <i class="bi bi-calendar-plus-fill"></i></a></span>
            </div>
            <div class="col text-center">
                <h3>Agenda - {{ Auth::user()->nombre }}</h3>
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

    <div id='calendar'></div>

@endsection


@section('modals')

    <!-- Modal detalles de la cita -->

    <div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="detallesModalLabel"><b>Detalles de la cita</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header text-center">
                                        <h4 class="card-title">Empresa</h4>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Nombre:</strong> <span id="nombre_empresa"></span></p>
                                        <p><strong>CIF:</strong> <span id="cif"></span></p>
                                        <p><strong>Teléfono:</strong> <span id="telefono_empresa"></span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header text-center">
                                        <h4 class="card-title">Empleado</h4>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>NIF:</strong> <span id="nif_empleado"></span></p>
                                        <p><strong>Nombre y apellidos:</strong> <span id="nombre_empleado"></span></p>
                                        <p><strong>Cargo:</strong> <span id="cargo"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header text-center">
                                        <h4 class="card-title">Servicios</h4>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Código:</strong> <span id="cod"></span></p>
                                        <p><strong>Nombre:</strong> <span id="nombre_servicio"></span></p>
                                        <p><strong>Precio:</strong> <span id="precio"></span> €</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header text-center">
                                        <h4 class="card-title">Cliente</h4>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>NIF:</strong> <span id="nif_cliente"></span></p>
                                        <p><strong>Nombre y apellidos:</strong> <span id="nombre_cliente"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header text-center">
                                        <h4 class="card-title">Detalles</h4>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Fecha de inicio:</strong> <span id="fecha_inicio"></span></p>
                                        <p><strong>Fecha de fin:</strong> <span id="fecha_fin"></span></p>
                                        <p><strong>Estado:</strong> <span id="estado"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger mr-auto">Borrar</button>
                    <button type="submit" class="btn btn-warning">Modificar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal añadir empleado -->

    <div class="modal fade" id="añadirModal" tabindex="-1" aria-labelledby="añadirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="añadirModalLabel"><b>Programar cita</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('crearUsuarioEmpleado') }}" method="post">
                        @csrf
                        <div class="row mb-3">

                            <div class="col">
                                <label for="cliente_id" class="form-label">Cliente:</label>
                                <select class="form-select" name="cliente_id" id="cliente_id_mod">
                                    <option value="" disabled selected>Seleccione un cliente</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id_cliente }}"
                                            {{ old('cliente_id') == $cliente->id_cliente ? 'selected' : '' }}>
                                            {{ $cliente->nif }}-{{ $cliente->nombre }} {{ $cliente->apellidos }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('cliente_id') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('cliente_id', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label for="cliente_id" class="form-label">Servicio:</label>
                                <select class="form-select" name="cliente_id" id="cliente_id_mod">
                                    <option value="" disabled selected>Seleccione un servicio</option>
                                    @foreach ($servicios as $servicio)
                                        <option value="{{ $servicio->id_servicio }}"
                                            {{ old('servicio_id') == $servicio->id_servicio ? 'selected' : '' }}>
                                            {{ $servicio->cod }}-{{ $servicio->nombre }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('cliente_id') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('cliente_id', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Fecha</label>
                                <input type="date" class="form-control border border-primary" name="fecha_nacimiento"
                                    @if (old('fecha_nacimiento') && session()->get('crear')) value="{{ old('fecha_nacimiento') }}" @endif>
                                @if ($errors->has('fecha_nacimiento') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('fecha_nacimiento', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Dar de alta</button>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection
