@extends('layouts.base')

@section('title', $page['name'].' - '. env('APP_NAME'))


@section('content')


<!-- Estados de las Solicitudes-->
<section class="flex items-center justify-center my-10 md:mt-20 lg:mt-40">
  <div class="flex flex-col justify-center">
    <div class="flex flex-col items-center">
      <h1 class="text-3xl md:text-4xl lg:text-5xl font-semibold flex items-center text-center">
        <img class="h-12 hidden md:block " src="{{asset('assets/img/hello.webp')}}" alt="👋">
        @php
        $h=date('G')
        @endphp
        @if ($h >= 0 && $h < 12)
          Buenos días
        @elseif ($h >= 12 && $h < 20)
          Buenas tardes
        @else
          Buenas noches
        @endif {{explode(' ', Auth::user()->nombre)[0]}}
      </h1>
      <h2 class="text-lg md:text-xl lg:text-2xl font-light">¡Estamos listos para comenzar!</h2>
    </div>

    <div class="mt-12 flex flex-wrap justify-center gap-4">

    @php
      $isUsuario = false;
      $isComprador = false;
      $isSupervisor = false;
      foreach (Auth::user()->rol as $role) {
        match($role->rol){
          'Usuario'=> $isUsuario = true,
          'Comprador'=> $isComprador = true,
          'Supervisor'=> $isSupervisor = true,
        };
      }
    @endphp
    @if ($isUsuario)
      <a href="{{route('solicitud.home')}}" class="card bg-cyan-50 border border-cyan-200 hover:bg-cyan-100 transition-all:200ms pannel-card w-72 text-cyan-900 rounded-3xl">
          <div class="card-body">
            <div class="flex items-center justify-between">

              <div class="p-2 rounded-full bg-slate-50 border-cyan-200 border">
                <svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round" class="size-8 text-cyan-500"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12l.01 0" /><path d="M13 12l2 0" /><path d="M9 16l.01 0" /><path d="M13 16l2 0" /></svg>
              </div>

                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round" class="anim h-5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M13 18l6 -6" /><path d="M13 6l6 6" /></svg>

            </div>

            <h4 class="text-lg font-bold">Mis Solicitudes</h4>
            <p>Registra y tramita nuevas solicitudes de materiales y recursos.</p>
          </div>
      </a>

      <a href="" class="card bg-cyan-50 border border-cyan-200 hover:bg-cyan-100 transition-all:200ms pannel-card w-72 text-cyan-900 rounded-3xl">
          <div class="card-body">
            <div class="flex items-center justify-between">

              <div class="p-2 rounded-full bg-slate-50 border-cyan-200 border">
                <svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round" class="size-8 text-cyan-500"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
              </div>

                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round" class="anim h-5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M13 18l6 -6" /><path d="M13 6l6 6" /></svg>

            </div>

            <h4 class="text-lg font-bold">Nueva Solicitud</h4>
            <p>Inicia nuevos trámites de solicitud de materiales y recursos.</p>
          </div>
      </a>
    @endif

    @if ($isComprador)
      <a href="" class="card bg-amber-50 border border-amber-200 hover:bg-amber-100 transition-all:200ms pannel-card w-72 text-amber-900 rounded-3xl">
          <div class="card-body">
            <div class="flex items-center justify-between">

              <div class="p-2 rounded-full bg-slate-50 border-amber-200 border">
                <svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round" class="size-8 text-amber-500"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" /></svg>
              </div>

                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round" class="anim h-5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M13 18l6 -6" /><path d="M13 6l6 6" /></svg>

            </div>

            <h4 class="text-lg font-bold">Gestión de Solicitudes</h4>
            <p>Monitorea y gestiona el estado de todas las solicitudes.</p>
          </div>
      </a>
    @endif

    @if ($isSupervisor)
      <a href="" class="card bg-pink-50 border border-pink-200 hover:bg-pink-100 transition-all:200ms pannel-card w-72 text-pink-900 rounded-3xl">
          <div class="card-body">
            <div class="flex items-center justify-between">

              <div class="p-2 rounded-full bg-slate-50 border-pink-200 border">
                <svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round" class="size-8 text-pink-500"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
              </div>

                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round" class="anim h-5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M13 18l6 -6" /><path d="M13 6l6 6" /></svg>

            </div>

            <h4 class="text-lg font-bold">Gestión de Usuarios</h4>
            <p>Administra los permisos y accesos de todos los usuarios del sistema.</p>
          </div>
      </a>

    @endif



    </div>
  </div>
</section>

@endsection