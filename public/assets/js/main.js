/**
 * Change Profile
 */
function profile(url) {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.reload) {
                // Recargar la página
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

/**
 * MENU
 * @returns 
 */
function menu() {
    return {
        rail: true,
        isMobile: window.innerWidth <= 768,
        isTable: window.innerWidth >= 768 && window.innerWidth <= 1291,
        isDesktop: window.innerWidth >= 768,
        checkRail: function () {
            // Verifica si storedRail no es null antes de asignar
            const storedRail = localStorage.getItem('drawer');
            // Asigna true por defecto si no existe
            this.rail = storedRail !== null ? storedRail === 'true' : true;
        },
        toggleMenu() {
            this.rail = !this.rail;
            localStorage.setItem('drawer', this.rail);
        },
        init() {
            if (this.isMobile) {
                this.rail = false
            }
            if (this.isDesktop) {
                this.checkRail();
            }
            /*if (this.isTable) {
                this.rail = false;
            }*/
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth <= 768;
                this.isTable = window.innerWidth >= 768 && window.innerWidth <= 1291;
                this.isDesktop = window.innerWidth >= 768;
                if (this.isMobile && this.rail) {
                    this.rail = false;
                }
                if (this.isDesktop) {
                    this.checkRail();

                }
                /*if (this.isTable) {
                    this.rail = false;
                }*/
            });
        }
    }
}

/**
 * SEARCH PRODUCT OLD
 * @returns 
 */
function searchProduct() {
    return {
        almacen: '',
        search: '',
        items: [],
        selectedProduct: null,
        products: [],
        errors: false,
        errorMessage: '',
        amountErr: '',
        error: function (show, message) {
            this.errors = show;
            this.errorMessage = message;
            setTimeout(() => {
                this.errors = false;
            }, 3000);
        },
        checkAlmacen: function () { //verificar si selecciono el almacen
            if (this.almacen == '') {
                this.$refs.selectField.focus();
                this.$refs.selectField.classList.add('select-error');
                this.error(true, 'Debe seleccionar un almacén antes de realizar la búsqueda');
            }
        },
        selection: function () {
            this.$refs.selectField.classList.remove('select-error');
        },
        setUrl: function (url) { //establecer url de api
            this.url = url;
            if (this.$refs.input.classList.contains('input-error')) {
                this.$refs.input.classList.remove('input-error');
            }
        },
        handlerInput: function () {
            if (this.$refs.input.value.length > 2) {
                this.fetchResults();
            } else {
                this.items = []
            }
        },
        fetchResults: async function () { //trae resultados de la api productos
            try {
                const data = { almacen: this.almacen, query: this.search };
                const token = document.querySelector('input[name=_token]').value;

                const response = await fetch(this.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    throw new Error(`Response: ${response.status} [${response.statusText}]`);
                }

                this.items = await response.json();
                this.selectedProduct = null;

            } catch (error) {
                console.error(error);
                this.error(true, error);
            }
        },
        selectItem: function (i) { //Selecciona un elemento para el input
            this.items = [];
            this.selectedProduct = i;
            this.$refs.input.value = i.Desc_Producto;
        },
        amount() {
            if (this.selectedProduct !== null) {
                return `En existencia: ${Math.floor(this.selectedProduct.Existencia_Actual)} ${this.selectedProduct.UM_Almacen}`;
            } else {
                return ''
            }
        },
        inputCant: function () {


            if (Number(this.$refs.cantidad.value) > 0) {
                this.$refs.cantidad.classList.remove('input-error');
                this.amountErr = '';
                return;
            }
            this.$refs.cantidad.classList.add('input-error');
            this.amountErr = 'Especifiqué una cantidad válida';
        },
        addProduct() {
            const select = this.$refs.selectField;
            const inputDesc = this.$refs.input;
            const inputCant = this.$refs.cantidad;

            if (inputCant.value > 0 && inputDesc.value != '' && this.almacen != '') {
                if (this.selectedProduct !== null) {
                    this.selectedProduct.Cantidad = this.$refs.cantidad.value;
                } else {
                    const data = {
                        Id_Producto: 'ID_' + Date.now(),
                        Desc_Producto: this.$refs.input.value,
                        Existencia_Actual: 0,
                        UM_Almacen: null,
                        Id_Almacen: this.$refs.selectField.value,
                        Cantidad: this.$refs.cantidad.value
                    };
                    this.selectedProduct = data;
                }
                this.products.push(this.selectedProduct);
                toggleModal(addProduct);
            } else {

                if (inputCant.value === '' || inputCant.value <= 0) {
                    inputCant.classList.add('input-error');
                }

                if (inputDesc.value === '') {
                    inputDesc.classList.add('input-error');
                }

                if (this.almacen === '') {
                    select.classList.add('select-error');
                }
                this.error(true, 'Debe de rellenar todos los campos requeridos antes de continuar.');
            }
        },
        deleteProduct(i) {
            this.products.splice(i, 1);
        }
    }
}
/**
 * Manejador de categoria seleccionada para crear solicitudes
 */
function selectHandler() {
    return {
        message: '',
        value: '',
        selection: function () {
            this.message = '';
            this.$refs.select.classList.remove('select-error');
        },
        sendValue: function (url) {
            if (this.value === '') {
                this.$refs.select.focus();
                this.$refs.select.classList.add('select-error');
                this.$refs.select.nextElementSibling.classList.add('text-error');
                this.message = 'Debe seleccionar una categoría para continuar';
            } else {
                window.location.href = `${url}/productos?categoria=${this.value}`;
            }
        }
    }
}

/**
 * Modal Add productos
 */
function toggleModal(id) {
    if (typeof id === 'string') {
        id = document.getElementById(id);
    }
    const aside = document.querySelector('aside');
    if (id.hasAttribute('open')) {
        aside.removeAttribute('style');
        id.removeAttribute('open');
        return 0;
    }
    aside.style = 'z-index:0;';
    setTimeout(() => {
        id.setAttribute('open', 'true');
    }, 200);
}

/**
 * Gestionador de solicitudes
 * 
 * ESTE ES EL ENCARGADO DE CAMBIAR EL ESTADO DE LA SOLICITUD
 */
function managerRequest() {
    return {
        state: '',
        retorn: false,
        btnAction: '',
        products: [],
        start(state, url, token) {
            if (state === 'Pendiente') {
                this.btnAction = 'Aprobar';
            } else if (state === 'En Proceso') {
                this.btnAction = 'Actualizar';
            } else {
                this.btnAction = 'Volver';
                this.retorn = true;
            }
            this.url = url;
            this.state = state;
            this.token = token;
        },
        editable(e) {
            //Hacer el elemento autoseleccionable
            let range = document.createRange();
            range.selectNodeContents(e.target);
            let sel = window.getSelection();
            sel.addRange(range);


            e.target.setAttribute('contenteditable', true);

            // Añadir evento input para filtrar solo números
            e.target.addEventListener('input', (event) => {
                let value = e.target.textContent;
                if (isNaN(value)) {
                    e.target.classList.add('text-error');
                } else {
                    if (e.target.classList.contains('text-error')) {
                        e.target.classList.remove('text-error');
                    }


                    let productIndex = this.products.findIndex(p => p.id === e.target.id);
                    if (productIndex === -1) {
                        // Si no existe, añadir el producto
                        this.products.push({ id: e.target.id, cantidad: value });
                    } else {
                        // Si existe, actualizar la cantidad
                        this.products[productIndex].cantidad = value;
                    }
                }
            });
        }
    }
}

/**
 * Modal Comprador cambiar estado solicitud
 */
function changeState() {
    return {
        products: [],
        modal: document.getElementById('modal1'),
        api: '/api/history/state/',
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        openModal(item) {
            // Convertir el JSON a un objeto si no lo es ya
            let product = typeof item === 'string' ? JSON.parse(item) : item;

            product.forEach(i => {
                if (i.id_producto.startsWith("ID_")) {
                    i.id_producto = 'Sin código';
                }
            });

            // Agregar el objeto al array products
            this.products.push(product);

            toggleModal(this.modal);


        },
        closeModal() {
            this.products = [];
            toggleModal(this.modal);
        },
        hasProducts() {
            return this.products.length > 0
        },
        approve(id) {

            const url = this.api + id;
            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrf
                },
                body: JSON.stringify({ state: 2 })  // Datos a enviar
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Respuesta no OK: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    //message = 'Respuesta del servidor: ' + JSON.stringify(data);
                    console.log(data.message);

                })
                .catch(error => {
                    this.message = 'Error: ' + error;
                });

        },
        cancel(id) {
            /*fetch('')
                .then()
                .then(data => {

                })
                .catch(error => console.error(error)
                );*/
            console.log(id);

        }
    };
}

/**
 * DataTable
 */
function dataTable() {
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
                    selector:"select sp"
                    //table: "datatable-table",
                    //thead: "datatable-thead",
                    //tbody: "datatable-tbody",
                    // ... más clases
                }
            });
        }
    }
}
