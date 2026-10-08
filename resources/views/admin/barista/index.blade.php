@extends('admin.layout')

@section('title', 'Barra y Preparación')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Barra</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Cola de Preparación</h1>
        <p class="text-xs text-muted mt-1">Revisa qué órdenes están pendientes, en preparación o listas para entregar.</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mt-6">
    @forelse ($orders as $order)
        <div class="bg-surface border border-border rounded-xl p-4 space-y-3">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <div class="font-mono text-sm font-bold text-cream">{{ $order->order_number }}</div>
                    <div class="text-[10px] text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-semibold uppercase
                    @if($order->status === 'ready') bg-emerald-500/15 text-emerald-400 border border-emerald-500/30
                    @elseif($order->status === 'preparing') bg-amber/15 text-amber border border-amber/30
                    @elseif($order->status === 'confirmed') bg-sky-500/15 text-sky-400 border border-sky-500/30
                    @else bg-card text-muted border border-border @endif">
                    {{ $order->status }}
                </span>
            </div>

            <div class="text-[11px] text-muted">
                <div><span class="text-cream font-medium">Cliente:</span> {{ $order->customer_name ?? 'Cliente POS' }}</div>
                <div><span class="text-cream font-medium">Pago:</span> {{ strtoupper($order->payment_method ?? 'cash') }}</div>
                <div><span class="text-cream font-medium">Nota:</span> {{ $order->notes ?? 'Sin notas' }}</div>
            </div>

            <div class="space-y-1.5">
                @foreach ($order->items as $item)
                    <div class="flex items-center justify-between text-[11px] border-b border-border/60 pb-1 last:border-none">
                        <span class="text-cream">
                            <span class="font-mono text-amber">{{ $item->quantity }}×</span> {{ $item->product?->name ?? 'Producto' }}
                        </span>
                        <span class="font-mono text-muted">S/ {{ number_format($item->subtotal, 2) }}</span>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="pt-2 border-t border-border/60">
                @csrf
                @method('PATCH')
                <label class="block text-[10px] uppercase tracking-wide text-muted mb-1">Actualizar estado</label>
                <div class="flex gap-2">
                    <select name="status" class="flex-1 py-2 px-2.5 rounded-lg bg-dark border border-border text-cream text-xs focus:outline-none focus:border-amber">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                        <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>En preparación</option>
                        <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Lista</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Entregada</option>
                    </select>
                    <button type="submit" class="px-3 py-2 rounded-lg bg-amber text-white text-[10px] font-semibold">Guardar</button>
                </div>
            </form>
        </div>
    @empty
        <div class="col-span-full bg-surface border border-border rounded-xl p-8 text-center text-xs text-muted">
            No hay órdenes activas en la barra.
        </div>
    @endforelse
</div>
@endsection
