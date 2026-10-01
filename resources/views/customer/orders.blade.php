@extends('layout.layout')

@section('title', 'Mis Pedidos & Compras')

@section('content')

<section class="min-h-screen bg-ink py-28 px-6 relative grain overflow-hidden" aria-label="Portal de Cliente">
    {{-- Glow decorativo --}}
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber/8 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-amber/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto relative z-10 space-y-10">

        {{-- Alertas / Mensajes --}}
        @if (session('status'))
        <div class="p-4 rounded-lg bg-amber/15 border border-amber/40 text-cream text-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-amber text-lg" aria-hidden="true"></i>
                <span>{{ session('status') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-muted hover:text-cream text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        @endif

        {{-- Cabecera del Portal del Cliente --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-8 border-b border-border">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="amber-tag">Portal del Cliente</span>
                </div>
                <h1 class="font-display text-4xl sm:text-5xl font-bold text-cream">
                    Mis <em class="text-amber not-italic">Pedidos</em> & Historial
                </h1>
                <p class="text-muted text-sm mt-1">
                    Hola, <strong class="text-cream">{{ $user->name }}</strong>. Revisa el estado de tus preparaciones y compras pasadas.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('carta') }}" class="btn-amber text-xs py-2.5 px-4">
                    <i class="fa-solid fa-mug-hot mr-1.5"></i> Explorar Carta
                </a>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-ghost text-xs py-2.5 px-4 hover:border-red-500 hover:text-red-400">
                        <i class="fa-solid fa-right-from-bracket mr-1.5"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>

        {{-- Tarjetas de Resumen del Cliente --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-card border border-border rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs uppercase font-medium text-muted">Pedidos Totales</span>
                    <i class="fa-solid fa-receipt text-amber"></i>
                </div>
                <div class="font-display text-3xl font-bold text-cream mb-1">{{ $activeOrders->count() + $pastOrders->count() }}</div>
                <p class="text-muted text-xs">{{ $activeOrders->count() }} en preparación o entrega</p>
            </div>

            <div class="bg-card border border-border rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs uppercase font-medium text-muted">Puntos de Fidelidad</span>
                    <i class="fa-solid fa-award text-amber"></i>
                </div>
                <div class="font-display text-3xl font-bold text-amber mb-1">{{ $customer->loyalty_points ?? 0 }} pts</div>
                <p class="text-muted text-xs">Equivalente a café gratis o descuentos</p>
            </div>

            <div class="bg-card border border-border rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs uppercase font-medium text-muted">Teléfono Registrado</span>
                    <i class="fa-solid fa-phone text-emerald-400"></i>
                </div>
                <div class="font-display text-lg font-bold text-cream mb-1">{{ $customer->phone ?? 'Sin registrar' }}</div>
                <p class="text-muted text-xs">{{ $customer->city ?? 'Piura' }}, Perú</p>
            </div>
        </div>

        {{-- SECCIÓN 1: PEDIDOS EN CURSO (ESTADO EN TIEMPO REAL) --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-cream flex items-center gap-2.5">
                        <i class="fa-solid fa-fire-burner text-amber text-xl"></i>
                        <span>Pedidos en Proceso</span>
                    </h2>
                    <p class="text-muted text-xs">Sigue en vivo la preparación de tu café.</p>
                </div>
                <span class="badge text-xs bg-amber/15 text-amber border-amber/30">{{ $activeOrders->count() }} en curso</span>
            </div>

            @if ($activeOrders->isEmpty())
            <div class="bg-card border border-border rounded-xl p-8 text-center text-muted text-xs">
                <i class="fa-regular fa-clock text-amber text-lg mb-2 block"></i>
                No tienes pedidos en preparación en este momento. ¡Pide uno en la carta!
            </div>
            @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach ($activeOrders as $order)
                <div class="bg-card border border-border rounded-xl p-6 shadow-xl space-y-5">
                    {{-- Encabezado del Pedido --}}
                    <div class="flex items-center justify-between border-b border-border/80 pb-4">
                        <div>
                            <span class="font-mono text-sm font-bold text-amber">{{ $order->order_number }}</span>
                            <span class="text-[11px] text-muted block">{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-bold text-cream block">S/ {{ number_format($order->total, 2) }}</span>
                            <span class="text-[10px] uppercase text-muted tracking-wider">{{ $order->channel === 'pos' ? 'En Barra' : 'Web / Delivery' }}</span>
                        </div>
                    </div>

                    {{-- Barra de Progreso del Pedido --}}
                    @php
                        $steps = [
                            'pending'   => 1,
                            'confirmed' => 2,
                            'preparing' => 3,
                            'ready'     => 4,
                        ];
                        $currentStep = $steps[$order->status] ?? 1;
                    @endphp

                    <div>
                        <div class="flex items-center justify-between text-[11px] font-medium text-cream mb-2">
                            <span class="{{ $currentStep >= 1 ? 'text-amber font-semibold' : 'text-muted' }}">1. Confirmado</span>
                            <span class="{{ $currentStep >= 3 ? 'text-amber font-semibold animate-pulse' : 'text-muted' }}">2. Barista Preparando</span>
                            <span class="{{ $currentStep >= 4 ? 'text-emerald-400 font-semibold' : 'text-muted' }}">3. Listo para Entrega</span>
                        </div>
                        <div class="w-full bg-surface rounded-full h-2 overflow-hidden border border-border">
                            <div
                                class="bg-linear-to-r from-amber to-gold h-full transition-all duration-500"
                                @style(['width: ' . ($currentStep === 1 ? '25%' : ($currentStep === 2 ? '50%' : ($currentStep === 3 ? '75%' : '100%')))])
                            ></div>
                        </div>
                    </div>

                    {{-- Ítems del Pedido --}}
                    <div class="bg-surface/60 rounded-lg p-3.5 border border-border/60 space-y-2">
                        <span class="text-[10px] uppercase font-semibold text-muted tracking-wider block">Artículos:</span>
                        <ul class="divide-y divide-border/40 text-xs">
                            @foreach ($order->items as $item)
                            <li class="py-1.5 flex items-center justify-between">
                                <div>
                                    <span class="text-cream font-medium">{{ $item->quantity }}x {{ $item->product_name }}</span>
                                    @if ($item->notes)
                                    <span class="text-muted text-[10px] block italic">Nota: {{ $item->notes }}</span>
                                    @endif
                                </div>
                                <span class="font-mono text-amber text-xs">S/ {{ number_format($item->subtotal, 2) }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Pie de tarjeta --}}
                    <div class="flex items-center justify-between text-[11px] pt-1">
                        <span class="text-muted flex items-center gap-1.5">
                            <i class="fa-solid fa-credit-card text-amber"></i>
                            Pago: <strong class="uppercase text-cream">{{ $order->payment_method ?? 'En caja' }}</strong> ({{ $order->payment_status === 'paid' ? 'Pagado' : 'Pendiente' }})
                        </span>

                        @if ($order->status === 'ready')
                        <span class="badge bg-emerald-500/15 text-emerald-400 border-emerald-500/30 text-[10px] animate-bounce">
                            ¡Puedes acercarte a la barra!
                        </span>
                        @else
                        <span class="badge bg-amber/15 text-amber border-amber/30 text-[10px]">
                            En preparación
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- SECCIÓN 2: HISTORIAL DE COMPRAS (PEDIDOS COMPLETADOS) --}}
        <div class="bg-card border border-border rounded-xl p-7 shadow-xl space-y-6">
            <div class="flex items-center justify-between border-b border-border pb-4">
                <div>
                    <h3 class="font-display text-2xl font-bold text-cream">Historial de Compras Anteriores</h3>
                    <p class="text-muted text-xs">Pedidos finalizados y tickets anteriores.</p>
                </div>
                <span class="badge text-xs">{{ $pastOrders->count() }} completados</span>
            </div>

            @if ($pastOrders->isEmpty())
            <div class="text-center py-10 text-muted text-xs">
                No hay compras pasadas registradas en el historial.
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-muted uppercase border-b border-border bg-surface/50 text-[10px] tracking-wider">
                        <tr>
                            <th class="p-4">Pedido #</th>
                            <th class="p-4">Fecha</th>
                            <th class="p-4">Canal</th>
                            <th class="p-4">Detalle</th>
                            <th class="p-4">Total</th>
                            <th class="p-4">Método de Pago</th>
                            <th class="p-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @foreach ($pastOrders as $order)
                        <tr class="hover:bg-surface/40 transition">
                            <td class="p-4 font-mono font-semibold text-amber">{{ $order->order_number }}</td>
                            <td class="p-4 text-cream/80">{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</td>
                            <td class="p-4 uppercase text-[10px] tracking-wider font-semibold text-muted">{{ $order->channel }}</td>
                            <td class="p-4 text-cream/90">
                                @foreach ($order->items as $it)
                                <span class="block text-[11px]">{{ $it->quantity }}x {{ $it->product_name }}</span>
                                @endforeach
                            </td>
                            <td class="p-4 font-bold text-cream">S/ {{ number_format($order->total, 2) }}</td>
                            <td class="p-4 uppercase text-cream/70">{{ $order->payment_method ?? 'En caja' }}</td>
                            <td class="p-4">
                                <span class="badge text-[10px] bg-emerald-500/10 text-emerald-400 border-emerald-500/30">
                                    Completado
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div>
</section>

@endsection
