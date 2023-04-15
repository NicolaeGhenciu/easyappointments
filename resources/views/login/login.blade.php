<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <script src={{ asset('js/login.js') }}></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
        integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2/dist/umd/popper.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Yantramanav&display=swap');

        * {
            font-family: 'Yantramanav', sans-serif;
        }

        body {
            background-color: rgb(84, 144, 254)
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center mt-3 mb-3 mx-3">
        <div class="col-md-6 p-5 shadow-sm border rounded-5 bg-white" style="border-radius: 2%">
            <h3 class="text-center mb-2 text-primary"><svg height="40px" width="40px" version="1.1" id="Layer_1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                    xml:space="preserve" fill="#000000">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <polygon style="fill:#E0E0E0;" points="512,467.149 0,467.149 79.087,107.324 432.913,107.324 ">
                        </polygon>
                        <polygon style="fill:#0094E2;"
                            points="432.913,107.324 79.087,107.324 58.79,199.672 453.211,199.672 "></polygon>
                        <polygon style="fill:#3B67AA;" points="432.913,107.324 353.827,467.149 512,467.149 "></polygon>
                        <polygon style="fill:#F1F1F1;" points="79.087,107.324 0,467.149 176.913,467.149 256,107.324 ">
                        </polygon>
                        <polygon style="fill:#3EBBFB;"
                            points="235.703,199.672 256,107.324 79.087,107.324 58.79,199.672 "></polygon>
                        <g>
                            <path style="fill:#3B67AA;"
                                d="M189.138,169.796c-34.447,0-62.471-28.025-62.471-62.473s28.025-62.473,62.471-62.473 c34.447,0,62.473,28.025,62.473,62.473h-31.343c0-17.165-13.965-31.129-31.129-31.129s-31.128,13.965-31.128,31.129 s13.965,31.129,31.128,31.129V169.796z">
                            </path>
                            <path style="fill:#3B67AA;"
                                d="M322.862,169.796c-34.447,0-62.473-28.025-62.473-62.473s28.025-62.473,62.473-62.473 s62.473,28.025,62.473,62.473h-31.343c0-17.165-13.965-31.129-31.129-31.129c-17.165,0-31.129,13.965-31.129,31.129 s13.965,31.129,31.129,31.129L322.862,169.796L322.862,169.796z">
                            </path>
                        </g>
                    </g>
                </svg>&nbsp;EasyAppointments</h3>

            <form id="login-form" action="{{ route('login') }}" method="post"
                @if (old('role') === 'empresa' || old('role') === 'cliente') style="display: none" @endif>
                @csrf

                <hr>

                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email:</label>
                    <input type="email" class="form-control border border-primary" id="exampleInputEmail1"
                        aria-describedby="emailHelp" name="email">
                    {!! $errors->first('email', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                </div>

                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Contraseña: </label>
                    <input type="password" class="form-control border border-primary" id="exampleInputPassword1"
                        name="password">
                    {!! $errors->first('password', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                </div>

                <p class="small"><a class="text-primary" href=""></a></p>

                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Iniciar sesión</button>
                    <a href="" class="btn btn-danger mt-1" type="submit">Continuar con &nbsp;<i
                            class="bi bi-google"></i></a>
                    <a href="" class="btn btn-dark mt-1" type="submit">Continuar con &nbsp;<i
                            class="bi bi-github"></i></a>
                    <a href="" class="btn btn-warning mt-1" type="submit">¿Has olvidado tu
                        contraseña? <i class="bi bi-key-fill"></i> </a>
                    <a class="btn btn-info mt-1" onclick="showRegisterForm()">¿No tienes
                        cuenta? <i class="bi bi-person-fill-add"></i></a>
                </div>

            </form>

            <div id="register-form" style="display: none">
                <hr>
                <h3 class="d-flex justify-content-center">¿Que eres?</h3>
                <div class="d-flex justify-content-center">
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <button type="button" class="btn btn-primary" onclick="showRegisterCliente()">Cliente</button>
                        <button type="button" class="btn btn-warning" onclick="showRegisterEmpresa()">Empresa</button>
                    </div>
                </div>
            </div>

            <div id="cliente-form"
                @if (old('role') === 'cliente') style="display:block;" @else style="display:none;" @endif>

                <form action="{{ route('crearUsuarioCliente') }}" method="post">
                    @csrf
                    <hr>
                    <h5 class="text-center">Registrarse como cliente</h5>
                    <hr>
                    <div class="row mb-3">

                        <div class="col">
                            <label for="exampleInputEmail1" class="form-label">Nombre:</label>
                            <input type="text" class="form-control border border-primary" name="nombre"
                                value="{{ old('nnombreame') }}">
                            {!! $errors->first('nombre', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>

                        <div class="col">
                            <label for="exampleInputEmail1" class="form-label">Apellidos:</label>
                            <input type="text" class="form-control border border-primary" name="apellidos"
                                value="{{ old('apellidos') }}">
                            {!! $errors->first('apellidos', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="exampleInputEmail1" class="form-label">Fecha de nacimiento:</label>
                            <input type="date" class="form-control border border-primary" name="fecha_nacimiento"
                                value="{{ old('fecha_nacimiento') }}">
                            {!! $errors->first('fecha_nacimiento', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>

                        <div class="col">
                            <label for="exampleInputEmail1" class="form-label">DNI:</label>
                            <input type="text" class="form-control border border-primary" name="dni"
                                value="{{ old('dni') }}">
                            {!! $errors->first('dni', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>
                    </div>

                    <div class="row mb-3">

                        <div class="col">
                            <label for="exampleInputEmail1" class="form-label">Email:</label>
                            <input type="email" class="form-control border border-primary" name="email"
                                value="{{ old('email') }}">
                            {!! $errors->first('email', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>

                        <div class="col">
                            <label for="exampleInputPassword1" class="form-label">Contraseña: </label>
                            <input type="password" class="form-control border border-primary" name="password"
                                value="{{ old('password') }}">
                            {!! $errors->first('password', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col">
                            <label for="exampleInputEmail1" class="form-label">Dirección:</label>
                            <input type="text" class="form-control border border-primary" name="direccion"
                                value="{{ old('direccion') }}">
                            {!! $errors->first('direccion', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>

                        <div class="col">
                            <label for="exampleInputEmail1" class="form-label">Teléfono:</label>
                            <input type="number" class="form-control border border-primary" name="telefono"
                                value="{{ old('telefono') }}">
                            {!! $errors->first('telefono', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col">
                            <label for="provincia" class="form-label">Provincia:</label>
                            <select class="form-select" name="provincia_id">
                                @foreach ($provincias as $provincia)
                                    <option value="{{ $provincia->id }}"
                                        {{ old('provincia_id') == $provincia->id ? 'selected' : '' }}>
                                        {{ $provincia->provincia }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <label for="municipio" class="form-label">Municipio:</label>
                            <select class="form-select" name="municipio_id">
                                @foreach ($municipios as $municipio)
                                    <option value="{{ $municipio->id }}"
                                        {{ old('municipio_id') == $municipio->id ? 'selected' : '' }}>
                                        {{ $municipio->municipio }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary" type="submit">Crear cuenta</button>
                    </div>

                </form>

                <div class="d-grid">
                    <a class="btn btn-info mt-1" onclick="showLoginForm()">Iniciar sesión</a>
                </div>

            </div>

            <div id="empresa-form"
                @if (old('role') === 'empresa') style="display:block;" @else style="display:none;" @endif>

                <form action="{{ route('crearUsuarioEmpresa') }}" method="post">
                    @csrf
                    <hr>
                    <h5 class="text-center">Registrarse como empresa</h5>
                    <hr>
                    <div class="row mb-3">

                        <div class="col">
                            <label for="exampleInputEmail1" class="form-label">Razón social:</label>
                            <input type="text" class="form-control border border-primary" name="nombre"
                                value="{{ old('nombre') }}">
                            {!! $errors->first('nombre', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}

                        </div>

                        <div class="col">
                            <label for="exampleInputEmail1" class="form-label">CIF:</label>
                            <input type="text" class="form-control border border-primary" name="cif"
                                value="{{ old('cif') }}">
                            {!! $errors->first('cif', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>
                    </div>

                    <div class="row mb-3">

                        <div class="col">
                            <label for="exampleInputEmail1" class="form-label">Email:</label>
                            <input type="email" class="form-control border border-primary" name="email"
                                value="{{ old('email') }}">
                            {!! $errors->first('email', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>

                        <div class="col">
                            <label for="exampleInputPassword1" class="form-label">Contraseña: </label>
                            <input type="password" class="form-control border border-primary" name="password"
                                value="{{ old('password') }}">
                            {!! $errors->first('password', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col">
                            <label for="exampleInputEmail1" class="form-label">Dirección:</label>
                            <input type="text" class="form-control border border-primary" name="direccion"
                                value="{{ old('direccion') }}">
                            {!! $errors->first('direccion', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>

                        <div class="col">
                            <label for="exampleInputEmail1" class="form-label">Teléfono:</label>
                            <input type="number" class="form-control border border-primary" name="telefono"
                                value="{{ old('telefono') }}">
                            {!! $errors->first('telefono', '<b style="color: rgb(184, 0, 0)">:message</b>') !!}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col">
                            <label for="provincia" class="form-label">Provincia:</label>
                            <select class="form-select" name="provincia_id">
                                @foreach ($provincias as $provincia)
                                    <option value="{{ $provincia->id }}"
                                        {{ old('provincia_id') == $provincia->id ? 'selected' : '' }}>
                                        {{ $provincia->provincia }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <label for="municipio" class="form-label">Municipio:</label>
                            <select class="form-select" name="municipio_id">
                                @foreach ($municipios as $municipio)
                                    <option value="{{ $municipio->id }}"
                                        {{ old('municipio_id') == $municipio->id ? 'selected' : '' }}>
                                        {{ $municipio->municipio }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary" type="submit">Crear cuenta</button>
                    </div>

                </form>

                <div class="d-grid">
                    <a class="btn btn-info mt-1" onclick="showLoginForm()">Iniciar sesión</a>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
