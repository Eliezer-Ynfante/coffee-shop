@extends('admin.layout')

@section('title', 'Caja Rápida POS')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Caja rápida</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Caja Rápida · POS</h1>
        <p class="text-xs text-muted mt-1">Registra ventas de mostrador, controla el turno y revisa las ventas por mesa.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mt-6">
    <div class="bg-surface border border-border rounded-xl p-4">
        <div class="text-[10px] uppercase tracking-wide text-muted">Turno actual</div>
        <div class="mt-2 text-xl font-bold text-cream">{{ ucfirst($shiftName) }}</div>
        <div class="mt-1 text-[11px] text-muted">Apertura: S/ {{ number_format($openingCash, 2) }}</div>
    </div>

    <div class="bg-surface border border-border rounded-xl p-4">
        <div class="text-[10px] uppercase tracking-wide text-muted">Ventas en efectivo</div>
        <div class="mt-2 text-xl font-bold text-emerald-400">S/ {{ number_format($cashSales, 2) }}</div>
        <div class="mt-1 text-[11px] text-muted">Tarjeta: S/ {{ number_format($cardSales, 2) }}</div>
    </div>

    <div class="bg-surface border border-border rounded-xl p-4">
        <div class="text-[10px] uppercase tracking-wide text-muted">Yape / Plin</div>
        <div class="mt-2 text-xl font-bold text-sky-400">S/ {{ number_format($yapeSales, 2) }}</div>
        <div class="mt-1 text-[11px] text-muted">Total hoy: S/ {{ number_format($totalSales, 2) }}</div>
    </div>

    <div class="bg-surface border border-border rounded-xl p-4">
        <div class="text-[10px] uppercase tracking-wide text-muted">Cierre de caja</div>
        <div class="mt-2 text-xl font-bold text-amber">S/ {{ $closingCash !== null ? number_format($closingCash, 2) : '—' }}</div>
        <div class="mt-1 text-[11px] {{ $cashDifference !== null && $cashDifference != 0 ? 'text-amber' : 'text-muted' }}">
            @if ($cashDifference !== null)
                Diferencia: S/ {{ number_format($cashDifference, 2) }}
            @else
                Pendiente de cierre
            @endif
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.pos.store') }}" class="mt-6">
    @csrf
    <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1.3fr)_360px] gap-6">
        <div class="space-y-4">
            <div class="bg-surface border border-border rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-cream">Productos disponibles</h2>
                    <span class="text-[10px] uppercase tracking-wide text-muted">{{ $products->count() }} items</span>
                </div>

                @if ($products->isEmpty())
                    <div class="bg-card border border-dashed border-border rounded-xl p-6 text-center text-xs text-muted">
                        No hay productos activos disponibles para POS.
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach ($products as $product)
                            <div class="border border-border rounded-xl p-3 bg-card/70">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div>
                                        <h3 class="text-sm font-semibold text-cream">{{ $product->name }}</h3>
                                        <p class="text-[10px] text-muted uppercase tracking-wider">{{ $product->sku ?? 'SIN SKU' }}</p>
                                    </div>
                                    <span class="text-sm font-mono text-amber font-bold">S/ {{ number_format($product->price, 2) }}</span>
                                </div>

                                <input type="hidden" name="items[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                                <div class="flex items-center justify-between gap-3 mt-3">
                                    <label class="text-[10px] uppercase tracking-wide text-muted">Cantidad</label>
                                    <input type="number" name="items[{{ $product->id }}][quantity]" min="0" max="20" value="0" class="w-20 py-2 px-2.5 rounded-lg bg-dark border border-border text-cream text-center focus:outline-none focus:border-amber">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="bg-surface border border-border rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-cream">Ventas por mesa</h2>
                    <span class="text-[10px] uppercase tracking-wide text-muted">{{ count($tableSales) }} mesas</span>
                </div>

                @if (empty($tableSales))
                    <div class="text-xs text-muted">Todavía no hay ventas asociadas a mesas.</div>
                @else
                    <div class="space-y-2">
                        @foreach ($tableSales as $entry)
                            <div class="flex items-center justify-between gap-3 border border-border rounded-lg px-3 py-2 bg-card/50">
                                <div>
                                    <div class="text-sm font-semibold text-cream">{{ $entry['table'] }}</div>
                                    <div class="text-[10px] text-muted">{{ $entry['count'] }} ventas</div>
                                </div>
                                <div class="text-sm font-mono text-amber font-bold">S/ {{ number_format($entry['total'], 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <aside class="space-y-4">
            <div class="bg-surface border border-border rounded-xl p-4 h-fit space-y-4">
                <div class="text-sm font-semibold text-cream">Registrar venta POS</div>

                <div>
                    <label class="block text-[10px] uppercase tracking-wide text-muted mb-1">Cliente</label>
                    <input type="text" name="customer_name" value="Cliente POS" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-wide text-muted mb-1">Método de pago</label>
                    <select name="payment_method" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                        <option value="cash">Efectivo</option>
                        <option value="card">Tarjeta</option>
                        <option value="yape">Yape / Plin</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-wide text-muted mb-1">Referencia / código</label>
                    <input type="text" name="payment_reference" value="POS-{{ now()->format('YmdHis') }}" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber font-mono">
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-wide text-muted mb-1">Notas</label>
                    <textarea name="notes" rows="3" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="Mesa, para llevar, sin azúcar..."></textarea>
                </div>

                <button type="submit" class="w-full py-3 rounded-lg bg-amber text-white text-xs font-semibold shadow-md hover:bg-amberLight transition">
                    <i class="fa-solid fa-lock mr-1.5"></i> Registrar venta POS
                </button>
            </div>
        </aside>
    </div>
</form>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">
    <form method="POST" action="{{ route('admin.pos.shift') }}" class="bg-surface border border-border rounded-xl p-4 space-y-4">
        @csrf
        <div class="text-sm font-semibold text-cream">Turno</div>
        <div>
            <label class="block text-[10px] uppercase tracking-wide text-muted mb-1">Turno</label>
            <select name="shift_name" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                <option value="mañana" {{ $shiftName === 'mañana' ? 'selected' : '' }}>Mañana</option>
                <option value="tarde" {{ $shiftName === 'tarde' ? 'selected' : '' }}>Tarde</option>
                <option value="noche" {{ $shiftName === 'noche' ? 'selected' : '' }}>Noche</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] uppercase tracking-wide text-muted mb-1">Apertura de caja</label>
            <input type="number" step="0.01" min="0" name="opening_cash" value="{{ number_format($openingCash, 2, '.', '') }}" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
        </div>
        <button type="submit" class="w-full py-2.5 rounded-lg bg-sky-500 text-white text-xs font-semibold hover:bg-sky-400 transition">
            Guardar turno
        </button>
    </form>

    <form method="POST" action="{{ route('admin.pos.close') }}" class="bg-surface border border-border rounded-xl p-4 space-y-4">
        @csrf
        <div class="text-sm font-semibold text-cream">Cierre de caja</div>
        <div>
            <label class="block text-[10px] uppercase tracking-wide text-muted mb-1">Efectivo final</label>
            <input type="number" step="0.01" min="0" name="closing_cash" value="{{ $closingCash !== null ? number_format($closingCash, 2, '.', '') : '' }}" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="0.00">
        </div>
        <button type="submit" class="w-full py-2.5 rounded-lg bg-amber text-white text-xs font-semibold hover:bg-amberLight transition">
            Registrar cierre
        </button>
    </form>
</div>
@endsection
