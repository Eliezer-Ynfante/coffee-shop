@extends('admin.layout')

@section('title', 'Control de Reservas y Mesas')

@section('content')

{{-- ENCABEZADO --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Reservas</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Control de Reservas y Asignación de Mesas</h1>
        <p class="text-xs text-muted mt-1">Supervisa y valida las reservas agendadas por los clientes desde la maqueta 3D y el formulario.</p>
    </div>

    <div>
        <a href="{{ route('reserva') }}" target="_blank"
           class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-medium rounded-lg bg-surface border border-border hover:border-amber/40 text-cream transition">
            <i class="fa-solid fa-cube text-amber text-[10px]"></i>
            <span>Ver Maqueta 3D Pública</span>
            <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-muted"></i>
        </a>
    </div>
</div>

{{-- MÉTRICAS DE ESTADO --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
    <a href="{{ route('admin.reservations.index') }}"
       class="p-3.5 rounded-xl bg-surface border {{ !request('status') ? 'border-amber/60 bg-card' : 'border-border' }} transition">
        <span class="text-[10px] text-muted font-semibold uppercase tracking-wider block mb-1">Todas las Reservas</span>
        <span class="text-xl font-bold font-mono text-cream">{{ $statusCounts['total'] }}</span>
    </a>
    <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}"
       class="p-3.5 rounded-xl bg-surface border {{ request('status') === 'pending' ? 'border-amber/60 bg-card' : 'border-border' }} transition">
        <span class="text-[10px] text-amber font-semibold uppercase tracking-wider block mb-1">Pendientes</span>
        <span class="text-xl font-bold font-mono text-amber">{{ $statusCounts['pending'] }}</span>
    </a>
    <a href="{{ route('admin.reservations.index', ['status' => 'confirmed']) }}"
       class="p-3.5 rounded-xl bg-surface border {{ request('status') === 'confirmed' ? 'border-emerald-500/60 bg-card' : 'border-border' }} transition">
        <span class="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider block mb-1">Confirmadas</span>
        <span class="text-xl font-bold font-mono text-emerald-400">{{ $statusCounts['confirmed'] }}</span>
    </a>
    <a href="{{ route('admin.reservations.index', ['status' => 'completed']) }}"
       class="p-3.5 rounded-xl bg-surface border {{ request('status') === 'completed' ? 'border-sky-500/60 bg-card' : 'border-border' }} transition">
        <span class="text-[10px] text-sky-400 font-semibold uppercase tracking-wider block mb-1">Completadas</span>
        <span class="text-xl font-bold font-mono text-sky-400">{{ $statusCounts['completed'] }}</span>
    </a>
</div>

{{-- FILTROS --}}
<form method="GET" action="{{ route('admin.reservations.index') }}" class="bg-surface border border-border rounded-xl p-3.5 flex flex-wrap items-center gap-3 text-xs">
    <div class="flex items-center gap-2">
        <label class="text-muted font-medium">Filtrar por Fecha:</label>
        <input type="date" name="fecha" value="{{ request('fecha') }}" onchange="this.form.submit()"
               class="py-1.5 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">
    </div>

    <div class="flex items-center gap-2">
        <label class="text-muted font-medium">Estado:</label>
        <select name="status" onchange="this.form.submit()" class="py-1.5 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">
            <option value="">Todos los Estados</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmada</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completada</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
        </select>
    </div>

    @if (request()->hasAny(['status', 'fecha']))
    <a href="{{ route('admin.reservations.index') }}" class="px-2.5 py-1.5 text-muted hover:text-cream border border-border rounded-lg transition ml-auto">
        Limpiar Filtros
    </a>
    @endif
</form>

{{-- TABLA DE RESERVAS --}}
<div class="bg-surface border border-border rounded-xl overflow-hidden shadow-lg">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-dark/80 text-[11px] font-semibold text-muted uppercase tracking-wider border-b border-border">
                <tr>
                    <th class="py-3 px-4">Código / Fecha</th>
                    <th class="py-3 px-4">Cliente</th>
                    <th class="py-3 px-4">Mesa / Zona</th>
                    <th class="py-3 px-4">Personas</th>
                    <th class="py-3 px-4">Ocasión / Detalles</th>
                    <th class="py-3 px-4">Estado</th>
                    <th class="py-3 px-4 text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border/60 text-cream/90 font-sans">
                @forelse ($reservations as $res)
                <tr class="hover:bg-card/40 transition">
                    {{-- Código y Fecha --}}
                    <td class="py-3.5 px-4">
                        <span class="font-mono font-bold text-amber">#RSV-{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <div class="text-[11px] text-cream mt-0.5 font-medium">
                            {{ \Carbon\Carbon::parse($res->fecha)->format('d/m/Y') }}
                        </div>
                        <div class="text-[10px] text-muted font-mono">
                            <i class="fa-regular fa-clock text-[9px] mr-0.5"></i> {{ $res->hora ?? '10:00' }}
                        </div>
                    </td>

                    {{-- Cliente --}}
                    <td class="py-3.5 px-4">
                        <div class="font-semibold text-cream">{{ $res->nombre }}</div>
                        <a href="tel:{{ $res->telefono }}" class="text-[11px] text-muted hover:text-amber block font-mono">
                            <i class="fa-solid fa-phone text-[9px] mr-1"></i> {{ $res->telefono }}
                        </a>
                        @if ($res->email)
                        <span class="text-[10px] text-muted block truncate max-w-[160px]">{{ $res->email }}</span>
                        @endif
                    </td>

                    {{-- Mesa y Zona --}}
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-1.5">
                            <span class="w-6 h-6 rounded bg-card border border-border text-amber font-mono font-bold flex items-center justify-center text-[11px]">
                                {{ $res->mesa_id ?? '?' }}
                            </span>
                            <div>
                                <span class="font-medium text-cream block text-[11px]">Mesa {{ $res->mesa_id ?? 'Por asignar' }}</span>
                                <span class="text-[10px] text-muted block">{{ $res->zona ?? 'Salón Principal' }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- Personas --}}
                    <td class="py-3.5 px-4 font-mono font-medium">
                        <span class="px-2 py-0.5 rounded bg-card border border-border">
                            <i class="fa-solid fa-user-group text-[9px] mr-1 text-muted"></i> {{ $res->personas }}
                        </span>
                    </td>

                    {{-- Ocasión y Comentarios --}}
                    <td class="py-3.5 px-4 text-muted text-[11px] max-w-xs">
                        @if ($res->ocasion)
                        <span class="text-cream font-medium block">{{ $res->ocasion }}</span>
                        @endif
                        <span class="truncate block italic">{{ $res->comentarios ?? 'Sin comentarios adicionales.' }}</span>
                    </td>

                    {{-- Estado con Selector Rápido --}}
                    <td class="py-3.5 px-4">
                        <form action="{{ route('admin.reservations.status', $res->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="py-1 px-2 rounded-lg text-[11px] font-medium border transition focus:outline-none cursor-pointer {{ $res->status === 'confirmed' ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30' : ($res->status === 'completed' ? 'bg-sky-500/15 text-sky-400 border-sky-500/30' : ($res->status === 'cancelled' ? 'bg-red-500/15 text-red-400 border-red-500/30' : 'bg-amber/15 text-amber border-amber/30')) }}">
                                <option value="pending" {{ $res->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmed" {{ $res->status === 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                                <option value="completed" {{ $res->status === 'completed' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelled" {{ $res->status === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </form>
                    </td>

                    {{-- Acciones --}}
                    <td class="py-3.5 px-4 text-right">
                        <form action="{{ route('admin.reservations.destroy', $res->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta reserva?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-muted hover:text-red-400 rounded transition" title="Eliminar Reserva">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-10 text-center text-muted">
                        No hay reservas registradas con los filtros actuales.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($reservations->hasPages())
    <div class="p-4 border-t border-border">
        {{ $reservations->links() }}
    </div>
    @endif
</div>

@endsection
