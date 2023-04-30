@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Noticias
@endsection

@section('contenido')
    <h1>Bienvenido a EasyAppointments</h1>
    <hr>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    Microsoft lanza una actualización de seguridad para Windows 10
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <strong>Microsoft lanza una actualización de seguridad para Windows 10
                        Microsoft ha lanzado una actualización de seguridad para Windows 10 para corregir varias
                        vulnerabilidades de seguridad en el sistema operativo. Se recomienda a todos los usuarios de Windows
                        10 que actualicen sus dispositivos para evitar posibles amenazas.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Facebook anuncia nuevas funciones de privacidad para su plataforma
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <strong>Facebook anuncia nuevas funciones de privacidad para su plataforma
                        Facebook ha anunciado nuevas funciones de privacidad para su plataforma, incluyendo una opción para
                        eliminar automáticamente el historial de actividad en la aplicación. Estas nuevas funciones están
                        diseñadas para ofrecer a los usuarios un mayor control sobre su información personal.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Apple presenta su nuevo modelo de MacBook Pro con chip M1X
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <strong>Apple presenta su nuevo modelo de MacBook Pro con chip M1X
                        Apple ha presentado su nuevo modelo de MacBook Pro con chip M1X, que ofrece un rendimiento aún más
                        rápido y eficiente que su predecesor. El nuevo modelo también incluye una pantalla de mayor
                        resolución y un diseño más delgado y ligero.
                </div>
            </div>
        </div>
    </div>
@endsection
