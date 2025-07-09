@extends('layouts.base')

@section('title', $page['name'].' - '. env('APP_NAME'))


@section('content')

<div class="toast toast-top toast-end z-10 cursor-pointer" id="form-response"
    x-data="{toggle:true}"
    @click="toggle=!toggle"
    x-show="toggle"></div>
<section>
    <h3 class="mb-4 font-semibold text-3xl">Nueva Solicitud</h3>


    <form class="card shadow-md border border-base-300"
        hx-post="{{ route('user.addSolicitud') }}"
        hx-target="#form-response"
        hx-swap="innerHTML"
        hx-trigger="submit">
        @csrf
        <input type="hidden" value="{{$solicitud['numero']}}" name="numero"/>
        <input type="hidden" value="{{session('logged.id')}}" name="usuario"/>
        <input type="hidden" name="productos" x-bind:value="sendData()">
        <div class="card-title font-mono flex justify-between items-center border-b border-base-300 p-5">
            <h3 class="text-2xl">Solicitud #{{$solicitud['numero']}}</h3>
            <button class="btn btn-md btn-primary">Guardar</button>
        </div>
        <div class="card-body">
            <h4 class="text-xl mb-3">Productos</h4>
            <div class="flex-col">



                <div class="mb-4">

                    <template x-for="(item, index) in products" :key="index">
                        <template x-if="true">
                            <div class="card border border-base-300 shadow-sm p-3 mb-3">
                                <div class="grid grid-cols-[auto_1fr_48px] gap-2.5">
                                    <div><span>Cant: <b x-text="item.Cantidad"></b></span></div>
                                    <span x-text="item.Desc_Producto"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-error cursor-pointer" @click="deleteProduct(index)">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 7l16 0" />
                                        <path d="M10 11l0 6" />
                                        <path d="M14 11l0 6" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                    </svg>
                                </div>
                            </div>
                        </template>
                    </template>

                </div>



                <a class="btn btn-dash w-full" onclick="toggleModal(addProduct)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg> Agregar Producto
                </a>

            </div>
        </div>
    </form>

</section>
@endsection