@extends('admin.layout')

@section('title', 'Dashboard de Operaciones')

@section('content')

{{-- ENCABEZADO DE SECCIÓN --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1.5">
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Operación en Directo
            </span>
            <span class="text-xs text-muted">·</span>
            <span class="text-xs text-muted font-mono">Última sync: {{ now()->format('H:i') }}</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-cream">
            Panel de Control y Operaciones
        </h1>
        <p class="text-xs sm:text-sm text-muted mt-1">
            Bienvenido, <strong class="text-cream font-medium">{{ Auth::user()->name }}</strong>. Supervisa las ventas, el catálogo y las reservas en tiempo real.
        </p>
    </div>

    <div class="flex items-center gap-2.5">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-medium rounded-lg bg-amber hover:bg-amberLight text-white shadow-sm transition">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Nuevo Producto</span>
        </a>
        <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-medium rounded-lg bg-surface hover:bg-card border border-border hover:border-amber/40 text-cream transition">
            <i class="fa-solid fa-calendar-check text-[10px] text-amber"></i>
            <span>Ver Reservas</span>
        </a>
    </div>
</div>

{{-- MÉTRICAS PRINCIPALES (KPIS) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- KPI 1: Productos y Stock --}}
    <div class="bg-surface border border-border rounded-xl p-5 relative overflow-hidden flex flex-col justify-between hover:border-amber/40 transition">
        <div class="flex items-center justify-between text-muted mb-3">
            <span class="text-[11px] font-semibold uppercase tracking-wider">Productos en Catálogo</span>
            <div class="w-8 h-8 rounded-lg bg-amber/10 border border-amber/20 text-amber flex items-center justify-center text-xs">
                <i class="fa-solid fa-mug-hot"></i>
            </div>
        </div>
        <div>
            <div class="text-2xl font-bold font-mono text-cream">{{ $stats['total_products'] }}</div>
            <p class="text-[11px] text-muted mt-1 flex items-center gap-1">
                <span class="text-amber font-medium">{{ $stats['total_categories'] }}</span> categorías activas
            </p>
        </div>
    </div>

    {{-- KPI 2: Órdenes y Pedidos --}}
    <div class="bg-surface border border-border rounded-xl p-5 relative overflow-hidden flex flex-col justify-between hover:border-amber/40 transition">
        <div class="flex items-center justify-between text-muted mb-3">
            <span class="text-[11px] font-semibold uppercase tracking-wider">Órdenes Realizadas</span>
            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>
        <div>
            <div class="text-2xl font-bold font-mono text-cream">{{ $stats['total_orders'] }}</div>
            <p class="text-[11px] text-muted mt-1 flex items-center gap-1">
                <span class="text-emerald-400 font-medium">{{ $stats['active_orders'] }}</span> pedidos en proceso
            </p>
        </div>
    </div>

    {{-- KPI 3: Reservas de Mesas --}}
    <div class="bg-surface border border-border rounded-xl p-5 relative overflow-hidden flex flex-col justify-between hover:border-amber/40 transition">
        <div class="flex items-center justify-between text-muted mb-3">
            <span class="text-[11px] font-semibold uppercase tracking-wider">Reservas Registradas</span>
            <div class="w-8 h-8 rounded-lg bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center text-xs">
                <i class="fa-solid fa-chair"></i>
            </div>
        </div>
        <div>
            <div class="text-2xl font-bold font-mono text-cream">{{ $stats['total_reservations'] }}</div>
            <p class="text-[11px] text-muted mt-1 flex items-center gap-1">
                @if ($stats['pending_reservas'] > 0)
                <span class="text-amber font-semibold">{{ $stats['pending_reservas'] }}</span> pendientes de confirmar
                @else
                <span class="text-muted">Todas al día</span>
                @endif
            </p>
        </div>
    </div>

    {{-- KPI 4: Mensajes de Clientes --}}
    <div class="bg-surface border border-border rounded-xl p-5 relative overflow-hidden flex flex-col justify-between hover:border-amber/40 transition">
        <div class="flex items-center justify-between text-muted mb-3">
            <span class="text-[11px] font-semibold uppercase tracking-wider">Bandeja de Contacto</span>
            <div class="w-8 h-8 rounded-lg bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-xs">
                <i class="fa-solid fa-inbox"></i>
            </div>
        </div>
        <div>
            <div class="text-2xl font-bold font-mono text-cream">{{ $stats['unread_messages'] }}</div>
            <p class="text-[11px] text-muted mt-1">
                <span class="text-purple-400 font-medium">{{ $stats['unread_messages'] }}</span> consultas sin leer
            </p>
        </div>
    </div>
</div>

{{-- MÓDULOS DE CONTROL DE LA WEB --}}
<div class="space-y-3">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-cream tracking-tight">Módulos de Control y Administración</h2>
            <p class="text-xs text-muted">Accede a las herramientas de edición y supervisión del sitio web.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {{-- Módulo 1: Productos / Menú --}}
        <a href="{{ route('admin.products.index') }}" class="group bg-surface hover:bg-card border border-border hover:border-amber/50 rounded-xl p-5 transition flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-lg bg-amber/10 border border-amber/30 text-amber flex items-center justify-center text-base group-hover:bg-amber group-hover:text-white transition">
                    <i class="fa-solid fa-mug-saucer"></i>
                </div>
                <span class="text-[11px] font-medium text-muted group-hover:text-amber transition flex items-center gap-1">
                    Gestionar <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-cream group-hover:text-amber transition">Gestión de Catálogo y Menú</h3>
                <p class="text-xs text-muted mt-1 leading-relaxed">
                    Administra productos, precios, ingredientes, stock y visibilidad en la carta pública.
                </p>
            </div>
            <div class="pt-3 border-t border-border/60 flex items-center justify-between text-[11px] text-muted font-mono">
                <span>{{ $stats['total_products'] }} productos activos</span>
                <span class="text-emerald-400">En línea</span>
            </div>
        </a>

        {{-- Módulo 2: Categorías --}}
        <a href="{{ route('admin.categories.index') }}" class="group bg-surface hover:bg-card border border-border hover:border-amber/50 rounded-xl p-5 transition flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-lg bg-amber/10 border border-amber/30 text-amber flex items-center justify-center text-base group-hover:bg-amber group-hover:text-white transition">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <span class="text-[11px] font-medium text-muted group-hover:text-amber transition flex items-center gap-1">
                    Gestionar <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-cream group-hover:text-amber transition">Familias y Categorías</h3>
                <p class="text-xs text-muted mt-1 leading-relaxed">
                    Crea y organiza las categorías de café caliente, bebidas frías, repostería y alimentos.
                </p>
            </div>
            <div class="pt-3 border-t border-border/60 flex items-center justify-between text-[11px] text-muted font-mono">
                <span>{{ $stats['total_categories'] }} categorías</span>
                <span class="text-amber">Estructura carta</span>
            </div>
        </a>

        {{-- Módulo 3: Reservas / Mesas --}}
        <a href="{{ route('admin.reservations.index') }}" class="group bg-surface hover:bg-card border border-border hover:border-amber/50 rounded-xl p-5 transition flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-lg bg-amber/10 border border-amber/30 text-amber flex items-center justify-center text-base group-hover:bg-amber group-hover:text-white transition">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <span class="text-[11px] font-medium text-muted group-hover:text-amber transition flex items-center gap-1">
                    Gestionar <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-cream group-hover:text-amber transition">Control de Reservas y Mesas</h3>
                <p class="text-xs text-muted mt-1 leading-relaxed">
                    Supervisa las solicitudes recibidas desde el plano 3D, confirma o cancela reservas.
                </p>
            </div>
            <div class="pt-3 border-t border-border/60 flex items-center justify-between text-[11px] text-muted font-mono">
                <span>{{ $stats['total_reservations'] }} registradas</span>
                <span class="text-sky-400">Plano interactivo</span>
            </div>
        </a>

        {{-- Módulo 4: Pedidos y Ventas --}}
        <a href="{{ route('admin.orders.index') }}" class="group bg-surface hover:bg-card border border-border hover:border-amber/50 rounded-xl p-5 transition flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-lg bg-amber/10 border border-amber/30 text-amber flex items-center justify-center text-base group-hover:bg-amber group-hover:text-white transition">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <span class="text-[11px] font-medium text-muted group-hover:text-amber transition flex items-center gap-1">
                    Gestionar <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-cream group-hover:text-amber transition">Órdenes y Ventas (POS)</h3>
                <p class="text-xs text-muted mt-1 leading-relaxed">
                    Control de pedidos, preparación de baristas, despacho y flujo de caja de la cafetería.
                </p>
            </div>
            <div class="pt-3 border-t border-border/60 flex items-center justify-between text-[11px] text-muted font-mono">
                <span>{{ $stats['total_orders'] }} transacciones</span>
                <span class="text-emerald-400">Seguimiento en vivo</span>
            </div>
        </a>

        {{-- Módulo 5: Mensajes de Contacto --}}
        <a href="{{ route('admin.messages.index') }}" class="group bg-surface hover:bg-card border border-border hover:border-amber/50 rounded-xl p-5 transition flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-lg bg-amber/10 border border-amber/30 text-amber flex items-center justify-center text-base group-hover:bg-amber group-hover:text-white transition">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <span class="text-[11px] font-medium text-muted group-hover:text-amber transition flex items-center gap-1">
                    Gestionar <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-cream group-hover:text-amber transition">Bandeja de Consultas</h3>
                <p class="text-xs text-muted mt-1 leading-relaxed">
                    Lee y atiende solicitudes de eventos, dudas sobre granos y contacto corporativo.
                </p>
            </div>
            <div class="pt-3 border-t border-border/60 flex items-center justify-between text-[11px] text-muted font-mono">
                <span>{{ $stats['unread_messages'] }} pendientes</span>
                <span class="text-purple-400">Canal directo</span>
            </div>
        </a>

        {{-- Módulo 6: Usuarios y Accesos --}}
        <a href="{{ route('admin.users.index') }}" class="group bg-surface hover:bg-card border border-border hover:border-amber/50 rounded-xl p-5 transition flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-lg bg-amber/10 border border-amber/30 text-amber flex items-center justify-center text-base group-hover:bg-amber group-hover:text-white transition">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <span class="text-[11px] font-medium text-muted group-hover:text-amber transition flex items-center gap-1">
                    Gestionar <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-cream group-hover:text-amber transition">Usuarios y Roles</h3>
                <p class="text-xs text-muted mt-1 leading-relaxed">
                    Administra cuentas de clientes, permisos de baristas y credenciales de acceso administrativo.
                </p>
            </div>
            <div class="pt-3 border-t border-border/60 flex items-center justify-between text-[11px] text-muted font-mono">
                <span>{{ $stats['total_users'] }} cuentas</span>
                <span class="text-muted">Roles & seguridad</span>
            </div>
        </a>

        {{-- Módulo 7: Galería Fotográfica --}}
        <a href="{{ route('admin.gallery.index') }}" class="group bg-surface hover:bg-card border border-border hover:border-rose-500/50 rounded-xl p-5 transition flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-lg bg-rose-500/10 border border-rose-500/30 text-rose-400 flex items-center justify-center text-base group-hover:bg-rose-500 group-hover:text-white transition">
                    <i class="fa-solid fa-images"></i>
                </div>
                <span class="text-[11px] font-medium text-muted group-hover:text-rose-400 transition flex items-center gap-1">
                    Gestionar <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-cream group-hover:text-rose-400 transition">Galería Fotográfica</h3>
                <p class="text-xs text-muted mt-1 leading-relaxed">
                    Administra las fotos del local, ambiente y productos que se muestran en la galería pública del sitio.
                </p>
            </div>
            <div class="pt-3 border-t border-border/60 flex items-center justify-between text-[11px] text-muted font-mono">
                <span>Contenido visual</span>
                <span class="text-rose-400">Galería web</span>
            </div>
        </a>

        {{-- Módulo 8: Mesas 3D y Plano del Local --}}
        <a href="{{ route('admin.tables.index') }}" class="group bg-surface hover:bg-card border border-border hover:border-sky-500/50 rounded-xl p-5 transition flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-lg bg-sky-500/10 border border-sky-500/30 text-sky-400 flex items-center justify-center text-base group-hover:bg-sky-500 group-hover:text-white transition">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <span class="text-[11px] font-medium text-muted group-hover:text-sky-400 transition flex items-center gap-1">
                    Gestionar <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-cream group-hover:text-sky-400 transition">Mesas 3D y Plano del Local</h3>
                <p class="text-xs text-muted mt-1 leading-relaxed">
                    Controla la disponibilidad de cada mesa y zona en el plano interactivo 3D visible al público.
                </p>
            </div>
            <div class="pt-3 border-t border-border/60 flex items-center justify-between text-[11px] text-muted font-mono">
                <span>{{ $stats['total_reservations'] }} reservas activas</span>
                <span class="text-sky-400">Plano 3D</span>
            </div>
        </a>

        {{-- Módulo 9: Ajustes Generales del Sitio --}}
        <a href="{{ route('admin.settings.index') }}" class="group bg-surface hover:bg-card border border-border hover:border-slate-400/50 rounded-xl p-5 transition flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-lg bg-slate-500/10 border border-slate-500/30 text-slate-400 flex items-center justify-center text-base group-hover:bg-slate-600 group-hover:text-white transition">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <span class="text-[11px] font-medium text-muted group-hover:text-slate-300 transition flex items-center gap-1">
                    Configurar <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-cream group-hover:text-slate-300 transition">Ajustes Generales del Sitio</h3>
                <p class="text-xs text-muted mt-1 leading-relaxed">
                    Edita nombre del negocio, slogan, horarios, datos de contacto y redes sociales del sitio.
                </p>
            </div>
            <div class="pt-3 border-t border-border/60 flex items-center justify-between text-[11px] text-muted font-mono">
                <span>Identidad corporativa</span>
                <span class="text-slate-400">Config. global</span>
            </div>
        </a>
    </div>
</div>

{{-- DOS COLUMNAS DE ACTIVIDAD RECIENTE --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-2">

    {{-- Columna 1: Últimos Pedidos --}}
    <div class="bg-surface border border-border rounded-xl p-5 space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                <h3 class="text-sm font-semibold text-cream">Últimas Órdenes de Venta</h3>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-amber hover:underline font-medium">Ver todas</a>
        </div>

        <div class="divide-y divide-border/60">
            @forelse ($recentOrders as $ord)
            <div class="py-3 flex items-center justify-between text-xs">
                <div class="space-y-0.5">
                    <div class="flex items-center gap-2">
                        <span class="font-mono font-medium text-cream">{{ $ord->order_number }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded font-mono uppercase {{ $ord->status === 'completed' ? 'bg-emerald-500/15 text-emerald-400' : ($ord->status === 'cancelled' ? 'bg-red-500/15 text-red-400' : 'bg-amber/15 text-amber') }}">
                            {{ $ord->status }}
                        </span>
                    </div>
                    <p class="text-muted text-[11px]">
                        {{ $ord->customer_name ?? 'Cliente POS' }} · {{ $ord->created_at ? $ord->created_at->diffForHumans() : 'Hoy' }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="font-mono font-bold text-cream">S/ {{ number_format($ord->total, 2) }}</div>
                    <span class="text-[10px] text-muted uppercase">{{ $ord->channel }}</span>
                </div>
            </div>
            @empty
            <div class="py-6 text-center text-xs text-muted">No hay órdenes recientes registradas.</div>
            @endforelse
        </div>
    </div>

    {{-- Columna 2: Próximas Reservas --}}
    <div class="bg-surface border border-border rounded-xl p-5 space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full bg-sky-400"></div>
                <h3 class="text-sm font-semibold text-cream">Próximas Reservas Agendadas</h3>
            </div>
            <a href="{{ route('admin.reservations.index') }}" class="text-xs text-amber hover:underline font-medium">Ver todas</a>
        </div>

        <div class="divide-y divide-border/60">
            @forelse ($upcomingReservations as $res)
            <div class="py-3 flex items-center justify-between text-xs">
                <div class="space-y-0.5">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-cream">{{ $res->nombre }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded font-mono uppercase {{ $res->status === 'confirmed' ? 'bg-emerald-500/15 text-emerald-400' : ($res->status === 'cancelled' ? 'bg-red-500/15 text-red-400' : 'bg-amber/15 text-amber') }}">
                            {{ $res->status }}
                        </span>
                    </div>
                    <p class="text-muted text-[11px]">
                        Mesa <span class="text-cream font-mono">{{ $res->mesa_id ?? 'General' }}</span> ({{ $res->zona ?? 'Salón' }}) · {{ $res->personas }} personas
                    </p>
                </div>
                <div class="text-right font-mono text-[11px]">
                    <div class="text-amber font-medium">{{ \Carbon\Carbon::parse($res->fecha)->format('d/m/Y') }}</div>
                    <div class="text-muted">{{ $res->hora ?? '10:00' }}</div>
                </div>
            </div>
            @empty
            <div class="py-6 text-center text-xs text-muted">No hay reservas programadas próximamente.</div>
            @endforelse
        </div>
    </div>

</div>

@endsection
