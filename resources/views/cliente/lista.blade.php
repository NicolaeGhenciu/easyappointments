@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Gestionar clientes
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

            #tabla-clientes td:nth-child(1),
            #tabla-clientes th:nth-child(1),
            #tabla-clientes td:nth-child(4),
            #tabla-clientes th:nth-child(4),
            #tabla-clientes td:nth-child(5),
            #tabla-clientes th:nth-child(5) {
                display: none;
            }
        }
    </style>

    <script>
        $(document).ready(function() {

            var dynatable = $('#tabla-clientes table').dynatable({
                dataset: {
                    perPageDefault: 5,
                    perPageOptions: [5, 10, 25, 50, 100]
                }
            }).data('dynatable');

            dynatable.paginationPerPage.set(5);
            dynatable.process();

            //Este evento se activa al abrir el modal de ver detalles y se encarga de cargar los datos del cliente.

            $('#detallesModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var cliente = button.data('cliente');
                $('#detalles-nombre').text(cliente.nombre + " " + cliente.apellidos);
                $('#detalles-nif').text(cliente.nif);
                $('#detalles-fecha_nacimiento').text(obtenerFechaFormateada(cliente.fecha_nacimiento));
                $('#detalles-direccion').text(cliente.direccion);
                $('#detalles-telefono').text(cliente.telefono);
                $('#detalles-provincia').text(cliente.provincia.provincia);
                $('#detalles-municipio').text(cliente.municipio.municipio);
            });

            //Este evento se activa al abrir el modal de borrar y se encarga de cargar los datos del desasociar.

            $('#borrarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var cliente = button.data('cliente');
                $('#borrar-nombre').text(cliente.nombre + " " + cliente.apellidos);
                $('#borrar-nif').text(cliente.nif);
                $('#borrar-fecha_nacimiento').text(obtenerFechaFormateada(cliente.fecha_nacimiento));
                $('#desasociar-form').submit(function() {
                    var url = "{{ route('desasociarCliente', ['id' => ':idcliente']) }}";
                    url = url.replace(':idcliente', cliente.id_cliente);
                    $('#desasociar-form').attr('action', url);
                });
            });

            //Funcion que recibe fecha por parametro y la formatea con neste formaro dd/mm/yy

            function obtenerFechaFormateada(fecha) {
                return new Date(fecha).toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }
        });

        //Esta funcion se activa al clicar el boton de modificar cliente y se encarga de cargar los datos del cliente.

        function modificar(cliente) {
            $("#nombre_mod").val(cliente.nombre);
            $("#apellidos_mod").val(cliente.apellidos);
            $("#nif_mod").val(cliente.nif);
            $("#fecha_nacimiento_mod").val(cliente.fecha_nacimiento);
            $("#direccion_mod").val(cliente.direccion);
            $("#telefono_mod").val(cliente.telefono);
            $("#provincia_id_mod").val(cliente.provincia_id);
            $("#municipio_id_mod").val(cliente.municipio_id);
            $('#modificar-cliente-form').submit(function() {
                var url = "{{ route('modificarCliente', ['id' => ':idcliente']) }}";
                url = url.replace(':idcliente', cliente.id_cliente);
                $('#modificar-cliente-form').attr('action', url);
            });
        }

        function getMunicipios(provinciaId, municipioId) {
            $.ajax({
                type: "GET",
                url: "{{ url('/municipiosPorProvincia') }}/" + provinciaId,
                success: function(data) {
                    $(municipioId).empty();
                    $.each(data, function(i, item) {
                        $(municipioId).append($('<option>', {
                            value: item.id,
                            text: item.municipio
                        }));
                    });
                    $(municipioId).prop('disabled', false);
                }
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

    <!-- Si existe una sesion de asociar mostramos el modal nada mas cargar la pagina -->

    @if (session()->get('asociar') && !session()->has('error'))
        <script>
            $(document).ready(function() {
                $('#asociarModal').modal('show');
            });
        </script>
    @endif

    <!-- Si existe una sesion de modificar mostramos el modal nada mas cargar la pagina -->

    @if (session()->get('modificar'))
        <script>
            $(document).ready(function() {
                $('#modificarModal').modal('show');
                $('#modificar-cliente-form').submit(function() {
                    var url = "{{ route('modificarCliente', ['id' => ':idcliente']) }}";
                    url = url.replace(':idcliente', {{ old('id_cliente', session('id_cliente')) }});
                    $('#modificar-cliente-form').attr('action', url);
                });
                //Limpiar los mensajes de error al cerrar el modal
                $('#modificarModal').on('hidden.bs.modal', function() {
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
                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Alta de un cliente">
                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#añadirModal">
                        <i class="bi bi-person-fill-add"></i></a></span>
            </div>
            <div class="col-auto">
                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Asociar un cliente existente">
                    <a class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#asociarModal">
                        <i class="bi bi-bookmark-plus-fill"></i></a></span>
            </div>
            <div class="col text-center">
                <h1>Lista de Clientes</h1>
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

    <div class="table-responsive" id="tabla-clientes">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">NIF</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellidos</th>
                    <th scope="col">Provincia</th>
                    <th scope="col">Municipio</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->nif }}</td>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->apellidos }}</td>
                        <td>{{ $cliente->provincia->provincia }}</td>
                        <td>{{ $cliente->municipio->municipio }}</td>
                        <td>
                            <div class="btn-group btn-group-md gap-1">
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Detalles">
                                    <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detallesModal"
                                        data-cliente="{{ $cliente }}">
                                        <i class="bi bi-eye-fill"></i></a></span>
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Modificar"
                                    onclick="modificar({{ $cliente }})">
                                    <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modificarModal"
                                        data-cliente="{{ $cliente }}">
                                        <i class="bi bi-person-fill-gear"></i></a></span>
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Desasociar">
                                    <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#borrarModal"
                                        data-cliente="{{ $cliente }}">
                                        <i class="bi bi-trash-fill"></i></a></span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('modals')

    <!-- Modal detalles cliente -->

    <div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="detallesModalLabel"><b>Detalles del cliente</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <th scope="row" class="bg-dark text-light">NIF</th>
                            <td class="bg-light"><span id="detalles-nif"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Nombre y Apellidos</th>
                            <td><span id="detalles-nombre"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Fecha de nacimiento</th>
                            <td><span id="detalles-fecha_nacimiento"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Dirección</th>
                            <td><span id="detalles-direccion"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Teléfono</th>
                            <td><span id="detalles-telefono"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Provincia</th>
                            <td><span id="detalles-provincia"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Municipio</th>
                            <td><span id="detalles-municipio"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal añadir cliente -->

    <div class="modal fade" id="añadirModal" tabindex="-1" aria-labelledby="añadirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="añadirModalLabel"><b>Dar de alta un cliente</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('crearUsuarioCliente') }}" method="post">
                        @csrf
                        <div class="row mb-3">

                            <div class="col">
                                <label class="form-label">Nombre:</label>
                                <input type="text" class="form-control border border-primary" name="nombre"
                                    @if (old('nombre') && session()->get('crear')) value="{{ old('nombre') }}" @endif>
                                @if ($errors->has('nombre') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('nombre', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">Apellidos:</label>
                                <input type="text" class="form-control border border-primary" name="apellidos"
                                    @if (old('apellidos') && session()->get('crear')) value="{{ old('apellidos') }}" @endif>
                                @if ($errors->has('apellidos') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('apellidos', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Fecha de nacimiento:</label>
                                <input type="date" class="form-control border border-primary" name="fecha_nacimiento"
                                    @if (old('fecha_nacimiento') && session()->get('crear')) value="{{ old('fecha_nacimiento') }}" @endif>
                                @if ($errors->has('fecha_nacimiento') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('fecha_nacimiento', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">NIF:</label>
                                <input type="text" class="form-control border border-primary" name="nif"
                                    @if (old('nif') && session()->get('crear')) value="{{ old('nif') }}" @endif>
                                @if ($errors->has('nif') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('nif', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-6">

                            <div class="col">
                                <label class="form-label">Email:</label>
                                <input type="email" class="form-control border border-primary" name="email"
                                    value="{{ old('email') }}">
                                @if ($errors->has('email') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('email', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col">
                                <label class="form-label">Dirección:</label>
                                <input type="text" class="form-control border border-primary" name="direccion"
                                    @if (old('direccion') && session()->get('crear')) value="{{ old('direccion') }}" @endif>
                                @if ($errors->has('direccion') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('direccion', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">Teléfono:</label>
                                <input type="number" class="form-control border border-primary" name="telefono"
                                    @if (old('telefono') && session()->get('crear')) value="{{ old('telefono') }}" @endif>
                                @if ($errors->has('telefono') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('telefono', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col">
                                <label for="provincia_id" class="form-label">Provincia:</label>
                                <select class="form-select" name="provincia_id" id="provincia_id"
                                    onchange="getMunicipios(this.value, '#municipio_id')">
                                    <option value="" disabled selected>Seleccione una provincia</option>
                                    @foreach ($provincias as $provincia)
                                        <option value="{{ $provincia->id }}">
                                            {{ $provincia->provincia }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('provincia_id') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('provincia_id', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label for="municipio_id" class="form-label">Municipio:</label>
                                <select class="form-select" name="municipio_id" id="municipio_id" disabled>
                                </select>
                                @if ($errors->has('municipio_id') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('municipio_id', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                        </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Dar de alta <i
                            class="bi bi-person-fill-add"></i></button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal modificar cliente -->

    <div class="modal fade" id="modificarModal" tabindex="-1" aria-labelledby="modificarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modificarModalLabel"><b>Modificar datos del cliente</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="modificar-cliente-form">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">

                            <div class="col">
                                <label class="form-label">Nombre:</label>
                                <input type="text" class="form-control border border-primary" name="nombre"
                                    id="nombre_mod" value="{{ old('nombre') }}">
                                @if ($errors->has('nombre') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('nombre', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">Apellidos:</label>
                                <input type="text" class="form-control border border-primary" name="apellidos"
                                    id="apellidos_mod" value="{{ old('apellidos') }}">
                                @if ($errors->has('apellidos') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('apellidos', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Fecha de nacimiento:</label>
                                <input type="date" class="form-control border border-primary" name="fecha_nacimiento"
                                    id="fecha_nacimiento_mod" value="{{ old('fecha_nacimiento') }}">
                                @if ($errors->has('fecha_nacimiento') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('fecha_nacimiento', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">NIF:</label>
                                <input type="text" class="form-control border border-primary" name="nif"
                                    id="nif_mod" value="{{ old('nif') }}">
                                @if ($errors->has('nif') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('nif', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">

                            <div class="col">
                                <label class="form-label">Dirección:</label>
                                <input type="text" class="form-control border border-primary" name="direccion"
                                    id="direccion_mod" value="{{ old('direccion') }}">
                                @if ($errors->has('direccion') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('direccion', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">Teléfono:</label>
                                <input type="number" class="form-control border border-primary" name="telefono"
                                    id="telefono_mod" value="{{ old('telefono') }}">
                                @if ($errors->has('telefono') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('telefono', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col">
                                <label for="provincia_id" class="form-label">Provincia:</label>
                                <select class="form-select" name="provincia_id" id="provincia_id_mod"
                                    onchange="getMunicipios(this.value, '#municipio_id_mod')">
                                    <option value="" disabled selected>Seleccione una provincia</option>
                                    @foreach ($provincias as $provincia)
                                        <option value="{{ $provincia->id }}"
                                            {{ old('provincia_id') == $provincia->id ? 'selected' : '' }}>
                                            {{ $provincia->provincia }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('provincia_id') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('provincia_id', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label for="municipio_id" class="form-label">Municipio:</label>
                                <select class="form-select" name="municipio_id" id="municipio_id_mod">
                                    @foreach ($municipios as $municipio)
                                        <option value="{{ $municipio->id }}"
                                            {{ old('municipio_id') == $municipio->id ? 'selected' : '' }}>
                                            {{ $municipio->municipio }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('municipio_id') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('municipio_id', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                        </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Modificar <i
                            class="bi bi-person-fill-gear"></i></button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal asociar cliente -->

    <div class="modal fade" id="asociarModal" tabindex="-1" aria-labelledby="asociarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="asociarModalLabel"><b>Asociar un cliente</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('asociarCliente') }}" method="post">
                        @csrf
                        <p>Ingrese el <b>correo electrónico</b> del cliente que desea asociar:</p>
                        <div class="row mb-6">

                            <div class="col">
                                <label class="form-label">Email:</label>
                                <input type="email" class="form-control border border-primary" name="email"
                                    value="{{ old('email') }}">
                                @if ($errors->has('email') && session()->get('asociar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('email', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                        </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Asociar <i
                            class="bi bi-bookmark-plus-fill"></i></button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal desasociar -->

    <div class="modal fade" id="borrarModal" tabindex="-1" aria-labelledby="borrarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="borrarModalLabel"><b>Desasociar un cliente</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas desasociar al cliente?</p>
                    <table class="table">
                        <tr>
                            <th scope="row" class="bg-dark text-light">NIF</th>
                            <td class="bg-light"><span id="borrar-nif"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Nombre y apellidos</th>
                            <td><span id="borrar-nombre"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Fecha de nacimiento</th>
                            <td><span id="borrar-fecha_nacimiento"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <form method="POST" id="desasociar-form" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Desasociar <i
                                class="bi bi-slash-circle"></i></button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

@endsection
