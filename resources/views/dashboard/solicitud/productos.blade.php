@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')
<section class=" w-full mb-6" x-data="searchEngine()">

    <h2 class="text-2xl font-semibold mb-8">{{$page['name']}}</h2>

    <div class="flex md:flex-row flex-col gap-8">
        <div class="lg:w-8/12 w-full">
            <form class="mt-4 mb-10 flex flex-col gap-4" method="post" action="{{route('solicitud.nueva')}}">
                <div>
                    <label class="input w-full">
                        <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g
                                stroke-linejoin="round"
                                stroke-linecap="round"
                                stroke-width="2.5"
                                fill="none"
                                stroke="currentColor">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </g>
                        </svg>
                        <input type="search" class="grow" placeholder="Buscar" autofocus
                        x-model="search"
                        @input.debounce.750="fetchProducts('{{route('api.producto')}}')"
                        @keydown.enter.prevent />
                    </label>
                    @csrf
                    <input type="hidden" name="productos" :value="JSON.stringify(products)" />
                </div>
                <div class="flex gap-4 flex-wrap">
                    <button type="submit"  class="btn btn-primary "
                        x-bind:class="{ 'btn-disabled': products.length < 1}"
                        @click="enviar">
                        Crear Solicitud
                        <span x-show="isBTNLoading" class="loading loading-spinner"></span>
                    </button>

                    <a class="btn btn-secondary "
                        @click="addNewProduct"
                        x-show="newProdBtn"
                        x-transition.opacity>Nuevo producto</a>
                </div>
            </form>

            <center x-show="isLoading">
                <span class="loading loading-spinner loading-xl text-primary"></span>
            </center>



            <template x-for="(item, index) in items" :key="index">


                <div class="card mb-4 bg-base-100 rounded-2xl p-4 border cursor-pointer"
                    x-bind:class="{
                                    'border-zinc-400': existsProduct(item.Id_Producto)===false,
                                    'border-primary ring-4 ring-primary/15': existsProduct(item.Id_Producto)
                                }"
                    @click="selectProducts(index)">

                    <div class="flex justify-between mb-6">
                        <div class="badge badge-soft badge-primary">Almacen <span x-text="item.Id_Almacen"></span></div>

                        <input hidden type="checkbox" class=" checkbox checkbox-xs" />
                    </div>

                    <div>
                        <p class="mb-2"><b>Código:</b> <span x-text="item.Id_Producto"></span></p>
                        <p class="mb-2">
                            <b>Cantidad:</b>
                            <span x-text="Math.floor(item.Existencia_Actual)"></span>
                            <span x-text="item.UM_Almacen"></span>
                        </p>
                        <p x-text="item.Desc_Producto"></p>
                    </div>
                </div>

            </template>
        </div>

        <div class="lg:w-4/12 w-full">
            <center class="mb-8" x-show="products.length > 0">
                <h3 class="text-xl font-semibold ">Productos seleccionados</h3>
            </center>
            <template x-for="(product, index) in products" :key="index">
                <div class="card bg-base-200 hover:bg-base-300 p-3 mb-2.5 cursor-pointer"
                    @click="excludeProducts(index)">

                    <div class="flex w-full justify-end">
                        <div x-show="Object.keys(product).length === 1"
                            class="badge badge-success rounded-2xl">Nuevo</div>
                    </div>

                    <p x-text="product.Desc_Producto"></p>

                    <div x-show="Object.keys(product).length > 3" class="flex flex-col mt-2">
                        <span class="text-xs">
                            <b>Código:</b> <span x-text="product.Id_Producto"></span>
                        </span>
                        <span class="text-xs">
                            <b>Almacén:</b> <span x-text="product.Id_Almacen"></span>
                        </span>
                        <span class="text-xs">
                            <b>Cantidad:</b> <span x-text="Math.floor(product.Existencia_Actual)"></span> <span x-text="product.UM_Almacen"></span>
                        </span>
                    </div>
                </div>
            </template>
        </div>
    </div>


    <div x-ref="toast" class="toast toast-top toast-end z-10 cursor-pointer"></div>
</section>

@endsection