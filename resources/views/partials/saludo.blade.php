<h1 class="mb-1 text-4xl font-semibold">{{$page['name']}}</h1>
<h3 class="mb-8  font-medium flex items-center @if (Auth::user()->perfil->theme=='light')
    text-slate-500
@else
    text-slate-300
@endif"><img height="24" width="24" class="mr-2" src="{{asset('assets/img/hello.webp')}}" alt="👋"> Hola {{explode(' ', Auth::user()->nombre)[0]}}, ¿qué piensa hacer hoy?</h3>