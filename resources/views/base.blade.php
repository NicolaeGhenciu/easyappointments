<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ asset('img/icono.png') }}" type="image/x-icon">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
        integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous">
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
        integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">

    <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">

    <script src={{ asset('js/base.js') }}></script>

    <script>
        window.onload = function() {
            document.getElementById('logout-link').addEventListener('click', function(event) {
                event.preventDefault();

                var form = document.createElement('form');
                form.setAttribute('method', 'POST');
                form.setAttribute('action', "{{ route('logout') }}");

                var csrfField = document.createElement('input');
                csrfField.setAttribute('type', 'hidden');
                csrfField.setAttribute('name', '_token');
                csrfField.setAttribute('value', '{{ csrf_token() }}');

                form.appendChild(csrfField);
                document.body.appendChild(form);
                form.submit();
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Yantramanav&display=swap');

        * {
            font-family: 'Yantramanav', sans-serif;
        }
    </style>

    @yield('linkScript')

</head>

<body>
    <nav class="navbar navbar-light bg-light p-2">
        <div class="d-flex col-12 col-md-3 col-lg-2 mb-2 mb-lg-0 flex-wrap flex-md-nowrap justify-content-between">
            <a class="navbar-brand" href="#">
                <svg height="40px" width="40px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"
                    fill="#000000">
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
                </svg>&nbsp;EasyAppointments
            </a>
            <button class="navbar-toggler d-md-none collapsed mb-3" type="button" data-toggle="collapse"
                data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="col-12 col-md-5 col-lg-8 d-flex align-items-center justify-content-md-end mt-3 mt-md-0">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-expanded="false">
                    @if (Auth::user()->role == 'empresa')
                        <i class="bi bi-building-fill"></i>
                    @elseif (Auth::user()->role == 'empleado')
                        <i class="bi bi-person-circle"></i></i>
                    @elseif (Auth::user()->role == 'cliente')
                        <i class="bi bi-person-fill"></i>
                    @endif
                    {{ Auth::user()->nombre }}
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person-fill-gear"></i></i> Mi cuenta</a>
                    </li>
                    <li><a class="dropdown-item" href="#" id="logout-link"><i class="bi bi-door-closed-fill"></i>
                            Cerrar sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('easyappointments') ? 'active' : '' }}"
                                aria-current="page" href="{{ route('easyappointments') }}" aria-current="page"
                                href="{{ route('easyappointments') }}">
                                <i class="bi bi-house-fill"></i>
                                <span class="ml-2">Noticias</span>
                            </a>
                        </li>
                        @if (Auth::check() && Auth::user()->role == 'empresa')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('listarEmpleados') ? 'active' : '' }}"
                                    aria-current="page" href="{{ route('listarEmpleados') }}">
                                    <i class="bi bi-people-fill"></i>
                                    <span class="ml-2">Gestionar Empleados</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('listarServicios') ? 'active' : '' }}"
                                    aria-current="page" href="{{ route('listarServicios') }}" aria-current="page"
                                    href="{{ route('listarServicios') }}">
                                    <i class="bi bi-bag-fill"></i>
                                    <span class="ml-2">Gestionar Servicios</span>
                                </a>
                            </li>
                        @endif
                        @if ((Auth::check() && Auth::user()->role == 'empleado') || Auth::user()->role == 'empresa')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('listarClientes') ? 'active' : '' }}"
                                    aria-current="page" href="{{ route('listarClientes') }}" aria-current="page"
                                    href="{{ route('listarClientes') }}">
                                    <i class="bi bi-person-video2"></i></i>
                                    <span class="ml-2">Gestionar Clientes</span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::check() && Auth::user()->role == 'empleado')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('agendaEmpleado') ? 'active' : '' }}"
                                    aria-current="page" href="{{ route('agendaEmpleado') }}" aria-current="page"
                                    href="{{ route('agendaEmpleado') }}">
                                    <i class="bi bi-calendar-week-fill"></i>
                                    <span class="ml-2">Agenda</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
                @yield('contenido')
                @yield('modals')
                <footer class="pt-5 d-flex justify-content-between">
                    <span class="text-secondary">Copyright © 2023 EasyAppointments</span>
                    <ul class="nav m-0">
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="https://github.com/NicolaeGhenciu"><i
                                    class="bi bi-github"></i>&nbsp;Github</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="#">Ghenciu Nicolae Adrian</a>
                        </li>
                    </ul>
                </footer>
            </main>
        </div>
    </div>
</body>

</html>
