@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')
@include('partials.breadcrumbs')

<section x-data="makeRequest" x-init='init(@json($productos->toArray()))'>

    <div class="toast toast-top toast-end z-6 md:max-w-6/10 cursor-pointer" id="form-response"
        x-data="{toggle:true}"
        @click="toggle=!toggle"
        x-show="toggle"></div>



    <div class="flex justify-between items-center mb-6">
        <h2 class="font-semibold text-2xl">{{$page['name']}}</h2>
    </div>

    @if ($solicitud->ultimoEstado->estado == 1)
    <div class="fab">
        <div class=" tooltip tooltip-left"
            data-tip="Editar"
            @click="toggleBtnSave"
            x-ref="fab">
            <button role="button" class="btn btn-circle btn-xl btn-primary" x-html="svg" type="submit"></button>
        </div>
    </div>
    @endif


    <div class="flex flex-wrap lg:flex-nowrap gap-4">
        <form id="saveRequest" class="card shadow-md border border-base-300 mb-10 lg:w-12/12"
            hx-put="{{route('solicitud.update')}}"
            hx-trigger="submit"
            hx-indicator="#loadingModal"
            hx-target="#form-response">
            @csrf
            <input type="hidden" name="id" value="{{$solicitud->id}}">
            <input type="hidden" name="productos" :value="JSON.stringify(products)">

            <div class="card-body">

                <!--SELECTS-->
                <div class="">
                    <div class="grid gap-0 md:gap-6 md:grid-cols-2">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Área <span class="text-error font-bold text-[1.25em]">*</span></legend>
                            <select class="select w-full transition-colors duration-200" name="area" required @change="isSelected()" disabled x-ref="area">
                                <option disabled selected value="">Seleccione un área</option>
                                @foreach ($areas as $a)
                                <option value="{{$a->id}}"
                                    {{$a->id == $a_selected?'selected':''}}>
                                    {{$a->area}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Destino por Centro de Costo<span class="text-error font-bold text-[1.25em]">*</span></legend>
                            <select class="select w-full transition-colors duration-200" name="ccosto" required @change="isSelected()" disabled x-ref="ccosto">
                                <option disabled selected value="">Seleccione un centro de costo</option>
                                @foreach ($c_costo as $cc)
                                <option value="{{$cc->idcc}}"
                                    {{$cc->idcc==$cc_selected?'selected':''}}>
                                    {{$cc->ccosto}}
                                </option>
                                @endforeach
                            </select>

                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Categorías<span class="text-error font-bold text-[1.25em]">*</span></legend>
                            <select class="select w-full transition-colors duration-200" name="categoria" required @change="isSelected()" disabled x-ref="categoria">
                                <option disabled selected value="">Seleccione una categoría</option>
                                @foreach ($categorias as $categoria)
                                <option value="{{$categoria->id}}"
                                    {{$categoria->id==$c_selected?'selected':''}}>
                                    {{$categoria->tipo}}
                                </option>
                                @endforeach
                            </select>

                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Prioridad<span class="text-error font-bold text-[1.25em]">*</span></legend>
                            <select class="select w-full transition-colors duration-200" name="prioridad" required @change="isSelected()" disabled x-ref="prioridad">
                                <option disabled selected value="">Selecciona una prioridad</option>
                                @foreach ($prioridades as $prioridad)
                                <option value="{{$prioridad->id}}"
                                    {{$prioridad->id==$p_selected ?'selected':''}}>
                                    {{$prioridad->tipo}}
                                </option>
                                @endforeach
                            </select>

                        </fieldset>
                    </div>


                    <fieldset class="fieldset mt-2">
                        <legend class="fieldset-legend">Detalles</legend>
                        <textarea class="textarea h-32 w-full" name="detalles" placeholder="Escribe aquí detalles sobre la solicitud" spellcheck="false" disabled x-ref="textarea">@if ($detalles!='') {{$detalles}} @endif</textarea>
                    </fieldset>
                </div>
                <!--/SELECTS-->

                <p hidden>Creado: {{$solicitud->fecha}}</p>
                <hr class="my-2.5">

                <div class="max-w-dvw w-full mr-[-8em] overflow-x-auto">

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Almacén</th>
                                    <th>Disponibilidad</th>
                                    <th>Solicitar</th>
                                    @if ($solicitud->ultimoEstado->estado == 1)
                                    <th>Acción</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productos as $index => $producto)
                                <tr id="{{$index}}" class="hover:bg-base-200 ">
                                    <td>
                                        {{
                                            str_starts_with($producto->id_producto,'ID')
                                            ?'':
                                            $producto->id_producto
                                        }}
                                    </td>
                                    <td>{{$producto->descripcion??''}}</td>
                                    <td>{{$producto->almacen}}</td>
                                    <td x-text="disponibilidad(`{{$producto->id_producto}}`)"></td>
                                    <td class="cantSolicita">
                                        <span class="px-3 py-2.5"
                                            id="editable-{{$producto->id_producto}}"
                                            x-bind:class="{ 'hover:cursor-pointer':edit == true }"
                                            @blur="editSave($event,{{$index}})"
                                            :contenteditable="edit">{{$producto->cant_solicitada}}</span>
                                    </td>
                                    @if ($solicitud->ultimoEstado->estado == 1)
                                    <td>
                                        <svg @click="edit && delProduct({{$index}})" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        x-bind:class="{
                                        'text-error hover:cursor-pointer': edit==true,
                                        'text-gray-500': edit==false
                                        }">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 7l16 0" />
                                            <path d="M10 11l0 6" />
                                            <path d="M14 11l0 6" />
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                        </svg>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>

        <div hidden class="lg:w-4/12 mb-8">
            <h3 class="text-xl font-semibold mb-2.5 text-center">Solicitudes similares</h3>
            <template x-for="(item, index) in searchEngine" :key="index">
                <div class="card p-3 bg-base-200 hover:bg-base-300 mb-3 cursor-pointer">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas nihil hic minima dolorem. Quas autem voluptas aperiam, eaque accusamus numquam sequi ducimus, pariatur, nemo tempore nesciunt! Officia dolores saepe consequuntur?
                </div>
            </template>
        </div>
    </div>
</section>

@endsection