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
        <p class="text-xs text-muted mt-1">Registra ventas de mostrador y envía la orden directamente a la barra.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.pos.store') }}" class="grid grid-cols-1 xl:grid-cols-[minmax(0,1.3fr)_360px] gap-6 mt-6">
    @csrf

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
    </div>

    <aside class="bg-surface border border-border rounded-xl p-4 h-fit space-y-4">
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
    </aside>
</form>
@endsection
