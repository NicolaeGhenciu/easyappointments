<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
        integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">

    <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">

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
                    {{ Auth::user()->nombre }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Messages</a></li>
                    <li><a class="dropdown-item" href="#">Sign out</a></li>
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
                            <a class="nav-link active" aria-current="page" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                <span class="ml-2">Home</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-file">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                    <polyline points="13 2 13 9 20 9"></polyline>
                                </svg>
                                <span class="ml-2">Location</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
                @yield('contenido')
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
