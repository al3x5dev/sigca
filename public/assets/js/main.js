/**
 * CHANGE THEME
 * @returns 
 */
function scheme() {
    return {
        darkMode: false,
        theme: 'dim',
        toggleAttr: function (mode = null) {
            let html = document.querySelector('html')
            if (mode != null) {
                html.setAttribute('data-theme', mode);
                return;
            }
            html.removeAttribute('data-theme');
        },
        changeMode: function () {
            this.darkMode = !this.darkMode;
            if (this.darkMode === true) {
                this.toggleAttr(this.theme);
                localStorage.setItem('schemeMode', 'dark');
            } else {
                this.toggleAttr();
                localStorage.setItem('schemeMode', 'light');
            }
        },
        init() {
            if (localStorage.getItem('schemeMode') != null) {
                if (localStorage.getItem('schemeMode') == 'dark') {
                    this.darkMode = true;
                    this.toggleAttr(this.theme);
                    return;
                }
                this.toggleAttr();
            }
        }
    }
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
 * Modal
 */
function toggleModal(id) {
    if (id.hasAttribute('open')) {
        id.removeAttribute('open');
        return 0;
    }
    id.setAttribute('open', 'true');
}