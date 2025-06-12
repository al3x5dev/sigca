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
            <span class="text-3xl font-semibold text-orange-700">15</span>
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
            <span class="text-3xl font-semibold text-indigo-700">15</span>
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
            <span class="text-3xl font-semibold text-emerald-700">15</span>
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
            <span class="text-3xl font-semibold text-rose-700">15</span>
            <span class="capitalize text-rose-900 text-sm font-semibold">canceladas</span>
        </div>
    </a>

</section>

<section class="mt-8">

    <div class="grid grid-cols-12 gap-4">
        <div class="xl:col-span-7 lg:col-span-6 col-span-full">
            <ul class="list bg-base-100 rounded-2xl shadow-lg border"
                x-bind:class="{
                                'border-white/10': darkMode,
                                'border-black/10': !darkMode
                                }">

                <li class="p-4 pb-2 text-xs opacity-60 tracking-wide">Most played songs this week</li>

                <li class="list-row">
                    <div><img class="size-10 rounded-2xl" src="https://img.daisyui.com/images/profile/demo/1@94.webp" /></div>
                    <div>
                        <div>Dio Lupa</div>
                        <div class="text-xs uppercase font-semibold opacity-60">Remaining Reason</div>
                    </div>
                    <button class="btn btn-square btn-ghost">
                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                <path d="M6 3L20 12 6 21 6 3z"></path>
                            </g>
                        </svg>
                    </button>
                    <button class="btn btn-square btn-ghost">
                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                            </g>
                        </svg>
                    </button>
                </li>

                <li class="list-row">
                    <div><img class="size-10 rounded-2xl" src="https://img.daisyui.com/images/profile/demo/4@94.webp" /></div>
                    <div>
                        <div>Ellie Beilish</div>
                        <div class="text-xs uppercase font-semibold opacity-60">Bears of a fever</div>
                    </div>
                    <button class="btn btn-square btn-ghost">
                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                <path d="M6 3L20 12 6 21 6 3z"></path>
                            </g>
                        </svg>
                    </button>
                    <button class="btn btn-square btn-ghost">
                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                            </g>
                        </svg>
                    </button>
                </li>

                <li class="list-row">
                    <div><img class="size-10 rounded-2xl" src="https://img.daisyui.com/images/profile/demo/3@94.webp" /></div>
                    <div>
                        <div>Sabrino Gardener</div>
                        <div class="text-xs uppercase font-semibold opacity-60">Cappuccino</div>
                    </div>
                    <button class="btn btn-square btn-ghost">
                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                <path d="M6 3L20 12 6 21 6 3z"></path>
                            </g>
                        </svg>
                    </button>
                    <button class="btn btn-square btn-ghost">
                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                            </g>
                        </svg>
                    </button>
                </li>

            </ul>
        </div>

        <div class="xl:col-span-5 lg:col-span-6 col-span-full">
            <ul class="list bg-base-100 rounded-2xl shadow-lg border"
            x-bind:class="{
                                'border-white/10': darkMode,
                                'border-black/10': !darkMode
                                }">

                <li class="p-4 pb-2 text-xs opacity-60 tracking-wide">Most played songs this week</li>

                <li class="list-row">
                    <div><img class="size-10 rounded-2xl" src="https://img.daisyui.com/images/profile/demo/1@94.webp" /></div>
                    <div>
                        <div>Dio Lupa</div>
                        <div class="text-xs uppercase font-semibold opacity-60">Remaining Reason</div>
                    </div>
                    <button class="btn btn-square btn-ghost">
                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                <path d="M6 3L20 12 6 21 6 3z"></path>
                            </g>
                        </svg>
                    </button>
                    <button class="btn btn-square btn-ghost">
                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                            </g>
                        </svg>
                    </button>
                </li>

                <li class="list-row">
                    <div><img class="size-10 rounded-2xl" src="https://img.daisyui.com/images/profile/demo/4@94.webp" /></div>
                    <div>
                        <div>Ellie Beilish</div>
                        <div class="text-xs uppercase font-semibold opacity-60">Bears of a fever</div>
                    </div>
                    <button class="btn btn-square btn-ghost">
                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                <path d="M6 3L20 12 6 21 6 3z"></path>
                            </g>
                        </svg>
                    </button>
                    <button class="btn btn-square btn-ghost">
                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                            </g>
                        </svg>
                    </button>
                </li>

                <li class="list-row">
                    <div><img class="size-10 rounded-2xl" src="https://img.daisyui.com/images/profile/demo/3@94.webp" /></div>
                    <div>
                        <div>Sabrino Gardener</div>
                        <div class="text-xs uppercase font-semibold opacity-60">Cappuccino</div>
                    </div>
                    <button class="btn btn-square btn-ghost">
                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                <path d="M6 3L20 12 6 21 6 3z"></path>
                            </g>
                        </svg>
                    </button>
                    <button class="btn btn-square btn-ghost">
                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                            </g>
                        </svg>
                    </button>
                </li>

            </ul>
        </div>
    </div>
</section>

@endsection