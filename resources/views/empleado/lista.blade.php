@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Empleados
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
        $empleado = null;
        $(document).ready(function() {
            $('#borrarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var empleado = button.data('empleado');
                $('#borrar-nombre').text(empleado.nombre + " " + empleado.apellidos);
                $('#borrar-nif').text(empleado.nif);
                $('#borrar-cargo').text(empleado.cargo);
                $('#borrar-empleado-form').submit(function() {
                    var url = "{{ route('borrarEmpleado', ['id' => '0']) }}";
                    url = url.replace('0', empleado.id_empleado);
                    $('#borrar-empleado-form').attr('action', url);
                });
            });
        })

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

    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#añadirModal').modal('show');
            });
        </script>
    @endif

@endsection

@section('contenido')
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#añadirModal"><i
                        class="bi bi-person-fill-add"></i></a>
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
                    <th scope="col">Fecha de nacimiento</th>
                    <th scope="col">Direccción</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Provincia</th>
                    <th scope="col">Municipio</th>
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
                        <td>{{ date('d-m-Y', strtotime($empleado->fecha_nacimiento)) }}</td>
                        <td>{{ $empleado->direccion }}</td>
                        <td>{{ $empleado->telefono }}</td>
                        <td>{{ $empleado->provincia->provincia }}</td>
                        <td>{{ $empleado->municipio->municipio }}</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Ver detalles">
                                <a class="btn btn-info" href="" data-bs-toggle="modal"
                                    data-bs-target="#detallesModal" data-empleado="{{ $empleado }}">
                                    <i class="bi bi-eye-fill"></i></a></span>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Borrar">
                                <a class="btn btn-danger" href="" data-bs-toggle="modal"
                                    data-bs-target="#borrarModal" data-empleado="{{ $empleado }}">
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

    <!-- Modal -->

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
                    <p><b>Nombre y apellido: </b> <span id="borrar-nombre"></span></p>
                    <p><b>NIF :</b> <span id="borrar-nif"></span></p>
                    <p><b>Cargo :</b> <span id="borrar-cargo"></span></p>
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

    <!-- Modal añadir empleado -->

    <div class="modal fade" id="añadirModal" tabindex="-1" aria-labelledby="añadirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="añadirModalLabel"><b>Añdir empleado</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('crearUsuarioEmpleado') }}" method="post">
                        @csrf
                        <div class="row mb-3">

                            <div class="col">
                                <label class="form-label">Nombre:</label>
                                <input type="text" class="form-control border border-primary" name="nombre"
                                    value="{{ old('nombre') }}">
                                @if ($errors->has('nombre'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('nombre', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">Apellidos:</label>
                                <input type="text" class="form-control border border-primary" name="apellidos"
                                    value="{{ old('apellidos') }}">
                                @if ($errors->has('apellidos'))
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
                                    value="{{ old('fecha_nacimiento') }}">
                                @if ($errors->has('fecha_nacimiento'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('fecha_nacimiento', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">NIF:</label>
                                <input type="text" class="form-control border border-primary" name="nif"
                                    value="{{ old('nif') }}">
                                @if ($errors->has('nif'))
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
                                @if ($errors->has('email'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('email', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label for="exampleInputPassword1" class="form-label">Contraseña: </label>
                                <input type="password" class="form-control border border-primary" name="password"
                                    value="{{ old('password') }}">
                                @if ($errors->has('password'))
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
                                    value="{{ old('cargo') }}">
                                @if ($errors->has('cargo'))
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
                                    value="{{ old('direccion') }}">
                                @if ($errors->has('direccion'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('direccion', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">Teléfono:</label>
                                <input type="number" class="form-control border border-primary" name="telefono"
                                    value="{{ old('telefono') }}">
                                @if ($errors->has('telefono'))
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
                                            {{ old('provincia_id') == $provincia->id ? 'selected' : '' }}>
                                            {{ $provincia->provincia }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('provincia_id'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('provincia_id', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label for="municipio_id" class="form-label">Municipio:</label>
                                <select class="form-select" name="municipio_id" id="municipio_id" disabled>
                                </select>
                                @if ($errors->has('municipio_id'))
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

@endsection
