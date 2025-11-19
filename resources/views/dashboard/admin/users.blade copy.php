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

    <!--buscador-->
    <label class="input">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-[1em] opacity-50">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
            <path d="M21 21l-6 -6" />
        </svg>
        <input type="search" class="grow" placeholder="Buscar" />

    </label>
    <!--/buscador-->

    <div class="max-w-dvw w-full mr-[-2em] overflow-x-auto">
        @if (count($usuarios)>0)
        <div class="overflow-x-auto">

            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Usuario</th>
                        <th>Fecha de Creación</th>
                        <th>Último Acceso</th>
                        <th hidden>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                    <tr class="hover:bg-base-200" id="user_{{$usuario->id}}">
                        <td>{{$usuario->nombre}}</td>

                        <td>{{$usuario->cargo}}</td>
                        <td>{{$usuario->usuario}}</td>
                        <td>{{date('d-m-Y', strtotime($usuario->creado))}}</td>
                        <td>
                            @if (!empty($usuario->ultm_acc))
                            {{date('d-m-Y h:iA', strtotime($usuario->ultm_acc))}}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($usuarios->hasPages())
        <!--Paginado-->
        <center class=" mt-10">
            <div class="join">
                <button class="join-item btn"
                    {{ $usuarios->onFirstPage() ? 'disabled' : '' }}
                    onclick="location.href='{{ $usuarios->url(1) }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M11 7l-5 5l5 5" />
                        <path d="M17 7l-5 5l5 5" />
                    </svg>
                </button>
                <button class="join-item btn"
                    {{ $usuarios->onFirstPage() ? 'disabled' : '' }}
                    onclick="location.href='{{ $usuarios->previousPageUrl() }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </button>
                <button class="join-item btn">{{$usuarios->currentPage()}} de {{$usuarios->lastPage()}}</button>

                <button class="join-item btn"
                    {{ $usuarios->onLastPage() ? 'disabled' : '' }}
                    onclick="location.href='{{ $usuarios->nextPageUrl() }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 6l6 6l-6 6" />
                    </svg>
                </button>
                <button class="join-item btn"
                    {{ $usuarios->onLastPage() ? 'disabled' : '' }}
                    onclick="location.href='{{ $usuarios->url($usuarios->lastPage()) }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 7l5 5l-5 5" />
                        <path d="M13 7l5 5l-5 5" />
                    </svg></button>
            </div>
        </center>
        @endif





        @else
        <center>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-14 w-14">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2zm-7 -7h.01m3.99 0h.01" />
                <path d="M10 18a3.5 3.5 0 0 1 4 0" />
            </svg>
            <p class="text-xl">No hay datos para mostrar</p>
        </center>
        @endif
    </div>

    <!-- Open the modal  -->
    @include('partials.products-modal')
    @include('partials.modal-new-products')
</section>


@endsection