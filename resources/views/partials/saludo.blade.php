<h1 class="mb-1 text-4xl font-semibold">{{$page['name']}}</h1>
<h3 class="mb-8  font-medium flex items-center"
    x-bind:class="{
'text-slate-300': darkMode,
'text-slate-500': !darkMode
}"><img height="24" width="24" class="mr-2" src="{{asset('assets/img/hello.webp')}}" alt="👋"> Hola {{explode(' ', session('logged.nombre'))[0]}}, ¿qué piensa hacer hoy?</h3>