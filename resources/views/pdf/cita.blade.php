<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="wid_citath=device-wid_citath, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Cita {{ $cita->servicio->cod }} {{ $cita->cliente->nombre }}</title>
    <style>
        .card-body {
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1 class="text-center"><img src="{{ asset('img/icono.png') }}" wid_citath="50px" height="50px">
                </h1>
                <div class="card">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title text-center" id="detallesModalLabel">
                            <b>EasyAppointments - Datos de la cita</b>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group list-group-item">
                            <label><b>Nombre de la empresa:</b> {{ $cita->empresa->nombre }}</label>
                            <label><b>CIF de la empresa: </b>{{ $cita->empresa->cif }}</label>
                            <label><b>Teléfono de la empresa: </b>{{ $cita->empresa->telefono }} </label>
                        </div>
                        <div class="form-group list-group-item">
                            <label><b>Nombre y Apellidos del Empleado: </b> {{ $cita->empleado->nombre }}
                                {{ $cita->empleado->apellidos }}</label>
                            <label><b>NIF del empleado: </b>{{ $cita->empleado->nif }}</label>
                        </div>
                        <div class="form-group list-group-item">
                            <label><b>Código del servicio: </b>{{ $cita->servicio->cod }}</label>
                            <label><b>Nombre del servicio: </b>{{ $cita->servicio->nombre }}</label>
                            <label><b>Precio del servicio: </b>{{ $cita->servicio->precio }}€</label><br>
                            <label><b>Duración del servicio: </b>{{ $cita->servicio->duracion }} minutos</label>
                        </div>
                        <div class="form-group list-group-item">
                            <label><b>NIF del cliente:</b> {{ $cita->cliente->nif }}</label>
                            <label><b>Nombre y apellidos del cliente: </b>{{ $cita->cliente->nombre }}
                                {{ $cita->cliente->apellidos }}</label>
                        </div>
                        <div class="form-group list-group-item">
                            <label><b>Fecha de inicio:</b> {{ $cita->fecha_inicio }}</label>
                            <label><b>Fecha de fin:</b> {{ $cita->fecha_fin }}</label> <br>
                            <label><b>Estado: </b>{{ $cita->status }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
