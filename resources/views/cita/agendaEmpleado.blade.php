@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Agenda
@endsection

@section('linkScript')

    <script src="{{ asset('js/fullcalendar@6.1.6.js') }}"></script>

    <script>
        var idCitaModificar = 0;
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            //configuracion del full calendar
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                headerToolbar: {
                    left: 'prev,today,next',
                    center: 'title',
                    right: 'dayGridMonth,listDay,listWeek'
                },

                // customize the button names,
                views: {
                    listDay: {
                        buttonText: 'Día'
                    },
                    listWeek: {
                        buttonText: 'Semana'
                    },
                    dayGridMonth: {
                        buttonText: 'Mes'
                    }
                },

                initialView: 'listDay',

                //ponemos como fecha inicial el dia de hoy
                initialDate: new Date(new Date().getTime() - (new Date().getTimezoneOffset() * 60000))
                    .toISOString().slice(0, 10),
                navLinks: true,
                editable: false,
                dayMaxEvents: true,
                events: [
                    //recogida de datos
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
                                cita: '{{ $cita }}',
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

                //evento al hacer clic en una cita donde se abrira el modal de ver detalles
                eventClick: function(info) {
                    // obtener los datos de la cita
                    var empresa = obtenerPropiedadDeEvento(info.event, 'empresa');
                    var empleado = obtenerPropiedadDeEvento(info.event, 'empleado');
                    var servicio = obtenerPropiedadDeEvento(info.event, 'servicio');
                    var cliente = obtenerPropiedadDeEvento(info.event, 'cliente');
                    //$('#boton-modificar-cita').attr('data-cita-unica', "");
                    //$('#boton-modificar-cita').attr('data-cita-unica', info.event.extendedProps.id);
                    idCitaModificar = info.event.extendedProps.id;

                    // cambiar id de descarga pdf
                    var url = "{{ route('citaPDF', ['id' => ':idcita']) }}";
                    url = url.replace(':idcita', info.event.extendedProps.id);
                    $('#descargarPDF').attr('href', url);

                    // poner los datos en el modal
                    $('#detalles_nombre_empresa').text(empresa.nombre);
                    $('#detalles_cif').text(empresa.cif);
                    $('#detalles_telefono_empresa').text(empresa.telefono);
                    $('#detalles_nif_empleado').text(empleado.nif);
                    $('#detalles_nombre_empleado').text(empleado.nombre + " " + empleado.apellidos);
                    $('#detalles_cargo').text(empleado.cargo);
                    $('#detalles_cod').text(servicio.cod);
                    $('#detalles_nombre_servicio').text(servicio.nombre);
                    $('#detalles_precio').text(servicio.precio);
                    $('#detalles_duracion').text(servicio.duracion);
                    $('#detalles_nif_cliente').text(cliente.nif);
                    $('#detalles_nombre_cliente').text(cliente.nombre + " " + cliente.apellidos);
                    $('#detalles_fecha_inicio').text(obtenerFechaFormateada(info.event.start));
                    $('#detalles_fecha_fin').text(obtenerFechaFormateada(info.event.end));
                    $('#detalles_estado').text(info.event.extendedProps.status);

                    // mostrar el modal y ocultar los popover de full calendar
                    $('#detallesModal').modal('show');
                    $('.fc-popover').hide();
                }
            });

            //renderizamos el full calendar
            calendar.render();
        });

        $(document).ready(function() {

            // DESACTIVAR DIAS ANTERIORES A HOY EN EL SELECT DE FECHA

            // Obtener la fecha actual
            var today = new Date().toISOString().split('T')[0];

            // Obtener el campo de fecha por su ID
            var fechaInput = document.getElementById('fecha');

            // Establecer la fecha mínima permitida como hoy
            fechaInput.min = today;

            // Obtener todas las etiquetas <option> dentro del campo de fecha
            var options = fechaInput.getElementsByTagName('option');

            // Recorrer todas las etiquetas <option> y deshabilitar las fechas anteriores a hoy
            for (var i = 0; i < options.length; i++) {
                var date = new Date(options[i].value);

                // Comparar la fecha con la fecha actual
                if (date < today) {
                    options[i].disabled = true;
                }
            }

            //LOGICA PARA EL SELECT DE FECHA Y EL SERVICIOS QUE SI LOS DOS ESTAN SELECCIONADOS
            //PROCEDERAN PRIMERO A MOSTRAR UNA TABLA CON INFO DEL SERVICIO Y A CALCULAR LAS POSIBLES
            //CITAS

            // Obtener los elementos del DOM una vez que el documento esté listo
            var servicioSelect = $('#servicio');
            var fechaInput = $('#fecha');
            var servicioModSelect = $('#servicio_obj_mod');
            var fechaInputMod = $('#fecha_mod');

            //Este evento muestra los datos del servicio al seleccionar en el select
            $('#servicio').change(function() {
                obtenerDisponibilidad();
                var servicioSeleccionado = servicioSelect.val();
                var servicioObjeto = JSON.parse(servicioSeleccionado);
                $('#añadir-info-cod').text(servicioObjeto.cod);
                $('#añadir-info-nombre').text(servicioObjeto.nombre);
                $('#añadir-info-descripcion').text(servicioObjeto.descripcion);
                $('#añadir-info-precio').text(servicioObjeto.precio);
                $('#añadir-info-duracion').text(servicioObjeto.duracion);
                $("#tablaDatosServicios").fadeIn(200);
            });

            //Este evento muestra los datos del servicio_obj_mod al seleccionar en el select
            $('#servicio_obj_mod').change(function() {
                obtenerDisponibilidad();
                var servicioSeleccionado = servicioModSelect.val();
                var servicioObjeto = JSON.parse(servicioSeleccionado);
                $('#mod-info-cod').text(servicioObjeto.cod);
                $('#mod-info-nombre').text(servicioObjeto.nombre);
                $('#mod-info-descripcion').text(servicioObjeto.descripcion);
                $('#mod-info-precio').text(servicioObjeto.precio);
                $('#mod-info-duracion').text(servicioObjeto.duracion);
                $("#tablaDatosServicios").fadeIn(200);
            });

            fechaInput.on('change', obtenerDisponibilidad);
            fechaInputMod.on('change', obtenerDisponibilidad);

            //funcion que cmprueba si hemos seleccionado tanto un servicio como una fecha y recoge ciertos datos
            function obtenerDisponibilidad() {
                // Obtén el botón por su clase o cualquier otro selector adecuado
                var boton = $('.btn-primary');

                // Accede al valor del atributo 'data-disponibilidad'
                var disponibilidad = boton.data('disponibilidad');
                // Accede al valor del atributo 'data-disponibilidad'
                var citas = boton.data('citas');

                var servicioSeleccionado = servicioSelect.val();
                var fechaSeleccionada = fechaInput.val();

                var servicioSeleccionadoMod = servicioModSelect.val();
                var fechaSeleccionadaMod = fechaInputMod.val();

                // Verificar si ambos valores están seleccionados
                if (servicioSeleccionadoMod && fechaSeleccionadaMod) {
                    var servicioObjetoMod = JSON.parse(servicioSeleccionadoMod);

                    var nombreSelect = "select_hora_mod";
                    //llamamos a la funcion
                    actualizarSelectHora(fechaSeleccionadaMod, servicioObjetoMod, disponibilidad, citas,
                        nombreSelect)
                }

                // Verificar si ambos valores están seleccionados
                if (servicioSeleccionado && fechaSeleccionada) {
                    // Realizar la petición AJAX o cualquier otra acción que desees realizar
                    var servicioObjeto = JSON.parse(servicioSeleccionado);

                    var nombreSelect = "select_hora";

                    //llamamos a la funcion
                    actualizarSelectHora(fechaSeleccionada, servicioObjeto, disponibilidad, citas, nombreSelect)
                }
            }

            //FUNCION QUE COMPRUEBA LAS CITAS DISPONIBLES

            function actualizarSelectHora(fechaSeleccionada, servicio, disponibilidadEmpleado, citasActivas,
                nombreSelect) {

                // Obtener la duración del servicio en minutos
                var duracionServicio = servicio.duracion;

                // Obtener la fecha de inicio y fecha de fin del empleado para el día de la semana seleccionado
                var fechaSeleccionadaObjeto = new Date(fechaSeleccionada);

                // Obtener el día de la semana (0: domingo, 1: lunes, ..., 6: sábado)
                var diaSemana = fechaSeleccionadaObjeto.getDay();
                var disponibilidadDia = disponibilidadEmpleado.filter(function(elemento) {
                    return elemento.dia_semana === diaSemana.toString();
                });

                if (disponibilidadDia.length != 0) {

                    var horaInicioEmpleado = new Date(fechaSeleccionadaObjeto.toDateString() + ' ' +
                        disponibilidadDia[
                            0].hora_inicio);
                    var horaFinEmpleado = new Date(fechaSeleccionadaObjeto.toDateString() + ' ' + disponibilidadDia[
                            0]
                        .hora_fin);

                    // Crear un arreglo para almacenar las opciones del select
                    var opcionesSelect = [];

                    // Iterar en intervalos de duración del servicio dentro de los límites del empleado
                    var horaActual = horaInicioEmpleado;
                    while (horaActual <= horaFinEmpleado) {
                        // Verificar si la hora actual está disponible
                        var horaOcupada = false;

                        // Verificar si la hora actual coincide exactamente con la hora de inicio de una cita existente
                        for (var i = 0; i < citasActivas.length; i++) {
                            var cita = citasActivas[i];
                            var horaInicioCita = new Date(cita.fecha_inicio);
                            var horaFinCita = new Date(cita.fecha_fin);

                            // Verificar si la hora actual se superpone con una cita existente
                            if (horaActual >= horaInicioCita && horaActual < horaFinCita) {
                                horaOcupada = true;
                                break;
                            }

                            // Verificar si la hora actual se encuentra dentro del tiempo de duración de la cita existente
                            if (horaActual.getTime() + duracionServicio * 60000 > horaInicioCita.getTime() &&
                                horaActual.getTime() + duracionServicio * 60000 <= horaFinCita.getTime()) {
                                horaOcupada = true;
                                break;
                            }
                        }

                        // Verificar si la hora actual más la duración del servicio excede el horario de finalización del empleado
                        if (horaActual.getTime() + duracionServicio * 60000 > horaFinEmpleado.getTime()) {
                            horaOcupada = true;
                        }

                        // Si la hora actual no está ocupada, agregarla como opción al select
                        if (!horaOcupada) {
                            var opcion = {
                                hora: horaActual.toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }),
                                timestamp: horaActual.getTime()
                            };
                            opcionesSelect.push(opcion);
                        }

                        // Avanzar al siguiente intervalo de duración del servicio
                        horaActual.setTime(horaActual.getTime() + duracionServicio * 60000);
                    }

                    // Actualizar el select de horas con las opciones generadas
                    var selectHora = document.getElementById(nombreSelect);
                    if (selectHora) {
                        selectHora.innerHTML = ''; // Limpiar opciones anteriores
                    }

                    // Agregar las opciones al select
                    opcionesSelect.forEach(function(opcion) {
                        var option = document.createElement('option');
                        option.value = opcion.hora;
                        option.text = opcion.hora;
                        selectHora.appendChild(option);
                    });

                } else {
                    var selectHora = document.getElementById(nombreSelect);
                    if (selectHora) {
                        selectHora.innerHTML = ''; // Limpiar opciones anteriores
                    }
                    var option = document.createElement('option');
                    option.text = "Seleccione otro día";
                    selectHora.appendChild(option);
                }
            }

        });

        //funcion para poner hacer objeto.atributo
        function obtenerPropiedadDeEvento(evento, propiedad) {
            var empresaString = $('<textarea />').html(evento.extendedProps[propiedad]).text();
            var objeto = JSON.parse(empresaString);
            return objeto;
        }

        //funcion para formatear fecha
        function obtenerFechaFormateada(fecha) {
            return new Date(fecha).toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function modificar() {
            let id = idCitaModificar;
            var boton = $('#boton-modificar-cita');
            var citas = boton.data('citas-all');
            var cita = citas.find(function(cita) {
                return cita.id_cita == id;
            });
            $('#titulo_mod').text(" - " + cita.servicio.cod + "-" + cita.cliente.nif)
            $("#cliente_id_mod option[value='" + cita.id_cliente + "']").prop('selected', true);
            $("#estado option[value='" + cita.status + "']").prop('selected', true);
            $("#servicio_obj_mod option[id='" + cita.servicio.id_servicio + "']").prop('selected', true);
            var partes = cita.fecha_inicio.split(" ");

            $("#fecha_mod").val(partes[0]);
            $("#fecha_mod").trigger('change');

            // Obtener la fecha actual
            var today = new Date().toISOString().split('T')[0];

            // Obtener el campo de fecha por su ID
            var fechaInput = document.getElementById('fecha_mod');

            // Establecer la fecha mínima permitida como hoy
            fechaInput.min = today;

            // Obtener todas las etiquetas <option> dentro del campo de fecha
            var options = fechaInput.getElementsByTagName('option');

            // Recorrer todas las etiquetas <option> y deshabilitar las fechas anteriores a hoy
            for (var i = 0; i < options.length; i++) {
                var date = new Date(options[i].value);

                // Comparar la fecha con la fecha actual
                if (date < today) {
                    options[i].disabled = true;
                }
            }

            //$("#select_hora_mod option[value='" + fecha[1] + "']").prop('selected', true);

            $('#mod-info-cod').text(cita.servicio.cod);
            $('#mod-info-nombre').text(cita.servicio.nombre);
            $('#mod-info-descripcion').text(cita.servicio.descripcion);
            $('#mod-info-precio').text(cita.servicio.precio);
            $('#mod-info-duracion').text(cita.servicio.duracion);
            $("#tablaDatosServiciosMod").fadeIn(200);


            $('#modificar-cita-form').submit(function() {
                var url = "{{ route('modificarCitaE', ['id' => ':idcita']) }}";
                url = url.replace(':idcita', cita.id_cita);
                $('#modificar-cita-form').attr('action', url);
            });
        }
    </script>

    <!-- Si existe una sesion de crear mostramos el modal nada mas cargar la pagina -->

    @if (session()->get('crear'))
        <script>
            $(document).ready(function() {
                $('#añadirModal').modal('show');
                //Limpiar los mensajes de error al cerrar el modal
                $('#añadirModal').on('hidden.bs.modal', function() {
                    // Limpiar los mensajes de error
                    $('.error-validacion').hide();
                });
            });
        </script>
    @endif

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
                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#añadirModal"
                        data-disponibilidad="{{ $disponibilidad }}" data-citas="{{ $citasConfirmadas }}">
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
                    <h5 class="modal-title" id="detallesModalLabel">
                        <a id="descargarPDF" href="" class="btn btn-danger">
                            <i class="bi bi-filetype-pdf"></i></a>&nbsp;
                        <b>Detalles de la cita</b>
                    </h5>
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
                                        <p><strong>Nombre:</strong> <span id="detalles_nombre_empresa"></span></p>
                                        <p><strong>CIF:</strong> <span id="detalles_cif"></span></p>
                                        <p><strong>Teléfono:</strong> <span id="detalles_telefono_empresa"></span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header text-center">
                                        <h4 class="card-title">Empleado</h4>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>NIF:</strong> <span id="detalles_nif_empleado"></span></p>
                                        <p><strong>Nombre y apellidos:</strong> <span id="detalles_nombre_empleado"></span>
                                        </p>
                                        <p><strong>Cargo:</strong> <span id="detalles_cargo"></span></p>
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
                                        <p><strong>Código:</strong> <span id="detalles_cod"></span></p>
                                        <p><strong>Nombre:</strong> <span id="detalles_nombre_servicio"></span></p>
                                        <p><strong>Precio:</strong> <span id="detalles_precio"></span> €</p>
                                        <p><strong>Duración:</strong> <span id="detalles_duracion"></span> minutos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header text-center">
                                        <h4 class="card-title">Cliente</h4>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>NIF:</strong> <span id="detalles_nif_cliente"></span></p>
                                        <p><strong>Nombre y apellidos:</strong> <span id="detalles_nombre_cliente"></span>
                                        </p>
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
                                        <p><strong>Fecha de inicio:</strong> <span id="detalles_fecha_inicio"></span></p>
                                        <p><strong>Fecha de fin:</strong> <span id="detalles_fecha_fin"></span></p>
                                        <p><strong>Estado:</strong> <span id="detalles_estado"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    {{-- <button type="submit" class="btn btn-danger mr-auto">Cancelar <i
                            class="bi bi-calendar-x-fill"></i></button> --}}
                    <button type="button" class="btn btn-warning" id="boton-modificar-cita" data-bs-toggle="modal"
                        data-bs-target="#modificarModal" data-cita-unica="" data-citas-all="{{ $citas }}"
                        onclick="modificar()">Modificar <i class="bi bi-pen-fill"></i></button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal añadir cita -->

    <div class="modal fade" id="añadirModal" tabindex="-1" aria-labelledby="añadirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="añadirModalLabel"><b>Programar cita</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('nuevaCitaE') }}" method="post">
                        @csrf
                        <div class="row mb-3">

                            <div class="col">
                                <label for="cliente_id" class="form-label">Cliente:</label>
                                <select class="form-select" name="cliente_id">
                                    <option value="" disabled selected>Seleccione un cliente</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id_cliente }}"
                                            {{ old('cliente_id') == $cliente->id_cliente ? 'selected' : '' }}>
                                            {{ $cliente->nif }}-{{ $cliente->nombre }} {{ $cliente->apellidos }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('cliente_id') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1 error-validacion">
                                        {!! $errors->first('cliente_id', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label for="servicio_obj" class="form-label">Servicio:</label>
                                <select class="form-select" name="servicio_obj" id="servicio">
                                    <option value="" disabled selected>Seleccione un servicio</option>
                                    @foreach ($servicios as $servicio)
                                        <option value="{{ $servicio }}"
                                            {{ old('servicio_obj') == $servicio->id_servicio ? 'selected' : '' }}>
                                            {{ $servicio->cod }} - {{ $servicio->nombre }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('servicio_obj') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1 error-validacion">
                                        {!! $errors->first('servicio_obj', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Fecha:</label>
                                <input type="date" class="form-control border border-primary" name="fecha"
                                    id="fecha" @if (old('fecha') && session()->get('crear')) value="{{ old('fecha') }}" @endif>
                                @if ($errors->has('fecha') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1 error-validacion">
                                        {!! $errors->first('fecha', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label for="hora" class="form-label">Hora:</label>
                                <select class="form-select" name="hora" id="select_hora">
                                    <option value="" disabled selected>Seleccione una hora</option>
                                </select>
                                @if ($errors->has('hora') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1 error-validacion">
                                        {!! $errors->first('hora', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="table-responsive mt-3" style="display:none;" id="tablaDatosServicios">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th class="bg-dark text-light" scope="row">Código</th>
                                            <td><span id="añadir-info-cod"></span></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-dark text-light" scope="row">Nombre</th>
                                            <td><span id="añadir-info-nombre"></span></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-dark text-light" scope="row">Descripción</th>
                                            <td><span id="añadir-info-descripcion"></span></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-dark text-light" scope="row">Precio</th>
                                            <td><span id="añadir-info-precio"></span> €</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-dark text-light" scope="row">Duración</th>
                                            <td><span id="añadir-info-duracion"></span> minutos</td>
                                        </tr>
                                    </tbody>
                                </table>
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

    <!-- Modal modificar cita -->

    <div class="modal fade" id="modificarModal" tabindex="-1" aria-labelledby="modificarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modificarModalLabel"><b>Modificar cita <span id="titulo_mod"></span></b>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="modificar-cita-form">
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
                                    <div class="alert alert-danger mt-1" id="error-validacion">
                                        {!! $errors->first('cliente_id', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label for="servicio_obj" class="form-label">Servicio:</label>
                                <select class="form-select" name="servicio_obj" id="servicio_obj_mod">
                                    <option value="" disabled selected>Seleccione un servicio</option>
                                    @foreach ($servicios as $servicio)
                                        <option id="{{ $servicio->id_servicio }}" value="{{ $servicio }}"
                                            {{ old('servicio_obj') == $servicio->id_servicio ? 'selected' : '' }}>
                                            {{ $servicio->cod }} - {{ $servicio->nombre }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('servicio_obj') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1" id="error-validacion">
                                        {!! $errors->first('servicio_obj', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="estado" class="form-label">Estado:</label>
                                <select class="form-select" name="estado" id="estado">
                                    <option value="" disabled selected>Seleccione un estado</option>
                                    <option value="Confirmada">Confirmada</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Cancelada">Cancelada</option>
                                </select>
                                @if ($errors->has('estado') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1" id="error-validacion">
                                        {!! $errors->first('estado', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>
                            <div class="col">
                                <label for="modificarFechayHora" class="form-label">Modificar fecha y hora:</label>
                                <select class="form-select" name="modificarFechayHora" id="modificarFechayHora">
                                    <option value="si">SI</option>
                                    <option value="no">NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Fecha:</label>
                                <input type="date" class="form-control border border-primary" name="fecha"
                                    id="fecha_mod"
                                    @if (old('fecha_mod') && session()->get('modificar')) value="{{ old('fecha_mod') }}" @endif>
                                @if ($errors->has('fecha') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1" id="error-validacion">
                                        {!! $errors->first('fecha', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label for="hora" class="form-label">Hora:</label>
                                <select class="form-select" name="hora" id="select_hora_mod">
                                    <option value="" disabled selected>Seleccione una hora</option>
                                </select>
                                @if ($errors->has('hora') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1" id="error-validacion">
                                        {!! $errors->first('hora', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>


                            <div class="table-responsive mt-3" style="display:none;" id="tablaDatosServiciosMod">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th class="bg-dark text-light" scope="row">Código</th>
                                            <td><span id="mod-info-cod"></span></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-dark text-light" scope="row">Nombre</th>
                                            <td><span id="mod-info-nombre"></span></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-dark text-light" scope="row">Descripción</th>
                                            <td><span id="mod-info-descripcion"></span></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-dark text-light" scope="row">Precio</th>
                                            <td><span id="mod-info-precio"></span> €</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-dark text-light" scope="row">Duración</th>
                                            <td><span id="mod-info-duracion"></span> minutos</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-warning" type="submit">Modificar <i class="bi bi-pen-fill"></i></button>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection
