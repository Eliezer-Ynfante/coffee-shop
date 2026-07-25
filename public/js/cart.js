/**
 * Sistema de Carrito de Compras
 * Maneja: agregar, eliminar, actualizar cantidad, localStorage
 */

class ShoppingCart {
    constructor() {
        this.storageKey = 'coffee_cart';
        this.cart = this.loadCart();
        this.initializeEventListeners();
        this.updateUI();
    }

    // ============= GESTIÓN DE DATOS =============

    /**
     * Cargar carrito desde localStorage
     */
    loadCart() {
        const stored = localStorage.getItem(this.storageKey);
        return stored ? JSON.parse(stored) : [];
    }

    /**
     * Guardar carrito en localStorage
     */
    saveCart() {
        localStorage.setItem(this.storageKey, JSON.stringify(this.cart));
    }

    /**
     * Agregar producto al carrito
     */
    addProduct(productData) {
        const existingItem = this.cart.find(item => item.id === productData.id);

        if (existingItem) {
            existingItem.quantity += productData.quantity || 1;
        } else {
            this.cart.push({
                id: productData.id,
                nombre: productData.nombre,
                precio: productData.precio,
                imagen: productData.imagen,
                quantity: productData.quantity || 1
            });
        }

        this.saveCart();
        this.updateUI();
        this.showToast(`${productData.nombre} agregado al carrito`);
    }

    /**
     * Actualizar cantidad de producto
     */
    updateQuantity(productId, quantity) {
        const item = this.cart.find(item => item.id === productId);
        if (item) {
            item.quantity = Math.max(1, quantity);
            this.saveCart();
            this.updateUI();
        }
    }

    /**
     * Eliminar producto del carrito
     */
    removeProduct(productId) {
        this.cart = this.cart.filter(item => item.id !== productId);
        this.saveCart();
        this.updateUI();
        this.showToast('Producto eliminado del carrito', 'info');
    }

    /**
     * Vaciar carrito completamente
     */
    clearCart() {
        if (confirm('¿Vaciar el carrito completamente?')) {
            this.cart = [];
            this.saveCart();
            this.updateUI();
            this.showToast('Carrito vaciado', 'info');
        }
    }

    /**
     * Obtener total del carrito
     */
    getTotal() {
        return this.cart.reduce((total, item) => total + (item.precio * item.quantity), 0);
    }

    /**
     * Obtener cantidad total de items
     */
    getItemCount() {
        return this.cart.reduce((total, item) => total + item.quantity, 0);
    }

    /**
     * Obtener datos del carrito (para enviar a servidor)
     */
    getCartData() {
        const subtotal = this.getTotal();
        const tax = subtotal * 0.18;
        const total = subtotal + tax;

        return {
            items: this.cart,
            subtotal: subtotal.toFixed(2),
            tax: tax.toFixed(2),
            total: total.toFixed(2),
            itemCount: this.getItemCount()
        };
    }

    // ============= UI UPDATES =============

    /**
     * Actualizar interfaz del carrito
     */
    updateUI() {
        this.updateCartBadge();
        this.updateModalContent();
        this.updatePrices();
    }

    /**
     * Actualizar el badge del carrito en el navbar
     */
    updateCartBadge() {
        const count = this.getItemCount();
        const cartLinks = document.querySelectorAll('a[href="/carrito"]');
        
        cartLinks.forEach(link => {
            // Remover badge anterior si existe
            const oldBadge = link.querySelector('.cart-badge');
            if (oldBadge) oldBadge.remove();

            // Agregar nuevo badge si hay items
            if (count > 0) {
                const badge = document.createElement('span');
                badge.className = 'cart-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center';
                badge.textContent = count > 9 ? '9+' : count;
                link.classList.add('relative');
                link.appendChild(badge);
            }
        });
    }

    /**
     * Actualizar contenido del modal del carrito
     */
    updateModalContent() {
        const emptyState = document.getElementById('cart-empty');
        const cartItems = document.getElementById('cart-items');
        const cartFooter = document.getElementById('cart-footer');

        if (this.cart.length === 0) {
            cartItems.classList.add('hidden');
            cartFooter.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        cartItems.classList.remove('hidden');
        cartFooter.classList.remove('hidden');

        // Limpiar items anteriores
        cartItems.innerHTML = '';

        // Renderizar items
        this.cart.forEach(item => {
            const li = document.createElement('li');
            li.className = 'p-4 hover:bg-ink/30 transition-colors';
            li.innerHTML = `
                <div class="flex gap-3">
                    <!-- Imagen -->
                    <div class="w-16 h-16 rounded-lg overflow-hidden shrink-0 bg-ink border border-border">
                        <img src="${item.imagen}" alt="${item.nombre}" class="w-full h-full object-cover">
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <h4 class="text-cream font-medium text-sm mb-0.5 truncate">${item.nombre}</h4>
                        <p class="text-amber font-semibold text-sm mb-2">S/. ${parseFloat(item.precio).toFixed(2)}</p>

                        <!-- Controles de cantidad -->
                        <div class="flex items-center gap-2 bg-ink px-2 py-1 rounded w-fit">
                            <button class="cart-decrease text-cream/60 hover:text-cream transition-colors px-1" data-product-id="${item.id}">
                                <i class="fa-solid fa-minus text-xs" aria-hidden="true"></i>
                            </button>
                            <span class="cart-qty text-cream text-xs font-medium w-6 text-center">${item.quantity}</span>
                            <button class="cart-increase text-cream/60 hover:text-cream transition-colors px-1" data-product-id="${item.id}">
                                <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Subtotal y eliminar -->
                    <div class="text-right flex flex-col justify-between">
                        <button class="cart-remove text-red-400 hover:text-red-300 transition-colors text-sm" data-product-id="${item.id}" aria-label="Eliminar producto">
                            <i class="fa-solid fa-trash-alt" aria-hidden="true"></i>
                        </button>
                        <p class="text-cream/70 text-xs">
                            S/. ${(item.precio * item.quantity).toFixed(2)}
                        </p>
                    </div>
                </div>
            `;
            cartItems.appendChild(li);
        });

        this.attachItemEventListeners();
    }

    /**
     * Actualizar precios (subtotal, impuesto, total)
     */
    updatePrices() {
        const data = this.getCartData();
        document.getElementById('cart-subtotal').textContent = `S/. ${data.subtotal}`;
        document.getElementById('cart-tax').textContent = `S/. ${data.tax}`;
        document.getElementById('cart-total').textContent = `S/. ${data.total}`;
    }

    /**
     * Mostrar notificación tipo toast
     */
    showToast(message, type = 'success') {
        const toast = document.getElementById('cart-toast');
        const toastMessage = document.getElementById('toast-message');

        if (!toast) return;

        toastMessage.textContent = message;
        toast.classList.remove('hidden', 'bg-blue-600/90', 'border-blue-500');
        toast.classList.add('animate-pulse');

        if (type === 'info') {
            toast.classList.add('bg-blue-600/90', 'border-blue-500');
        } else {
            toast.classList.add('bg-green-600/90', 'border-green-500');
        }

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }

    // ============= EVENT LISTENERS =============

    /**
     * Inicializar event listeners globales
     */
    initializeEventListeners() {
        // Botones para abrir/cerrar modal
        const cartLinks = document.querySelectorAll('a[href="/carrito"]');
        const cartModal = document.getElementById('cart-modal');
        const cartClose = document.getElementById('cart-close');
        const cartOverlay = document.getElementById('cart-overlay');

        cartLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.openModal();
            });
        });

        if (cartClose) cartClose.addEventListener('click', () => this.closeModal());
        if (cartOverlay) cartOverlay.addEventListener('click', () => this.closeModal());

        // Botón "Seguir comprando"
        const continueBtn = document.getElementById('cart-continue');
        if (continueBtn) {
            continueBtn.addEventListener('click', () => this.closeModal());
        }

        // Botón "Pagar"
        const checkoutBtn = document.getElementById('cart-checkout');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', () => this.checkout());
        }

        // Cerrar con tecla Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !document.getElementById('cart-modal').classList.contains('hidden')) {
                this.closeModal();
            }
        });
    }

    /**
     * Adjuntar event listeners a items del carrito
     */
    attachItemEventListeners() {
        // Botones aumentar cantidad
        document.querySelectorAll('.cart-increase').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const productId = parseInt(e.currentTarget.dataset.productId);
                const item = this.cart.find(i => i.id === productId);
                if (item) this.updateQuantity(productId, item.quantity + 1);
            });
        });

        // Botones disminuir cantidad
        document.querySelectorAll('.cart-decrease').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const productId = parseInt(e.currentTarget.dataset.productId);
                const item = this.cart.find(i => i.id === productId);
                if (item) this.updateQuantity(productId, item.quantity - 1);
            });
        });

        // Botones eliminar
        document.querySelectorAll('.cart-remove').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const productId = parseInt(e.currentTarget.dataset.productId);
                this.removeProduct(productId);
            });
        });
    }

    /**
     * Abrir modal del carrito
     */
    openModal() {
        const modal = document.getElementById('cart-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    /**
     * Cerrar modal del carrito
     */
    closeModal() {
        const modal = document.getElementById('cart-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    /**
     * Procesar compra
     */
    checkout() {
        const data = this.getCartData();
        console.log('Datos del carrito para compra:', data);
        
        // Aquí iría la lógica para enviar a tu servidor
        alert(`Total a pagar: S/. ${data.total}\n\nRedirigiéndote a checkout...`);
        
        // Redirigir a página de checkout (cambiar según tu ruta)
        // window.location.href = `/checkout?cart=${encodeURIComponent(JSON.stringify(data))}`;
    }
}

// Inicializar carrito cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.cart = new ShoppingCart();
    });
} else {
    window.cart = new ShoppingCart();
}
