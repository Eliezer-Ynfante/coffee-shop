@extends('layout.layout')
@section('content')

<!-- ================================================================
     CARRITO - PÁGINA COMPLETA
     ================================================================ -->
<section class="min-h-screen bg-ink py-28 px-6" aria-label="Carrito de compras">
    <div class="max-w-5xl mx-auto">

        <!-- Encabezado -->
        <div class="mb-10 reveal">
            <h1 class="font-display text-4xl md:text-5xl font-bold text-cream mb-2">
                <i class="fa-solid fa-cart-shopping text-amber mr-3" aria-hidden="true"></i>Mi Carrito
            </h1>
            <p class="text-cream/60 text-sm md:text-base">Revisa y modifica tus productos antes de pagar</p>
        </div>

        <!-- Grid: Carrito + Resumen -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Columna principal: Items del carrito -->
            <div class="lg:col-span-2 reveal">
                <div id="cart-page-items" class="space-y-4">
                    <!-- Items se cargarán con JavaScript -->
                    <div id="cart-page-empty" class="bg-card border border-border rounded-lg p-12 text-center">
                        <i class="fa-solid fa-bag-shopping text-5xl text-amber/30 mb-4" aria-hidden="true"></i>
                        <h2 class="text-cream font-display text-2xl mb-2">Carrito vacío</h2>
                        <p class="text-cream/60 text-sm mb-6">Aún no has agregado productos</p>
                        <a href="/#galeria" class="btn-amber inline-block">
                            <i class="fa-solid fa-arrow-left mr-2" aria-hidden="true"></i>Volver al menú
                        </a>
                    </div>
                </div>
            </div>

            <!-- Columna lateral: Resumen y checkout -->
            <div class="lg:col-span-1 reveal" style="transition-delay:.1s">
                <div id="cart-page-summary" class="bg-card border border-border rounded-lg p-6 sticky top-28 hidden">
                    <!-- Encabezado -->
                    <h3 class="font-display text-xl font-semibold text-cream mb-6 pb-4 border-b border-border">
                        Resumen de orden
                    </h3>

                    <!-- Detalles -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm text-cream/70">
                            <span>Cantidad de items:</span>
                            <span id="cart-page-qty" class="text-cream font-medium">0</span>
                        </div>
                        <div class="flex justify-between text-sm text-cream/70">
                            <span>Subtotal:</span>
                            <span id="cart-page-subtotal" class="text-cream font-medium">S/. 0.00</span>
                        </div>
                        <div class="flex justify-between text-sm text-cream/70">
                            <span>IGV (18%):</span>
                            <span id="cart-page-tax" class="text-cream font-medium">S/. 0.00</span>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between border-t border-border pt-3 mt-3">
                            <span class="font-display font-semibold text-cream">Total:</span>
                            <span id="cart-page-total" class="font-display text-2xl font-bold text-amber">S/. 0.00</span>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="space-y-2">
                        <button id="cart-page-checkout" class="btn-amber w-full text-center">
                            <i class="fa-solid fa-credit-card mr-2" aria-hidden="true"></i>Proceder a pago
                        </button>
                        <a href="/#galeria" class="btn-ghost w-full text-center block">
                            <i class="fa-solid fa-arrow-left mr-2" aria-hidden="true"></i>Seguir comprando
                        </a>
                        <button id="cart-page-clear" class="w-full text-center text-xs text-red-400 hover:text-red-300 transition-colors py-2">
                            <i class="fa-solid fa-trash-alt mr-2" aria-hidden="true"></i>Vaciar carrito
                        </button>
                    </div>

                    <!-- Nota -->
                    <p class="text-muted text-xs text-center mt-6 p-3 bg-ink rounded border border-border">
                        <i class="fa-solid fa-shield-heart mr-1" aria-hidden="true"></i>
                        Tu pago es seguro y encriptado
                    </p>
                </div>

                <!-- Estado vacío en sidebar (mobile) -->
                <div id="cart-page-summary-empty" class="bg-card border border-border rounded-lg p-6 hidden md:block">
                    <p class="text-cream/60 text-sm text-center">El resumen aparecerá cuando agregues productos</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    /**
     * Renderizador de página de carrito
     * Sincroniza con el carrito global
     */
    class CartPageRenderer {
        constructor() {
            this.init();
        }

        init() {
            // Esperar a que el carrito global esté listo
            if (typeof window.cart !== 'undefined') {
                this.render();
                // Observer para cambios en el carrito
                this.watchCartChanges();
            } else {
                setTimeout(() => this.init(), 100);
            }
        }

        render() {
            const cart = window.cart;
            const empty = document.getElementById('cart-page-empty');
            const items = document.getElementById('cart-page-items');
            const summary = document.getElementById('cart-page-summary');
            const summaryEmpty = document.getElementById('cart-page-summary-empty');

            if (cart.cart.length === 0) {
                empty.classList.remove('hidden');
                items.innerHTML = '';
                summary.classList.add('hidden');
                summaryEmpty.classList.remove('hidden');
                return;
            }

            empty.classList.add('hidden');
            summary.classList.remove('hidden');
            summaryEmpty.classList.add('hidden');

            // Renderizar items
            items.innerHTML = cart.cart.map((item, index) => `
                <div class="bg-card border border-border rounded-lg p-5 flex gap-4 reveal" style="transition-delay:${index * 0.05}s">
                    <!-- Imagen -->
                    <div class="w-24 h-24 rounded-lg overflow-hidden shrink-0 bg-ink border border-border/50">
                        <img src="${item.imagen}" alt="${item.nombre}" class="w-full h-full object-cover">
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="font-display text-lg font-semibold text-cream mb-1">${item.nombre}</h3>
                        <p class="text-amber font-bold text-sm mb-3">S/. ${parseFloat(item.precio).toFixed(2)} c/u</p>

                        <!-- Controles -->
                        <div class="flex items-center gap-2">
                            <button class="cart-page-decrease transition-colors text-cream/60 hover:text-cream px-2 py-1 rounded hover:bg-ink" data-product-id="${item.id}">
                                <i class="fa-solid fa-minus" aria-hidden="true"></i>
                            </button>
                            <span class="cart-page-qty text-cream font-medium w-8 text-center text-sm">${item.quantity}</span>
                            <button class="cart-page-increase transition-colors text-cream/60 hover:text-cream px-2 py-1 rounded hover:bg-ink" data-product-id="${item.id}">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                            </button>
                            <span class="text-cream/50 text-xs">|</span>
                            <button class="cart-page-remove transition-colors text-red-400 hover:text-red-300 px-2 py-1 rounded hover:bg-red-400/10" data-product-id="${item.id}">
                                <i class="fa-solid fa-trash-alt" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Subtotal -->
                    <div class="text-right">
                        <p class="text-cream/70 text-xs mb-2">Subtotal</p>
                        <p class="text-amber font-display font-bold text-lg">S/. ${(item.precio * item.quantity).toFixed(2)}</p>
                    </div>
                </div>
            `).join('');

            // Actualizar resumen
            const data = cart.getCartData();
            document.getElementById('cart-page-qty').textContent = data.itemCount;
            document.getElementById('cart-page-subtotal').textContent = `S/. ${data.subtotal}`;
            document.getElementById('cart-page-tax').textContent = `S/. ${data.tax}`;
            document.getElementById('cart-page-total').textContent = `S/. ${data.total}`;

            // Adjuntar event listeners
            this.attachEventListeners();
        }

        attachEventListeners() {
            // Aumentar
            document.querySelectorAll('.cart-page-increase').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const productId = parseInt(e.currentTarget.dataset.productId);
                    const item = window.cart.cart.find(i => i.id === productId);
                    if (item) window.cart.updateQuantity(productId, item.quantity + 1);
                });
            });

            // Disminuir
            document.querySelectorAll('.cart-page-decrease').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const productId = parseInt(e.currentTarget.dataset.productId);
                    const item = window.cart.cart.find(i => i.id === productId);
                    if (item) window.cart.updateQuantity(productId, item.quantity - 1);
                });
            });

            // Eliminar
            document.querySelectorAll('.cart-page-remove').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const productId = parseInt(e.currentTarget.dataset.productId);
                    window.cart.removeProduct(productId);
                });
            });

            // Checkout
            const checkoutBtn = document.getElementById('cart-page-checkout');
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', () => {
                    window.cart.checkout();
                });
            }

            // Vaciar
            const clearBtn = document.getElementById('cart-page-clear');
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    window.cart.clearCart();
                });
            }
        }

        watchCartChanges() {
            // Observador simple: verificar localStorage cada segundo
            setInterval(() => {
                const storedCart = localStorage.getItem('coffee_cart');
                this.render();
            }, 500);
        }
    }

    // Inicializar renderer
    new CartPageRenderer();
</script>

@endsection
