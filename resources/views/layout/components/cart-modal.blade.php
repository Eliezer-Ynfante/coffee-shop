<!-- ================================================================
     CARRITO MODAL
     ================================================================ -->
<div id="cart-modal" class="flex fixed inset-0 z-40 items-end md:items-center justify-center" aria-hidden="true">
    <!-- Overlay -->
    <div id="cart-overlay" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <!-- Modal -->
    <div class="relative bg-card border border-border rounded-t-xl md:rounded-lg w-full md:max-w-2xl max-h-[90vh] md:max-h-96 flex flex-col shadow-2xl">
        
        <!-- Encabezado -->
        <div class="flex items-center justify-between p-5 border-b border-border shrink-0">
            <h2 class="font-display text-xl font-semibold text-cream flex items-center gap-2">
                <i class="fa-solid fa-cart-shopping text-amber" aria-hidden="true"></i>
                Mi carrito
            </h2>
            <button id="cart-close" class="text-cream/60 hover:text-cream transition-colors p-1" aria-label="Cerrar carrito">
                <i class="fa-solid fa-xmark text-xl" aria-hidden="true"></i>
            </button>
        </div>

        <!-- Contenido del carrito -->
        <div id="cart-content" class="flex-1 overflow-y-auto">
            <!-- Items se cargarán aquí dinámicamente -->
            <div id="cart-empty" class="flex flex-col items-center justify-center h-full p-6 text-center">
                <i class="fa-solid fa-bag-shopping text-4xl text-amber/30 mb-3" aria-hidden="true"></i>
                <p class="text-cream/60 text-sm">Tu carrito está vacío</p>
                <p class="text-muted text-xs mt-1">Agrega algunos productos para empezar</p>
            </div>
            <ul id="cart-items" class="divide-y divide-border hidden"></ul>
        </div>

        <!-- Footer con resumen y botones -->
        <div id="cart-footer" class="border-t border-border p-5 shrink-0 hidden space-y-3">
            <!-- Resumen de precio -->
            <div class="space-y-2">
                <div class="flex justify-between text-sm text-cream/70">
                    <span>Subtotal:</span>
                    <span id="cart-subtotal">$0.00</span>
                </div>
                <div class="flex justify-between text-sm text-cream/70">
                    <span>Impuesto (IGV 18%):</span>
                    <span id="cart-tax">$0.00</span>
                </div>
                <div class="flex justify-between font-semibold text-cream border-t border-border pt-2 mt-2">
                    <span>Total:</span>
                    <span id="cart-total" class="text-amber text-lg">$0.00</span>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-3 pt-2">
                <button id="cart-continue" class="flex-1 btn-ghost text-center text-sm">
                    Seguir comprando
                </button>
                <button id="cart-checkout" class="flex-1 btn-amber text-center text-sm">
                    <i class="fa-solid fa-credit-card mr-2" aria-hidden="true"></i>Pagar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notificación de producto agregado -->
<div id="cart-toast" class="fixed bottom-6 left-6 bg-green-600/90 border border-green-500 text-cream text-sm px-4 py-3 rounded-lg hidden z-50 animate-pulse">
    <i class="fa-solid fa-check mr-2" aria-hidden="true"></i>
    <span id="toast-message">Producto agregado al carrito</span>
</div>
