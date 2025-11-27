@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')

@include('partials.breadcrumbs')
<section class="mb-20">
    <div class="flex justify-between items-center mb-6 ">
        <h3 class="text-2xl font-semibold ">{{$page['name']}}</h3>
        <a class="btn btn-primary btn-lg md:btn-md rounded-2xl ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                <path d="M16 19h6" />
                <path d="M19 16v6" />
                <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
            </svg>
            <span class="hidden md:block">Nuevo Usuario</span>
        </a>
    </div>





    <div class="max-w-dvw w-full mr-[-2em] overflow-x-auto">
        <div class="overflow-x-auto">
            <div x-data="dataTable">

                <table class="table" id="my-table">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Usuario</th>
                            <th>Acceso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                        <tr class="hover:bg-base-200" id="user_{{$usuario->id}}">
                            <td>{{$usuario->nombre}}</td>

                            <td>{{$usuario->cargo}}</td>
                            <td>{{$usuario->usuario}}</td>
                            <td>
                                @if (!empty($usuario->ultm_acc))
                                {{$usuario->ultm_acc->diffForHumans()}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</section>


@endsection