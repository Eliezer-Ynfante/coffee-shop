@extends('admin.layout')

@section('title', 'Control de Mesas y Plano 3D')

@section('content')

{{-- ENCABEZADO --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Mesas 3D</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Gestión de Mesas y Ocupación del Plano 3D</h1>
        <p class="text-xs text-muted mt-1">Supervisa y actualiza en tiempo real el estado de ocupación de las mesas visibles en la maqueta 3D.</p>
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

{{-- MÉTRICAS DE OCUPACIÓN --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
    <div class="p-3.5 rounded-xl bg-surface border border-border">
        <span class="text-[10px] text-muted font-semibold uppercase tracking-wider block mb-1">Total Mesas</span>
        <span class="text-xl font-bold font-mono text-cream">{{ $stats['total'] }}</span>
    </div>
    <div class="p-3.5 rounded-xl bg-surface border border-emerald-500/30 bg-emerald-500/5">
        <span class="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider block mb-1">Disponibles</span>
        <span class="text-xl font-bold font-mono text-emerald-400">{{ $stats['disponible'] }}</span>
    </div>
    <div class="p-3.5 rounded-xl bg-surface border border-red-500/30 bg-red-500/5">
        <span class="text-[10px] text-red-400 font-semibold uppercase tracking-wider block mb-1">Ocupadas (En Sala)</span>
        <span class="text-xl font-bold font-mono text-red-400">{{ $stats['ocupada'] }}</span>
    </div>
    <div class="p-3.5 rounded-xl bg-surface border border-amber/30 bg-amber/5">
        <span class="text-[10px] text-amber font-semibold uppercase tracking-wider block mb-1">Reservadas</span>
        <span class="text-xl font-bold font-mono text-amber">{{ $stats['reservada'] }}</span>
    </div>
</div>

{{-- FILTROS POR ZONA --}}
<div class="flex items-center gap-2 text-xs flex-wrap">
    <a href="{{ route('admin.tables.index') }}"
       class="px-3 py-1.5 rounded-lg border transition {{ !request('zone') ? 'bg-amber/15 text-amber border-amber/30 font-medium' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Todas las Zonas
    </a>
    <a href="{{ route('admin.tables.index', ['zone' => 'barra']) }}"
       class="px-3 py-1.5 rounded-lg border transition {{ request('zone') === 'barra' ? 'bg-amber/15 text-amber border-amber/30 font-medium' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Barra de Especialidad
    </a>
    <a href="{{ route('admin.tables.index', ['zone' => 'salon']) }}"
       class="px-3 py-1.5 rounded-lg border transition {{ request('zone') === 'salon' ? 'bg-amber/15 text-amber border-amber/30 font-medium' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Salón Principal
    </a>
    <a href="{{ route('admin.tables.index', ['zone' => 'terraza']) }}"
       class="px-3 py-1.5 rounded-lg border transition {{ request('zone') === 'terraza' ? 'bg-amber/15 text-amber border-amber/30 font-medium' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Terraza Cafetalera
    </a>
    <a href="{{ route('admin.tables.index', ['zone' => 'coworking']) }}"
       class="px-3 py-1.5 rounded-lg border transition {{ request('zone') === 'coworking' ? 'bg-amber/15 text-amber border-amber/30 font-medium' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Rincón Coworking
    </a>
</div>

{{-- GRID DE MESAS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @forelse ($tables as $table)
    <div class="bg-surface border {{ $table->status === 'disponible' ? 'border-border' : ($table->status === 'ocupada' ? 'border-red-500/40 bg-red-500/5' : 'border-amber/40 bg-amber/5') }} rounded-xl p-4 flex flex-col justify-between space-y-3 transition">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-2.5">
                <span class="w-10 h-10 rounded-lg bg-card border border-border text-amber font-mono font-bold flex items-center justify-center text-sm shadow-inner">
                    {{ $table->code }}
                </span>
                <div>
                    <h3 class="font-semibold text-cream text-xs">{{ $table->name }}</h3>
                    <span class="text-[10px] text-muted block">{{ $table->zone_name ?? ucfirst($table->zone) }}</span>
                </div>
            </div>

            <span class="text-[10px] px-2 py-0.5 rounded font-mono font-medium {{ $table->status === 'disponible' ? 'bg-emerald-500/15 text-emerald-400' : ($table->status === 'ocupada' ? 'bg-red-500/15 text-red-400' : 'bg-amber/15 text-amber') }}">
                {{ ucfirst($table->status) }}
            </span>
        </div>

        <div class="space-y-1.5 text-[11px] text-muted pt-2 border-t border-border/60">
            <div class="flex items-center justify-between">
                <span>Capacidad:</span>
                <span class="font-mono text-cream font-medium">{{ $table->capacity }} {{ $table->capacity === 1 ? 'persona' : 'personas' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span>Posición Plano:</span>
                <span class="font-mono text-muted text-[10px]">X: {{ $table->coord_x }}% · Y: {{ $table->coord_y }}%</span>
            </div>
        </div>

        {{-- Selector Rápido de Estado --}}
        <div class="pt-2">
            <form action="{{ route('admin.tables.status', $table->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <label class="block text-[10px] text-muted mb-1 font-medium">Cambiar estado en 3D:</label>
                <select name="status" onchange="this.form.submit()"
                        class="w-full py-1.5 px-2 rounded-lg text-xs font-medium bg-dark border border-border text-cream focus:outline-none focus:border-amber cursor-pointer transition">
                    <option value="disponible" {{ $table->status === 'disponible' ? 'selected' : '' }}>🟢 Disponible</option>
                    <option value="ocupada" {{ $table->status === 'ocupada' ? 'selected' : '' }}>🔴 Ocupada</option>
                    <option value="reservada" {{ $table->status === 'reservada' ? 'selected' : '' }}>🟡 Reservada</option>
                    <option value="mantenimiento" {{ $table->status === 'mantenimiento' ? 'selected' : '' }}>⚪ Mantenimiento</option>
                </select>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-4 bg-surface border border-border rounded-xl p-10 text-center text-muted text-xs">
        No se encontraron mesas en esta zona.
    </div>
    @endforelse
</div>

@endsection
