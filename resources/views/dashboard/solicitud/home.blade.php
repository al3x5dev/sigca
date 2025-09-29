@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')


<section x-data="changeState" class="mb-20">
    <div class="flex justify-between items-center mb-6 ">
        <h3 class="text-2xl font-semibold ">{{$page['name']}}</h3>
        <a onclick="toggleModal('newProducts')" class="btn btn-primary btn-lg md:btn-md rounded-2xl ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                <path d="M13.5 6.5l4 4" />
            </svg>
            <span class="hidden md:block">Nueva Solicitud</span>
        </a>
    </div>

    <div class="max-w-dvw w-full mr-[-2em] overflow-x-auto">
        @if (count($items)>0)
        <div class="overflow-x-auto">

            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th>Número </th>
                        <th>Estado</th>
                        <th>Categoria</th>
                        <th>Comprador</th>
                        <th hidden>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr class="hover:bg-base-200" id="solicitud-{{$item->id}}">
                        <td>{{$item->numero}}</td>
                        <td>
                            <span class="badge badge-soft
                    {{ $item->estado_id == 1 ? 'badge-primary' : '' }}
                    {{ $item->estado_id == 2 ? 'badge-info' : '' }}
                    {{ $item->estado_id == 3 ? 'badge-success' : '' }}
                    {{ $item->estado_id == 4 ? 'badge-error' : '' }}">
                                {{$item->estado}}
                            </span>
                        </td>
                        <td>{{$item->categoria}}</td>
                        <td>{{$item->getRelation('comprador')->nombre??'Sin asignar'}}</td>
                        <td hidden>{{date('d-m-Y', strtotime($item->fecha))}}</td>

                        <td class="flex gap-6">
                            <div class="tooltip" data-tip="Visualizar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="cursor-pointer stroke-current text-slate-400" @click="openModal({{json_encode($item->getRelation('productos'))}})">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                </svg>
                            </div>

                            <form
                                hx-delete="{{route('api.deleteSolicitud',$item->id )}}"
                                hx-trigger="click"
                                hx-target="#solicitud-{{ $item->id }}"
                                hx-target="closest tr" hx-swap="outerHTML swap:1s">
                                @csrf
                                <div class="tooltip" data-tip="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="cursor-pointer stroke-current text-error">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 7l16 0" />
                                        <path d="M10 11l0 6" />
                                        <path d="M14 11l0 6" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                    </svg>
                                </div>

                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
        <!--Paginado-->
        <center class=" mt-10">
            <div class="join">
                <button class="join-item btn"
                    {{ $items->onFirstPage() ? 'disabled' : '' }}
                    onclick="location.href='{{ $items->url(1) }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M11 7l-5 5l5 5" />
                        <path d="M17 7l-5 5l5 5" />
                    </svg>
                </button>
                <button class="join-item btn"
                    {{ $items->onFirstPage() ? 'disabled' : '' }}
                    onclick="location.href='{{ $items->previousPageUrl() }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </button>
                <button class="join-item btn">{{$items->currentPage()}} de {{$items->lastPage()}}</button>

                <button class="join-item btn"
                    {{ $items->onLastPage() ? 'disabled' : '' }}
                    onclick="location.href='{{ $items->nextPageUrl() }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 6l6 6l-6 6" />
                    </svg>
                </button>
                <button class="join-item btn"
                    {{ $items->onLastPage() ? 'disabled' : '' }}
                    onclick="location.href='{{ $items->url($items->lastPage()) }}'">
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