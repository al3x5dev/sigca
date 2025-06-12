<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGCA - Inicir Sesión</title>
    <!-- FAVICON - ADAPTATIVO -->
    <link rel="icon" type="image/svg+xml" href="{{asset('assets/img/favicon.svg')}}" />

    <!-- ALPINE.JS -->
    <!--<script src="//unpkg.com/alpinejs" defer></script>-->
    <script src="https://unpkg.com/alpinejs@3.14.9/dist/cdn.min.js" defer></script>

    <!-- TAILWIND.CSS -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- DAISY-UI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
</head>

<body class="h-dvh">
    <main class="h-full flex flex-row">
        <div class="xl:basis-8/12 lg:basis-7/12
        hidden lg:block
        bg-cover bg-center
        bg-[url({{asset('assets/img/bg-login.webp')}})]" style="background-color: gray;"></div>
        <div class="xl:basis-4/12 lg:basis-5/12 basis-full flex justify-center items-center p-4">

            @if ($errors->any())
            <div class="fixed top-8 cursor-pointer"
                x-data="{alert: true}"
                @click="alert=!alert"
                x-show="alert"
                x-transition>
                <div role="alert" class="alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{!!$errors->first()!!}</span>
                </div>
            </div>
            @endif


            <form action="{{route('signin')}}" method="post">
                <div class="mb-4">
                    <!-- Logo -->
                    <div class="flex items-center mb-3 justify-center lg:justify-start">
                        <span class="">
                            <img class="w-[38px]" src="{{asset('assets/img/logo.png')}}" alt="">
                        </span>
                        <span class="ml-3 text-3xl font-semibold">SIGCA</span>
                    </div>
                    <!-- /Logo -->

                    <h1 class="text-xl text-center lg:text-start">Ingresa tus datos de acceso para continuar</h1>
                </div>
                @csrf

                <fieldset class="fieldset">
                    <legend class="fieldset-legend text-[1em]">Usuario</legend>
                    <input required type="text" class="input w-full" id="username" name="user" placeholder="Introduzca su nombre de usuario" autocomplete="off" value="{{old('user')}}" />
                </fieldset>

                <fieldset class="fieldset" x-data="{isVisible:false}">
                    <legend class="fieldset-legend text-[1em]">Contraseña</legend>

                    <label class="input w-full">
                        <input required :type="isVisible ? 'text' : 'password'" id="password" class="grow " name="pass" placeholder="************" aria-describedby="password" />
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
                </fieldset>

                <button type="submit" class="btn btn-neutral w-full mt-4">Iniciar</button>

                <center class="mt-8 text-slate-500 text-md">
                    <span> © 2025 @if (date('Y') !== '2025') - {{ date('Y') }} @endif </span>
                    <a class="text-sky-600" href="https://serversql/" target="_blank" rel="noopener noreferrer">
                        <span>CTE Carlos Manuel de Céspedes.</span>
                    </a>
                </center>
            </form>

        </div>
    </main>
</body>

</html>