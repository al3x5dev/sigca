@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')
@include('partials.breadcrumbs')
<div class="toast toast-top toast-end z-6 md:max-w-6/10 cursor-pointer" id="form-response"
    x-data="{toggle:true}"
    @click="toggle=!toggle"
    x-show="toggle"></div>

<section x-data="searchProduct">
    <h3 class="mb-4 font-semibold text-2xl">Nueva Solicitud</h3>


    <form class="card shadow-md border border-base-300"
        hx-post="{{ route('solicitud.save') }}"
        hx-target="#form-response"
        hx-swap="innerHTML"
        hx-trigger="submit">
        @csrf
        <input type="hidden" value="{{$solicitud['numero']}}" name="numero" />
        <input type="hidden" value="{{$categoria}}" name="categoria" />
        <input type="hidden" value="{{Auth::user()->id}}" name="usuario" />
        <input type="hidden" name="productos" :value="JSON.stringify(products)" />

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

    <dialog id="addProduct" class="p-4 w-full h-full flex justify-center items-center backdrop-blur-xs">
        <div class="text-base-content card bg-base-100 shadow-2xl border border-base-300 w-lg transition-transform">
            <div class="card-body">

                <div class="block">
                    <svg onclick="toggleModal(addProduct)" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="float-end cursor-pointer">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18 6l-12 12" />
                        <path d="M6 6l12 12" />
                    </svg>
                </div>
                <div class="card-title mb-4">Nuevo Producto</div>


                <div x-show="errors" x-transition.duration.250ms role="alert" class="alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-text="errorMessage"></span>
                </div>


                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Almacén</legend>
                    <select class="select w-full mb-2" x-model="almacen" x-ref="selectField" @change="selection">
                        <option class="text-base-content/50">Seleccionar almacén</option>
                        @foreach ($almacenes as $almacen)
                        <option value="{{$almacen->Id_Almacen}}">{{$almacen->Id_Almacen}} - {{$almacen->Desc_Almacen}}</option>
                        @endforeach
                    </select>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Descripción del producto</legend>
                    <input type="text" class="input w-full" placeholder="Buscar"
                        x-model="search"
                        x-ref="input"
                        @focus="checkAlmacen"
                        @click="setUrl('{{route('api.producto')}}')"
                        @input.debounce.500ms="handlerInput">
                    <p class="label" x-text="amount" style="text-wrap: auto;"></p>
                </fieldset>

                <ul id="product-list" class="list bg-base-100 rounded-box shadow-2xl/30 absolute left-6 overflow-x-auto" x-show="items.length>0"
                    x-transition.duration.500ms>

                    <template x-for="(item, index) in items" :key="index">
                        <li class="list-row cursor-pointer hover:bg-base-200" x-text="item.Desc_Producto" @click="selectItem(item)">
                        </li>
                    </template>
                </ul>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Cantidad</legend>
                    <input x-ref="cantidad" @input="inputCant" type="number" class="input w-full" placeholder="0" min="0" required />
                </fieldset>
                <p class="label text-error" x-text="amountErr" style="text-wrap: auto;"></p>
                <br>

                <div class="flex justify-end mt-8">
                    <button class="btn mr-3" onclick="toggleModal(addProduct)">Cancelar</button>
                    <button class="btn btn-primary" @click="addProduct">Añadir</button>
                </div>

            </div>
        </div>
    </dialog>
</section>

@endsection