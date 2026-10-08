<!-- ================================================================
     MODAL / DRAWER DEL CARRITO DE COMPRAS (Sprint A)
     ================================================================ -->
<div id="cart-drawer" class="fixed inset-0 z-50 pointer-events-none transition-opacity duration-300 opacity-0" aria-hidden="true">
    <!-- Backdrop oscuro -->
    <div id="cart-backdrop" class="absolute inset-0 bg-ink/80 backdrop-blur-sm pointer-events-auto"></div>

    <!-- Panel lateral deslizante -->
    <aside class="absolute top-0 right-0 w-full max-w-md h-full bg-surface border-l border-border shadow-2xl flex flex-col pointer-events-auto transform translate-x-full transition-transform duration-300 ease-in-out">
        
        <!-- Header del carrito -->
        <div class="p-6 border-b border-border flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-full bg-amber/15 border border-amber/40 flex items-center justify-center text-amber text-sm">
                    <i class="fa-solid fa-bag-shopping"></i>
                </span>
                <h3 class="font-display text-xl font-bold text-cream">Tu Pedido</h3>
                <span id="cart-drawer-count-badge" class="badge text-xs ml-1">0 ítems</span>
            </div>
            <button id="cart-close-btn" type="button" class="text-muted hover:text-cream text-lg p-1.5 transition cursor-pointer" aria-label="Cerrar carrito">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Contenedor con lista de ítems -->
        <div id="cart-items-container" class="flex-1 overflow-y-auto p-6 space-y-4">
            <!-- Rellenado dinámicamente con JS -->
        </div>

        <!-- Mensaje de carrito vacío -->
        <div id="cart-empty-message" class="hidden flex-1 flex flex-col items-center justify-center p-6 text-center">
            <span class="w-16 h-16 rounded-full bg-card border border-border flex items-center justify-center text-muted text-2xl mb-3">
                <i class="fa-solid fa-mug-saucer"></i>
            </span>
            <p class="font-display text-lg font-semibold text-cream mb-1">Aún no tienes productos</p>
            <p class="text-muted text-xs max-w-xs mb-5">Explora nuestra carta y agrega tus cafés y postres favoritos usando el botón +.</p>
            <a href="{{ route('carta') }}" id="cart-go-to-menu-btn" class="btn-amber text-xs py-2 px-5">Ver Carta</a>
        </div>

        <!-- Footer del carrito con totales y botón a checkout -->
        <div id="cart-footer" class="p-6 border-t border-border bg-card/60 space-y-4">
            <div class="flex items-center justify-between text-sm">
                <span class="text-muted">Subtotal estimado:</span>
                <span id="cart-subtotal" class="font-semibold text-cream text-base">S/ 0.00</span>
            </div>
            <p class="text-[11px] text-muted">Precios de carta en S/ (Soles). Se verifica en checkout.</p>

            <div class="flex gap-3">
                <button id="cart-clear-btn" type="button" class="btn-ghost text-xs py-3 px-4 hover:border-red-500/50 hover:text-red-400">
                    Vaciar
                </button>
                <a href="{{ route('checkout') }}" id="cart-checkout-btn" class="btn-amber flex-1 text-center justify-center text-xs py-3.5 shadow-lg">
                    Continuar al Checkout <i class="fa-solid fa-arrow-right ml-1.5"></i>
                </a>
            </div>
        </div>

    </aside>
</div>

<!-- Toast flotante de confirmación rápida -->
<div id="cart-toast" class="fixed bottom-6 right-6 z-50 pointer-events-none transform translate-y-10 opacity-0 transition-all duration-300 flex items-center gap-3 bg-card border border-amber/40 text-cream px-4 py-3 rounded-xl shadow-2xl">
    <span class="w-8 h-8 rounded-full bg-amber/20 text-amber flex items-center justify-center text-xs shrink-0">
        <i class="fa-solid fa-check"></i>
    </span>
    <div>
        <p id="cart-toast-title" class="text-xs font-semibold text-cream">Producto agregado</p>
        <p id="cart-toast-sub" class="text-[11px] text-muted">Añadido a tu pedido</p>
    </div>
</div>

<script>
(function () {
    'use strict';

    const STORAGE_KEY = 'rg_cart_items_v1';

    function getCart() {
        try {
            return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
        } catch (e) {
            return [];
        }
    }

    function saveCart(items) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
        updateCartUI();
    }

    function addToCart(name, priceStr) {
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
        showToast(name);
    }

    function updateCartQuantity(name, delta) {
        let items = getCart();
        const target = items.find(i => i.name === name);
        if (!target) return;

        target.quantity += delta;
        if (target.quantity <= 0) {
            items = items.filter(i => i.name !== name);
        }

        saveCart(items);
    }

    function removeFromCart(name) {
        let items = getCart();
        items = items.filter(i => i.name !== name);
        saveCart(items);
    }

    function clearCart() {
        saveCart([]);
    }

    function openCartDrawer() {
        const drawer = document.getElementById('cart-drawer');
        if (!drawer) return;
        drawer.classList.remove('pointer-events-none', 'opacity-0');
        drawer.classList.add('opacity-100');
        const aside = drawer.querySelector('aside');
        if (aside) aside.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
    }

    function closeCartDrawer() {
        const drawer = document.getElementById('cart-drawer');
        if (!drawer) return;
        const aside = drawer.querySelector('aside');
        if (aside) aside.classList.add('translate-x-full');
        drawer.classList.remove('opacity-100');
        drawer.classList.add('opacity-0', 'pointer-events-none');
        document.body.style.overflow = '';
    }

    function showToast(productName) {
        const toast = document.getElementById('cart-toast');
        const title = document.getElementById('cart-toast-title');
        if (!toast || !title) return;

        title.textContent = productName;
        toast.classList.remove('translate-y-10', 'opacity-0', 'pointer-events-none');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-10', 'opacity-0', 'pointer-events-none');
        }, 2500);
    }

    function updateCartUI() {
        const items = getCart();
        const totalCount = items.reduce((sum, item) => sum + item.quantity, 0);
        const subtotal = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        // Badges en navbar
        document.querySelectorAll('.cart-count-badge').forEach(badge => {
            badge.textContent = totalCount;
            if (totalCount > 0) {
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });

        // Badge en drawer
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
                            <h4 class="font-medium text-cream text-sm truncate">${escapeHtml(item.name)}</h4>
                            <span class="text-amber font-semibold text-xs">S/ ${(item.price * item.quantity).toFixed(2)}</span>
                            <span class="text-muted text-[10px]"> (S/ ${item.price.toFixed(2)} c/u)</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="button" data-action="dec" data-name="${escapeHtml(item.name)}" class="w-6 h-6 rounded-md bg-surface border border-border text-cream hover:border-amber hover:text-amber flex items-center justify-center text-xs cursor-pointer">
                                <i class="fa-solid fa-minus pointer-events-none"></i>
                            </button>
                            <span class="font-mono text-cream text-xs w-5 text-center font-bold">${item.quantity}</span>
                            <button type="button" data-action="inc" data-name="${escapeHtml(item.name)}" class="w-6 h-6 rounded-md bg-surface border border-border text-cream hover:border-amber hover:text-amber flex items-center justify-center text-xs cursor-pointer">
                                <i class="fa-solid fa-plus pointer-events-none"></i>
                            </button>
                            <button type="button" data-action="remove" data-name="${escapeHtml(item.name)}" class="text-muted hover:text-red-400 p-1 text-xs cursor-pointer ml-1" title="Quitar">
                                <i class="fa-solid fa-trash-can pointer-events-none"></i>
                            </button>
                        </div>
                    </div>
                `).join('');
            }
        }

        if (typeof window.renderCheckoutSummary === 'function') {
            window.renderCheckoutSummary(items, subtotal);
        }
    }

    function escapeHtml(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    // Exponer helpers globales seguros
    window.getCart = getCart;
    window.saveCart = saveCart;
    window.clearCart = clearCart;
    window.updateCartUI = updateCartUI;
    window.openCartDrawer = openCartDrawer;
    window.closeCartDrawer = closeCartDrawer;

    document.addEventListener('DOMContentLoaded', function () {
        updateCartUI();

        // Escuchar clics globales para agregar al carrito con micro-interacción animada
        document.addEventListener('click', function (e) {
            const addBtn = e.target.closest('.add-to-cart-btn');
            if (addBtn) {
                e.preventDefault();
                const name = addBtn.dataset.name;
                const price = addBtn.dataset.price;
                if (name && price) {
                    addToCart(name, price);

                    // Animación 1: Animación del botón (de '+' a 'check' rotando con resplandor)
                    const icon = addBtn.querySelector('i');
                    if (icon) {
                        const origClasses = icon.className;
                        icon.className = 'fa-solid fa-check text-emerald-400 transform scale-125 rotate-360 transition-all duration-300';
                        addBtn.classList.add('border-emerald-400', 'bg-emerald-500/20');

                        setTimeout(() => {
                            icon.className = origClasses;
                            addBtn.classList.remove('border-emerald-400', 'bg-emerald-500/20');
                        }, 700);
                    }

                    // Animación 2: Pulso/rebote en los badges del carrito de la barra de navegación
                    document.querySelectorAll('.cart-toggle-btn').forEach(cartBtn => {
                        cartBtn.classList.add('scale-125', 'border-amber', 'text-amber');
                        setTimeout(() => {
                            cartBtn.classList.remove('scale-125', 'border-amber', 'text-amber');
                        }, 350);
                    });
                }
                return;
            }

            const toggleBtn = e.target.closest('.cart-toggle-btn');
            if (toggleBtn) {
                e.preventDefault();
                openCartDrawer();
                return;
            }

            // Event delegation dentro del drawer de carrito
            const actionBtn = e.target.closest('[data-action]');
            if (actionBtn && actionBtn.closest('#cart-items-container')) {
                e.preventDefault();
                const action = actionBtn.dataset.action;
                const name = actionBtn.dataset.name;
                if (action === 'inc') updateCartQuantity(name, 1);
                if (action === 'dec') updateCartQuantity(name, -1);
                if (action === 'remove') removeFromCart(name);
            }
        });

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
</script>
