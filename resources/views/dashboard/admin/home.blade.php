@extends('layouts.base')

@section('title', $page['name'].' | '. env('APP_NAME'))


@section('content')


<section>
    <div class="flex justify-between items-center mb-6 ">
        <h3 class="text-2xl font-semibold ">{{$page['name']}}</h3>
    </div>


    <center>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-14 w-14">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2zm-7 -7h.01m3.99 0h.01" />
            <path d="M10 18a3.5 3.5 0 0 1 4 0" />
        </svg>
        <p class="text-xl">No hay datos para mostrar</p>
    </center>

</section>


@endsection