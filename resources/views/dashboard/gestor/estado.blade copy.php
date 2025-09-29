@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')
@include('partials.breadcrumbs')
<section
    x-data="managerRequest"
    x-init="start(`{{$state}}`,`{{route('api.changeStateSolicitud',[$id])}}`,`{{csrf_token()}}`)
">
    <div class="toast toast-top toast-end z-10" x-show="toast!=''">
        <div class="alert" x-bind:class="{
        'alert-success': level===1,
        'alert-error': level===0
         }">
            <span x-text="toast"></span>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <h2 class="font-semibold text-2xl">{{$page['name']}}</h2>
        <button class="btn btn-md"
            x-bind:class="{
        'btn-primary':!retorn,
        'btn-secondary':retorn
        }"
            x-text="btnAction"
            @click="sender"></button>
    </div>
    <div class="card shadow-md border border-base-300">
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

                    <tr class="cursor-default hover:bg-base-200">
                        <td>{{$producto->id_producto}}</td>
                        <td>{{$producto->descripcion}}</td>
                        <td class="text-center">{{$producto->cant_solicitada}}</td>
                        <td class="text-center" @click='editable($event)'>{{$producto->cant_recibida}}</td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
</div>
</section>
@endsection