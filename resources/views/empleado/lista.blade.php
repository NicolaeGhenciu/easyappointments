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

@endsection

@section('contenido')
    <h1>Lista de Empleados</h1>
    <hr>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table">
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
                        <td>{{ $empleado->fecha_nacimiento }}</td>
                        <td>{{ $empleado->direccion }}</td>
                        <td>{{ $empleado->telefono }}</td>
                        <td>{{ $empleado->provincia->provincia }}</td>
                        <td>{{ $empleado->municipio->municipio }}</td>
                        <td><a class="btn btn-danger" href="" title="Borrar" data-bs-toggle="modal"
                                data-bs-target="#borrarModal"><i class="bi bi-trash-fill"></i></a>
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

    {{-- <div class="modal fade" id="borrarModal" tabindex="-1" aria-labelledby="borrarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="borrarModalLabel">ATENCION</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas borrar a ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" action="{{ route('borrarEmpleado', $empleado->id_empleado) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Borrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

@endsection
