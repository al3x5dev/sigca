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


