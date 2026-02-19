@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')
@include('partials.breadcrumbs')
<div class="toast toast-top toast-end z-10 md:max-w-6/10 cursor-pointer" id="toast"
    x-data={toggle:false}
    x-show="toggle"
    @click="toggle=!toggle">
</div>
<section>

    <div class="flex justify-between items-center mb-6">
        <h2 class="font-semibold text-2xl">{{$page['name']}}</h2>

        <button
            type="submit" role="button" form="updSolicitud"
            class="btn btn-md btn-primary
        {{$solicitud->ultimoEstado->estado!=1? 'hidden':''}}">Aprobar</button>
    </div>
    <form id="updSolicitud" class="card shadow-md border border-base-300" method="get"
        hx-put="{{route('api.changeStateSolicitud',[$solicitud->id])}}"
        hx-trigger="submit"
        hx-indicator="#loadingModal"
        hx-target="#toast">
        @csrf
        <input type="hidden" name="type" value="aprobar">

        <div class="card-body">

            <p>
                @if ($solicitud->ultimoEstado->estado>1)
                @if ($solicitud->ultimoEstado->estado==4)
                <b class="text-error">Eliminado por: {{$solicitud->ultimoEstado->usuario->nombre}}</b>
                @else
                <b>Gestionado por: {{$solicitud->ultimoEstado->usuario->nombre}}</b>
                @endif
                @else
                <b>Gestionado por:</b>
                @endif
                <br>
                <b>Prioridad:</b> <span class=" font-bold
                            {{ $solicitud->prioridad == 1 ? 'text-error' : '' }}
                            {{ $solicitud->prioridad == 2 ? 'text-orange-400 ' : '' }}
                            {{ $solicitud->prioridad == 3 ? 'text-accent' : '' }}">
                    {{$solicitud->prioridadSolicitud->tipo}}
                </span>
                <br>
                <b>Solicitante:</b> {{$solicitud->usuario->nombre}}
                <br>
                <b>Cargo:</b> {{$solicitud->usuario->cargo}}
                <br>
                <b>Categoría:</b> {{$solicitud->categoriaSolicitud->tipo}}
                <br>
                <b>Estado:</b> {{$state}}
                <br>
                <b>Área:</b> {{$solicitud->vwArea->area}}
                <br>
                <b>Centro de costo:</b> {{$solicitud->vwCcosto->ccosto}}
                <br>
                <b>Fecha:</b> {{date('d/m/Y',strtotime($solicitud->fecha))}}
                <br>
                <b>Detalles:</b> {{$solicitud->detalles}}
                @if (!is_null($solicitud->archivo))
                @php
                $file=basename($solicitud->archivo);
                @endphp
                <br><br>
                <a class="btn btn-info"
                href="{{route('solicitud.download', $file)}}">
                <span class="mr-2">Descargar archivo</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-cloud-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 18a3.5 3.5 0 0 0 0 -7h-1a5 4.5 0 0 0 -11 -2a4.6 4.4 0 0 0 -2.1 8.4" /><path d="M12 13l0 9" /><path d="M9 19l3 3l3 -3" /></svg>
                </a>
                @endif
            </p>
            <hr class="my-2.5">

            <div class="max-w-dvw w-full mr-[-8em] overflow-x-auto">

                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Almacén</th>
                                <th>Solcitado</th>
                                <th>Recibido</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($solicitud->productos as $producto)

                            <tr class="cursor-default hover:bg-base-200">
                                <td>
                                    {{str_starts_with($producto->id_producto,'ID')?'':$producto->id_producto}}
                                </td>
                                <td>{{$producto->descripcion}}</td>
                                <td>{{$producto->almacen??''}}</td>
                                <td>{{$producto->cant_solicitada}}</td>
                                <td>{{$producto->cant_recibida}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection