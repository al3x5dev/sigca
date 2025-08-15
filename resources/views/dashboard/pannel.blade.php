<div>
    <h1>Hello {{Auth::user()->nombre}}</h1>
   We must ship. - Taylor Otwell
   <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Cerrar Sesión</button>
</form>
</div>
