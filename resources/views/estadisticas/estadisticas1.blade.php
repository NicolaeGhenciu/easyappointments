@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Estadisticas
@endsection

@section('linkScript')

    <script src="https://cdn.jsdelivr.net/npm/chartist-plugin-tooltips@0.0.17/dist/chartist-plugin-tooltip.min.js"></script>

    <style>
        .chartist-tooltip {
            position: relative;
            display: flex;
            width: 2em;
            /* Ancho del cuadrado */
            height: 35px;
            /* Alto del cuadrado */
            opacity: 0;
            min-width: 5em;
            padding: .5em;
            background: #F4C63D;
            color: #453D3F;
            font-family: Oxygen, Helvetica, Arial, sans-serif;
            font-weight: 700;
            text-align: center;
            pointer-events: none;
            z-index: 1;
            -webkit-transition: opacity .2s linear;
            -moz-transition: opacity .2s linear;
            -o-transition: opacity .2s linear;
            transition: opacity .2s linear;
        }

        .chartist-tooltip:before {
            content: "";
            position: absolute;
            /* Cambiado de "st" a "absolute" */
            top: 100%;
            left: 50%;
            width: 0;
            height: 0;
            margin-left: -15px;
            border: 15px solid transparent;
            border-top-color: #F4C63D;
        }

        .chartist-tooltip.tooltip-show {
            opacity: 1;
        }

        .ct-area,
        .ct-line {
            pointer-events: none;
        }

        @media screen and (max-width: 767px) {
            .ct-chart .ct-label {
                font-size: 5px;
                /* Ajusta el tamaño de la fuente según tus necesidades */
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var citas = {!! json_encode($citas) !!};

            // Preparar los datos para el gráfico
            var data = {};

            // Obtener los nombres de los meses
            var monthNames = [
                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            ];

            // Establecer la cantidad inicial en 0 para cada mes
            monthNames.forEach(function(monthName, index) {
                data[monthName] = 0;
            });

            // Agrupar citas por mes y contar la cantidad correspondiente
            citas.forEach(function(cita) {
                var fecha = new Date(cita.fecha_inicio);
                var mes = monthNames[fecha.getMonth()]; // Obtener el nombre del mes

                // Incrementar la cantidad de citas para el mes correspondiente
                data[mes]++;
            });

            // Convertir los datos en el formato necesario para el gráfico
            var chartData = {
                labels: Object.keys(data), // Utilizar los nombres de los meses como etiquetas
                series: [Object.values(data)] // Utilizar la cantidad de citas por mes
            };

            // Opciones de configuración del gráfico
            var options = {
                // Configura las opciones del gráfico según tus necesidades
                // Consulta la documentación de Chartist para más opciones
                plugins: [
                    Chartist.plugins.tooltip()
                ]
            };

            // Crear una instancia de Chartist y generar el gráfico de barras
            var chart = new Chartist.Bar('.ct-chart', chartData, options);

            // Agregar eventos de mouseover y mouseout para mostrar y ocultar tooltips
            var tooltip = document.querySelector('.chartist-tooltip');
            var bars = chart.container.querySelectorAll('.ct-bar');

            bars.forEach(function(bar) {
                bar.addEventListener('mouseover', function(event) {
                    var value = event.target.getAttribute('ct:value');
                    tooltip.innerHTML = value + ' citas';
                    tooltip.style.display = 'block';
                });

                bar.addEventListener('mouseout', function() {
                    tooltip.style.display = 'none';
                });
            });
        });
    </script>

@endsection

@section('contenido')
    <div class="container">
        <div class="row align-items-center">
            <div class="col text-center">
                <h1>Estadisticas</h1>
            </div>
        </div>
    </div>
    <hr>
    <h2>Citas mensuales</h2>
    <div class="ct-chart"></div>
@endsection

@section('modals')
@endsection
