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
                <img class="w-[38px]" src="{{asset('assets/img/logo.png')}}" alt="">
            </span>
            <span class="ml-3 text-3xl font-semibold">SIGCA</span>
        </a>

        <span class="opacity-0">Sigca</span>

    </nav>

    <style>
        #sidebar-menu>li:hover {
            /*bg-neutral/20*/
            background-color: color-mix(in oklab, var(--color-base-content)10%, #0000);
        }
    </style>

    <ul class="mt-8" id="sidebar-menu" style="flex: 1 0 auto;">
        @switch(session('logged.rol'))
        @case('Supervisor')
        <li class="my-3 p-1.5 rounded-md font-semibold transition-[background-color] {{ $page['title'] == 'dashboard' ? 'bg-neutral/20' : '' }}">
            <a href="" class="flex items-center">
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

        <li class="my-3 p-1.5 rounded-md font-semibold transition-[background-color] {{ $page['title'] == 'checkSolicitudes' ? 'bg-neutral/20' : '' }}">
            <a href="" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2.5">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" />
                </svg>
                Solicitudes
            </a>
        </li>

        <li class="my-3 p-1.5 rounded-md font-semibold transition-[background-color] {{ $page['title'] == 'checkCompra' ? 'bg-neutral/20' : '' }}">
            <a href="" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2.5">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4c.267 0 .529 .026 .781 .076" />
                    <path d="M19 16l-2 3h4l-2 3" />
                </svg>
                Compradores
            </a>
        </li>

        <li class="my-3 p-1.5 rounded-md font-semibold transition-[background-color] {{ $page['title'] == 'allUsers' ? 'bg-neutral/20' : '' }}">
            <a href="" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2.5">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                </svg>
                Usuarios
            </a>
        </li>
        @break

        @case('Comprador')

        @break

        @default
        <li class="my-3 p-1.5 rounded-md font-semibold transition-[background-color] {{ $page['title'] == 'dashboard' ? 'bg-neutral/20' : '' }}">
            <a href="" class="flex items-center">
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

        <li class="my-3 p-1.5 rounded-md font-semibold transition-[background-color] {{ $page['title'] == 'solicitudes' ? 'bg-neutral/20' : '' }}">
            <a href="" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2.5">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" />
                </svg>
                Solicitudes
            </a>
        </li>
        @endswitch
    </ul>




    <ul tabindex="0">
        <li class="rounded-md w-full p-1.5 font-semibold text-red-500 hover:bg-red-200 transition-colors ease-in-out">
            <a class="flex items-center" href="{{route('off')}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2.5">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 6a7.75 7.75 0 1 0 10 0" />
                    <path d="M12 4l0 8" />
                </svg>Salir</a>
        </li>
    </ul>


</aside>