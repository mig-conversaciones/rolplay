/**
 * Accordion Component
 * Sistema de accordions colapsables para RolPlay EDU
 */

class Accordion {
    constructor(element) {
        this.accordion = element;
        this.items = [];
        this.allowMultiple = element.dataset.multiple === 'true';
        this.animated = element.dataset.animated !== 'false';

        this.init();
    }

    /**
     * Inicializar el accordion
     */
    init() {
        // Obtener todos los items
        this.items = Array.from(this.accordion.querySelectorAll('[data-accordion-item]'));

        if (this.items.length === 0) {
            console.warn('No se encontraron items en el accordion');
            return;
        }

        // Configurar cada item
        this.items.forEach((item, index) => {
            this.setupItem(item, index);
        });

        // Abrir el primer item si está marcado como activo
        const firstActive = this.items.find(item => item.classList.contains('active'));
        if (firstActive) {
            const index = this.items.indexOf(firstActive);
            this.expand(index, false);
        }
    }

    /**
     * Configurar un item individual
     */
    setupItem(item, index) {
        const trigger = item.querySelector('[data-accordion-trigger]');
        const content = item.querySelector('[data-accordion-content]');
        const icon = item.querySelector('[data-accordion-icon]');

        if (!trigger || !content) {
            console.warn('Item de accordion incompleto', item);
            return;
        }

        // ID único para accesibilidad
        const contentId = content.id || `accordion-content-${Date.now()}-${index}`;
        content.id = contentId;

        // ARIA attributes
        trigger.setAttribute('aria-expanded', 'false');
        trigger.setAttribute('aria-controls', contentId);
        content.setAttribute('role', 'region');

        // Inicializar colapsado
        if (!item.classList.contains('active')) {
            content.style.maxHeight = '0';
            content.style.overflow = 'hidden';
            content.style.opacity = '0';
        } else {
            trigger.setAttribute('aria-expanded', 'true');
            content.style.maxHeight = content.scrollHeight + 'px';
            content.style.opacity = '1';
        }

        // Transición suave
        content.style.transition = 'max-height 0.3s ease, opacity 0.3s ease';

        if (icon) {
            icon.style.transition = 'transform 0.3s ease';
        }

        // Event listener
        trigger.addEventListener('click', () => {
            this.toggle(index);
        });

        // Navegación por teclado
        trigger.addEventListener('keydown', (e) => {
            this.handleKeydown(e, index);
        });
    }

    /**
     * Toggle de un item
     */
    toggle(index) {
        const item = this.items[index];
        const isActive = item.classList.contains('active');

        if (isActive) {
            this.collapse(index);
        } else {
            // Si no permite múltiples, cerrar todos los demás
            if (!this.allowMultiple) {
                this.items.forEach((otherItem, otherIndex) => {
                    if (otherIndex !== index && otherItem.classList.contains('active')) {
                        this.collapse(otherIndex);
                    }
                });
            }

            this.expand(index);
        }
    }

    /**
     * Expandir un item
     */
    expand(index, animate = true) {
        const item = this.items[index];
        const trigger = item.querySelector('[data-accordion-trigger]');
        const content = item.querySelector('[data-accordion-content]');
        const icon = item.querySelector('[data-accordion-icon]');

        if (!content) return;

        item.classList.add('active');
        trigger.setAttribute('aria-expanded', 'true');

        // Calcular altura
        const height = content.scrollHeight;

        if (animate && this.animated) {
            content.style.maxHeight = height + 'px';
            content.style.opacity = '1';
        } else {
            content.style.maxHeight = 'none';
            content.style.opacity = '1';
            content.style.transition = 'none';
            setTimeout(() => {
                content.style.transition = 'max-height 0.3s ease, opacity 0.3s ease';
            }, 10);
        }

        // Rotar icono
        if (icon) {
            icon.style.transform = 'rotate(180deg)';
        }

        // Disparar evento
        item.dispatchEvent(new CustomEvent('accordion:expand', {
            detail: { index }
        }));
    }

    /**
     * Colapsar un item
     */
    collapse(index, animate = true) {
        const item = this.items[index];
        const trigger = item.querySelector('[data-accordion-trigger]');
        const content = item.querySelector('[data-accordion-content]');
        const icon = item.querySelector('[data-accordion-icon]');

        if (!content) return;

        item.classList.remove('active');
        trigger.setAttribute('aria-expanded', 'false');

        if (animate && this.animated) {
            content.style.maxHeight = '0';
            content.style.opacity = '0';
        } else {
            content.style.maxHeight = '0';
            content.style.opacity = '0';
            content.style.transition = 'none';
            setTimeout(() => {
                content.style.transition = 'max-height 0.3s ease, opacity 0.3s ease';
            }, 10);
        }

        // Restaurar icono
        if (icon) {
            icon.style.transform = 'rotate(0deg)';
        }

        // Disparar evento
        item.dispatchEvent(new CustomEvent('accordion:collapse', {
            detail: { index }
        }));
    }

    /**
     * Expandir todos
     */
    expandAll() {
        if (!this.allowMultiple) {
            console.warn('expandAll() solo funciona con allowMultiple=true');
            return;
        }

        this.items.forEach((item, index) => {
            if (!item.classList.contains('active')) {
                this.expand(index);
            }
        });
    }

    /**
     * Colapsar todos
     */
    collapseAll() {
        this.items.forEach((item, index) => {
            if (item.classList.contains('active')) {
                this.collapse(index);
            }
        });
    }

    /**
     * Navegación por teclado
     */
    handleKeydown(e, currentIndex) {
        const triggers = this.items.map(item => item.querySelector('[data-accordion-trigger]'));

        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                const nextIndex = (currentIndex + 1) % this.items.length;
                triggers[nextIndex]?.focus();
                break;

            case 'ArrowUp':
                e.preventDefault();
                const prevIndex = currentIndex === 0 ? this.items.length - 1 : currentIndex - 1;
                triggers[prevIndex]?.focus();
                break;

            case 'Home':
                e.preventDefault();
                triggers[0]?.focus();
                break;

            case 'End':
                e.preventDefault();
                triggers[this.items.length - 1]?.focus();
                break;

            case 'Enter':
            case ' ':
                e.preventDefault();
                this.toggle(currentIndex);
                break;
        }
    }

    /**
     * Recalcular alturas (útil si el contenido cambia)
     */
    refresh() {
        this.items.forEach((item, index) => {
            if (item.classList.contains('active')) {
                const content = item.querySelector('[data-accordion-content]');
                if (content) {
                    content.style.maxHeight = content.scrollHeight + 'px';
                }
            }
        });
    }

    /**
     * Destruir el accordion
     */
    destroy() {
        this.items.forEach(item => {
            const trigger = item.querySelector('[data-accordion-trigger]');
            if (trigger) {
                trigger.replaceWith(trigger.cloneNode(true));
            }
        });

        this.accordion.__component = null;
    }
}

// Export
window.Accordion = Accordion;

if (typeof module !== 'undefined' && module.exports) {
    module.exports = Accordion;
}
