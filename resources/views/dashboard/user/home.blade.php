@extends('layouts.base')

@section('title', $page['name'].' - '. env('APP_NAME'))


@section('content')

@include('partials.saludo')

<!-- Estados de las Solicitudes-->
<section class="mt-4 grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">

  <a class="rounded-2xl p-6 flex justify-between items-center
    bg-orange-100
    text-orange-400
    hover:bg-orange-200
    transition-all
    cursor-pointer
    ">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[38px] h-[38px]">
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
      <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
      <path d="M9 12h6" />
      <path d="M9 16h6" />
    </svg>
    <div class="flex flex-col items-center">
      <span class="text-3xl font-semibold text-orange-700">{{$estado['pendiente']}}</span>
      <span class="capitalize text-orange-900 text-sm font-semibold">pendientes</span>
    </div>
  </a>

  <a class="rounded-2xl p-6 flex justify-between items-center
    bg-indigo-100
    text-indigo-400
    hover:bg-indigo-200
    transition-all
    cursor-pointer">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[38px] h-[38px]">
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
      <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
      <path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />
      <path d="M3 9l4 0" />
    </svg>
    <div class="flex flex-col items-center">
      <span class="text-3xl font-semibold text-indigo-700">{{$estado['proceso']}}</span>
      <span class="capitalize text-indigo-900 text-sm font-semibold">en proceso</span>
    </div>
  </a>

  <a class="rounded-2xl p-6 flex justify-between items-center
    bg-emerald-100
    text-emerald-400
    hover:bg-emerald-200
    transition-all
    cursor-pointer">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[38px] h-[38px]">
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" />
      <path d="M2 13.5v5.5l5 3" />
      <path d="M7 16.545l5 -3.03" />
      <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" />
      <path d="M12 19l5 3" />
      <path d="M17 16.5l5 -3" />
      <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5" />
      <path d="M7 5.03v5.455" />
      <path d="M12 8l5 -3" />
    </svg>
    <div class="flex flex-col items-center">
      <span class="text-3xl font-semibold text-emerald-700">{{$estado['completado']}}</span>
      <span class="capitalize text-emerald-900 text-sm font-semibold">completadas</span>
    </div>
  </a>

  <a class=" rounded-2xl p-6 flex justify-between items-center
    bg-rose-100
    text-rose-400
    hover:bg-rose-200
    transition-all
    cursor-pointer">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[38px] h-[38px]">
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M5 21v-16m2 -2h10a2 2 0 0 1 2 2v10m0 4.01v1.99l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" />
      <path d="M11 7l4 0" />
      <path d="M9 11l2 0" />
      <path d="M13 15l2 0" />
      <path d="M15 11l0 .01" />
      <path d="M3 3l18 18" />
    </svg>
    <div class="flex flex-col items-center">
      <span class="text-3xl font-semibold text-rose-700">{{$estado['eliminado']}}</span>
      <span class="capitalize text-rose-900 text-sm font-semibold">canceladas</span>
    </div>
  </a>

</section>

<section class="mt-8 " x-data="changeState">
  <h3 class="text-xl font-semibold mb-2">Solicitudes</h3>



  <div class="overflow-x-auto">
    @if (count($items)>0)
    <table class="table">
      <!-- head -->
      <thead>
        <tr>
          <th>Número </th>
          <th>Estado</th>
          <th>Comprador</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
        <tr class="hover:bg-base-200" id="solicitud-{{$item->id}}">
          <td>{{$item->numero}}</td>
          <td>
            <span class="badge badge-soft
                    {{ $item->estado_id == 1 ? 'badge-primary' : '' }}
                    {{ $item->estado_id == 2 ? 'badge-info' : '' }}
                    {{ $item->estado_id == 3 ? 'badge-success' : '' }}
                    {{ $item->estado_id == 4 ? 'badge-error' : '' }}">
              {{$item->estado}}
            </span>
          </td>
          <td>{{$item->getRelation('comprador')->nombre??'Sin asignar'}}</td>
          <td>{{date('d-m-Y', strtotime($item->fecha))}}</td>

          <td class="flex gap-6">
            <div class="tooltip" data-tip="Visualizar">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="cursor-pointer stroke-current text-slate-400" @click="openModal({{json_encode($item->getRelation('productos'))}})">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
              </svg>
            </div>

            <form
              hx-delete="{{route('api.deleteSolicitud',$item->id )}}"
              hx-trigger="click"
              hx-target="#solicitud-{{ $item->id }}"
              hx-target="closest tr" hx-swap="outerHTML swap:1s">
              @csrf
              <div class="tooltip" data-tip="Eliminar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="cursor-pointer stroke-current text-error">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M4 7l16 0" />
                  <path d="M10 11l0 6" />
                  <path d="M14 11l0 6" />
                  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                </svg>
              </div>

            </form>

          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
    <p class="text-lg">No hay datos para mostrar</p>
    @endif
  </div>

  <!-- Open the modal using ID.showModal() method -->
  <dialog id="modal1" class="fixed p-4 w-full h-full flex justify-center items-center backdrop-blur-xs">
    <div class="text-base-content card bg-base-100 shadow-2xl border border-base-300 w-lg transition-transform">

      <div class="card-body">
        <div class="block">
          <svg @click="closeModal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="float-end cursor-pointer">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M18 6l-12 12" />
            <path d="M6 6l12 12" />
          </svg>
        </div>
        <div class="card-title mb-4">Productos</div>

        <div class="overflow-x-auto mb-5">
          <table class="table">
            <thead>
              <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Cantidad</th>
              </tr>
            </thead>
            <tbody>

              <template x-for="(groups, index) in products" :key="index">
                <template x-for="item in groups">
                  <tr>
                    <td x-text="item.id_producto"></td>
                    <td x-text="item.descripcion"></td>
                    <td x-text="item.cant_solicitada"></td>
                  </tr>
                </template>
              </template>

            </tbody>
          </table>
        </div>

      </div>

    </div>
  </dialog>

</section>


@endsection