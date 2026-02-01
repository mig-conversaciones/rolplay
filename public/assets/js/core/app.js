/**
 * RolPlay EDU - Core Application
 * Sistema de inicialización y gestión de componentes JavaScript
 */

class RolPlayApp {
    constructor() {
        this.components = new Map();
        this.initialized = false;
        this.debug = false; // Cambiar a true para ver logs
        this.init();
    }

    /**
     * Inicialización principal de la aplicación
     */
    init() {
        if (this.initialized) {
            return;
        }

        this.log('🚀 Iniciando RolPlay EDU...');

        // Esperar a que el DOM esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }

        this.initialized = true;
    }

    /**
     * Configuración de la aplicación
     */
    setup() {
        this.log('⚙️ Configurando aplicación...');

        // Registrar componentes disponibles
        this.registerComponents();

        // Inicializar componentes en el DOM
        this.initComponents();

        // Adjuntar event listeners globales
        this.attachGlobalListeners();

        // Configurar funcionalidades globales
        this.setupGlobalFeatures();

        this.log('✅ Aplicación iniciada correctamente');
    }

    /**
     * Registrar componentes disponibles
     */
    registerComponents() {
        this.log('📦 Registrando componentes...');

        // Componentes interactivos (se cargan dinámicamente)
        if (typeof Modal !== 'undefined') {
            this.registerComponent('modal', Modal);
        }

        if (typeof Accordion !== 'undefined') {
            this.registerComponent('accordion', Accordion);
        }

        if (typeof Dropdown !== 'undefined') {
            this.registerComponent('dropdown', Dropdown);
        }

        if (typeof Tabs !== 'undefined') {
            this.registerComponent('tabs', Tabs);
        }

        if (typeof Toast !== 'undefined') {
            this.registerComponent('toast', Toast);
        }

        if (typeof Tooltip !== 'undefined') {
            this.registerComponent('tooltip', Tooltip);
        }

        this.log(`✓ ${this.components.size} componentes registrados`);
    }

    /**
     * Registrar un componente
     */
    registerComponent(name, ComponentClass) {
        this.components.set(name, ComponentClass);
        this.log(`  • ${name} registrado`);
    }

    /**
     * Inicializar componentes en el DOM
     */
    initComponents() {
        this.log('🔧 Inicializando componentes en DOM...');

        let initialized = 0;

        this.components.forEach((ComponentClass, name) => {
            const elements = document.querySelectorAll(`[data-component="${name}"]`);

            elements.forEach(el => {
                if (!el.__component) {
                    try {
                        el.__component = new ComponentClass(el);
                        initialized++;
                    } catch (error) {
                        console.error(`Error al inicializar ${name}:`, error);
                    }
                }
            });
        });

        this.log(`✓ ${initialized} instancias de componentes inicializadas`);
    }

    /**
     * Adjuntar event listeners globales
     */
    attachGlobalListeners() {
        this.log('🎧 Adjuntando listeners globales...');

        // ESC key handler
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.dispatchEvent(new CustomEvent('escape', { detail: e }));
            }
        });

        // Click outside handler
        document.addEventListener('click', (e) => {
            document.dispatchEvent(new CustomEvent('clickoutside', { detail: e.target }));
        });

        // Form submission handler
        document.addEventListener('submit', (e) => {
            const form = e.target;

            // Si el formulario tiene data-loading, mostrar loading state
            if (form.hasAttribute('data-loading')) {
                const submitBtn = form.querySelector('[type="submit"]');
                if (submitBtn) {
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                }
            }
        });

        this.log('✓ Listeners globales configurados');
    }

    /**
     * Configurar funcionalidades globales
     */
    setupGlobalFeatures() {
        this.log('🌟 Configurando funcionalidades globales...');

        // Smooth scroll para anclas
        this.setupSmoothScroll();

        // Lazy loading de imágenes
        this.setupLazyLoading();

        // Auto-dismiss de alertas
        this.setupAutoDismissAlerts();

        this.log('✓ Funcionalidades globales configuradas');
    }

    /**
     * Configurar smooth scroll
     */
    setupSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && document.querySelector(href)) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Actualizar URL sin recargar
                    if (history.pushState) {
                        history.pushState(null, null, href);
                    }
                }
            });
        });
    }

    /**
     * Configurar lazy loading
     */
    setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            const lazyImages = document.querySelectorAll('[data-lazy-src]');

            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.lazySrc;
                        img.removeAttribute('data-lazy-src');
                        imageObserver.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(img => imageObserver.observe(img));
        }
    }

    /**
     * Auto-dismiss de alertas/flash messages
     */
    setupAutoDismissAlerts() {
        const alerts = document.querySelectorAll('[data-auto-dismiss]');

        alerts.forEach(alert => {
            const delay = parseInt(alert.dataset.autoDismiss) || 5000;

            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => alert.remove(), 300);
            }, delay);
        });
    }

    /**
     * Re-inicializar componentes (útil para contenido dinámico)
     */
    refresh() {
        this.log('🔄 Refrescando componentes...');
        this.initComponents();
    }

    /**
     * Logging condicional
     */
    log(...args) {
        if (this.debug) {
            console.log('[RolPlay EDU]', ...args);
        }
    }

    /**
     * API pública para crear componentes dinámicamente
     */
    createComponent(name, element) {
        const ComponentClass = this.components.get(name);

        if (!ComponentClass) {
            console.error(`Componente '${name}' no encontrado`);
            return null;
        }

        if (element.__component) {
            return element.__component;
        }

        try {
            const instance = new ComponentClass(element);
            element.__component = instance;
            return instance;
        } catch (error) {
            console.error(`Error al crear componente '${name}':`, error);
            return null;
        }
    }
}

// Crear instancia global de la aplicación
window.RolPlayApp = new RolPlayApp();

// Export para módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RolPlayApp;
}
