<dialog id="modal1" class="fixed p-4 w-full h-full flex justify-center items-center backdrop-blur-xs">
    <div class="text-base-content card bg-base-100 shadow-2xl border border-base-300 w-lg transition-transform">

        <div class="card-body">
            <div class="block">
                <svg @click="closeModal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="float-end cursor-pointer">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18 6l-12 12" />
                    <path d="M6 6l12 12" />
                </svg>
            </div>
            <div class="card-title mb-4">Productos</div>

            <div class="overflow-x-auto mb-5">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Cantidad Solc.</th>
                            <!--<th>Cantidad Recibida</th>-->
                        </tr>
                    </thead>
                    <tbody>

                        <template x-for="(groups, index) in products" :key="index">
                            <template x-for="item in groups">
                                <tr class="cursor-default hover:bg-base-200">
                                    <td x-text="item.id_producto"></td>
                                    <td x-text="item.descripcion"></td>
                                    <td x-text="item.cant_solicitada"></td>
                                    <!--<td x-text="item.cant_recibida"></td>-->
                                </tr>
                            </template>
                        </template>

                    </tbody>
                </table>
            </div>

        </div>

    </div>
</dialog>