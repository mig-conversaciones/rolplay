/**
 * Toast Notification System
 * Sistema de notificaciones tipo toast para RolPlay EDU
 */

class Toast {
    static container = null;
    static queue = [];
    static maxToasts = 5;
    static defaultDuration = 5000;

    /**
     * Mostrar una notificación toast
     * @param {string} message - Mensaje a mostrar
     * @param {string} type - Tipo: 'success', 'error', 'warning', 'info'
     * @param {number} duration - Duración en ms (0 para permanente)
     * @returns {Toast} Instancia del toast
     */
    static show(message, type = 'info', duration = null) {
        if (!this.container) {
            this.createContainer();
        }

        // Limitar número de toasts simultáneos
        if (this.queue.length >= this.maxToasts) {
            const oldestToast = this.queue.shift();
            if (oldestToast) {
                oldestToast.dismiss();
            }
        }

        const toast = new Toast(message, type, duration);
        toast.render();
        this.queue.push(toast);

        return toast;
    }

    /**
     * Métodos estáticos de conveniencia
     */
    static success(message, duration = null) {
        return this.show(message, 'success', duration);
    }

    static error(message, duration = null) {
        return this.show(message, 'error', duration);
    }

    static warning(message, duration = null) {
        return this.show(message, 'warning', duration);
    }

    static info(message, duration = null) {
        return this.show(message, 'info', duration);
    }

    /**
     * Crear contenedor de toasts
     */
    static createContainer() {
        this.container = document.createElement('div');
        this.container.id = 'toast-container';
        this.container.className = 'fixed top-4 right-4 z-50 flex flex-col gap-3 pointer-events-none';
        this.container.style.maxWidth = '420px';
        document.body.appendChild(this.container);
    }

    /**
     * Limpiar todos los toasts
     */
    static clearAll() {
        this.queue.forEach(toast => toast.dismiss());
        this.queue = [];
    }

    /**
     * Constructor
     */
    constructor(message, type, duration) {
        this.message = message;
        this.type = type;
        this.duration = duration !== null ? duration : Toast.defaultDuration;
        this.element = null;
        this.timeout = null;
        this.dismissed = false;
    }

    /**
     * Renderizar el toast
     */
    render() {
        const colors = {
            success: {
                bg: 'bg-green-50',
                border: 'border-green-500',
                text: 'text-green-800',
                icon: 'text-green-500'
            },
            error: {
                bg: 'bg-red-50',
                border: 'border-red-500',
                text: 'text-red-800',
                icon: 'text-red-500'
            },
            warning: {
                bg: 'bg-yellow-50',
                border: 'border-yellow-500',
                text: 'text-yellow-800',
                icon: 'text-yellow-500'
            },
            info: {
                bg: 'bg-blue-50',
                border: 'border-blue-500',
                text: 'text-blue-800',
                icon: 'text-blue-500'
            },
        };

        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle',
        };

        const colorScheme = colors[this.type] || colors.info;
        const icon = icons[this.type] || icons.info;

        // Crear elemento
        this.element = document.createElement('div');
        this.element.className = `${colorScheme.bg} ${colorScheme.border} ${colorScheme.text} border-l-4 p-4 rounded-lg shadow-xl pointer-events-auto transition-all duration-300 transform translate-x-0 opacity-100`;
        this.element.style.animation = 'slideInRight 0.3s ease-out';

        // Estructura HTML
        this.element.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <i class="fas ${icon} ${colorScheme.icon} text-xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm leading-relaxed break-words">${this.escapeHtml(this.message)}</p>
                </div>
                <button class="flex-shrink-0 text-gray-500 hover:text-gray-700 transition-colors focus:outline-none" data-toast-close>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        // Progress bar si tiene duración
        if (this.duration > 0) {
            const progressBar = document.createElement('div');
            progressBar.className = 'mt-2 h-1 bg-black bg-opacity-10 rounded-full overflow-hidden';
            progressBar.innerHTML = `<div class="h-full bg-current transition-all duration-${this.duration}" style="width: 100%"></div>`;
            this.element.appendChild(progressBar);

            // Animar progress bar
            setTimeout(() => {
                const bar = progressBar.querySelector('div');
                if (bar) {
                    bar.style.width = '0%';
                    bar.style.transitionDuration = `${this.duration}ms`;
                }
            }, 50);
        }

        // Event listeners
        const closeBtn = this.element.querySelector('[data-toast-close]');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.dismiss());
        }

        // Añadir al contenedor
        Toast.container.appendChild(this.element);

        // Auto-dismiss
        if (this.duration > 0) {
            this.timeout = setTimeout(() => this.dismiss(), this.duration);
        }

        // Pausar en hover
        this.element.addEventListener('mouseenter', () => {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
        });

        this.element.addEventListener('mouseleave', () => {
            if (this.duration > 0 && !this.dismissed) {
                this.timeout = setTimeout(() => this.dismiss(), 1000);
            }
        });
    }

    /**
     * Cerrar el toast
     */
    dismiss() {
        if (this.dismissed || !this.element) {
            return;
        }

        this.dismissed = true;

        if (this.timeout) {
            clearTimeout(this.timeout);
        }

        // Animación de salida
        this.element.style.opacity = '0';
        this.element.style.transform = 'translateX(100%)';

        setTimeout(() => {
            if (this.element && this.element.parentNode) {
                this.element.remove();
            }

            // Remover de la cola
            const index = Toast.queue.indexOf(this);
            if (index > -1) {
                Toast.queue.splice(index, 1);
            }
        }, 300);
    }

    /**
     * Escapar HTML para prevenir XSS
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Animación CSS inline si no está en animations.css
if (!document.querySelector('#toast-animations')) {
    const style = document.createElement('style');
    style.id = 'toast-animations';
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
}

// API global
window.showToast = (message, type = 'info', duration = null) => Toast.show(message, type, duration);
window.Toast = Toast;

// Export para módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Toast;
}
