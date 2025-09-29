@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')


<section x-data="changeState" class="mb-20">
    <div class="flex justify-between items-center mb-6 ">
        <h3 class="text-2xl font-semibold ">{{$page['name']}}</h3>
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
                        <th>Categoría</th>
                        <th>Solicita</th>
                        <th>Cargo</th>
                        <th hidden>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    @php
                        $exp = explode('/',$item->numero);
                    @endphp
                    <tr class="hover:bg-base-200 hover:cursor-pointer" @click="window.location=`{{route('gestion.solicitud',['anno' => $exp[1], 'numb' => $exp[0]])}}`">
                        <td>{{$item->numero}}</td>
                        <td>
                            <span class="badge badge-soft "
                            x-data="{estado:{{$item->estado_id}}}"
                            x-bind:class="{
                            'badge-primary': estado==1,
                            'badge-info': estado==2,
                            'badge-success': estado==3,
                            'badge-error': estado==4
                            }">
                                {{$item->estado}}
                            </span>
                        </td>
                        <td>{{$item->categoria}}</td>
                        <td>{{$item->getRelation('usuario')->nombre}}</td>
                        <td>{{$item->getRelation('usuario')->cargo}}</td>
                        <td hidden>{{date('d M Y', strtotime($item->fecha))}}</td>
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

</section>


@endsection