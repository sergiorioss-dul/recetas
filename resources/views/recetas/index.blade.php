@extends('layouts.app')

@section('botones')
    @include('ui.navegacion')
@endsection

@section('content')
    <h2 class="text-center mb-5">Administra tus recetas</h2>
    <div class="col-md-10 mx-auto bg-white p-3">
        <table class="table">
            <thead class="bg-primary text-light">
                <tr>
                    <th scole="col">Titulo</th>
                    <th scole="col">Categoria</th>
                    <th scole="col">Acciones</th>
                </tr>
                <tbody>
                    @foreach($recetas as $receta)
                        <tr>
                            <td>{{$receta->titulo}}</td>
                            <td>{{$receta->categoria->nombre}}</td>
                            <td>
                                <eliminar-receta receta-id={{$receta->id}}>
                                </eliminar-receta>
                                <a href="{{route('recetas.edit',['receta' => $receta->id])}}" class="btn btn-dark mt-2 d-block">Editar</a>
                                <a href="{{route('recetas.show',['receta' => $receta->id])}}" class="btn btn-success mt-2 d-block">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </thead>
        </table>
        <div class="col-12 mt-4 justify-content-center d-flex">
        {{ $recetas->links() }}
        </div>
        <h2 class="text-center my-5">Recetas que te gustan</h2>
        <div class="col-md-10 mx-auto bg-white p-3">
            @if(count($usuario->meGusta)>0)
                <ul-list class="group">
                    @foreach($usuario->meGusta as $receta)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p>{{$receta->titulo}}</p>
                            <a class="btn btn-outline-success text-uppercase font-weight-bold" href="{{ route('recetas.show',['receta'=> $receta->id]) }}">Ver</a>
                        </li>
                    @endforeach
                </ul-list>
            @else
                <p class="text-center">Aún no has dado Likes</p>
            @endif

        </div>
    </div>

@endsection