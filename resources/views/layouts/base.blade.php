<!DOCTYPE html>
<html lang="en" data-theme="{{session('logged.theme')}}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', env('APP_NAME'))</title>

    <!-- FAVICON - ADAPTATIVO -->
    <link rel="icon" type="image/svg+xml" href="{{asset('assets/img/favicon.svg')}}" />

    <!-- ALPINE.JS -->
    <!--<script src="//unpkg.com/alpinejs" defer></script>-->
    <script src="https://unpkg.com/alpinejs@3.14.9/dist/cdn.min.js" defer></script>

    <!-- HTMX -->
    <script src="{{asset('assets/js/htmx.min.js')}}" defer></script>

    @if ($page['title']=='pannelComprador')
    <!-- APEXCHARTS -->
    <script src="{{asset('assets/js/apexcharts.min.js')}}"></script>
    @endif


    <!-- TAILWIND.CSS -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- DAISY-UI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />

    <!--Custom-->
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
</head>

<body class="bg-base-300" x-data="menu()">



    @if ($page['title']=='newSolicitud')
    <main class="min-h-dvh max-w-dvw">
        @else
        <main class="min-h-dvh max-w-dvw">
            @endif



            @include('partials.sidebar')

            <div id="container-main" class="bg-base-100 fixed md:top-2.5 h-[100vh] w-auto 
        transition-[margin-left]
        duration-300
        ease-in-out
        md:rounded-t-xl md:mr-3"
                x-bind:class="{
        'ml-[16em]':isDesktop && rail,
        'ml-[0.75em]': isDesktop && !rail,
        
        }" style="width: -moz-available;">


                <div class="h-full overflow-auto">

                    <nav class="px-4 py-2 backdrop-blur-xl bg-base-100/70 sticky top-0 md:rounded-t-xl z-10">
                        <div class="flex justify-between items-center">
                            <button class="btn btn-circle btn-ghost" @click="toggleMenu">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 6h16" />
                                    <path d="M7 12h13" />
                                    <path d="M10 18h10" />
                                </svg>
                            </button>

                            <menu class="flex items-center ">

                                @if (!true)
                                <style>
                                    #icon-bell {
                                        display: block !important;
                                        width: 6px;
                                        height: 6px;
                                        background-color: var(--color-success);
                                        border-radius: 100%;
                                        position: absolute;
                                        top: .75em;
                                        right: 1.25em;
                                    }

                                    .btn:active>#icon-bell {
                                        top: calc(.75em - 4px);
                                        right: calc(1.25em - 5px);
                                    }
                                </style>
                                @endif
                                @if (session('logged.notify'))
                                <div class="dropdown dropdown-end ">
                                    <div tabindex="0" role="button" class="btn btn-circle btn-ghost m-1">
                                        <span style="display: none;" id="icon-bell"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                            <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                                        </svg>
                                    </div>

                                    <ul tabindex="0" class="dropdown-content
                                bg-base-200
                                rounded-box
                                menu
                                z-1 w-52 p-2
                                shadow-xl border border-white/5">
                                        <li><a>Item 1</a></li>
                                        <li><a>Item 2</a></li>
                                    </ul>
                                </div>
                                @endif

                                <div class="dropdown dropdown-end ">
                                    <div tabindex="0" role="button" class="btn btn-circle btn-ghost m-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-adjustments-horizontal">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M14 6m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                            <path d="M4 6l8 0" />
                                            <path d="M16 6l4 0" />
                                            <path d="M8 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                            <path d="M4 12l2 0" />
                                            <path d="M10 12l10 0" />
                                            <path d="M17 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                            <path d="M4 18l11 0" />
                                            <path d="M19 18l1 0" />
                                        </svg>
                                    </div>

                                    <ul tabindex="0" class="dropdown-content
                                bg-base-200
                                rounded-box
                                menu
                                z-1 w-52 p-2
                                shadow-xl border border-white/5">
                                        <li><a onclick="profile(`{{url('/')}}/api/profile/?m={{session('logged.theme') == 'light' ? 'dim' : 'light'}}`)">
                                                Activar modo
                                                @if (session('logged.theme')=='light')
                                                oscuro
                                                @else
                                                claro
                                                @endif
                                            </a></li>
                                        <li><a onclick="profile(`{{url('/')}}/api/profile/?n={{session('logged.notify') == 1 ? 0 : 1}}`)">
                                                @if (session('logged.notify'))
                                                Desactivar
                                                @else
                                                Activar
                                                @endif
                                                notificaciones
                                            </a></li>
                                    </ul>
                                </div>

                            </menu>
                        </div>
                    </nav>

                    <div class="py-4 lg:py-8 px-4 xl:px-24 lg:px-12 md:px-6">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>

        <script src="{{asset('assets/js/main.js')}}"></script>
</body>

</html>