@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')
@include('partials.breadcrumbs')
<div class="toast toast-top toast-end z-10 md:max-w-6/10 cursor-pointer" id="toast"
    x-data={toggle:false}
    x-show="toggle"
    @click="toggle=!toggle">
</div>
<section
    x-data="managerRequest"
    x-init="start(`{{$state}}`)
">

    <div class="flex justify-between items-center mb-6">
        <h2 class="font-semibold text-2xl">{{$page['name']}}</h2>

        <template x-if="retorn">
            <button form="updSolicitud" class="btn btn-md btn-secondary"
            x-text="btnAction"
            @click="window.history.back()"
            ></button>
        </template>

        <template x-if="!retorn">
            <button type="submit" form="updSolicitud" class="btn btn-md btn-primary" x-text="btnAction">
            </button>
        </template>

    </div>
    <form id="updSolicitud" class="card shadow-md border border-base-300"
        hx-post="{{route('api.changeStateSolicitud',[$id])}}"
        hx-trigger="submit"
        hx-target="#toast">
        @csrf
        @if ($state==='En Proceso')
        <input type="hidden" name="productos" :value="JSON.stringify(products)" />
        <input type="hidden" name="type" value="actualizar">
        @else
        <input type="hidden" name="type" value="aprobar">
        @endif

        @foreach ($items as $item)
        <div class="card-body">

            <p>
                <b>Solicitante:</b> {{$item->usuario->nombre}}
                <br>
                <b>Cargo:</b> {{$item->usuario->cargo}}
                <br>
                <b>Categoría:</b> {{$item->categoria}}
                <br>
                <b>Estado:</b> {{$item->estado}}
                <br>
                <b>Fecha:</b> {{date('d/m/Y',strtotime($item->fecha))}}
            </p>
            <hr class="my-2.5">

            <div class="max-w-dvw w-full mr-[-8em] overflow-x-auto">

                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Solcitado</th>
                                <th>Recibido</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($item->productos as $producto)

                            @if ($state!=='En Proceso')
                            <tr class="cursor-default hover:bg-base-200">
                                <td>
                                    @if ($producto->nuevo == false )
                                    {{$producto->id_producto}}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{$producto->descripcion}}</td>
                                <td>{{$producto->cant_solicitada}}</td>
                                <td>
                                    <span>
                                        {{$producto->cant_recibida}}
                                    </span>
                                </td>
                            </tr>
                            @else
                            @if ($producto->cant_solicitada > $producto->cant_recibida)
                            <tr class="cursor-default hover:bg-base-200">
                                <td>
                                    @if ($producto->nuevo == false )
                                    {{$producto->id_producto}}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{$producto->descripcion}}</td>
                                <td>{{$producto->cant_solicitada}}</td>
                                <td>
                                    <span id="{{$producto->id_producto}}" class="p-[12px] cursor-pointer" @click="editable($event)">
                                        {{$producto->cant_recibida}}
                                    </span>
                                </td>
                            </tr>
                            @endif
                            @endif

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </form>
</section>
@endsection