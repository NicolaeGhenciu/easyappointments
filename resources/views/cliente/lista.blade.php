@section('titulo', 'EasyAppointments')

@extends('base')

@section('title')
    Gestionar clientes
@endsection

@section('linkScript')
    <style>
        #centrar {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #cuerpo {
            margin: 2em;
        }

        tr,
        td,
        th {
            text-align: center;
        }
    </style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dynatable/0.3.1/jquery.dynatable.css" integrity="sha512-ISgwJJHLSdAI/2kZfXxac5LxCF7Abn05oSE9wcexBGcrUEAi7YQbwBRw+1CYq6/OZP7hocqHypcuXpYQWF8YBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dynatable/0.3.1/jquery.dynatable.js" integrity="sha512-3V7MrISalFn0ZIORbbXtOVhv52Xx60vXnsij3rEfSK/AXXARYQLqkFqalqf1OyCukFSdKv0w3uWh7XiDjWbC7g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        var dynatable = $('#tabla-clientes table').dynatable({
            dataset: {
                perPageDefault: 5,
                perPageOptions: [5, 10, 25, 50, 100]
            }
        }).data('dynatable');
        
        dynatable.paginationPerPage.set(5);
        dynatable.process();
    });
</script>

@endsection

@section('contenido')

    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Alta de un cliente">
                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#añadirModal"><i
                            class="bi bi-person-fill-add"></i></a></span>
            </div>
            <div class="col text-center">
                <h1>Lista de Clientes</h1>
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

    <div class="table-responsive" id="tabla-clientes">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">NIF</th>
                    <th scope="col">Nombre</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->nif }}</td>
                        <td>{{ $cliente->nombre }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('modals')



@endsection
