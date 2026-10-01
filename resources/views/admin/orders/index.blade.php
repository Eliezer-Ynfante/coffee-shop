@extends('admin.layout')

@section('title', 'Control de Órdenes y Ventas')

@section('content')

{{-- ENCABEZADO --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Ventas</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Control de Órdenes y Despacho</h1>
        <p class="text-xs text-muted mt-1">Supervisa los pedidos de cafetería, estado de preparación de los baristas y flujo de caja.</p>
    </div>

    <div class="bg-surface border border-border px-4 py-2.5 rounded-xl flex items-center gap-3">
        <div class="text-right">
            <span class="text-[10px] text-muted uppercase tracking-wider block">Ventas Completadas</span>
            <span class="text-base font-bold font-mono text-emerald-400">S/ {{ number_format($orderStats['sales'], 2) }}</span>
        </div>
        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-sm border border-emerald-500/20">
            <i class="fa-solid fa-coins"></i>
        </div>
    </div>
</div>

{{-- MÉTRICAS DE ESTADO --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
    <a href="{{ route('admin.orders.index') }}"
       class="p-3.5 rounded-xl bg-surface border {{ !request('status') ? 'border-amber/60 bg-card' : 'border-border' }} transition">
        <span class="text-[10px] text-muted font-semibold uppercase tracking-wider block mb-1">Total Órdenes</span>
        <span class="text-xl font-bold font-mono text-cream">{{ $orderStats['total'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'preparing']) }}"
       class="p-3.5 rounded-xl bg-surface border {{ request('status') === 'preparing' ? 'border-amber/60 bg-card' : 'border-border' }} transition">
        <span class="text-[10px] text-amber font-semibold uppercase tracking-wider block mb-1">En Preparación</span>
        <span class="text-xl font-bold font-mono text-amber">{{ $orderStats['preparing'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'ready']) }}"
       class="p-3.5 rounded-xl bg-surface border {{ request('status') === 'ready' ? 'border-emerald-500/60 bg-card' : 'border-border' }} transition">
        <span class="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider block mb-1">Listas para Entrega</span>
        <span class="text-xl font-bold font-mono text-emerald-400">{{ $orderStats['ready'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}"
       class="p-3.5 rounded-xl bg-surface border {{ request('status') === 'completed' ? 'border-sky-500/60 bg-card' : 'border-border' }} transition">
        <span class="text-[10px] text-sky-400 font-semibold uppercase tracking-wider block mb-1">Entregadas</span>
        <span class="text-xl font-bold font-mono text-sky-400">{{ $orderStats['completed'] }}</span>
    </a>
</div>

{{-- FILTROS --}}
<form method="GET" action="{{ route('admin.orders.index') }}" class="bg-surface border border-border rounded-xl p-3.5 flex flex-wrap items-center gap-3 text-xs">
    <div class="flex items-center gap-2">
        <label class="text-muted font-medium">Canal de Venta:</label>
        <select name="channel" onchange="this.form.submit()" class="py-1.5 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">
            <option value="">Todos los Canales</option>
            <option value="pos" {{ request('channel') === 'pos' ? 'selected' : '' }}>Punto de Venta (POS)</option>
            <option value="ecommerce" {{ request('channel') === 'ecommerce' ? 'selected' : '' }}>Tienda Online</option>
        </select>
    </div>

    <div class="flex items-center gap-2">
        <label class="text-muted font-medium">Estado:</label>
        <select name="status" onchange="this.form.submit()" class="py-1.5 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">
            <option value="">Todos los Estados</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmada</option>
            <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>En Preparación</option>
            <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Lista para Entrega</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Entregada</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
        </select>
    </div>

    @if (request()->hasAny(['channel', 'status']))
    <a href="{{ route('admin.orders.index') }}" class="px-2.5 py-1.5 text-muted hover:text-cream border border-border rounded-lg transition ml-auto">
        Limpiar Filtros
    </a>
    @endif
</form>

{{-- TABLA DE ÓRDENES --}}
<div class="bg-surface border border-border rounded-xl overflow-hidden shadow-lg">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-dark/80 text-[11px] font-semibold text-muted uppercase tracking-wider border-b border-border">
                <tr>
                    <th class="py-3 px-4">Orden / Fecha</th>
                    <th class="py-3 px-4">Cliente / Canal</th>
                    <th class="py-3 px-4">Artículos Solicitados</th>
                    <th class="py-3 px-4">Total</th>
                    <th class="py-3 px-4">Pago</th>
                    <th class="py-3 px-4">Estado del Pedido</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border/60 text-cream/90 font-sans">
                @forelse ($orders as $ord)
                <tr class="hover:bg-card/40 transition">
                    {{-- Código y Fecha --}}
                    <td class="py-3.5 px-4">
                        <span class="font-mono font-bold text-cream">{{ $ord->order_number }}</span>
                        <div class="text-[10px] text-muted mt-0.5">
                            {{ $ord->created_at ? $ord->created_at->format('d/m/Y H:i') : 'Hoy' }}
                        </div>
                    </td>

                    {{-- Cliente y Canal --}}
                    <td class="py-3.5 px-4">
                        <div class="font-semibold text-cream">
                            {{ $ord->customer_name ?? ($ord->customer->first_name ?? 'Cliente POS') }}
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded font-mono uppercase bg-card border border-border text-muted inline-block mt-0.5">
                            {{ $ord->channel }}
                        </span>
                    </td>

                    {{-- Ítems --}}
                    <td class="py-3.5 px-4">
                        <ul class="space-y-1">
                            @foreach ($ord->items as $item)
                            <li class="flex items-center gap-1.5 text-[11px]">
                                <span class="w-4 h-4 rounded bg-dark border border-border text-amber font-mono font-bold flex items-center justify-center text-[10px]">
                                    {{ $item->quantity }}
                                </span>
                                <span class="text-cream font-medium">{{ $item->product->name ?? 'Producto' }}</span>
                                <span class="text-muted font-mono">(S/ {{ number_format($item->subtotal, 2) }})</span>
                            </li>
                            @endforeach
                        </ul>
                    </td>

                    {{-- Total --}}
                    <td class="py-3.5 px-4 font-mono font-bold text-cream text-sm">
                        S/ {{ number_format($ord->total, 2) }}
                    </td>

                    {{-- Pago --}}
                    <td class="py-3.5 px-4">
                        <div class="text-[11px] font-mono uppercase text-cream">
                            {{ $ord->payment_method ?? 'Efectivo' }}
                        </div>
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-mono uppercase {{ $ord->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-400' : 'bg-amber/15 text-amber' }}">
                            {{ $ord->payment_status === 'paid' ? 'Pagado' : 'Pendiente' }}
                        </span>
                    </td>

                    {{-- Estado de la Orden con Selector Rápido --}}
                    <td class="py-3.5 px-4">
                        <form action="{{ route('admin.orders.status', $ord->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="py-1 px-2.5 rounded-lg text-[11px] font-medium border transition focus:outline-none cursor-pointer {{ $ord->status === 'completed' ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30' : ($ord->status === 'ready' ? 'bg-sky-500/15 text-sky-400 border-sky-500/30' : ($ord->status === 'cancelled' ? 'bg-red-500/15 text-red-400 border-red-500/30' : 'bg-amber/15 text-amber border-amber/30')) }}">
                                <option value="pending" {{ $ord->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmed" {{ $ord->status === 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                                <option value="preparing" {{ $ord->status === 'preparing' ? 'selected' : '' }}>En Preparación</option>
                                <option value="ready" {{ $ord->status === 'ready' ? 'selected' : '' }}>Listo para Entrega</option>
                                <option value="completed" {{ $ord->status === 'completed' ? 'selected' : '' }}>Entregada</option>
                                <option value="cancelled" {{ $ord->status === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 text-center text-muted">No hay órdenes registradas con los filtros seleccionados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($orders->hasPages())
    <div class="p-4 border-t border-border">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@endsection
