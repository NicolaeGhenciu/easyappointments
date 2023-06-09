@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Servicios - {{ $empleado->nombre }}
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
            text-align: left;
        }

        @media screen and (max-width: 767px) {

            #tabla-servicios td:nth-child(3),
            #tabla-servicios th:nth-child(3),
            #tabla-servicios td:nth-child(4),
            #tabla-servicios th:nth-child(4),
            #tabla-servicios td:nth-child(5),
            #tabla-servicios th:nth-child(5) {
                display: none;
            }
        }
    </style>

    <script>
        $(document).ready(function() {

            //Este evento muestra los datos del servicio al seleccionar en el select
            $('#servicio_id').change(function() {
                var servicio = $(this).find('option:selected').data('servicios');
                $('#asociar-cod').text(servicio.cod);
                $('#asociar-nombre').text(servicio.nombre);
                $('#asociar-descripcion').text(servicio.descripcion);
                $('#asociar-precio').text(servicio.precio);
                $('#asociar-duracion').text(servicio.duracion);
                $("#tabladatos").fadeIn(200);
            });

            //Este evento muestra los datos del servicio al pinchar en borrar y envia el formulario.

            $('#borrarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var servicio = button.data('servicio');
                console.log(servicio);
                $('#borrar-cod').text(servicio.servicio.cod);
                $('#borrar-nombre').text(servicio.servicio.nombre);
                $('#borrar-descripcion').text(servicio.servicio.descripcion);
                $('#borrar-precio').text(servicio.servicio.precio);
                $('#borrar-duracion').text(servicio.servicio.duracion);
                $('#desasociar-form').submit(function() {
                    var url = "{{ route('desasociarServicio', ['id' => ':idasociacion']) }}";
                    url = url.replace(':idasociacion', servicio.id_servicio_empleado);
                    $('#desasociar-form').attr('action', url);
                });
            });
        });
    </script>

    <!-- Si existe una sesion de crear mostramos el modal nada mas cargar la pagina -->

    @if (session()->get('asociar') && !session()->has('error'))
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

@endsection

@section('contenido')

    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Asociar un servicio">
                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#añadirModal">
                        <i class="bi bi-link"></i></a></span>
            </div>
            <div class="col text-center">
                <h3>Servicios que presta: {{ $empleado->nombre }} {{ $empleado->apellidos }}, NIF - {{ $empleado->nif }}
                </h3>
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
                        <td>{{ $servicio->servicio->cod }}</td>
                        <td>{{ $servicio->servicio->nombre }}</td>
                        <td>{{ $servicio->servicio->descripcion }}</td>
                        <td>{{ $servicio->servicio->precio }} €</td>
                        <td>{{ $servicio->servicio->duracion }} minutos</td>
                        <td>
                            <div class="btn-group btn-group-md gap-1">
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar una asociación">
                                    <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#borrarModal"
                                        data-servicio="{{ $servicio }}">
                                        <i class="bi bi-trash-fill"></i></a></span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="centrar">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item {{ $servicios->currentPage() == 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $servicios->previousPageUrl() }}">Anterior</a>
                </li>
                <li class="page-item {{ $servicios->currentPage() == 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $servicios->url(1) }}">Primera</a>
                </li>
                @php
                    $start = max($servicios->currentPage() - 1, 1);
                    $end = min($start + 2, $servicios->lastPage());
                @endphp
                @for ($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $servicios->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $servicios->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item {{ $servicios->currentPage() == $servicios->lastPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $servicios->url($servicios->lastPage()) }}">Última</a>
                </li>
                <li class="page-item {{ $servicios->currentPage() == $servicios->lastPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $servicios->nextPageUrl() }}">Siguiente</a>
                </li>
            </ul>
        </nav>
    </div>
    <div id="centrar">
        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Volver atras">
            <a class="btn btn-primary" href="{{ route('listarEmpleados') }}">
                <i class="bi bi-arrow-left"></i> Volver atrás</a></span>
    </div>
@endsection

@section('modals')

    <!-- Modal asociar servicio -->

    <div class="modal fade" id="añadirModal" tabindex="-1" aria-labelledby="añadirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="añadirModalLabel"><b>Asociar un servicio a {{ $empleado->nombre }}
                            {{ $empleado->apellidos }}</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('asociarServicio', ['id' => $empleado->id_empleado]) }}" method="post">
                        @csrf
                        <div class="col">
                            <label for="servicio_id" class="form-label">Servicios:</label>
                            <select class="form-select" name="servicio_id" id="servicio_id">
                                <option value="" disabled selected>Seleccione un servicio</option>
                                @foreach ($allservicios as $servicio)
                                    <option value="{{ $servicio->id_servicio }}"
                                        {{ old('servicio_id') == $servicio->id_servicio ? 'selected' : '' }}
                                        data-servicios="{{ $servicio }}">
                                        {{ $servicio->cod }} - {{ $servicio->nombre }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('servicio_id'))
                                <div class="alert alert-danger mt-1">
                                    {!! $errors->first('servicio_id', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                </div>
                            @endif
                        </div>

                        <div class="table-responsive mt-3" style="display:none;" id="tabladatos">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th class="bg-dark text-light" scope="row">Código</th>
                                        <td><span id="asociar-cod"></span></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-dark text-light" scope="row">Nombre</th>
                                        <td><span id="asociar-nombre"></span></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-dark text-light" scope="row">Descripción</th>
                                        <td><span id="asociar-descripcion"></span></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-dark text-light" scope="row">Precio</th>
                                        <td><span id="asociar-precio"></span> €</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-dark text-light" scope="row">Duración</th>
                                        <td><span id="asociar-duracion"></span> minutos</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Asociar <i class="bi bi-link"></i></button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal borrar asociacion -->

    <div class="modal fade" id="borrarModal" tabindex="-1" aria-labelledby="borrarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="borrarModalLabel"><b>Borrar asociación-servicio</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas desasociar este servicio de {{ $empleado->nombre }}
                        {{ $empleado->apellidos }}?</p>
                    <table class="table">
                        <tr>
                            <th scope="row" class="bg-dark text-light">Código</th>
                            <td class="bg-light"><span id="borrar-cod"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Nombre</th>
                            <td><span id="borrar-nombre"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Descripción</th>
                            <td><span id="borrar-descripcion"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Precio</th>
                            <td><span id="borrar-precio"></span> €</td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Duración</th>
                            <td><span id="borrar-duracion"></span> minutos</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <form method="POST" id="desasociar-form" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar asociación <i
                                class="bi bi-trash3"></i></button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>


@endsection
