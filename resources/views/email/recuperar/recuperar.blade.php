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
    <p>Estimado/a {{ $nombre }} {{ $apellidos ?? '' }},</p>
    <p>Es un placer informarte que se ha realizado la modificación de tu contraseña en EasyAppointments. A continuación,
        te proporcionamos tu nueva contraseña:</p>
    <h2 style="color:goldenrod"><b>{{ $pass }}</b></h2>
    <p>Agradecemos sinceramente que hayas elegido EasyAppointments como tu aplicación de gestión de citas. Nos
        esforzamos por brindarte una experiencia excepcional y estamos seguros de que disfrutarás de todas las funciones
        y beneficios que nuestra aplicación tiene para ofrecer.</p>
    <p>¡Gracias nuevamente por confiar en EasyAppointments!</p>
    <p>Atentamente,</p>
    <p><b>El equipo de EasyAppointments</b></p>
</body>
