@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Agenda
@endsection

@section('linkScript')

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.6/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {

                headerToolbar: {
                    left: 'prev,today,next',
                    center: 'title',
                    right: 'dayGridMonth,listDay,listWeek'
                },

                // customize the button names,
                // otherwise they'd all just say "list"
                views: {
                    listDay: {
                        buttonText: 'list day'
                    },
                    listWeek: {
                        buttonText: 'list week'
                    },
                    dayGridMonth: {
                        buttonText: 'month'
                    }
                },

                initialView: 'listDay',
                initialDate: new Date(new Date().getTime() - (new Date().getTimezoneOffset() * 60000))
                    .toISOString().slice(0, 10),
                navLinks: true,
                editable: false,
                dayMaxEvents: true,
                events: [
                    @foreach ($citas as $cita)
                        {
                            title: '#{{ $cita->servicio->nombre }} {{ $cita->cliente->apellidos }}',
                            start: '{{ $cita->fecha_inicio }}',
                            end: '{{ $cita->fecha_fin }}',
                            extendedProps: {
                                id: '{{ $cita->id_cita }}',
                                status: '{{ $cita->status }}',
                            },
                            color: @if ($cita->status == 'confirmada')
                                'green'
                            @elseif ($cita->status == 'cancelada')
                                'red'
                            @else
                                'gray'
                            @endif ,
                        },
                    @endforeach
                ],

                eventClick: function(info) {

                    // $('#detallesModal').on('show.bs.modal', function(event) {

                    // });
                    $('#detallesModal').modal('show');
                    // obtener la información de la cita
                    var title = info.event.title;
                    var start = info.event.start;
                    var end = info.event.end;

                    // mostrar un alert con la información de la cita
                    console.log(info.event.extendedProps)
                }

            });

            calendar.render();
        });
    </script>

    <style>
        #calendar {
            height: 75vh;
            /* el calendario ocupará el 60% de la altura de la pantalla */
        }

        .fc-daygrid-day-number {
            color: black;
            text-decoration: none;
            /* Este estilo desactiva el subrayado de texto que se utiliza para los enlaces hipervínculos */
        }

        .fc-col-header-cell-cushion {
            cursor: default;
            pointer-events: none;
            color: black;
            text-decoration: none;
            /* Este estilo desactiva el subrayado de texto que se utiliza para los enlaces hipervínculos */
        }
    </style>

@endsection

@section('contenido')

    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Programar una nueva cita">
                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#añadirModal">
                        <i class="bi bi-calendar-plus-fill"></i></a></span>
            </div>
            <div class="col text-center">
                <h3>Agenda - {{ Auth::user()->nombre }}</h3>
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

    <div id='calendar'></div>

@endsection


@section('modals')

    <!-- Modal -->

    <div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detallesModalLabel"><b>Confirmar baja de servicio </b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

@endsection
