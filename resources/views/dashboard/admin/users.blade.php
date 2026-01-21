@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')

@include('partials.breadcrumbs')
<section class="mb-20">
    <div class="flex justify-between items-center mb-6 ">
        <h3 class="text-2xl font-semibold ">{{$page['name']}}</h3>
        <a class="btn btn-primary btn-lg md:btn-md rounded-2xl "
        @click="$dispatch('new-user', { foo: 'bar' })">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                <path d="M16 19h6" />
                <path d="M19 16v6" />
                <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
            </svg>
            <span class="hidden md:block">Nuevo Usuario</span>
        </a>
    </div>





    <div class="max-w-dvw w-full mr-[-2em] overflow-x-auto">
        <div class="overflow-x-auto">
            <div x-data="datatable">

                <table id="my-table">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Usuario</th>
                            <th>Tipo</th>
                            <th>Acceso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                        <tr class="hover:bg-base-200 cursor-pointer"
                        onclick="window.location.href = `{{route('admin.userOptions',$usuario->id)}}`">
                            <td>{{$usuario->nombre}}</td>

                            <td>{{$usuario->cargo}}</td>
                            <td>{{$usuario->usuario}}</td>
                            <td>@if (is_null($usuario->password))
                                Red
                                @else
                                Sistema
                                @endif</td>
                            <td>
                                @if (!empty($usuario->ultm_acc))
                                {{$usuario->ultm_acc->diffForHumans()}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!--Modal-->
    <div class="modal z-50" x-data="{foo:null}"
        @new-user.window="
    foo=$event.detail.foo;
    "
        :class="{ 'modal-open': foo !==null }">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Crear un nuevo usuario</h3>
            <p class="py-4">Vamos a crear al usuario <span x-text="foo"></span></p>
            <div class="modal-action">
                <button @click="foo=null" class="btn">Cerrar</button>
                <button  class="btn btn-primary">Crear</button>
            </div>
        </div>
    </div>

</section>


@endsection