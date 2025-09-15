@php
$categorias = \App\Models\Categoria::all();
@endphp
<dialog id="newProducts" class="fixed p-4 w-full h-full flex justify-center items-center backdrop-blur-xs">
    <div class="text-base-content card bg-base-100 shadow-2xl border border-base-300 w-lg transition-transform">

        <div class="card-body" x-data="selectHandler()">
            <div class="block">
                <svg onclick="toggleModal(newProducts)" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="float-end cursor-pointer">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18 6l-12 12" />
                    <path d="M6 6l12 12" />
                </svg>
            </div>
            <div class="card-title mb-4">Nueva Solicitud</div>

            <fieldset class="fieldset">
                <legend class="fieldset-legend">Seleccione una Categoría</legend>
                <select id="selectCatgNueva" class="select w-full mb-2"
                x-model="value"
                x-ref="select"
                @change="selection">
                <option class="text-base-content/50">Seleccionar categoría</option>
                    @foreach ($categorias as $c)
                        <option value="{{$c->id}}">{{$c->tipo}}</option>
                    @endforeach
                </select>
                <span class="label" x-show="message !== ''" x-text="message"></span>
            </fieldset>


            <div class="modal-action mb-6">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                    <button class="btn">Cerrar</button>
                </form>
                <button class="btn btn-primary" @click="sendValue('{{route('solicitud.home')}}')">Crear</button>
            </div>
        </div>

    </div>
</dialog>