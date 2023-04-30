@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Gestionar empleados
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

            //Este evento se activa al abrir el modal de borrar y se encarga de cargar los datos del empleado.

            $('#borrarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var empleado = button.data('empleado');
                $('#borrar-nombre').text(empleado.nombre + " " + empleado.apellidos);
                $('#borrar-nif').text(empleado.nif);
                $('#borrar-cargo').text(empleado.cargo);
                $('#borrar-fecha_nacimiento').text(obtenerFechaFormateada(empleado.fecha_nacimiento));
                $('#borrar-empleado-form').submit(function() {
                    var url = "{{ route('borrarEmpleado', ['id' => ':idempleado']) }}";
                    url = url.replace(':idempleado', empleado.id_empleado);
                    $('#borrar-empleado-form').attr('action', url);
                });
            });

            //Este evento se activa al abrir el modal de ver detalles y se encarga de cargar los datos del empleado.

            $('#detallesModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var empleado = button.data('empleado');
                $('#detalles-nombre').text(empleado.nombre + " " + empleado.apellidos);
                $('#detalles-nif').text(empleado.nif);
                $('#detalles-cargo').text(empleado.cargo);
                $('#detalles-fecha_nacimiento').text(obtenerFechaFormateada(empleado.fecha_nacimiento));
                $('#detalles-direccion').text(empleado.direccion);
                $('#detalles-telefono').text(empleado.telefono);
                $('#detalles-provincia').text(empleado.provincia.provincia);
                $('#detalles-municipio').text(empleado.municipio.municipio);
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

        //Esta funcion se activa al clicar el boton de modificar empleado y se encarga de cargar los datos del empleado.

        function modificar(empleado) {
            $("#nombre_mod").val(empleado.nombre);
            $("#apellidos_mod").val(empleado.apellidos);
            $("#nif_mod").val(empleado.nif);
            $("#fecha_nacimiento_mod").val(empleado.fecha_nacimiento);
            $("#cargo_mod").val(empleado.cargo);
            $("#direccion_mod").val(empleado.direccion);
            $("#telefono_mod").val(empleado.telefono);
            $("#provincia_id_mod").val(empleado.provincia_id);
            $("#municipio_id_mod").val(empleado.municipio_id);
            $('#modificar-empleado-form').submit(function() {
                var url = "{{ route('modificarEmpleado', ['id' => ':idempleado']) }}";
                url = url.replace(':idempleado', empleado.id_empleado);
                $('#modificar-empleado-form').attr('action', url);
            });
        }

        //Funcion que mdifica el select de municipios con los de la provincia correspondiente

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

    @if (session()->get('crear'))
        <script>
            $(document).ready(function() {
                $('#añadirModal').modal('show');
            });
        </script>
    @endif

    <!-- Si existe una sesion de modificar mostramos el modal nada mas cargar la pagina -->

    @if (session()->get('modificar'))
        <script>
            $(document).ready(function() {
                $('#modificarModal').modal('show');
            });
        </script>
    @endif

@endsection

@section('contenido')

    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Alta de un empleado">
                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#añadirModal"><i
                            class="bi bi-person-fill-add"></i></a></span>
            </div>
            <div class="col text-center">
                <h1>Lista de Empleados</h1>
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
                    <th scope="col">NIF</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellidos</th>
                    <th scope="col">Cargo</th>
                    <th scope="col">Provincia</th>
                    <th scope="col">Municipio</th>
                    <th scope="col">Servicios</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($empleados as $empleado)
                    <tr>
                        <td>{{ $empleado->nif }}</td>
                        <td>{{ $empleado->nombre }}</td>
                        <td>{{ $empleado->apellidos }}</td>
                        <td>{{ $empleado->cargo }}</td>
                        <td>{{ $empleado->provincia->provincia }}</td>
                        <td>{{ $empleado->municipio->municipio }}</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Servicios">
                                <a class="btn btn-dark"
                                    href="{{ route('serviciosEmpleado', ['id' => $empleado->id_empleado]) }}">
                                    <i class="bi bi-bag-fill"></i></a></span>
                        </td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Detalles">
                                <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detallesModal"
                                    data-empleado="{{ $empleado }}">
                                    <i class="bi bi-eye-fill"></i></a></span>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Modificar"
                                onclick="modificar({{ $empleado }})">
                                <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modificarModal"
                                    data-empleado="{{ $empleado }}">
                                    <i class="bi bi-person-fill-gear"></i></a></span>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Dar de baja">
                                <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#borrarModal"
                                    data-empleado="{{ $empleado }}">
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
                <li class="page-item {{ $empleados->currentPage() == 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $empleados->previousPageUrl() }}">Anterior</a>
                </li>
                <li class="page-item {{ $empleados->currentPage() == 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $empleados->url(1) }}">Primera</a>
                </li>
                @for ($i = 1; $i <= $empleados->lastPage(); $i++)
                    <li class="page-item {{ $empleados->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $empleados->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item {{ $empleados->currentPage() == $empleados->lastPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $empleados->url($empleados->lastPage()) }}">Última</a>
                </li>
                <li class="page-item {{ $empleados->currentPage() == $empleados->lastPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $empleados->nextPageUrl() }}">Siguiente</a>
                </li>
            </ul>
        </nav>
    </div>

@endsection

@section('modals')

    <!-- Modal -->

    <!-- Modal detalles empleado -->

    <div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detallesModalLabel"><b>Detalles del empleado</b></h5>
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
                            <th scope="row" class="bg-dark text-light">Cargo</th>
                            <td><span id="detalles-cargo"></span></td>
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

    <!-- Modal añadir empleado -->

    <div class="modal fade" id="añadirModal" tabindex="-1" aria-labelledby="añadirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="añadirModalLabel"><b>Dar de alta un empleado</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('crearUsuarioEmpleado') }}" method="post">
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

                        <div class="row mb-3">

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

                            <div class="col">
                                <label for="exampleInputPassword1" class="form-label">Contraseña: </label>
                                <input type="password" class="form-control border border-primary" name="password"
                                    value="{{ old('password') }}">
                                @if ($errors->has('password') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('password', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col">
                                <label class="form-label">Cargo:</label>
                                <input type="text" class="form-control border border-primary" name="cargo"
                                    @if (old('cargo') && session()->get('crear')) value="{{ old('cargo') }}" @endif>
                                @if ($errors->has('cargo') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('cargo', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
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
                                        <option value="{{ $provincia->id }}"
                                            @if (old('provincia_id') && session()->get('crear')) {{ old('provincia_id') == $provincia->id ? 'selected' : '' }} @endif>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Dar de alta</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal modificar empleado -->

    <div class="modal fade" id="modificarModal" tabindex="-1" aria-labelledby="modificarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modificarModalLabel"><b>Modificar datos del empleado</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="modificar-empleado-form">
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
                                <label class="form-label">Cargo:</label>
                                <input type="text" class="form-control border border-primary" name="cargo"
                                    id="cargo_mod" value="{{ old('cargo') }}">
                                @if ($errors->has('cargo') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('cargo', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Modificar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal borrar empleado -->

    <div class="modal fade" id="borrarModal" tabindex="-1" aria-labelledby="borrarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="borrarModalLabel"><b>Confirmar baja de empleado </b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas dar de baja a este empleado?</p>
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
                            <th scope="row" class="bg-dark text-light">Cargo</th>
                            <td><span id="borrar-cargo"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" class="bg-dark text-light">Fecha de nacimiento</th>
                            <td><span id="borrar-fecha_nacimiento"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" id="borrar-empleado-form" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Dar de baja</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
