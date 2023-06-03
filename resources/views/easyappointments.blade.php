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
                    Módulo de gestión para empresas &nbsp; <i class="bi bi-buildings-fill"></i>
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <strong>En esta sección, las empresas pueden
                        administrar su negocio y gestionar las citas. Pueden dar de alta su empresa, agregar
                        información sobre sus servicios y empleados, establecer horarios disponibles, y tener acceso a un
                        calendario para gestionar eficientemente sus citas y horarios.
                        <div style="text-align: center;">
                            <img src="{{ asset('img/bienvenido.png') }}" alt=""
                                style="display: block; margin-left: auto; margin-right: auto; max-width: 100%;">
                        </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Módulo de gestion para empleados &nbsp; <i class="bi bi-person-circle"></i>
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <strong>Este módulo está diseñado específicamente para los empleados de las empresas que utilizan
                        EasyAppointments. Los empleados tienen acceso a una sección dedicada donde pueden ver las citas
                        disponibles, crear nuevas citas y gestionar de manera eficiente su agenda de citas.
                        <div style="text-align: center;">
                            <img src="{{ asset('img/bienvenido.png') }}" alt=""
                                style="display: block; margin-left: auto; margin-right: auto; max-width: 100%;">
                        </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Módulo de gestion para clientes &nbsp; <i class="bi bi-person-fill"></i>
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <strong>En esta parte de la aplicación, los clientes tienen la capacidad de buscar empresas y servicios
                        disponibles. Pueden verificar la disponibilidad de citas en función de los horarios establecidos por
                        las empresas y realizar reservas en los horarios que les resulten convenientes.
                        <div style="text-align: center;">
                            <img src="{{ asset('img/bienvenido.png') }}" alt=""
                                style="display: block; margin-left: auto; margin-right: auto; max-width: 100%;">
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
