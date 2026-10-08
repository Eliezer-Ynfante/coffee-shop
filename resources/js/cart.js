/**
 * Sistema de Carrito de Compras Persistente (Sprint A - Venta Web)
 * Maneja el estado en localStorage y sincroniza UI, badges, drawer y checkout.
 */
(function () {
    'use strict';

    const STORAGE_KEY = 'rg_cart_items_v1';

    // Obtener estado
    window.getCart = function () {
        try {
            return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
        } catch (e) {
            return [];
        }
    };

    // Guardar estado
    window.saveCart = function (items) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
        updateCartUI();
    };

    // Agregar producto
    window.addToCart = function (name, priceStr) {
        const items = getCart();
        const cleanPrice = parseFloat(String(priceStr).replace(/[^0-9.]/g, '')) || 0;
        
        const existing = items.find(i => i.name === name);
        if (existing) {
            existing.quantity += 1;
        } else {
            items.push({
                name: name,
                price: cleanPrice,
                quantity: 1
            });
        }

        saveCart(items);
        openCartDrawer();
    };

    // Cambiar cantidad
    window.updateCartQuantity = function (name, delta) {
        let items = getCart();
        const target = items.find(i => i.name === name);
        if (!target) return;

        target.quantity += delta;
        if (target.quantity <= 0) {
            items = items.filter(i => i.name !== name);
        }

        saveCart(items);
    };

    // Eliminar producto
    window.removeFromCart = function (name) {
        let items = getCart();
        items = items.filter(i => i.name !== name);
        saveCart(items);
    };

    // Vaciar carrito
    window.clearCart = function () {
        saveCart([]);
    };

    // Control del drawer
    window.openCartDrawer = function () {
        const drawer = document.getElementById('cart-drawer');
        if (!drawer) return;
        drawer.classList.remove('pointer-events-none', 'opacity-0');
        drawer.classList.add('opacity-100');
        const aside = drawer.querySelector('aside');
        if (aside) aside.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
    };

    window.closeCartDrawer = function () {
        const drawer = document.getElementById('cart-drawer');
        if (!drawer) return;
        const aside = drawer.querySelector('aside');
        if (aside) aside.classList.add('translate-x-full');
        drawer.classList.remove('opacity-100');
        drawer.classList.add('opacity-0', 'pointer-events-none');
        document.body.style.overflow = '';
    };

    // Renderizar UI del carrito
    window.updateCartUI = function () {
        const items = getCart();
        const totalCount = items.reduce((sum, item) => sum + item.quantity, 0);
        const subtotal = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        // Actualizar badges en navbar
        const countBadges = document.querySelectorAll('.cart-count-badge');
        countBadges.forEach(badge => {
            badge.textContent = totalCount;
            if (totalCount > 0) {
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });

        // Actualizar drawer si existe
        const drawerCountBadge = document.getElementById('cart-drawer-count-badge');
        if (drawerCountBadge) {
            drawerCountBadge.textContent = totalCount + (totalCount === 1 ? ' ítem' : ' ítems');
        }

        const itemsContainer = document.getElementById('cart-items-container');
        const emptyMessage = document.getElementById('cart-empty-message');
        const cartFooter = document.getElementById('cart-footer');
        const subtotalEl = document.getElementById('cart-subtotal');

        if (subtotalEl) {
            subtotalEl.textContent = 'S/ ' + subtotal.toFixed(2);
        }

        if (itemsContainer && emptyMessage && cartFooter) {
            if (items.length === 0) {
                itemsContainer.innerHTML = '';
                itemsContainer.classList.add('hidden');
                emptyMessage.classList.remove('hidden');
                cartFooter.classList.add('hidden');
            } else {
                emptyMessage.classList.add('hidden');
                itemsContainer.classList.remove('hidden');
                cartFooter.classList.remove('hidden');

                itemsContainer.innerHTML = items.map(item => `
                    <div class="bg-card border border-border rounded-lg p-3.5 flex items-center justify-between gap-3 shadow-xs">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-cream text-sm truncate">${item.name}</h4>
                            <span class="text-amber font-semibold text-xs">S/ ${(item.price * item.quantity).toFixed(2)}</span>
                            <span class="text-muted text-[10px]"> (S/ ${item.price.toFixed(2)} c/u)</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" onclick="updateCartQuantity('${item.name}', -1)" class="w-6 h-6 rounded-md bg-surface border border-border text-cream hover:border-amber hover:text-amber flex items-center justify-center text-xs cursor-pointer">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <span class="font-mono text-cream text-xs w-4 text-center font-bold">${item.quantity}</span>
                            <button type="button" onclick="updateCartQuantity('${item.name}', 1)" class="w-6 h-6 rounded-md bg-surface border border-border text-cream hover:border-amber hover:text-amber flex items-center justify-center text-xs cursor-pointer">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                            <button type="button" onclick="removeFromCart('${item.name}')" class="text-muted hover:text-red-400 p-1 text-xs cursor-pointer ml-1" title="Quitar">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                `).join('');
            }
        }

        // Si estamos en la página de checkout, actualizar su vista
        if (typeof window.renderCheckoutSummary === 'function') {
            window.renderCheckoutSummary(items, subtotal);
        }
    };

    // Event Listeners globales
    document.addEventListener('DOMContentLoaded', function () {
        updateCartUI();

        // Clic en botones de agregar (+)
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.add-to-cart-btn');
            if (btn) {
                e.preventDefault();
                const name = btn.dataset.name;
                const price = btn.dataset.price;
                if (name && price) {
                    addToCart(name, price);
                }
            }
        });

        // Abrir drawer desde botón de navbar
        document.querySelectorAll('.cart-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                openCartDrawer();
            });
        });

        // Cerrar drawer
        const closeBtn = document.getElementById('cart-close-btn');
        if (closeBtn) closeBtn.addEventListener('click', closeCartDrawer);

        const backdrop = document.getElementById('cart-backdrop');
        if (backdrop) backdrop.addEventListener('click', closeCartDrawer);

        const clearBtn = document.getElementById('cart-clear-btn');
        if (clearBtn) clearBtn.addEventListener('click', function () {
            if (confirm('¿Deseas vaciar todos los productos de tu pedido?')) {
                clearCart();
            }
        });
    });

})();
