@extends('admin.layout')

@section('title', 'Clientes')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Clientes</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Clientes y perfiles</h1>
        <p class="text-xs text-muted mt-1">Consulta pagos, puntos y datos de contacto de los clientes registrados.</p>
    </div>
</div>

<div class="mt-6 bg-surface border border-border rounded-xl p-4">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="text-[10px] uppercase tracking-wide text-muted border-b border-border">
                    <th class="pb-3 pr-3">Cliente</th>
                    <th class="pb-3 pr-3">Teléfono</th>
                    <th class="pb-3 pr-3">Ciudad</th>
                    <th class="pb-3 pr-3 text-right">Puntos</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr class="border-b border-border/60">
                        <td class="py-3 pr-3">
                            <div class="font-medium text-cream">{{ trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) ?: ($customer->user?->name ?? 'Cliente sin nombre') }}</div>
                            <div class="text-[10px] text-muted">{{ $customer->user?->email ?? 'Sin email' }}</div>
                        </td>
                        <td class="py-3 pr-3 text-muted">{{ $customer->phone ?? '—' }}</td>
                        <td class="py-3 pr-3 text-muted">{{ $customer->city ?? '—' }}</td>
                        <td class="py-3 pr-3 text-right font-mono text-amber">{{ $customer->loyalty_points ?? 0 }} pts</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-xs text-muted">Todavía no hay clientes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
