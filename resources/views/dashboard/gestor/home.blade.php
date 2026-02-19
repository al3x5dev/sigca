@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')


<section class="mb-20">
    <div class="flex justify-between items-center mb-6 ">
        <h3 class="text-2xl font-semibold ">{{$page['name']}}</h3>
        <a href="{{route('gestion.closeSolicitud')}}" class="btn btn-primary btn-lg md:btn-md rounded-2xl ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" /><path d="M9 15l2 2l4 -4" /></svg>
            <span class="hidden md:block">Cerrar Solicitudes</span>
        </a>
    </div>

    <div class="max-w-dvw w-full mr-[-20em] overflow-x-auto">

        <div x-data="datatable">

            <table id="my-table">
                <!-- head -->
                <thead>
                    <tr>
                        <th>No </th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Categoría</th>
                        <th>Solicita</th>
                        <th>Cargo</th>
                        <th>Área</th>
                        <th>Centro de Costo</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr class="hover:bg-base-200 {{$item->ultimoEstado->estado == 4 ? 'text-zinc-500' : '' }}"
                        id="solicitud-{{$item->id}}">
                        <td>{{$item->numero}}</td>
                        <td class="text-center">
                            <span class="badge rounded-full
                            {{ $item->prioridad == 1 ? 'badge-error' : '' }}
                            {{ $item->prioridad == 2 ? 'badge-warning ' : '' }}
                            {{ $item->prioridad == 3 ? 'badge-accent' : '' }}">
                                {{$item->prioridadSolicitud->tipo}}
                            </span>
                        </td>
                        <td id="estado-{{$item->id}}">
                            <span class="badge badge-soft rounded-full w-24
                            {{ $item->ultimoEstado->estado == 1 ? 'badge-primary' : '' }}
                            {{ $item->ultimoEstado->estado == 2 ? 'badge-info' : '' }}
                            {{ $item->ultimoEstado->estado == 3 ? 'badge-success' : '' }}
                            {{ $item->ultimoEstado->estado == 4 ? 'badge-error' : '' }}">
                                {{$item->ultimoEstado->estadoDetalles->estado}}
                            </span>
                        </td>
                        <td>{{$item->categoriaSolicitud->tipo}}</td>
                        <td>{{$item->getRelation('usuario')->nombre}}</td>
                        <td>{{$item->getRelation('usuario')->cargo}}</td>
                        <td>{{$item->vwArea->area}}</td>
                        <td>{{$item->vwCcosto->ccosto}}</td>
                        <td class="text-center">{{date('d-m-Y', strtotime($item->fecha))}}</td>

                        <td>
                            <div class="flex gap-4">

                                @php
                                $expl = explode('/',$item->numero);
                                @endphp

                                <a href="{{route('gestion.solicitud',['anno' => $expl[1], 'numb' => $expl[0]])}}" class="tooltip" data-tip="Visualizar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="cursor-pointer stroke-current text-slate-400">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                    </svg>
                                </a>

                                @if ($item->ultimoEstado->estado < 3)
                                    <button x-on:click="$dispatch('openmodal', { id:'{{$item->id}}', numero: '{{$item->numero}}', open: true})">
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
                                </button>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</section>
<!-- Modal de Confirmación -->
@include('partials.modal-delete')

@endsection