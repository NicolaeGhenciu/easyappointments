<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>

<body>
    <h2>
        📅&nbsp;EasyAppointments
    </h2>
    <hr>
    <p>Estimado/a {{ $cliente->nombre }} {{ $cliente->apellidos }},</p>
    ! Estamos encantados de que hayas decidido utilizar
    <b>EasyAppointments</b>, una aplicación de gestión de citas que te ayudará a planificar tus citas de manera
    eficiente y a aumentar tu productividad.</p>
    <p>Estamos seguros de que encontrarás nuestra aplicación fácil de usar y útil para tu día a día.</p>
    <p>A continuación, encontrarás tus credenciales:</p>
    <p>Su contraseña: <b>{{ $pass }}</b></p>
    <p>Te recomendamos cambiar tu contraseña después de iniciar sesión por primera vez. Puedes hacerlo desde la sección
        de configuración de tu cuenta. Es importante que cambies tu contraseña lo antes posible por motivos de
        seguridad.</p>
    <p>¡Gracias por elegir EasyAppointments! Esperamos que tengas una excelente experiencia con nuestra aplicación.</p>
    <p>Atentamente,</p>
    <p><b>El equipo de EasyAppointments</b></p>
</body>
