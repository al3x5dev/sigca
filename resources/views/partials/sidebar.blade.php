<aside class="fixed left-0 z-30
h-full bg-base-300
transition-all duration-300 ease-in-out
px-4 md:pt-4 md:pb-8 py-2  md:mt-2.5
flex flex-col
"
    x-bind:class="{
            'w-64': isDesktop && rail,
            '-translate-x-1/1': isDesktop && !rail,
            'w-full': isMobile,
            'translate-x-0': isMobile && rail,
            '-translate-x-full': isMobile && !rail
            }">

    <nav class="flex justify-between items-center">
        <button class="btn btn-circle btn-ghost md:hidden" @click="toggleMenu">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M4 6h16"></path>
                <path d="M7 12h13"></path>
                <path d="M10 18h10"></path>
            </svg>
        </button>

        <!-- Logo -->
        <a href="{{url('/')}}" class="flex items-center justify-center ">
            <span class="">
                <img class="w-[38px]"
                    src="
                @if (Auth::user()->perfil->theme!='dim')
                    {{asset('assets/img/logo.png') }}
                @else
                    {{asset('assets/img/logo-dark.png')}}
                @endif">
            </span>
            <span class="ml-3 text-3xl font-semibold @if (Auth::user()->perfil->theme=='dim')
                text-white
            @endif">SIGCA</span>
        </a>

        <span class="opacity-0">Sigca</span>

    </nav>

    <style>
        #sidebar-menu>li.active {
            background-color: var(--color-primary) !important;
            color: var(--color-primary-content);
        }

        #sidebar-menu>li:hover {
            background-color: color-mix(in oklab, var(--color-base-content)20%, transparent);
        }
    </style>

    <ul class="mt-8" id="sidebar-menu" style="flex: 1 0 auto;">
        <li class="my-3 p-1.5 rounded-md font-semibold transition-[background-color] {{ $page['title'] == 'dashboard' ? 'active' : '' }}">
            <a href="{{route('dashboard')}}" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2.5">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 4h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1" />
                    <path d="M5 16h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1" />
                    <path d="M15 12h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1" />
                    <path d="M15 4h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1" />
                </svg>
                Panel de Control
            </a>
        </li>

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
            <li class="my-3 p-1.5 rounded-md font-semibold transition-[background-color] {{ $page['title'] == 'solicitud' ? 'active' : '' }}">
            <a href="{{route('solicitud.home')}}" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2.5">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" />
                </svg>
                Solicitudes
            </a>
        </li>
        @endif

        @if ($isComprador)
            <li class="my-3 p-1.5 rounded-md font-semibold transition-[background-color] {{ $page['title'] == 'gestor' ? 'active' : '' }}">
            <a href="" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v3.5" /><path d="M9 9h1" /><path d="M9 13h6" /><path d="M9 17h3" /><path d="M19 22.5a4.75 4.75 0 0 1 3.5 -3.5a4.75 4.75 0 0 1 -3.5 -3.5a4.75 4.75 0 0 1 -3.5 3.5a4.75 4.75 0 0 1 3.5 3.5" /></svg>
                Gestión
            </a>
        @endif
        </li>

        @if ($isSupervisor)
            <li class="my-3 p-1.5 rounded-md font-semibold transition-[background-color] {{ $page['title'] == 'admin' ? 'active' : '' }}">
            <a href="" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 3v4a.997 .997 0 0 0 1 1h4" /><path d="M11 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v3.5" /><path d="M8 9h1" /><path d="M8 12.994l3 0" /><path d="M8 16.997l2 0" /><path d="M21 15.994c0 4 -2.5 6 -3.5 6s-3.5 -2 -3.5 -6c1 0 2.5 -.5 3.5 -1.5c1 1 2.5 1.5 3.5 1.5" /></svg>
                Supervisión
            </a>
        </li>
        @endif
    </ul>




    <ul tabindex="0">
        <li class="rounded-md w-full p-1.5 font-semibold text-red-500 hover:bg-red-200 transition-colors ease-in-out">
            <form action="{{route('logout')}}" method="post">
                @csrf
                <button type="submit" class="flex items-center cursor-pointer w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2.5">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 6a7.75 7.75 0 1 0 10 0" />
                        <path d="M12 4l0 8" />
                    </svg>Salir
                </button>
            </form>

        </li>
    </ul>


</aside>