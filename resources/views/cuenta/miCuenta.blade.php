@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Mi cuenta
@endsection

@section('linkScript')
    <script>
        function showModificarEmpresaContraseña() {
            $("#body-empresa").fadeOut(200, function() {
                $("#body-empresa-pass").fadeIn(200);
            });
        }
    </script>

    @if (session()->get('cambiar'))
        <script>
            $(document).ready(function() {
                $("#body-empresa").hide();
                $("#body-empresa-pass").show();
            });
        </script>
    @endif
@endsection

@section('contenido')
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    @if (Auth::check() && Auth::user()->role == 'empresa')
        <div class="container" id="body-empresa">
            <div class="card mt-5">
                <div class="card-header">
                    <h1 class="text-center">Cuenta de empresa <i class="bi bi-building-fill"></i></h1>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Información</h4>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Nombre:</strong> {{ $empresa->nombre }}
                        </li>
                        <li class="list-group-item">
                            <strong>Email:</strong> {{ Auth::user()->email }}
                        </li>
                        <li class="list-group-item">
                            <strong>CIF:</strong> {{ $empresa->cif }}
                        </li>
                        <li class="list-group-item">
                            <strong>Teléfono:</strong> {{ $empresa->telefono }}
                        </li>
                        <li class="list-group-item">
                            <strong>Dirección:</strong> {{ $empresa->direccion }}
                        </li>
                        <li class="list-group-item">
                            <strong>Provincia:</strong> {{ $empresa->provincia->provincia }}
                        </li>
                        <li class="list-group-item">
                            <strong>Municipio:</strong> {{ $empresa->municipio->municipio }}
                        </li>
                    </ul>
                </div>
                <a class="btn btn-warning" onclick="showModificarEmpresaContraseña()">Cambiar contraseña <i
                        class="bi bi-lock"></i></a>
            </div>
        </div>
    @endif

    @if (Auth::check() && Auth::user()->role == 'empleado')
        <div class="container" id="body-empresa">
            <div class="card mt-5">
                <div class="card-header">
                    <h1 class="text-center">Cuenta de empelado <i class="bi bi-person-circle"></i></h1>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Información</h4>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Nombre:</strong> {{ $empleado->nombre }}
                        </li>
                        <li class="list-group-item">
                            <strong>Apellidos:</strong> {{ $empleado->apellidos }}
                        </li>
                        <li class="list-group-item">
                            <strong>Email:</strong> {{ Auth::user()->email }}
                        </li>
                        <li class="list-group-item">
                            <strong>NIF:</strong> {{ $empleado->nif }}
                        </li>
                        <li class="list-group-item">
                            <strong>Cargo:</strong> {{ $empleado->cargo }}
                        </li>
                        <li class="list-group-item">
                            <strong>Fecha de nacimiento:</strong>
                            {{ date('d-m-Y', strtotime($empleado->fecha_nacimiento)) }}
                        </li>
                        <li class="list-group-item">
                            <strong>Teléfono:</strong> {{ $empleado->telefono }}
                        </li>
                        <li class="list-group-item">
                            <strong>Dirección:</strong> {{ $empleado->direccion }}
                        </li>
                        <li class="list-group-item">
                            <strong>Provincia:</strong> {{ $empleado->provincia->provincia }}
                        </li>
                        <li class="list-group-item">
                            <strong>Municipio:</strong> {{ $empleado->municipio->municipio }}
                        </li>
                    </ul>
                </div>
                <a class="btn btn-warning" onclick="showModificarEmpresaContraseña()">Cambiar contraseña <i
                        class="bi bi-lock"></i></a>
            </div>
        </div>
    @endif

    @if (Auth::check() && Auth::user()->role == 'cliente')
        <div class="container" id="body-empresa">
            <div class="card mt-5">
                <div class="card-header">
                    <h1 class="text-center">Cuenta de cliente <i class="bi bi-person-fill"></i></h1>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Información</h4>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Nombre:</strong> {{ $cliente->nombre }}
                        </li>
                        <li class="list-group-item">
                            <strong>Apellidos:</strong> {{ $cliente->apellidos }}
                        </li>
                        <li class="list-group-item">
                            <strong>Email:</strong> {{ Auth::user()->email }}
                        </li>
                        <li class="list-group-item">
                            <strong>NIF:</strong> {{ $cliente->nif }}
                        </li>
                        <li class="list-group-item">
                            <strong>Fecha de nacimiento:</strong>
                            {{ date('d-m-Y', strtotime($cliente->fecha_nacimiento)) }}
                        </li>
                        <li class="list-group-item">
                            <strong>Teléfono:</strong> {{ $cliente->telefono }}
                        </li>
                        <li class="list-group-item">
                            <strong>Dirección:</strong> {{ $cliente->direccion }}
                        </li>
                        <li class="list-group-item">
                            <strong>Provincia:</strong> {{ $cliente->provincia->provincia }}
                        </li>
                        <li class="list-group-item">
                            <strong>Municipio:</strong> {{ $cliente->municipio->municipio }}
                        </li>
                    </ul>
                </div>
                <a class="btn btn-warning" onclick="showModificarEmpresaContraseña()">Cambiar contraseña <i
                        class="bi bi-lock"></i></a>
            </div>
        </div>
    @endif

    <div class="container" id="body-empresa-pass" style="display:none;">
        <div class="card mt-5">
            <div class="card-header">
                <h1 class="text-center">Nueva contraseña</h1>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <form action="{{ route('cambiarPass') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <input type="password" name="pass" class="form-control" id="password"
                                    placeholder="Ingrese su nueva contraseña">
                                @if ($errors->has('pass'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('pass', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Repita la Contraseña</label>
                                <input type="password" name="pass2" class="form-control" id="confirmPassword"
                                    placeholder="Repita su contraseña">
                                @if ($errors->has('pass2'))
                                    <div class="alert alert-danger mt-1">
                                        {!! $errors->first('pass2', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endsection

@section('modals')
@endsection
