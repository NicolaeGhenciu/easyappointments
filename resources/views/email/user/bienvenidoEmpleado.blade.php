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
    <p>¡Hola {{ $empleado->nombre }} {{ $empleado->apellidos }}!</p>
    <p>Bienvenido/a a <b>{{ $empresa->nombre }}</b>. Estamos encantados de que hayas decidido usar
        <b>EasyAppointments</b>, una
        aplicación de gestión de citas que te permitirá optimizar tu trabajo y aumentar tu productividad.</p>
    <p>Estamos seguros de que nuestra aplicación es fácil de usar y será una herramienta valiosa para tu negocio. Si en
        algún momento necesitas ayuda, no dudes en contactarnos, estaremos encantados de ayudarte.</p>
    <p>A continuación, encontrarás tus credenciales:</p>
    <p>Su contraseña: <b>{{ $pass }}</b></p>
    <p>Te recomendamos cambiar tu contraseña después de iniciar sesión por primera vez. Puedes hacerlo desde la sección
        de configuración de tu cuenta. Es importante que cambies tu contraseña lo antes posible por motivos de
        seguridad.</p>
    <p>¡Gracias por elegir EasyAppointments! Esperamos que tengas un excelente día.</p>
    <p>Atentamente,</p>
    <p><b>{{ $empresa->nombre }}</b></p>
</body>
