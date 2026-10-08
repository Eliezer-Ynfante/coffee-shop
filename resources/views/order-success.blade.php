@extends('layout.layout')

@section('title', 'Pedido Confirmado #' . $order->order_number)

@section('content')

<section class="min-h-screen bg-ink py-10 px-4 flex items-center justify-center relative grain overflow-hidden" aria-label="Confirmación de Pedido">
    {{-- Glow ambiental --}}
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber/8 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="w-full max-w-5xl mx-auto relative z-10">

        <div class="ticket-voucher bg-card border border-border/80 rounded-2xl p-5 sm:p-6 shadow-2xl shadow-black/80 space-y-3.5">
            <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.9fr)] lg:items-start">
                <div class="space-y-3.5">
            {{-- Encabezado compacto con estado --}}
            <div class="text-center pb-3 border-b border-border/80">
                @if ($order->payment_status === 'paid')
                <span class="badge text-emerald-400 bg-emerald-500/10 border-emerald-500/30 text-[10px] py-0.5 px-2.5 mb-1 inline-block font-semibold">
                    <i class="fa-solid fa-circle-check mr-1"></i> Pago Conciliado · Orden Confirmada
                </span>
                @else
                <span class="badge text-amber bg-amber/10 border-amber/30 text-[10px] py-0.5 px-2.5 mb-1 inline-block font-semibold">
                    <i class="fa-solid fa-clock mr-1"></i> Pedido Recibido · Pendiente de Pago
                </span>
                @endif
                <h1 class="font-display text-2xl sm:text-3xl font-bold text-cream">¡Gracias por tu pedido!</h1>
                <p class="text-muted text-xs mt-0.5">
                    Registrado en el sistema de barra de <strong class="text-cream">{{ setting('nombre', config('cafe.nombre', 'Raíz & Grano')) }}</strong>.
                </p>
            </div>

            {{-- Datos de la Orden (Cuadrícula ordenada sin huecos vacíos) --}}
            <div class="space-y-2.5 text-xs">
                <div class="flex items-center justify-between bg-surface/90 px-3.5 py-2 rounded-xl border border-border/80 shadow-inner">
                    <span class="text-muted uppercase font-medium text-[11px]">Número de Pedido</span>
                    <span class="font-mono text-sm font-bold text-amber">#{{ $order->order_number }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    <div class="bg-surface/60 p-2.5 rounded-xl border border-border/60 shadow-xs">
                        <span class="text-muted text-[10px] block mb-0.5">Cliente</span>
                        <span class="font-medium text-cream block text-xs truncate">{{ $order->customer_name }}</span>
                    </div>

                    <div class="bg-surface/60 p-2.5 rounded-xl border border-border/60 shadow-xs">
                        <span class="text-muted text-[10px] block mb-0.5">Fecha y Hora</span>
                        <span class="font-medium text-cream block text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    @if ($order->customer_phone)
                    <div class="bg-surface/60 p-2.5 rounded-xl border border-border/60 shadow-xs">
                        <span class="text-muted text-[10px] block mb-0.5">Teléfono</span>
                        <span class="font-medium text-cream block text-xs">{{ $order->customer_phone }}</span>
                    </div>
                    @endif

                    <div class="bg-surface/60 p-2.5 rounded-xl border border-border/60 shadow-xs {{ !$order->customer_phone ? 'sm:col-span-2' : '' }}">
                        <span class="text-muted text-[10px] block mb-0.5">Modalidad / Entrega</span>
                        <span class="font-semibold text-amber block text-xs truncate">{{ $order->notes ?? 'Web' }}</span>
                    </div>
                </div>

                {{-- Ítems pedidos (compacto) --}}
                <div class="bg-surface/90 p-2.5 rounded-xl border border-border/80 space-y-1 shadow-inner">
                    <span class="text-muted text-[10px] uppercase font-medium block">Detalle del Pedido</span>
                    
                    <div class="max-h-24 overflow-y-auto pr-1 space-y-1">
                        @foreach ($order->items as $item)
                        <div class="flex items-center justify-between py-0.5 border-b border-border/40 last:border-none text-xs">
                            <span class="text-cream font-medium">
                                {{ $item->quantity }} × {{ $item->product?->name ?? 'Producto' }}
                            </span>
                            <span class="text-amber font-mono font-semibold">
                                S/ {{ number_format($item->subtotal, 2) }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between pt-1.5 border-t border-border/80 text-xs font-bold text-cream">
                        <span>Total a pagar</span>
                        <span class="text-amber text-sm font-mono">S/ {{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
                </div>

            {{-- Sprint B: Bloque interactivo de Pago con QR Yape, Tarjeta y Efectivo --}}
                <div>
            @if ($order->payment_status !== 'paid')
            <div class="p-3.5 bg-surface border border-amber/30 rounded-xl space-y-3 shadow-inner">
                <div class="flex items-center justify-between border-b border-border/60 pb-2">
                    <span class="text-xs font-semibold text-cream">
                        <i class="fa-solid fa-wallet text-amber mr-1"></i> Selecciona Medio de Pago
                    </span>
                    <span class="text-[10px] text-amber font-medium">Conciliación directa</span>
                </div>

                <form id="payment-form" action="{{ route('pedido.pagar', ['order_number' => $order->order_number]) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded-lg border border-red-500/40 bg-red-500/10 p-2 text-xs text-red-200" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    
                    <!-- Selector de pestañas de pago -->
                    <div class="grid grid-cols-3 gap-2" role="radiogroup">
                        <label class="pay-tab flex flex-col items-center justify-center p-2 rounded-xl border border-amber bg-amber/15 shadow-sm cursor-pointer text-center transition-all">
                            <input type="radio" name="payment_method" value="yape" checked class="hidden">
                            <i class="fa-solid fa-qrcode text-amber text-sm mb-0.5"></i>
                            <span class="text-[11px] font-medium text-cream">Yape / Plin</span>
                        </label>
                        <label class="pay-tab flex flex-col items-center justify-center p-2 rounded-xl border border-border/80 bg-card hover:border-amber/40 cursor-pointer text-center transition-all">
                            <input type="radio" name="payment_method" value="card" class="hidden">
                            <i class="fa-solid fa-credit-card text-muted text-sm mb-0.5"></i>
                            <span class="text-[11px] font-medium text-cream">Tarjeta</span>
                        </label>
                        <label class="pay-tab flex flex-col items-center justify-center p-2 rounded-xl border border-border/80 bg-card hover:border-amber/40 cursor-pointer text-center transition-all">
                            <input type="radio" name="payment_method" value="cash" class="hidden">
                            <i class="fa-solid fa-money-bill-wave text-muted text-sm mb-0.5"></i>
                            <span class="text-[11px] font-medium text-cream">Efectivo</span>
                        </label>
                    </div>

                    <!-- 1. Formulario Yape / Plin (QR + N° Operación + Foto de comprobante) -->
                    <div id="pay-panel-yape" class="space-y-2.5 pt-1">
                        @php
                            $paymentQr = setting('payment_qr', config('cafe.payment_qr'));
                            $yapePhone = setting('yape_phone', config('cafe.yape_phone'));
                        @endphp
                        <div class="p-2.5 rounded-xl bg-card border border-border/60 flex items-center gap-3">
                            @if ($paymentQr)
                                <img src="{{ asset('storage/' . $paymentQr) }}" alt="QR de pago Yape o Plin" class="w-20 h-20 rounded-lg bg-white p-1 shrink-0 object-contain border border-amber/40 shadow-xs">
                            @else
                                <div class="w-20 h-20 rounded-lg bg-white/5 shrink-0 flex items-center justify-center border border-border/60 text-center p-2">
                                    <span class="text-[9px] text-muted">QR pendiente de configurar</span>
                                </div>
                            @endif
                            <div class="text-xs space-y-0.5 min-w-0">
                                <span class="text-amber font-semibold block text-[11px]">Yapea o Plinea a {{ setting('nombre', config('cafe.nombre')) }}</span>
                                @if ($yapePhone)
                                    <span class="font-mono text-cream font-bold text-sm block">{{ $yapePhone }}</span>
                                @endif
                                <span class="text-muted text-[10px] block">Envía el importe exacto de S/ {{ number_format($order->total, 2) }}.</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div>
                                <label class="f-label text-[10px] mb-1" for="transaction_reference">N° de Operación Yape/Plin *</label>
                                <input
                                    type="text"
                                    name="transaction_reference"
                                    id="transaction_reference"
                                    data-required-for="wallet"
                                    class="f-input text-xs py-1.5 px-3 w-full shadow-inner"
                                    placeholder="Ej. 123456"
                                >
                            </div>

                            <div>
                                <label class="f-label text-[10px] mb-1" for="payment_voucher">Subir foto del pago *</label>
                                <input
                                    type="file"
                                    name="payment_voucher"
                                    id="payment_voucher"
                                    accept="image/*"
                                    data-required-for="wallet"
                                    class="f-input text-[11px] py-1 px-2 w-full file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-amber/20 file:text-amber shadow-inner"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- 2. Formulario Tarjeta (Débito / Crédito) -->
                    <div id="pay-panel-card" class="hidden space-y-2 pt-1">
                        <div>
                            <label class="f-label text-[10px] mb-1" for="card_holder">Titular de la Tarjeta *</label>
                            <input type="text" name="card_holder" id="card_holder" autocomplete="cc-name" data-required-for="card" class="f-input text-xs py-1.5 px-3 w-full shadow-inner" placeholder="Como aparece en la tarjeta">
                        </div>
                        <div>
                            <label class="f-label text-[10px] mb-1" for="card_number">Número de Tarjeta *</label>
                            <input
                                type="text"
                                name="card_number"
                                id="card_number"
                                maxlength="19"
                                inputmode="numeric"
                                autocomplete="cc-number"
                                data-required-for="card"
                                class="f-input text-xs py-1.5 px-3 w-full shadow-inner font-mono"
                                placeholder="4557 0000 0000 0000"
                            >
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="f-label text-[10px] mb-1" for="card_exp">Expiración *</label>
                                <input type="text" name="card_exp" id="card_exp" maxlength="5" inputmode="numeric" autocomplete="cc-exp" data-required-for="card" class="f-input text-xs py-1.5 px-3 w-full shadow-inner" placeholder="MM/AA">
                            </div>
                            <div>
                                <label class="f-label text-[10px] mb-1" for="card_cvc">CVC / CVV *</label>
                                <input type="password" name="card_cvc" id="card_cvc" maxlength="4" inputmode="numeric" autocomplete="cc-csc" data-required-for="card" class="f-input text-xs py-1.5 px-3 w-full shadow-inner" placeholder="123">
                            </div>
                        </div>
                        <p class="text-muted text-[10px]">No guardamos los datos de la tarjeta. El cobro requiere una pasarela de pago configurada.</p>
                    </div>

                    <!-- 3. Formulario Efectivo (Código de confirmación) -->
                    <div id="pay-panel-cash" class="hidden space-y-2 pt-1">
                        <div>
                            <label class="f-label text-[10px] mb-1" for="cash_code">Código de Pago en Efectivo *</label>
                            <input
                                type="text"
                                name="cash_code"
                                id="cash_code"
                                data-required-for="cash"
                                class="f-input text-xs py-1.5 px-3 w-full shadow-inner"
                                placeholder="Ingresa el código entregado en caja o por el personal"
                            >
                            <span class="text-muted text-[10px] block mt-1">Ingresa el código de confirmación del ticket entregado en caja para verificar tu pago.</span>
                        </div>
                    </div>

                    <button type="submit" id="pay-submit-btn" class="btn-amber w-full py-2.5 text-xs font-semibold shadow-md flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-lock"></i>
                        <span>Confirmar Pago de S/ {{ number_format($order->total, 2) }}</span>
                    </button>
                </form>
            </div>
            @else
            {{-- Detalle del pago conciliado --}}
            <div class="p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-xs space-y-1 shadow-sm">
                <div class="flex items-center justify-between text-emerald-400 font-medium">
                    <span><i class="fa-solid fa-check-double mr-1"></i> Pago Conciliado & Confirmado</span>
                    <span class="font-mono text-[11px]">Ref: {{ $order->latestPayment?->transaction_reference ?? ('CONFIRMADO-' . $order->id) }}</span>
                </div>
                <p class="text-muted text-[11px]">Medio: <strong class="text-cream uppercase">{{ $order->payment_method ?? 'EN LINEA' }}</strong> | Monto: <strong class="text-cream">S/ {{ number_format($order->total, 2) }}</strong></p>
            </div>
            @endif
                </div>
            </div>

            <div class="ticket-divider my-1"></div>

            {{-- Acciones (WhatsApp SOLO para Delivery) --}}
            <div class="flex flex-col sm:flex-row gap-2 pt-0.5">
                @php
                    $isDeliveryOrder = str_contains(strtolower($order->notes ?? ''), 'modalidad: delivery');
                @endphp

                @if ($isDeliveryOrder && !empty(setting('redes', config('cafe.redes', []))['whatsapp']))
                <a
                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('telefono', config('cafe.telefono', '51999999999'))) }}?text=Hola,%20acabo%20de%20hacer%20el%20pedido%20%23{{ $order->order_number }}%20para%20delivery"
                    target="_blank"
                    rel="noopener"
                    class="btn-amber text-xs py-2.5 flex-1 text-center justify-center shadow-md shadow-amber/20"
                >
                    <i class="fa-brands fa-whatsapp mr-1.5 text-sm"></i> Coordinar por WhatsApp
                </a>
                @endif

                <a href="{{ route('carta') }}" class="btn-ghost text-xs py-2.5 flex-1 text-center justify-center">
                    <i class="fa-solid fa-mug-hot mr-1.5"></i> Volver a la Carta
                </a>
            </div>

        </div>

    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const payForm = document.getElementById('payment-form');
    if (payForm) {
        const payRadios = payForm.querySelectorAll('input[name="payment_method"]');
        const panelYape = document.getElementById('pay-panel-yape');
        const panelCard = document.getElementById('pay-panel-card');
        const panelCash = document.getElementById('pay-panel-cash');

        const updatePaymentPanel = method => {
                payForm.querySelectorAll('.pay-tab').forEach(tab => {
                    tab.classList.remove('border-amber', 'bg-amber/15', 'shadow-sm');
                    tab.classList.add('border-border/80', 'bg-card');
                    const ic = tab.querySelector('i');
                    if (ic) { ic.classList.remove('text-amber'); ic.classList.add('text-muted'); }
                });

                const selectedRadio = payForm.querySelector(`input[name="payment_method"][value="${method}"]`);
                const parent = selectedRadio?.closest('.pay-tab');
                if (!parent) return;
                parent.classList.remove('border-border/80', 'bg-card');
                parent.classList.add('border-amber', 'bg-amber/15', 'shadow-sm');
                const ic = parent.querySelector('i');
                if (ic) { ic.classList.remove('text-muted'); ic.classList.add('text-amber'); }

                if (panelYape) panelYape.classList.add('hidden');
                if (panelCard) panelCard.classList.add('hidden');
                if (panelCash) panelCash.classList.add('hidden');

                const requiredGroup = method === 'yape' || method === 'plin' ? 'wallet' : method;
                payForm.querySelectorAll('[data-required-for]').forEach(field => {
                    field.required = field.dataset.requiredFor === requiredGroup;
                });

                if (method === 'yape' || method === 'plin') {
                    if (panelYape) panelYape.classList.remove('hidden');
                } else if (method === 'card') {
                    if (panelCard) panelCard.classList.remove('hidden');
                } else if (method === 'cash') {
                    if (panelCash) panelCash.classList.remove('hidden');
                }
        };

        payRadios.forEach(radio => radio.addEventListener('change', () => updatePaymentPanel(radio.value)));
        updatePaymentPanel(payForm.querySelector('input[name="payment_method"]:checked')?.value ?? 'yape');

        // Enviar pago vía FormData AJAX para soportar archivos de imagen
        payForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const submitBtn = document.getElementById('pay-submit-btn');
            const btnSpan = submitBtn ? submitBtn.querySelector('span') : null;

            if (submitBtn) submitBtn.disabled = true;
            if (btnSpan) btnSpan.textContent = 'Procesando pago...';

            const formData = new FormData(payForm);

            try {
                const response = await fetch(payForm.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    const msg = result.errors
                        ? Object.values(result.errors).flat().join(' ')
                        : (result.message || 'Error al procesar el pago.');
                    alert(msg);
                    return;
                }

                // Recargar la página para mostrar el voucher conciliado
                window.location.reload();
            } catch (err) {
                alert('Error de red al procesar el pago. Por favor intenta de nuevo.');
            } finally {
                if (submitBtn) submitBtn.disabled = false;
                if (btnSpan) btnSpan.textContent = 'Confirmar Pago';
            }
        });
    }
});
</script>
@endpush

@endsection
