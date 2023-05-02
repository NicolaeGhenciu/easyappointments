@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Gestionar servicios
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

            //Este evento se activa al abrir el modal de borrar y se encarga de cargar los datos del servicio.

            $('#borrarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var servicio = button.data('servicio');
                $('#borrar-cod').text(servicio.cod);
                $('#borrar-nombre').text(servicio.nombre);
                $('#borrar-descripcion').text(servicio.descripcion);
                $('#borrar-precio').text(servicio.precio);
                $('#borrar-servicio-form').submit(function() {
                    // event.preventDefault();
                    //var url = "{{ route('borrarServicio', ['id' => '0']) }}";
                    var url = "{{ route('borrarServicio', ['id' => ':idservicio']) }}";
                    //url = url.replace(/:idservicio/g, servicio.id_servicio);
                    url = url.replace(':idservicio', servicio.id_servicio);
                    $('#borrar-servicio-form').attr('action', url);
                });
            });

        });

        //Esta funcion se activa al clicar el boton de modificar y se encarga de cargar los datos del servicio.

        function modificar(servicio) {
            $("#cod_mod").val(servicio.cod);
            $("#nombre_mod").val(servicio.nombre);
            $("#descripcion_mod").val(servicio.descripcion);
            $("#precio_mod").val(servicio.precio);
            $('#modificar-servicio-form').submit(function() {
                var url = "{{ route('modificarServicio', ['id' => 'idservicio']) }}";
                url = url.replace('idservicio', servicio.id_servicio);
                $('#modificar-servicio-form').attr('action', url);
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
                $('#modificar-servicio-form').submit(function() {
                    var url = "{{ route('modificarServicio', ['id' => 'idservicio']) }}";
                    url = url.replace('idservicio', {{ old('id_servicio', session('id_servicio')) }});
                    $('#modificar-servicio-form').attr('action', url);
                });
            });
        </script>
    @endif

@endsection

@section('contenido')

    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Alta de un servicio">
                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#añadirModal">
                        <i class="bi bi-bag-plus-fill"></i></a></span>
            </div>
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
                        <td>{{ $servicio->cod }}</td>
                        <td>{{ $servicio->nombre }}</td>
                        <td>{{ $servicio->descripcion }}</td>
                        <td>{{ $servicio->precio }} €</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Modificar"
                                onclick="modificar({{ $servicio }})">
                                <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modificarModal"
                                    data-servicio="{{ $servicio }}">
                                    <i class="bi bi-gear-fill"></i></a></span>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Dar de baja">
                                <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#borrarModal"
                                    data-servicio="{{ $servicio }}">
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

    <!-- Modal añadir servicio -->

    <div class="modal fade" id="añadirModal" tabindex="-1" aria-labelledby="añadirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="añadirModalLabel"><b>Dar de alta un servicio</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('crearServicio') }}" method="post">
                        @csrf

                        <div class="row mb-3">

                            <div class="col">
                                <label class="form-label">Código:</label>
                                <input type="text" class="form-control border border-primary" name="cod"
                                    @if (old('cod') && session()->get('crear')) value="{{ old('cod') }}" @endif>
                                @if ($errors->has('cod') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('cod', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

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
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Descripción:</label>
                                <input type="text" class="form-control border border-primary" name="descripcion"
                                    @if (old('descripcion') && session()->get('crear')) value="{{ old('descripcion') }}" @endif>
                                @if ($errors->has('descripcion') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('descripcion', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">Precio:</label>
                                <input type="text" class="form-control border border-primary" name="precio"
                                    @if (old('precio') && session()->get('crear')) value="{{ old('precio') }}" @endif>
                                @if ($errors->has('precio') && session()->get('crear'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('precio', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
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

    <!-- Modal modificar servicios -->

    <div class="modal fade" id="modificarModal" tabindex="-1" aria-labelledby="modificarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modificarModalLabel"><b>Modificar datos del servicio</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="modificar-servicio-form">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">

                            <div class="col">
                                <label class="form-label">Código:</label>
                                <input type="text" class="form-control border border-primary" name="cod"
                                    id="cod_mod" value="{{ old('cod') }}">
                                @if ($errors->has('cod') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('cod', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

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

                        </div>

                        <div class="row mb-3">

                            <div class="col">
                                <label class="form-label">Descripción:</label>
                                <input type="text" class="form-control border border-primary" name="descripcion"
                                    id="descripcion_mod" value="{{ old('descripcion') }}">
                                @if ($errors->has('descripcion') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('descripcion', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col">
                                <label class="form-label">Precio:</label>
                                <input type="text" class="form-control border border-primary" name="precio"
                                    id="precio_mod" value="{{ old('precio') }}">
                                @if ($errors->has('precio') && session()->get('modificar'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('precio', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
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

    <!-- Modal borrar servicio -->

    <div class="modal fade" id="borrarModal" tabindex="-1" aria-labelledby="borrarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="borrarModalLabel"><b>Confirmar baja de servicio </b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas dar de baja este servicio?</p>
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
                    <form method="POST" id="borrar-servicio-form" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Dar de baja</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
