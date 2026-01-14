<div class="modal z-50" x-data="deleteModal"
    @openmodal.window="
    id=$event.detail.id;
    numero=$event.detail.numero;
    open=$event.detail.open;
    "
    :class="{ 'modal-open': open }">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Confirmar Eliminación</h3>
        <p class="py-4">¿Estás seguro de que deseas eliminar la solicitud <span x-text="numero"></span>?</p>
        <div class="modal-action">
            <button @click="open=false" class="btn">No</button>
            <button @click="del(`{{csrf_token()}}`)" class="btn btn-error">Si</button>
        </div>
    </div>
</div>