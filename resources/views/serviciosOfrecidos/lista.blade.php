@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Buscar servicios
@endsection

@section('linkScript')

    <link rel="stylesheet" href="{{ asset('css/dynatable.css') }}">

    <script src="{{ asset('js/Dynatable-031.js') }}"></script>

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
            text-align: left;
        }

        @media screen and (max-width: 767px) {

            #tabla-servicios td:nth-child(3),
            #tabla-servicios th:nth-child(3) {
                display: none;
            }
        }
    </style>

    <script>
        $(document).ready(function() {

            var dynatable = $('#tabla-servicios table').dynatable({
                dataset: {
                    perPageDefault: 5,
                    perPageOptions: [5, 10, 25, 50, 100]
                }
            }).data('dynatable');

            dynatable.paginationPerPage.set(5);
            dynatable.process();

            $('#detallesModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var servicio = button.data('servicio');
                // poner los datos en el modal
                $('#detalles_nombre_empresa').text(servicio.empresa.nombre);
                $('#detalles_cif').text(servicio.empresa.cif);
                $('#detalles_telefono_empresa').text(servicio.empresa.telefono);
                $('#detalles_cod').text(servicio.cod);
                $('#detalles_nombre_servicio').text(servicio.nombre);
                $('#detalles_precio').text(servicio.precio);
                $('#detalles_duracion').text(servicio.duracion);
            });
            var servicioSupra;
            $('#añadirModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var servicio = button.data('servicio');
                servicioSupra = servicio;
                if (servicioSupra == undefined) {
                    servicio = {!! json_encode(session('servicio_solicitado')) !!};
                    servicioSupra = servicio;
                }
                $('#servicio_id').val(servicio.id_servicio);
                $('#añadir-info-cod').text(servicio.cod);
                $('#añadir-info-nombre').text(servicio.nombre);
                $('#añadir-info-descripcion').text(servicio.descripcion);
                $('#añadir-info-precio').text(servicio.precio);
                $('#añadir-info-duracion').text(servicio.duracion);
                $("#tablaDatosServicios").fadeIn(200);

                getEmpleadoServicio(servicio);

                $('#programar-cita-form').submit(function() {
                    var url = "{{ route('nuevaCita_Cliente', ['id' => ':idservicio']) }}";
                    url = url.replace(':idservicio', servicio.id_servicio);
                    $('#programar-cita-form').attr('action', url);
                });
            });

            function getEmpleadoServicio(servicio) {

                $("#fecha").val();
                $("#fecha").trigger('change');

                // Obtener la fecha actual
                var today = new Date().toISOString().split('T')[0];

                // Obtener el campo de fecha por su ID
                var fechaInput = document.getElementById('fecha');

                // Establecer la fecha mínima permitida como hoy
                fechaInput.min = today;

                // Obtener todas las etiquetas <option> dentro del campo de fecha
                var options = fechaInput.getElementsByTagName('option');

                //Disponibilidad del empleado
                var disponibilidad;

                //Citas del empleado
                var citas;

                // Recorrer todas las etiquetas <option> y deshabilitar las fechas anteriores a hoy
                for (var i = 0; i < options.length; i++) {
                    var date = new Date(options[i].value);

                    // Comparar la fecha con la fecha actual
                    if (date < today) {
                        options[i].disabled = true;
                    }
                }

                var urlEnvio = "{{ route('getEmpleadoServicio', ['idEmpresa' => 'idE', 'idServicio' => 'idS']) }}";
                urlEnvio = urlEnvio.replace('idE', servicio.id_empresa);
                urlEnvio = urlEnvio.replace('idS', servicio.id_servicio);
                $.ajax({
                    type: "GET",
                    url: urlEnvio,
                    success: function(data) {
                        $('#empleado_id').empty();

                        if (data.length === 0) {
                            $('#empleado_id').append($('<option>', {
                                value: '0',
                                text: 'No hay ningún empleado disponible para este servicio'
                            }));
                        } else {
                            $.each(data, function(i, item) {
                                $('#empleado_id').append($('<option>', {
                                    value: item.id_empleado,
                                    text: item.nombre + " " + item.apellidos
                                }));
                            });
                        }

                        $('#empleado_id').prop('disabled', false);
                        $('#empleado_id').trigger('change');
                    }
                });
            }

            $("#empleado_id").on('change', function() {
                let idEmpleado = $('#empleado_id').val();
                var urlEnvio = "{{ route('getEmpleadoCitasDisponibilidad', ['idEmpleado' => 'idE']) }}";
                urlEnvio = urlEnvio.replace('idE', idEmpleado);
                $.ajax({
                    type: "GET",
                    url: urlEnvio,
                    success: function(data) {
                        citas = data.citas;
                        disponibilidad = data.disponibilidad;
                        if ($("#fecha").val() === "") {} else {
                            let fecha = $("#fecha").val();
                            actualizarSelectHora(fecha, servicioSupra, disponibilidad, citas,
                                "select_hora");
                        }
                    }
                });
            });
            $("#fecha").on('change', function() {
                $('#empleado_id').val()
                if ($("#fecha").val() === "") {} else {
                    let fecha = $("#fecha").val();
                    actualizarSelectHora(fecha, servicioSupra, disponibilidad, citas, "select_hora");
                }
            });
            $('#añadirModal').on('hidden.bs.modal', function() {
                $('#select_hora').empty();
                $('#select_hora').append($('<option>', {
                    value: '',
                    text: 'Seleccione una hora'
                }));
            });
        });

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
                console.log(opcionesSelect)
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
                option.value = "";
                selectHora.appendChild(option);
            }
        }
    </script>

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

@endsection

@section('contenido')

    <div class="container">
        <div class="row align-items-center">
            <div class="col text-center">
                <h1>Lista de Servicios</h1>
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

    <div class="table-responsive" id="tabla-servicios">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Empresa</th>
                    <th scope="col">Código</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Duración</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($servicios as $servicio)
                    <tr>
                        <td>{{ $servicio->empresa->nombre }}</td>
                        <td>{{ $servicio->cod }}</td>
                        <td>{{ $servicio->nombre }}</td>
                        <td>{{ $servicio->descripcion }}</td>
                        <td>{{ $servicio->precio }} €</td>
                        <td>{{ $servicio->duracion }} minutos</td>
                        <td>
                            <div class="btn-group btn-group-md gap-1">
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Detalles">
                                    <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detallesModal"
                                        data-servicio="{{ $servicio }}">
                                        <i class="bi bi-eye-fill"></i></a></span>
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Pedir cita">
                                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#añadirModal"
                                        data-servicio="{{ $servicio }}">
                                        <i class="bi bi-calendar2-plus"></i></a></span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('modals')

    <!-- Modal añadir cita -->

    <div class="modal fade" id="añadirModal" tabindex="-1" aria-labelledby="añadirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="añadirModalLabel"><b>Programar cita</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="programar-cita-form">
                        @csrf
                        <div class="row mb-3">

                            <div class="col">
                                <label for="empleado_id" class="form-label">Empleados:</label>
                                <select class="form-select" name="empleado_id" id="empleado_id">
                                    <option value="" disabled selected>Seleccione un empleado</option>
                                </select>
                                @if ($errors->has('empleado_id') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1 error-validacion">
                                        {!! $errors->first('empleado_id', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Fecha:</label>
                                <input type="date" class="form-control border border-primary" name="fecha"
                                    id="fecha">
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
                    <button class="btn btn-primary" type="submit">Programar cita <i
                            class="bi bi-calendar2-plus"></i></button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal detalles del servicio -->

    <div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="detallesModalLabel">
                        <b>Detalles del servicio</b>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">

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
                        <div class="card mb-3">
                            <div class="card-header text-center">
                                <h4 class="card-title">Servicio</h4>
                            </div>
                            <div class="card-body">
                                <p><strong>Código:</strong> <span id="detalles_cod"></span></p>
                                <p><strong>Nombre:</strong> <span id="detalles_nombre_servicio"></span></p>
                                <p><strong>Precio:</strong> <span id="detalles_precio"></span> €</p>
                                <p><strong>Duración:</strong> <span id="detalles_duracion"></span> minutos</p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

@endsection
