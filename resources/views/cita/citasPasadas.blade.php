@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Citas Pasadas
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
            text-align: center;
        }

        @media screen and (max-width: 767px) {

            #tabla-citas td:nth-child(3),
            #tabla-citas th:nth-child(3),
            #tabla-citas td:nth-child(4),
            #tabla-citas th:nth-child(4) {
                display: none;
            }
        }
    </style>

    <script>
        $(document).ready(function() {

            var dynatable = $('#tabla-citas table').dynatable({
                dataset: {
                    perPageDefault: 5,
                    perPageOptions: [5, 10, 25, 50, 100]
                }
            }).data('dynatable');

            dynatable.paginationPerPage.set(5);
            dynatable.process();

            $('#detallesModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var cita = button.data('cita');
                var cliente = button.data('cliente');

                // cambiar id de descarga pdf
                var url = "{{ route('citaPDF', ['id' => ':idcita']) }}";
                url = url.replace(':idcita', cita.id_cita);
                $('#descargarPDF').attr('href', url);
                console.log(cliente)
                // poner los datos en el modal
                $('#detalles_nombre_empresa').text(cita.empresa.nombre);
                $('#detalles_cif').text(cita.empresa.cif);
                $('#detalles_telefono_empresa').text(cita.empresa.telefono);
                $('#detalles_nif_empleado').text(cita.empleado.nif);
                $('#detalles_nombre_empleado').text(cita.empleado.nombre + " " + cita.empleado.apellidos);
                $('#detalles_cargo').text(cita.empleado.cargo);
                $('#detalles_cod').text(cita.servicio.cod);
                $('#detalles_nombre_servicio').text(cita.servicio.nombre);
                $('#detalles_precio').text(cita.servicio.precio);
                $('#detalles_duracion').text(cita.servicio.duracion);
                $('#detalles_nif_cliente').text(cliente.nif);
                $('#detalles_nombre_cliente').text(cliente.nombre + " " + cliente.apellidos);
                $('#detalles_fecha_inicio').text(obtenerFechaFormateada(cita.fecha_inicio));
                $('#detalles_fecha_fin').text(obtenerFechaFormateada(cita.fecha_fin));
                $('#detalles_estado').text(cita.status);
            });
        });

        //funcion para formatear fecha
        function obtenerFechaFormateada(fecha) {
            return new Date(fecha).toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            });
        }
    </script>

@endsection

@section('contenido')
    <div class="container">
        <div class="row align-items-center">
            <div class="col text-center">
                <h1>Citas pasadas</h1>
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

    <div class="table-responsive" id="tabla-citas">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Nombre de la empresa</th>
                    <th scope="col">Nombre del empleado</th>
                    <th scope="col">Codigo del servicio</th>
                    <th scope="col">Nombre del servicio</th>
                    <th scope="col">Fecha de inicio de la cita</th>
                    <th scope="col">Fecha fin de la cita</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($citas as $cita)
                    <tr>
                        <td>{{ $cita->empresa->nombre }}</td>
                        <td>{{ $cita->empleado->nombre }} {{ $cita->empleado->apellidos }}</td>
                        <td>{{ $cita->servicio->cod }}</td>
                        <td>{{ $cita->servicio->nombre }}</td>
                        <td>{{ $cita->fecha_inicio }}</td>
                        <td>{{ $cita->fecha_fin }}</td>
                        <td>
                            <div class="btn-group btn-group-md gap-1">
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Detalles">
                                    <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detallesModal"
                                        data-cita="{{ $cita }}" data-cliente="{{ $cita->cliente }}">
                                        <i class="bi bi-eye-fill"></i></a></span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>



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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

@endsection
