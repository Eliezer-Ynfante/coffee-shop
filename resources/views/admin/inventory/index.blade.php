@extends('admin.layout')

@section('title', 'Inventario')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Inventario</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Control de inventario</h1>
        <p class="text-xs text-muted mt-1">Revisa el stock de cada producto y registra ajustes con trazabilidad.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
    <div class="bg-surface border border-border rounded-xl p-4">
        <div class="text-[10px] uppercase tracking-wide text-muted">Productos</div>
        <div class="mt-2 text-2xl font-bold text-cream">{{ $products->total() }}</div>
    </div>
    <div class="bg-surface border border-border rounded-xl p-4">
        <div class="text-[10px] uppercase tracking-wide text-muted">Stock bajo</div>
        <div class="mt-2 text-2xl font-bold text-amber">{{ $lowStockCount }}</div>
    </div>
    <div class="bg-surface border border-border rounded-xl p-4">
        <div class="text-[10px] uppercase tracking-wide text-muted">Último movimiento</div>
        <div class="mt-2 text-sm font-semibold text-cream">
            @if ($recentLogs->isNotEmpty())
                {{ $recentLogs->first()->created_at?->format('d/m H:i') ?? '—' }}
            @else
                Sin movimientos
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1.25fr)_360px] gap-6 mt-6">
    <div class="bg-surface border border-border rounded-xl p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-cream">Stock por producto</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-[10px] uppercase tracking-wide text-muted border-b border-border">
                        <th class="pb-3 pr-3">Producto</th>
                        <th class="pb-3 pr-3">Categoría</th>
                        <th class="pb-3 pr-3 text-right">Stock</th>
                        <th class="pb-3 pr-3 text-right">Alerta</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="border-b border-border/60 align-top">
                            <td class="py-3 pr-3">
                                <div class="font-medium text-cream">{{ $product->name }}</div>
                                <div class="text-[10px] text-muted">{{ $product->sku ?? 'Sin SKU' }}</div>
                            </td>
                            <td class="py-3 pr-3 text-muted">{{ $product->category?->name ?? 'Sin categoría' }}</td>
                            <td class="py-3 pr-3 text-right font-mono {{ $product->stock <= ($product->min_stock_alert ?? 0) ? 'text-amber' : 'text-cream' }}">
                                {{ $product->stock }}
                            </td>
                            <td class="py-3 pr-3 text-right font-mono text-muted">{{ $product->min_stock_alert ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-xs text-muted">No hay productos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <aside class="space-y-4">
        <form method="POST" action="{{ route('admin.inventory.adjust') }}" class="bg-surface border border-border rounded-xl p-4 space-y-4">
            @csrf
            <div class="text-sm font-semibold text-cream">Ajuste de stock</div>

            <div>
                <label class="block text-[10px] uppercase tracking-wide text-muted mb-1">Producto</label>
                <select name="product_id" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-wide text-muted mb-1">Tipo</label>
                <select name="type" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                    <option value="restock">Reposición</option>
                    <option value="adjustment">Ajuste manual</option>
                    <option value="waste">Merma</option>
                    <option value="return">Devolución</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-wide text-muted mb-1">Cantidad</label>
                <input type="number" name="quantity" min="1" value="1" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-wide text-muted mb-1">Nota</label>
                <textarea name="notes" rows="3" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="Motivo del ajuste..."></textarea>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-lg bg-amber text-white text-xs font-semibold hover:bg-amberLight transition">
                Guardar ajuste
            </button>
        </form>

        <div class="bg-surface border border-border rounded-xl p-4">
            <div class="text-sm font-semibold text-cream mb-3">Últimos movimientos</div>
            <div class="space-y-2">
                @forelse ($recentLogs as $log)
                    <div class="border border-border rounded-lg px-3 py-2 bg-card/40">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-medium text-cream uppercase">{{ $log->type }}</span>
                            <span class="text-[10px] text-muted">{{ $log->created_at?->format('d/m H:i') }}</span>
                        </div>
                        <div class="mt-1 text-xs text-muted">
                            {{ $log->product?->name ?? 'Producto' }} · {{ $log->quantity > 0 ? '+' : '' }}{{ $log->quantity }}
                        </div>
                    </div>
                @empty
                    <div class="text-xs text-muted">Todavía no hay movimientos de inventario.</div>
                @endforelse
            </div>
        </div>
    </aside>
</div>
@endsection
