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
 * SEARCH PRODUCT
 * @returns 
 */
function searchProduct() {
    return {
        items: [],
        selectedProduct: null,
        products: [],
        selectItem(item) {
            this.selectedProduct = item;
            this.$refs.autocomplete.value = item.Desc_Producto;
            this.items = [];
        },
        hasItems() {
            return this.items.length > 0;
        },
        getData(response) {
            try {
                this.items = JSON.parse(response);
                this.selectedProduct = null;
            } catch (error) {
                console.error(error);
                alert(error);
            }
        },
        amount() {
            if (this.selectedProduct !== null) {
                return `En existencia: ${Math.floor(this.selectedProduct.Existencia_Actual)} ${this.selectedProduct.UM_Almacen}`;
            } else {
                return ''
            }
        },
        checkInput() {
            const input = this.$refs.autocomplete;
            const nextEl = input.nextElementSibling;

            if (input.value && input.classList.contains('input-error')) {
                input.classList.remove('input-error');
                nextEl.classList.remove('text-error');
            }
            nextEl.innerText = '';
        },
        addProduct() {
            const inputDesc = this.$refs.autocomplete;
            const inputCant = this.$refs.cantidad;
            if (inputDesc.value === "") {
                inputDesc.classList.add('input-error');
                const nextEl = inputDesc.nextElementSibling;
                nextEl.classList.add('text-error');
                nextEl.innerText = 'Asegúrate de introducir todos los parámetros requeridos para que podamos realizar una búsqueda precisa.';
                return;
            }

            if (inputCant.value === '' || inputCant.value === null || isNaN(inputCant.value) || inputCant.value <= 0) {
                alert('Por favor, introduce para la cantidad un número válido mayor que 0.');
                return 0;
            }

            if (this.selectedProduct == null) {
                // Crear un nuevo producto
                const newProduct = {
                    Id_Producto: null,
                    UM_Almacen: 'U',
                    Desc_Producto: inputDesc.value,
                    Cantidad: inputCant.value
                };

                this.products.push(newProduct);
            } else {
                delete this.selectedProduct.Existencia_Actual;
                this.selectedProduct.Cantidad = inputCant.value;
                this.products.push(this.selectedProduct);
            }

            inputDesc.value = '';
            inputCant.value = '';

            toggleModal(addProduct);
        },
        deleteProduct(i) {
            this.products.splice(i, 1);
        },
        sendData() {
            let str = JSON.stringify(this.products);
            return str;
        }
    }
}


/**
 * Modal Add productos
 */
function toggleModal(id) {
    const body = document.body;
    const main = document.getElementById('container-main');
    if (id.hasAttribute('open')) {
        body.style.overflow = '';
        main.style.position = '';
        id.removeAttribute('open');
        return 0;
    }
    body.style.overflow = 'hidden';
    main.style.position = 'relative';
    id.setAttribute('open', 'true');
}


/**
 * Modal Comprador cambiar estado solicitud
 */
function changeState() {
    return {
        products: [],
        modal: document.getElementById('modal1'),
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
        sendData(){
            
        }
    };
}