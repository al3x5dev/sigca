import './bootstrap';
import Alpine from 'alpinejs';
import { DataTable } from 'simple-datatables';
import 'simple-datatables/dist/style.css';





/**
 * Datatable global
 */
function datatable() {
    return {
        init() {
            new DataTable('#my-table', {
                searchable: true,
                fixedHeight: true,
                //perPage: 8,
                perPageSelect: [5, 10, 25, 50],
                labels: {
                    placeholder: "Buscar...", // Placeholder del campo de búsqueda
                    perPage: "registros por página", // Texto del selector de filas
                    noRows: "No se encontraron registros", // Mensaje cuando no hay datos
                    info: "Mostrando del {start} al {end} de {rows} registros", // Información de paginación
                    loading: "Cargando...", // Mensaje de carga
                    infoFiltered: "filtrados de {rows} registros totales" // Información cuando se filtra
                },
                // Puedes añadir clases para personalizar el estilo con Tailwind CSS
                classes: {
                    input: "input",
                    container: "datatable-container",
                    selector: "select sp",
                    table: "table",
                    //thead: "datatable-thead",
                    //tbody: "datatable-tbody",
                    // ... más clases
                }
            });
        }
    }
}

// DataTable disponible globalmente para Alpine
window.datatable = datatable;
window.DataTable = DataTable;





/**
 * Motor de busqueda
 */
function searchEngine() {
    return {
        items: [], // respuesta del servidor
        products: [], // Productos guardados para visualizar
        search: '', // input
        isLoading: false, // muestra spinner
        ok: false, // respuesta del servidor
        newProdBtn: false, // muestra btn add nuevo product
        isBTNLoading: false, // btn spinner
        async fetchProducts(url) { //motor de busqueda
            const token = document.querySelector('input[name=_token]').value;

            this.isLoading = true;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ query: this.search })
                });

                this.isLoading = false;
                this.ok = response.ok; // para mostrar btn add product

                if (!this.ok) {
                    throw new Error(`Response: ${response.status} [${response.statusText}]`);
                }

                this.items = await response.json();

                //muestra boton de add producto nuevo
                if (!this.newProdBtn && this.search.length > 3 && this.items < 1) {
                    this.newProdBtn = true;
                } else {
                    this.newProdBtn = false;
                }

            } catch (error) {
                console.error(error);
                this.message(error, 'error');
            }

        },
        selectProducts(i) { // selecciona el producto
            if (!this.existsProduct(this.items[i].Id_Producto)) {
                //eliminar producto y guardarlo en la variable
                const producto = this.items.splice(i, 1)[0];
                // añadir objeto completo al arreglo produts.
                this.products.push(producto);
            } else {
                this.message(`El producto ya se encuentra seleccionado`, 'error');
            }
        },
        addNewProduct() { // action del btn add new product

            if (!this.products.some(p => p.Desc_Producto === this.search.toUpperCase())) {
                this.products.push({
                    Desc_Producto: this.search.toUpperCase()
                })
            } else {
                this.message(`El producto ya se encuentra seleccionado`, 'error');
            }
        },
        excludeProducts(i) { // elimina elemento de products y lo agrega a items
            const producto = this.products.splice(i, 1)[0];
            if (producto.Desc_Producto.includes(this.search)) {
                this.items.push(producto);
            }
        },
        existsProduct(id) { //verifica si un id existe en products
            return this.products.some(p => p.Id_Producto === id);
        },
        async enviar() {

            this.isBTNLoading = true;

        },
        message(text = '', level = 'warning') {
            const el = document.createElement('div');

            el.className = `alert alert-${level}`;
            el.innerHTML = `<span>${text}</span>`;
            this.$refs.toast.appendChild(el);

            setTimeout(() => {
                this.$refs.toast.removeChild(el);
            }, 3000);
        }

    }
}
window.searchEngine = searchEngine;


/**
 * salva solicitud
 */
function makeRequest() {
    return {
        products: [],
        cantidad_solicitada: 0,
        isValid: true,
        edit: false,
        svg: '',
        pencil: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>`,
        init(products) {
            this.products = products;
            this.svg = this.pencil
        },
        editSave(e, i) {
            if (isNaN(Number(e.target.textContent)) || e.target.textContent < 1) {
                alert('La cantidad debe ser un número mayor a 0')
            }
            this.products[i].Solicitado = e.target.textContent;
            e.target.classList.remove('custom-error');
        },
        delProduct(i) {
            document.getElementById(i).remove();
            this.products.splice(i, 1);
        },
        isSelected() {
            const e = this.$event.target;
            if (e.classList.contains('custom-error')) {
                e.classList.remove('custom-error');
            }
        },
        valForm() {
            //verifica selects con datos todos antes del submit
            document.querySelectorAll('select.select[required]').forEach(select => {
                if (select.value.length == 0) {
                    this.isValid = false;
                    select.classList.add('custom-error');
                } else {
                    this.isValid = true;
                }
            });

            //Verifica cantidad solicitada
            document.querySelectorAll('.cantSolicita').forEach(el => {
                const elm = el.firstElementChild;
                let cantidad = Number(elm.innerText);

                if (isNaN(cantidad) || cantidad < 1) {
                    this.isValid = false;
                    elm.classList.add('custom-error');

                }

            });

            if (this.isValid === false) {
                event.preventDefault();
            }
        },
        async disponibilidad(id) {
            const url = window.location.origin;

            try {
                const response = await fetch(`${url}/api/p/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: null
                });
                if (!response.ok) {
                    throw new Error(`Response: ${response.status} [${response.statusText}]`);
                }

                let txt = await response.json();
                return `${Math.abs(txt.Existencia_Actual)} ${txt.UM_Almacen}`;

            } catch (error) {
                console.error(error);
                alert(error);
            }
        },
        toggleBtnSave() {
            this.edit = !this.edit;



            const fab = this.$refs.fab;
            if (this.edit) {
                /**
                 * Botones
                 */
                fab.setAttribute('data-tip', 'Guardar');
                fab.children[0].classList.toggle('btn-accent');
                fab.children[0].classList.toggle('btn-primary');
                this.svg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>`;

                /**
                 * Elementos
                 */
                this.$refs.area.removeAttribute('disabled');
                this.$refs.ccosto.removeAttribute('disabled');
                this.$refs.categoria.removeAttribute('disabled');
                this.$refs.prioridad.removeAttribute('disabled');
                this.$refs.textarea.removeAttribute('disabled');

                this.products.forEach(p => {
                    let editable = document.getElementById(`editable-${p.id_solicitud}`);
                    if (editable!=null) {
                        editable.setAttribute('title', "Doble click para modificar");
                    }
                });
                //this.$refs.editable.setAttribute('title', "Doble click para modificar");
                fab.firstElementChild.removeAttribute('form');
            } else {
                /**
                 * Botones
                 */
                fab.setAttribute('data-tip', 'Editar');
                this.svg = this.pencil
                fab.children[0].classList.toggle('btn-accent');
                fab.children[0].classList.toggle('btn-primary');

                /**
                 * Elementos
                 */
                setTimeout(() => {
                    this.$refs.area.setAttribute('disabled', 'true');
                    this.$refs.ccosto.setAttribute('disabled', 'true');
                    this.$refs.categoria.setAttribute('disabled', 'true');
                    this.$refs.prioridad.setAttribute('disabled', 'true');
                    this.$refs.textarea.setAttribute('disabled', 'true');
                }, 100);

                this.products.forEach(p => {
                    let editable = document.getElementById(`editable-${p.id_solicitud}`);
                    if (editable!=null) {
                        editable.removeAttribute('title');
                    }
                });
                //this.$refs.editable.removeAttribute('title');

                /**
                 * Procesa solicitud
                 */

                fab.firstElementChild.setAttribute('form', 'saveRequest');
                this.valForm();

            }
        },
        searchEngine() { // PARA BUSQUEDA DE SOLICITUDES SIMILARES
        }
    };
}
window.makeRequest = makeRequest;



/**
 * Elimina solicitud
 */
function deleteModal() {
    return {
        id: null,
        numero: null,
        open: false,
        async del(token) {
            const url = window.location.origin;
            const fila = document.querySelector(`tr#solicitud-${this.id ?? 0}`);
            const estado = document.querySelector(`td#estado-${this.id ?? 0}>span`);

            try {
                const response = await fetch(`${url}/api/delsolicitud/${this.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: null
                });

                if (!response.ok) {
                    throw new Error(`Response: ${response.status} [${response.statusText}]`);
                }

                let data = await response.json();

                if (data.saved != 'ok') {
                    throw new Error("Error al salvar los datos");
                }

                this.open = false;
                fila.classList.add('text-gray-500');
                estado.className = 'badge badge-soft rounded-full w-24 badge-error';
                estado.innerText = 'Cancelada';
            } catch (error) {
                alert(error);
            }
        }
    };
}
window.deleteModal = deleteModal;


window.Alpine = Alpine;

Alpine.start();