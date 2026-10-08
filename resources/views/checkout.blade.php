@extends('layout.layout')

@section('title', 'Finalizar Pedido')

@section('content')

<section class="min-h-screen bg-ink py-28 px-6 relative grain overflow-hidden" aria-label="Finalizar Pedido">
    {{-- Glow ambiental --}}
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber/8 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-amber/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-6xl mx-auto relative z-10">

        <!-- Encabezado -->
        <div class="mb-10 text-center sm:text-left">
            <p class="amber-tag mb-2">Venta Web</p>
            <h1 class="font-display text-3xl sm:text-4xl font-bold text-cream">
                Finalizar tu <em class="text-amber not-italic">Pedido</em>
            </h1>
            <p class="text-muted text-sm mt-1">Configura la entrega y confirma tus datos para procesar la orden.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

            <!-- Columna Izquierda: Formulario (7 cols) con sombras de alta definición -->
            <div class="lg:col-span-7 bg-card border border-border/80 rounded-2xl p-7 sm:p-9 shadow-2xl shadow-black/60">
                
                <form id="checkout-form" action="{{ route('pedido.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <p id="checkout-error" class="hidden rounded-xl border border-red-500/30 bg-red-500/10 p-3.5 text-sm text-red-300 shadow-md" role="alert" aria-live="polite"></p>

                    <!-- Tipo de Entrega con tarjetas con sombra -->
                    <div>
                        <label class="f-label mb-2.5 block text-cream font-medium">
                            <i class="fa-solid fa-bell-concierge mr-1.5 text-amber"></i> ¿Cómo deseas recibir tu pedido? *
                        </label>
                        <div class="grid grid-cols-3 gap-3" role="radiogroup">
                            <label class="delivery-option flex flex-col items-center justify-center p-3.5 rounded-xl border border-amber bg-amber/10 shadow-md shadow-amber/10 cursor-pointer text-center text-xs transition-all duration-200">
                                <input type="radio" name="delivery_type" value="mesa" checked class="hidden">
                                <i class="fa-solid fa-utensils text-amber mb-1.5 text-lg"></i>
                                <span class="font-medium text-cream">En Mesa</span>
                            </label>

                            <label class="delivery-option flex flex-col items-center justify-center p-3.5 rounded-xl border border-border/80 bg-surface shadow-md shadow-black/40 hover:border-amber/50 cursor-pointer text-center text-xs transition-all duration-200">
                                <input type="radio" name="delivery_type" value="recojo" class="hidden">
                                <i class="fa-solid fa-bag-shopping text-muted mb-1.5 text-lg"></i>
                                <span class="font-medium text-cream">Para Llevar</span>
                            </label>

                            <label class="delivery-option flex flex-col items-center justify-center p-3.5 rounded-xl border border-border/80 bg-surface shadow-md shadow-black/40 hover:border-amber/50 cursor-pointer text-center text-xs transition-all duration-200">
                                <input type="radio" name="delivery_type" value="delivery" class="hidden">
                                <i class="fa-solid fa-motorcycle text-muted mb-1.5 text-lg"></i>
                                <span class="font-medium text-cream">Delivery</span>
                            </label>
                        </div>
                    </div>

                    <!-- Fila 1: Cuadrícula dinámica para campos principales -->
                    <div id="grid-contact" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        
                        <!-- Campo Código de Mesa (Fila 1 - Columna 1 en Mesa) -->
                        <div id="field-mesa" class="col-span-1">
                            <label class="f-label" for="table_number">
                                <i class="fa-solid fa-chair mr-1 text-amber"></i> Número o Código de Mesa *
                            </label>
                            <input id="table_number" type="text" name="table_number" class="f-input w-full shadow-inner shadow-black/50" placeholder="Ej. S1, T2, Barra 3" value="S1">
                        </div>

                        <!-- Campo Nombre Completo (Fila 1 - Columna 2 en Mesa; o Col-span-2 en Para Llevar) -->
                        <div id="field-nombre" class="col-span-1">
                            <label class="f-label" for="customer_name">
                                <i class="fa-regular fa-user mr-1 text-amber"></i> Nombre Completo *
                            </label>
                            <input
                                id="customer_name"
                                type="text"
                                name="customer_name"
                                class="f-input w-full shadow-inner shadow-black/50"
                                value="{{ Auth::user()?->name ?? '' }}"
                                placeholder="Tu nombre completo"
                                required
                            >
                        </div>

                        <!-- Campo Teléfono / WhatsApp (Solo en Delivery) -->
                        <div id="field-telefono" class="hidden col-span-1">
                            <label class="f-label" for="customer_phone">
                                <i class="fa-solid fa-phone mr-1 text-amber"></i> Teléfono / WhatsApp *
                            </label>
                            <input
                                id="customer_phone"
                                type="tel"
                                name="customer_phone"
                                class="f-input w-full shadow-inner shadow-black/50"
                                placeholder="+51 999 000 000"
                            >
                        </div>

                        <!-- Campo Dirección de Entrega (Solo en Delivery - Fila completa) -->
                        <div id="field-direccion" class="hidden col-span-1 sm:col-span-2">
                            <label class="f-label" for="address">
                                <i class="fa-solid fa-location-dot mr-1 text-amber"></i> Dirección de Entrega *
                            </label>
                            <input
                                id="address"
                                type="text"
                                name="address"
                                class="f-input w-full shadow-inner shadow-black/50"
                                placeholder="Av. Principal 123, Urb. Los Rosales"
                            >
                        </div>

                    </div>

                    <!-- Fila 2: Notas para preparación (opcional) -->
                    <div>
                        <label class="f-label" for="notes">
                            <i class="fa-regular fa-comment mr-1 text-amber"></i> Notas para preparación (opcional)
                        </label>
                        <textarea id="notes" name="notes" class="f-textarea w-full shadow-inner shadow-black/50" placeholder="Ej. Leche de almendras, sin azúcar, poco hielo..." style="min-height: 75px;"></textarea>
                    </div>

                    <!-- Botón de enviar con resplandor y sombra -->
                    <button id="submit-order-btn" type="submit" class="btn-amber w-full py-4 text-sm font-semibold tracking-wide shadow-xl shadow-amber/25 hover:shadow-2xl hover:shadow-amber/40 transition-all duration-200 active:scale-[0.99] flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Confirmar y Enviar Pedido</span>
                    </button>
                </form>

            </div>

            <!-- Columna Derecha: Resumen de Ítems (5 cols) con sombra premium -->
            <div class="lg:col-span-5 bg-card border border-border/80 rounded-2xl p-7 shadow-2xl shadow-black/60 space-y-6">
                <div class="border-b border-border pb-4 flex items-center justify-between">
                    <h3 class="font-display text-xl font-bold text-cream">Resumen de Compra</h3>
                    <a href="{{ route('carta') }}" class="text-xs text-amber hover:underline font-medium">
                        + Agregar más
                    </a>
                </div>

                <div id="checkout-items-list" class="space-y-3 max-h-80 overflow-y-auto pr-1">
                    <!-- Rellenado dinámicamente desde JS -->
                </div>

                <div class="border-t border-border pt-4 space-y-2 text-sm">
                    <div class="flex items-center justify-between text-muted">
                        <span>Subtotal</span>
                        <span id="checkout-subtotal" class="font-medium text-cream">S/ 0.00</span>
                    </div>
                    <div class="flex items-center justify-between text-muted">
                        <span>Impuestos (IGV incluido)</span>
                        <span class="font-medium text-cream">S/ 0.00</span>
                    </div>
                    <div class="flex items-center justify-between text-base font-bold text-cream pt-2 border-t border-border/60">
                        <span>Total a pagar</span>
                        <span id="checkout-total" class="text-amber text-xl font-mono">S/ 0.00</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-surface border border-border/80 shadow-md text-xs text-muted space-y-1">
                    <div class="flex items-center gap-1.5 text-cream font-medium">
                        <i class="fa-solid fa-shield-halved text-amber"></i> Creación de orden segura
                    </div>
                    <p>El pedido quedará registrado de inmediato en el sistema del barista para su preparación.</p>
                </div>
            </div>

        </div>

    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Manejo de opciones de entrega y cuadrícula dinámica
    const radios = document.querySelectorAll('input[name="delivery_type"]');
    const fieldMesa = document.getElementById('field-mesa');
    const fieldNombre = document.getElementById('field-nombre');
    const fieldTel = document.getElementById('field-telefono');
    const fieldDir = document.getElementById('field-direccion');

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.delivery-option').forEach(l => {
                l.classList.remove('border-amber', 'bg-amber/10', 'shadow-amber/10');
                l.classList.add('border-border/80', 'bg-surface', 'shadow-black/40');
                const ic = l.querySelector('i');
                if (ic) { ic.classList.remove('text-amber'); ic.classList.add('text-muted'); }
            });

            const parentLabel = this.closest('.delivery-option');
            parentLabel.classList.remove('border-border/80', 'bg-surface', 'shadow-black/40');
            parentLabel.classList.add('border-amber', 'bg-amber/10', 'shadow-amber/10');
            const ic = parentLabel.querySelector('i');
            if (ic) { ic.classList.remove('text-muted'); ic.classList.add('text-amber'); }

            if (this.value === 'mesa') {
                // Fila 1: Mesa (Col 1) | Nombre Completo (Col 2)
                if (fieldMesa) fieldMesa.classList.remove('hidden');
                if (fieldNombre) fieldNombre.className = 'col-span-1';
                if (fieldTel) fieldTel.classList.add('hidden');
                if (fieldDir) fieldDir.classList.add('hidden');
            } else if (this.value === 'recojo') {
                // Fila 1: Nombre Completo (Ancho completo col-span-2)
                if (fieldMesa) fieldMesa.classList.add('hidden');
                if (fieldNombre) fieldNombre.className = 'col-span-1 sm:col-span-2';
                if (fieldTel) fieldTel.classList.add('hidden');
                if (fieldDir) fieldDir.classList.add('hidden');
            } else { // delivery
                // Fila 1: Nombre (Col 1) | Teléfono (Col 2). Fila 2: Dirección (Col-span-2)
                if (fieldMesa) fieldMesa.classList.add('hidden');
                if (fieldNombre) fieldNombre.className = 'col-span-1';
                if (fieldTel) fieldTel.classList.remove('hidden');
                if (fieldDir) fieldDir.classList.remove('hidden');
            }
        });
    });

    // Renderizar resumen de checkout
    window.renderCheckoutSummary = function (items, subtotal) {
        const listEl = document.getElementById('checkout-items-list');
        const subtotalEl = document.getElementById('checkout-subtotal');
        const totalEl = document.getElementById('checkout-total');
        const submitBtn = document.getElementById('submit-order-btn');

        if (!listEl) return;

        if (items.length === 0) {
            listEl.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-muted text-sm mb-3">Tu carrito está vacío.</p>
                    <a href="{{ route('carta') }}" class="btn-amber text-xs py-2 px-4">Ir a la carta</a>
                </div>
            `;
            if (submitBtn) submitBtn.disabled = true;
            if (subtotalEl) subtotalEl.textContent = 'S/ 0.00';
            if (totalEl) totalEl.textContent = 'S/ 0.00';
            return;
        }

        if (submitBtn) submitBtn.disabled = false;

        listEl.innerHTML = items.map(item => `
            <div class="flex items-center justify-between p-3.5 rounded-xl bg-surface border border-border/80 shadow-md text-xs">
                <div>
                    <h5 class="font-medium text-cream text-sm">${escapeHtml(item.name)}</h5>
                    <span class="text-muted text-[11px]">Cantidad: <strong class="text-cream">${item.quantity}</strong> × S/ ${item.price.toFixed(2)}</span>
                </div>
                <span class="font-semibold text-amber font-mono text-sm">S/ ${(item.price * item.quantity).toFixed(2)}</span>
            </div>
        `).join('');

        if (subtotalEl) subtotalEl.textContent = 'S/ ' + subtotal.toFixed(2);
        if (totalEl) totalEl.textContent = 'S/ ' + subtotal.toFixed(2);
    };

    function escapeHtml(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    updateCartUI();

    // Procesar envío del formulario vía AJAX
    const form = document.getElementById('checkout-form');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const items = getCart();
            const errorEl = document.getElementById('checkout-error');
            const submitBtn = document.getElementById('submit-order-btn');
            const btnSpan = submitBtn ? submitBtn.querySelector('span') : null;

            if (items.length === 0) {
                errorEl.textContent = 'Tu carrito está vacío. Agrega productos de la carta para continuar.';
                errorEl.classList.remove('hidden');
                return;
            }

            errorEl.classList.add('hidden');
            if (submitBtn) submitBtn.disabled = true;
            if (btnSpan) btnSpan.textContent = 'Procesando orden...';

            const phoneInput = document.getElementById('customer_phone');
            const addressInput = document.getElementById('address');
            const tableInput = document.getElementById('table_number');

            const payload = {
                customer_name:  document.getElementById('customer_name').value,
                customer_phone: phoneInput ? phoneInput.value : '',
                delivery_type:  document.querySelector('input[name="delivery_type"]:checked')?.value || 'mesa',
                table_number:   tableInput ? tableInput.value : '',
                address:        addressInput ? addressInput.value : '',
                notes:          document.getElementById('notes').value,
                items:          items.map(i => ({ name: i.name, quantity: i.quantity })),
                _token:         document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            };

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    const msg = result.errors
                        ? Object.values(result.errors).flat().join(' ')
                        : (result.message || 'Error al procesar el pedido.');
                    errorEl.textContent = msg;
                    errorEl.classList.remove('hidden');
                    return;
                }

                // Vaciar carrito tras compra exitosa
                clearCart();

                // Redirigir a página de confirmación
                window.location.href = result.data.redirect_url;

            } catch (err) {
                errorEl.textContent = 'Ocurrió un error de red al procesar tu pedido. Intenta nuevamente.';
                errorEl.classList.remove('hidden');
            } finally {
                if (submitBtn) submitBtn.disabled = false;
                if (btnSpan) btnSpan.textContent = 'Confirmar y Enviar Pedido';
            }
        });
    }
});
</script>
@endpush

@endsection
