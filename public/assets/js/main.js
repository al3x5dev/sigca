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
                    this.darkMode=true;
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