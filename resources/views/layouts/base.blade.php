<!DOCTYPE html>
<html lang="en" x-data="scheme()">

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


    <!-- TAILWIND.CSS -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- DAISY-UI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-base-300" x-data="menu()">



    @if ($page['title']=='newSolicitud')
    <main class="min-h-dvh max-w-dvw" x-data="searchProduct">
        @else
        <main class="min-h-dvh max-w-dvw">
            @endif



            @include('partials.sidebar')

            <div class="bg-base-100 fixed md:top-2.5 h-full w-auto 
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

                                <label class="ml-1 btn btn-circle btn-ghost swap swap-rotate">
                                    <!-- this hidden checkbox controls the state -->
                                    <input @click="changeMode" type="checkbox" />

                                    <!-- sun icon -->
                                    <svg
                                        class="swap-on h-6 w-6 fill-current"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z" />
                                    </svg>

                                    <!-- moon icon -->
                                    <svg
                                        class="swap-off h-6 w-6 fill-current"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z" />
                                    </svg>
                                </label>

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
                                shadow-xl border"
                                        x-bind:class="{
                                'border-white/5': darkMode,
                                'border-black/5': !darkMode
                                }">
                                        <li><a>Item 1</a></li>
                                        <li><a>Item 2</a></li>
                                    </ul>
                                </div>


                            </menu>
                        </div>
                    </nav>

                    <div class="py-4 lg:py-8 px-4 xl:px-32 lg:px-12 md:px-6">
                        @yield('content')
                    </div>
                </div>
            </div>

            <!-- Open the modal using ID.showModal() method -->
            @if ($page['title']=='newSolicitud')
            <style>
                dialog {
                    visibility: hidden;
                    background-color: #fff1;
                    position: absolute;
                    top: 0;
                    z-index: 99;
                    opacity: 0;
                }

                dialog[open="true"] {
                    visibility: visible;
                    opacity: 1;
                }

                dialog .card {
                    transform: scale(0%);
                }

                dialog[open="true"] .card {
                    transform: scale(100%);
                }

                dialog ul {
                    padding: .75em;
                    max-height: 130px;
                    width: calc(100% - 48px);
                    top: 190px;
                    z-index: 1;
                    /*display: none;*/
                }
            </style>
            <dialog id="addProduct" class="p-4 w-full h-full flex justify-center items-center backdrop-blur-xs">
                <div class="text-base-content card bg-base-100 shadow-2xl border border-base-300 w-lg transition-transform">
                    <div class="card-body">
                        <div class="block">
                            <svg onclick="toggleModal(addProduct)" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="float-end cursor-pointer">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="card-title mb-4">Nuevo Producto</div>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Descripción del producto</legend>
                            <input type="text" class="input w-full" name="p" placeholder="Buscar"
                                x-ref="autocomplete"
                                hx-get="{{route('api.producto')}}"
                                hx-trigger="keyup[this.value.trim() !== ''] changed delay:500ms"
                                @htmx:after-request="getData($event.detail.xhr.response)"
                                @input="checkInput"/>
                            <p class="label" x-text="amount" style="text-wrap: auto;"></p>
                        </fieldset>


                        <ul id="product-list" class="list bg-base-100 rounded-box shadow-xl absolute left-6 overflow-x-auto" x-show="hasItems" x-transition.duration.500ms>

                            <template x-for="(item, index) in items" :key="index">

                                <li class="list-row cursor-pointer hover:bg-base-200"
                                    x-text="item.Desc_Producto"
                                    @click="selectItem(item)"></li>

                            </template>

                        </ul>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Cantidad</legend>
                            <input x-ref="cantidad" type="number" class="input w-full" placeholder="0" min="0" required />
                        </fieldset>
                        <br>

                        <div class="flex justify-end">
                            <button class="btn mr-3" onclick="toggleModal(addProduct)">Cancelar</button>
                            <button class="btn btn-primary" @click="addProduct">Añadir</button>
                        </div>

                    </div>
                </div>
            </dialog>
            @endif
        </main>

        <script src="{{asset('assets/js/main.js')}}"></script>
</body>

</html>