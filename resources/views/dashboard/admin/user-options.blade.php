@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')

@include('partials.breadcrumbs')
<section class="mb-20">
    <div class="flex justify-between items-center mb-6 ">
        <h3 class="text-2xl font-semibold ">{{$usuario->nombre}}</h3>
        <button type="submit" role="button" class="btn btn-primary btn-lg md:btn-md rounded-2xl ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                <path d="M15 19l2 2l4 -4" />
            </svg>
            <span class="hidden md:block">Salvar Datos</span>
        </button>
    </div>

    <div class="card shadow-md border border-base-300 mb-10">
        <div class="card-body">
            @if (!is_null($usuario->password))
            <fieldset class="fieldset mb-4" x-data="{isVisible:false}">
                <legend class="fieldset-legend text-lg">Cambiar Contraseña</legend>

                <label class="input validator md:w-92">
                    <input
                        :type="isVisible ? 'text' : 'password'"
                        name="pass"
                        class="grow "
                        required
                        placeholder="************"
                        aria-describedby="password"
                        minlength="8"
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}" />
                    <svg @click="isVisible = !isVisible" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-[1.25em] opacity-50 z-10 cursor-pointer">
                        <path x-show="!isVisible" stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path x-show="!isVisible" d="M21 9c-2.4 2.667 -5.4 4 -9 4c-3.6 0 -6.6 -1.333 -9 -4" />
                        <path x-show="!isVisible" d="M3 15l2.5 -3.8" />
                        <path x-show="!isVisible" d="M21 14.976l-2.492 -3.776" />
                        <path x-show="!isVisible" d="M9 17l.5 -4" />
                        <path x-show="!isVisible" d="M15 17l-.5 -4" />

                        <path x-show="isVisible" stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path x-show="isVisible" d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path x-show="isVisible" d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                    </svg>
                </label>

                <p class="validator-hint">
                    La contraseña debe tener más de 8 caracteres,números, minúsculas, mayúsculas y caracteres especiales
                </p>
            </fieldset>
            @endif

            <div class="mb-4" x-data="{value:{{$usuario->activo}},label:null}" x-init="label = value ? 'Activo:' : 'Inactivo:'">
                <p class="text-xl font-semibold">Estado</p>

                <div class="flex items-center mt-2">
                    <b class="mr-2.5" x-text="label"></b>
                    <input type="checkbox" class="toggle toggle-sm toggle-primary"
                        x-bind:checked="value" />
                </div>
            </div>

            <!--Roles-->
            <p class="text-xl font-semibold ">Módulos</p>
            @foreach ($roles as $rol)
            <span class="flex items-center"><input type="checkbox" {{
                !empty(array_filter($usuario->rol->toArray(), fn($r) => $r['id'] == $rol->id)) ? 'checked="checked"' : ''
            }}
                    class="checkbox checkbox-sm mr-2.5" /> {{$rol->rol}}</span>
            @endforeach


            <!--Categorias-->
            @if (!empty(array_filter($usuario->rol->toArray(), fn($r) => $r['id'] == 2)))
            <p class="text-xl font-semibold mt-4">Categorías</p>
            @foreach ($categorias as $cat)
            <span class="flex items-center"><input type="checkbox" {{
                !empty(array_filter($usuario->categoria->toArray(), fn($r) => $r['id'] == $cat->id)) ? 'checked="checked"' : ''
            }} class="checkbox checkbox-sm mr-2.5" /> {{$cat->tipo}}</span>
            @endforeach
            @endif

        </div>
    </div>

</section>


@endsection