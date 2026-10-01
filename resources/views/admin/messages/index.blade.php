@extends('admin.layout')

@section('title', 'Bandeja de Mensajes y Consultas')

@section('content')

{{-- ENCABEZADO --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Contacto</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Bandeja de Consultas de Clientes</h1>
        <p class="text-xs text-muted mt-1">Revisa y gestiona los mensajes enviados a través del formulario de contacto.</p>
    </div>

    @if ($unreadCount > 0)
    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-purple-500/15 border border-purple-500/30 text-purple-300 text-xs font-medium">
        <i class="fa-solid fa-envelope"></i>
        <span>{{ $unreadCount }} mensajes sin leer</span>
    </div>
    @endif
</div>

{{-- FILTROS --}}
<div class="flex items-center gap-2 text-xs">
    <a href="{{ route('admin.messages.index') }}"
       class="px-3 py-1.5 rounded-lg border transition {{ !request('status') ? 'bg-amber/15 text-amber border-amber/30' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Todos
    </a>
    <a href="{{ route('admin.messages.index', ['status' => 'unread']) }}"
       class="px-3 py-1.5 rounded-lg border transition {{ request('status') === 'unread' ? 'bg-amber/15 text-amber border-amber/30' : 'bg-surface border-border text-muted hover:text-cream' }}">
        No Leídos
    </a>
    <a href="{{ route('admin.messages.index', ['status' => 'read']) }}"
       class="px-3 py-1.5 rounded-lg border transition {{ request('status') === 'read' ? 'bg-amber/15 text-amber border-amber/30' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Leídos
    </a>
    <a href="{{ route('admin.messages.index', ['status' => 'attended']) }}"
       class="px-3 py-1.5 rounded-lg border transition {{ request('status') === 'attended' ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Atendidos
    </a>
</div>

{{-- LISTA DE MENSAJES --}}
<div class="space-y-3">
    @forelse ($messages as $msg)
    <div class="bg-surface border {{ $msg->status === 'unread' ? 'border-purple-500/40 bg-card/60' : 'border-border' }} rounded-xl p-5 space-y-3 transition">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-3 border-b border-border/60">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-card border border-border text-amber font-mono font-bold flex items-center justify-center text-xs">
                    {{ strtoupper(substr($msg->nombre, 0, 2)) }}
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-cream flex items-center gap-2">
                        <span>{{ $msg->nombre }}</span>
                        @if ($msg->status === 'unread')
                        <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse" title="Nuevo"></span>
                        @endif
                    </h3>
                    <div class="flex items-center gap-3 text-xs text-muted">
                        <a href="mailto:{{ $msg->email }}" class="hover:text-amber">{{ $msg->email }}</a>
                        @if ($msg->telefono)
                        <span>·</span>
                        <a href="tel:{{ $msg->telefono }}" class="hover:text-amber font-mono">{{ $msg->telefono }}</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 self-end sm:self-auto">
                <span class="text-[11px] text-muted font-mono">
                    {{ $msg->created_at ? $msg->created_at->format('d/m/Y H:i') : 'Hoy' }}
                </span>

                {{-- Selector de Estado --}}
                <form action="{{ route('admin.messages.status', $msg->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()"
                            class="py-1 px-2.5 rounded-lg text-[11px] font-medium border transition focus:outline-none cursor-pointer {{ $msg->status === 'attended' ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30' : ($msg->status === 'read' ? 'bg-zinc-700/20 text-zinc-300 border-zinc-700' : 'bg-purple-500/15 text-purple-300 border-purple-500/30') }}">
                        <option value="unread" {{ $msg->status === 'unread' ? 'selected' : '' }}>No Leído</option>
                        <option value="read" {{ $msg->status === 'read' ? 'selected' : '' }}>Leído</option>
                        <option value="attended" {{ $msg->status === 'attended' ? 'selected' : '' }}>Atendido</option>
                    </select>
                </form>

                <form action="{{ route('admin.messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este mensaje?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1.5 text-muted hover:text-red-400 transition" title="Eliminar">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </form>
            </div>
        </div>

        <div>
            <div class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider bg-card border border-border text-amber mb-2">
                Motivo: {{ $msg->motivo }}
            </div>
            <p class="text-xs text-cream/85 leading-relaxed bg-dark/40 p-3.5 rounded-lg border border-border/40">
                {{ $msg->mensaje }}
            </p>
        </div>
    </div>
    @empty
    <div class="bg-surface border border-border rounded-xl p-12 text-center text-muted text-xs">
        No hay mensajes en esta bandeja de entrada.
    </div>
    @endforelse

    @if ($messages->hasPages())
    <div class="p-4 border-t border-border">
        {{ $messages->links() }}
    </div>
    @endif
</div>

@endsection
