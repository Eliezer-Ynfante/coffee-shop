@extends('admin.layout')

@section('title', 'Control de Usuarios y Roles')

@section('content')

{{-- ENCABEZADO --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Usuarios</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Directorio de Usuarios y Accesos</h1>
        <p class="text-xs text-muted mt-1">Supervisa las cuentas registradas en el sistema y administra los roles de acceso.</p>
    </div>
</div>

{{-- FILTROS Y BÚSQUEDA --}}
<form method="GET" action="{{ route('admin.users.index') }}" class="bg-surface border border-border rounded-xl p-3.5 flex flex-col sm:flex-row items-center gap-3 text-xs">
    <div class="relative flex-1 w-full">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-muted"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o correo electrónico..."
               class="w-full pl-9 pr-3 py-2 bg-dark/60 border border-border rounded-lg text-cream placeholder-muted focus:outline-none focus:border-amber transition">
    </div>

    <div class="w-full sm:w-48">
        <select name="role" onchange="this.form.submit()" class="w-full py-2 px-3 bg-dark/60 border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">
            <option value="">Todos los Roles</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administradores</option>
            <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Clientes</option>
        </select>
    </div>

    @if (request()->hasAny(['search', 'role']))
    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-muted hover:text-cream border border-border rounded-lg transition whitespace-nowrap">
        Limpiar
    </a>
    @endif
</form>

{{-- TABLA DE USUARIOS --}}
<div class="bg-surface border border-border rounded-xl overflow-hidden shadow-lg">
    <table class="w-full text-left text-xs">
        <thead class="bg-dark/80 text-[11px] font-semibold text-muted uppercase tracking-wider border-b border-border">
            <tr>
                <th class="py-3 px-4">Usuario</th>
                <th class="py-3 px-4">Correo Electrónico</th>
                <th class="py-3 px-4">Rol del Sistema</th>
                <th class="py-3 px-4">Fecha de Registro</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border/60 text-cream/90 font-sans">
            @forelse ($users as $u)
            <tr class="hover:bg-card/40 transition">
                <td class="py-3.5 px-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-card border border-border text-amber font-mono font-bold flex items-center justify-center text-xs">
                            {{ strtoupper(substr($u->name, 0, 2)) }}
                        </div>
                        <div>
                            <span class="font-semibold text-cream block">{{ $u->name }}</span>
                            <span class="text-[10px] text-muted font-mono">ID: #{{ $u->id }}</span>
                        </div>
                    </div>
                </td>

                <td class="py-3.5 px-4 font-mono text-muted">
                    {{ $u->email }}
                </td>

                <td class="py-3.5 px-4">
                    @if ($u->id === auth()->id())
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber/15 text-amber border border-amber/30">
                        <i class="fa-solid fa-shield-halved text-[10px]"></i>
                        Administrador (Tu Cuenta)
                    </span>
                    @else
                    <form action="{{ route('admin.users.role', $u->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="role" onchange="this.form.submit()"
                                class="py-1 px-2.5 rounded-lg text-[11px] font-medium border transition focus:outline-none cursor-pointer {{ $u->role === 'admin' ? 'bg-amber/15 text-amber border-amber/30' : 'bg-card border-border text-cream' }}">
                            <option value="customer" {{ $u->role === 'customer' ? 'selected' : '' }}>Cliente</option>
                            <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </form>
                    @endif
                </td>

                <td class="py-3.5 px-4 text-muted font-mono text-[11px]">
                    {{ $u->created_at ? $u->created_at->format('d/m/Y H:i') : 'Reciente' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-10 text-center text-muted">No se encontraron usuarios.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if ($users->hasPages())
    <div class="p-4 border-t border-border">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
