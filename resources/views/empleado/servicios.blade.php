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
            text-align: center;
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
                $("#tabladatos").fadeIn(200);
            });

            //Este evento muestra los datos del servicio al pinchar en borrar y envia el formulario.

            $('#borrarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var servicio = button.data('servicio');
                $('#borrar-id').val(servicio.id_servicio);
                $('#borrar-cod').text(servicio.cod);
                $('#borrar-nombre').text(servicio.nombre);
                $('#borrar-descripcion').text(servicio.descripcion);
                $('#borrar-precio').text(servicio.precio);
            });
        });
    </script>

@endsection

@section('contenido')

    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Asociar un servicio">
                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#añadirModal">
                        <i class="bi bi-bag-plus-fill"></i></a></span>
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

    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Precio</th>
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
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Borrar asociación">
                                <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#borrarModal"
                                    data-servicio="{{ $servicio->servicio }}">
                                    <i class="bi bi-trash-fill"></i></a></span>
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
                @for ($i = 1; $i <= $servicios->lastPage(); $i++)
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

@endsection

@section('modals')

    <!-- Modal asociar servicio -->

    <div class="modal fade" id="añadirModal" tabindex="-1" aria-labelledby="añadirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
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
                                </tbody>
                            </table>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Asociar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal borrar asociacion -->

    <div class="modal fade" id="borrarModal" tabindex="-1" aria-labelledby="borrarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
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
                            <td><span id="borrar-precio"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" id="desasociar-form"
                        action="{{ route('desasociarServicio', ['id' => $empleado->id_empleado]) }}">
                        @csrf
                        @method('DELETE')
                        <input type="text" id="borrar-id" name="servicio_id" value="" hidden>
                        <button type="submit" class="btn btn-danger">Borrar asociación</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
