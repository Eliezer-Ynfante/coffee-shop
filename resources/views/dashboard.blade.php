@extends('layout.layout')

@section('title', 'Panel de Control')

@section('content')

<section class="min-h-screen bg-ink py-28 px-6 relative grain overflow-hidden" aria-label="Panel de Control">
    {{-- Glow decorativo de fondo --}}
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber/8 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-amber/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto relative z-10 space-y-10">

        {{-- Mensaje de éxito / estatus --}}
        @if (session('status'))
        <div class="p-4 rounded-lg bg-amber/15 border border-amber/40 text-cream text-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-amber text-lg" aria-hidden="true"></i>
                <span>{{ session('status') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-muted hover:text-cream text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        @endif

        {{-- Cabecera del Panel --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-8 border-b border-border">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="amber-tag">Sesión Activa</span>
                </div>
                <h1 class="font-display text-4xl sm:text-5xl font-bold text-cream">
                    Panel de <em class="text-amber not-italic">Administración</em>
                </h1>
                <p class="text-muted text-sm mt-1">
                    Bienvenido, <strong class="text-cream">{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('welcome') }}" class="btn-ghost text-xs py-2.5 px-4">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Volver a la Tienda
                </a>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-amber text-xs py-2.5 px-4 bg-red-600/80 hover:bg-red-600 border-none">
                        <i class="fa-solid fa-right-from-bracket mr-1.5"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>

        {{-- Métricas de la Base de Datos --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Tarjeta 1: Usuarios --}}
            <div class="bg-card border border-border rounded-xl p-6 relative overflow-hidden shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs uppercase font-medium tracking-wider text-muted">Usuarios</span>
                    <div class="w-10 h-10 rounded-lg bg-amber/10 border border-amber/30 text-amber flex items-center justify-center">
                        <i class="fa-solid fa-users text-sm"></i>
                    </div>
                </div>
                <div class="font-display text-3xl font-bold text-cream mb-1">{{ $stats['total_users'] }}</div>
                <p class="text-muted text-xs">Cuentas registradas en el sistema</p>
            </div>

            {{-- Tarjeta 2: Productos --}}
            <div class="bg-card border border-border rounded-xl p-6 relative overflow-hidden shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs uppercase font-medium tracking-wider text-muted">Productos</span>
                    <div class="w-10 h-10 rounded-lg bg-amber/10 border border-amber/30 text-amber flex items-center justify-center">
                        <i class="fa-solid fa-mug-hot text-sm"></i>
                    </div>
                </div>
                <div class="font-display text-3xl font-bold text-cream mb-1">{{ $stats['total_products'] }}</div>
                <p class="text-muted text-xs">Registrados en tabla `products`</p>
            </div>

            {{-- Tarjeta 3: Categorías --}}
            <div class="bg-card border border-border rounded-xl p-6 relative overflow-hidden shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs uppercase font-medium tracking-wider text-muted">Categorías</span>
                    <div class="w-10 h-10 rounded-lg bg-amber/10 border border-amber/30 text-amber flex items-center justify-center">
                        <i class="fa-solid fa-layer-group text-sm"></i>
                    </div>
                </div>
                <div class="font-display text-3xl font-bold text-cream mb-1">{{ $stats['total_categories'] }}</div>
                <p class="text-muted text-xs">Familias de bebidas y alimentos</p>
            </div>

            {{-- Tarjeta 4: Órdenes / Pedidos --}}
            <div class="bg-card border border-border rounded-xl p-6 relative overflow-hidden shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs uppercase font-medium tracking-wider text-muted">Órdenes</span>
                    <div class="w-10 h-10 rounded-lg bg-amber/10 border border-amber/30 text-amber flex items-center justify-center">
                        <i class="fa-solid fa-receipt text-sm"></i>
                    </div>
                </div>
                <div class="font-display text-3xl font-bold text-cream mb-1">{{ $stats['total_orders'] }}</div>
                <p class="text-muted text-xs">Ventas registradas en POS y tienda</p>
            </div>
        </div>

        {{-- Dos Columnas: Perfil y Accesos Directos --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Columna Izquierda: Información de Perfil (5 columnas) --}}
            <div class="lg:col-span-5 bg-card border border-border rounded-xl p-7 shadow-xl space-y-6">
                <div class="flex items-center gap-4 pb-6 border-b border-border">
                    <div class="w-16 h-16 rounded-full bg-amber/20 border-2 border-amber/60 flex items-center justify-center text-amber text-2xl font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="font-display text-2xl font-bold text-cream">{{ Auth::user()->name }}</h2>
                        <span class="badge text-[10px] bg-amber/15 text-amber border-amber/30">Administrador</span>
                    </div>
                </div>

                <div class="space-y-4 text-xs">
                    <div class="flex items-center justify-between py-2 border-b border-border/50">
                        <span class="text-muted">Correo electrónico:</span>
                        <span class="text-cream font-medium">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-border/50">
                        <span class="text-muted">ID de Usuario:</span>
                        <span class="font-mono text-amber">#{{ Auth::user()->id }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-border/50">
                        <span class="text-muted">Fecha de creación:</span>
                        <span class="text-cream">{{ Auth::user()->created_at ? Auth::user()->created_at->format('d/m/Y H:i') : 'Reciente' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-border/50">
                        <span class="text-muted">Motor de Base de Datos:</span>
                        <span class="text-emerald-400 font-mono">MySQL 8.4 (Docker)</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-muted">Base de Datos:</span>
                        <span class="text-cream font-mono">coffee_shop</span>
                    </div>
                </div>
            </div>

            {{-- Columna Derecha: Módulos y Accesos Directos (7 columnas) --}}
            <div class="lg:col-span-7 bg-card border border-border rounded-xl p-7 shadow-xl space-y-6">
                <div>
                    <h3 class="font-display text-2xl font-bold text-cream mb-1">Módulos del Sistema</h3>
                    <p class="text-muted text-xs">Accesos rápidos para operar y supervisar la cafetería.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Módulo Carta --}}
                    <a href="{{ route('carta') }}" class="p-4 rounded-lg bg-surface border border-border hover:border-amber/60 transition group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-9 h-9 rounded-md bg-amber/10 border border-amber/30 text-amber flex items-center justify-center text-sm group-hover:bg-amber group-hover:text-white transition">
                                <i class="fa-solid fa-book-open"></i>
                            </span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-muted group-hover:text-amber transition"></i>
                        </div>
                        <div>
                            <h4 class="text-cream font-semibold text-sm mb-1 group-hover:text-amber transition">Carta y Menú</h4>
                            <p class="text-muted text-xs leading-relaxed">Visualizar los productos, precios y especialidades de la cafetería.</p>
                        </div>
                    </a>

                    {{-- Módulo Reservas --}}
                    <a href="{{ route('reserva') }}" class="p-4 rounded-lg bg-surface border border-border hover:border-amber/60 transition group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-9 h-9 rounded-md bg-amber/10 border border-amber/30 text-amber flex items-center justify-center text-sm group-hover:bg-amber group-hover:text-white transition">
                                <i class="fa-solid fa-cube"></i>
                            </span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-muted group-hover:text-amber transition"></i>
                        </div>
                        <div>
                            <h4 class="text-cream font-semibold text-sm mb-1 group-hover:text-amber transition">Maqueta 3D y Reservas</h4>
                            <p class="text-muted text-xs leading-relaxed">Supervisar la distribución de mesas, zonas y agendar clientes.</p>
                        </div>
                    </a>

                    {{-- Módulo Galería --}}
                    <a href="{{ route('galeria') }}" class="p-4 rounded-lg bg-surface border border-border hover:border-amber/60 transition group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-9 h-9 rounded-md bg-amber/10 border border-amber/30 text-amber flex items-center justify-center text-sm group-hover:bg-amber group-hover:text-white transition">
                                <i class="fa-solid fa-images"></i>
                            </span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-muted group-hover:text-amber transition"></i>
                        </div>
                        <div>
                            <h4 class="text-cream font-semibold text-sm mb-1 group-hover:text-amber transition">Galería de Fotos</h4>
                            <p class="text-muted text-xs leading-relaxed">Revisar el catálogo fotográfico de eventos, local y barismo.</p>
                        </div>
                    </a>

                    {{-- Módulo Mensajes de Contacto --}}
                    <a href="{{ route('contacto') }}" class="p-4 rounded-lg bg-surface border border-border hover:border-amber/60 transition group flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-9 h-9 rounded-md bg-amber/10 border border-amber/30 text-amber flex items-center justify-center text-sm group-hover:bg-amber group-hover:text-white transition">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-muted group-hover:text-amber transition"></i>
                        </div>
                        <div>
                            <h4 class="text-cream font-semibold text-sm mb-1 group-hover:text-amber transition">Atención & Contacto</h4>
                            <p class="text-muted text-xs leading-relaxed">Canales de soporte, dudas frecuentes y ubicación de la tienda.</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>

@endsection
