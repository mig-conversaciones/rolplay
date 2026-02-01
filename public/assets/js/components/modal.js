/**
 * Modal Component
 * Sistema de modales para RolPlay EDU
 */

class Modal {
    constructor(element) {
        this.modal = element;
        this.backdrop = null;
        this.isOpen = false;
        this.closeOnEscape = true;
        this.closeOnBackdrop = true;

        this.init();
    }

    /**
     * Inicializar el modal
     */
    init() {
        // Crear backdrop si no existe
        if (!this.modal.previousElementSibling?.classList.contains('modal-backdrop')) {
            this.createBackdrop();
        } else {
            this.backdrop = this.modal.previousElementSibling;
        }

        // Configurar opciones
        this.closeOnEscape = this.modal.dataset.closeEscape !== 'false';
        this.closeOnBackdrop = this.modal.dataset.closeBackdrop !== 'false';

        // Asegurar que el modal esté oculto inicialmente
        this.modal.classList.add('hidden');

        // Adjuntar event listeners
        this.attachEventListeners();
    }

    /**
     * Crear backdrop
     */
    createBackdrop() {
        this.backdrop = document.createElement('div');
        this.backdrop.className = 'modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity duration-300';
        this.modal.parentNode.insertBefore(this.backdrop, this.modal);
    }

    /**
     * Adjuntar event listeners
     */
    attachEventListeners() {
        // Botones que abren el modal
        const triggers = document.querySelectorAll(`[data-modal-open="${this.modal.id}"]`);
        triggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                this.open();
            });
        });

        // Botones que cierran el modal
        const closeButtons = this.modal.querySelectorAll('[data-modal-close]');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.close();
            });
        });

        // ESC key
        if (this.closeOnEscape) {
            document.addEventListener('escape', (e) => {
                if (this.isOpen) {
                    this.close();
                }
            });
        }

        // Click en backdrop
        if (this.closeOnBackdrop && this.backdrop) {
            this.backdrop.addEventListener('click', () => {
                this.close();
            });
        }

        // Prevenir cierre al hacer click dentro del modal
        this.modal.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }

    /**
     * Abrir el modal
     */
    open() {
        if (this.isOpen) return;

        this.isOpen = true;

        // Mostrar backdrop
        if (this.backdrop) {
            this.backdrop.classList.remove('hidden');
            setTimeout(() => {
                this.backdrop.style.opacity = '1';
            }, 10);
        }

        // Mostrar modal
        this.modal.classList.remove('hidden');
        this.modal.style.display = 'flex';

        // Animación de entrada
        setTimeout(() => {
            this.modal.querySelector('.modal-content')?.classList.add('animate-scale-in');
        }, 10);

        // Bloquear scroll del body
        document.body.style.overflow = 'hidden';

        // Focus en el modal
        const firstFocusable = this.modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (firstFocusable) {
            setTimeout(() => firstFocusable.focus(), 100);
        }

        // Disparar evento
        this.modal.dispatchEvent(new CustomEvent('modal:open'));
    }

    /**
     * Cerrar el modal
     */
    close() {
        if (!this.isOpen) return;

        this.isOpen = false;

        // Animación de salida
        const content = this.modal.querySelector('.modal-content');
        if (content) {
            content.style.opacity = '0';
            content.style.transform = 'scale(0.95)';
        }

        // Ocultar backdrop
        if (this.backdrop) {
            this.backdrop.style.opacity = '0';
        }

        // Ocultar modal después de la animación
        setTimeout(() => {
            this.modal.classList.add('hidden');
            this.modal.style.display = 'none';

            if (this.backdrop) {
                this.backdrop.classList.add('hidden');
            }

            // Restaurar body scroll
            document.body.style.overflow = '';

            // Resetear animaciones
            if (content) {
                content.style.opacity = '';
                content.style.transform = '';
            }
        }, 300);

        // Disparar evento
        this.modal.dispatchEvent(new CustomEvent('modal:close'));
    }

    /**
     * Toggle modal
     */
    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    /**
     * Destruir el modal
     */
    destroy() {
        this.close();
        if (this.backdrop) {
            this.backdrop.remove();
        }
        this.modal.__component = null;
    }

    /**
     * Método estático para crear modales dinámicamente
     */
    static create(config) {
        const {
            title = '',
            content = '',
            size = 'md',
            type = 'default',
            showClose = true,
            buttons = []
        } = config;

        const modalId = `modal-${Date.now()}`;

        // Tamaños
        const sizes = {
            sm: 'max-w-md',
            md: 'max-w-lg',
            lg: 'max-w-2xl',
            xl: 'max-w-4xl',
            full: 'max-w-full mx-4'
        };

        // Tipos (colores)
        const types = {
            default: '',
            danger: 'border-t-4 border-red-500',
            success: 'border-t-4 border-green-500',
            warning: 'border-t-4 border-yellow-500',
            info: 'border-t-4 border-blue-500'
        };

        // Crear estructura HTML
        const modalHTML = `
            <div id="${modalId}" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" data-component="modal">
                <div class="modal-content bg-white rounded-xl shadow-2xl ${sizes[size]} w-full ${types[type]} transition-all duration-300 transform">
                    ${title ? `
                        <div class="modal-header px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-800">${title}</h3>
                            ${showClose ? `
                                <button data-modal-close class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            ` : ''}
                        </div>
                    ` : ''}

                    <div class="modal-body px-6 py-4">
                        ${content}
                    </div>

                    ${buttons.length > 0 ? `
                        <div class="modal-footer px-6 py-4 border-t border-gray-200 flex gap-3 justify-end">
                            ${buttons.map(btn => `
                                <button
                                    class="${btn.class || 'btn-secondary'}"
                                    ${btn.close ? 'data-modal-close' : ''}
                                    ${btn.onclick ? `onclick="${btn.onclick}"` : ''}
                                >
                                    ${btn.icon ? `<i class="fas ${btn.icon} mr-2"></i>` : ''}
                                    ${btn.text}
                                </button>
                            `).join('')}
                        </div>
                    ` : ''}
                </div>
            </div>
        `;

        // Insertar en el DOM
        document.body.insertAdjacentHTML('beforeend', modalHTML);

        // Crear instancia
        const modalElement = document.getElementById(modalId);
        const modalInstance = new Modal(modalElement);

        return modalInstance;
    }
}

// Export
window.Modal = Modal;

if (typeof module !== 'undefined' && module.exports) {
    module.exports = Modal;
}
