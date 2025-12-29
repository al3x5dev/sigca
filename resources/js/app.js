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
        init(products) {
            this.products = products;
        },
        editSave(e, i) {
            if (isNaN(Number(e.target.textContent)) || e.target.textContent < 1) {
                alert('error')
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
        searchEngine(){ // PARA BUSQUEDA DE SOLICITUDES SIMILARES
        }
    };
}
window.makeRequest = makeRequest;


window.Alpine = Alpine;

Alpine.start();