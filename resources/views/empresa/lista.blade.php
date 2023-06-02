@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Pedir cita
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
                console.log(servicio)
                // poner los datos en el modal
                $('#detalles_nombre_empresa').text(servicio.empresa.nombre);
                $('#detalles_cif').text(servicio.empresa.cif);
                $('#detalles_telefono_empresa').text(servicio.empresa.telefono);
                $('#detalles_cod').text(servicio.cod);
                $('#detalles_nombre_servicio').text(servicio.nombre);
                $('#detalles_precio').text(servicio.precio);
                $('#detalles_duracion').text(servicio.duracion);
            });
        });
    </script>

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
                                    <option value="" disabled selected>Seleccione un empleado</option>
                                    {{-- @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id_cliente }}"
                                            {{ old('cliente_id') == $cliente->id_cliente ? 'selected' : '' }}>
                                            {{ $cliente->nif }}-{{ $cliente->nombre }} {{ $cliente->apellidos }}</option>
                                    @endforeach --}}
                                </select>
                                @if ($errors->has('cliente_id') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1 error-validacion">
                                        {!! $errors->first('cliente_id', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
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
