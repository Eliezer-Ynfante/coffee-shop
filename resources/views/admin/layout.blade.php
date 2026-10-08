<!DOCTYPE html>
<html lang="es" class="h-full bg-[#0A0704]">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Control') — Raíz & Grano Admin</title>

    <!-- Google Fonts: Inter (Tipografía empresarial, limpia y neutra) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        /* Custom scrollbar para panel de administración */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #0A0704;
        }
        ::-webkit-scrollbar-thumb {
            background: #2C1E12;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #C8783A;
        }
    </style>
</head>

<body class="min-h-full bg-ink text-cream font-sans antialiased flex flex-col">

    {{-- BARRA SUPERIOR EMPRESARIAL / TOP NAVIGATION --}}
    <header class="sticky top-0 z-50 bg-dark/95 backdrop-blur border-b border-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between min-h-14 py-2">
                
                {{-- Marca y Badge de Entorno --}}
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex flex-col group">
                        <div class="flex flex-col">
                            <span class="font-bold text-sm tracking-tight text-cream">Raíz & Grano</span>
                            <span class="text-[10px] font-mono tracking-wider uppercase text-amber">Backoffice</span>
                        </div>
                    </a>
                </div>

                {{-- Acciones Derecha: Ver Tienda, Usuario y Salir --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('welcome') }}" target="_blank"
                       class="hidden sm:inline-flex items-center gap-1.5 text-xs text-muted hover:text-cream px-2.5 py-1.5 rounded-md border border-border hover:border-amber/40 transition">
                        <span>Ir a la Tienda</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>

                    <div class="h-5 w-px bg-border hidden sm:block"></div>

                    {{-- Identidad del Usuario --}}
                    <div class="hidden sm:flex flex-col text-left pl-1">
                        <span class="text-xs font-medium text-cream leading-tight">{{ Auth::user()->name ?? 'Admin' }}</span>
                        <span class="text-[10px] text-amber leading-tight">Superadministrador</span>
                    </div>

                    {{-- Botón Cerrar Sesión --}}
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                title="Cerrar Sesión"
                                class="p-2 text-muted hover:text-red-400 hover:bg-red-500/10 rounded-md border border-transparent hover:border-red-500/20 transition text-xs">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Enlaces de Módulos Administrativos (Desktop) --}}
            <nav class="hidden xl:flex items-center justify-between gap-1 border-t border-border/60 py-2 text-xs font-medium">
                <a href="{{ route('admin.dashboard') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber/15 text-amber border border-amber/30' : 'text-cream/70 hover:text-cream hover:bg-surface' }}">
                    <i class="fa-solid fa-chart-line mr-1.5"></i>Resumen
                </a>
                <a href="{{ route('admin.products.index') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md transition {{ request()->routeIs('admin.products.*') ? 'bg-amber/15 text-amber border border-amber/30' : 'text-cream/70 hover:text-cream hover:bg-surface' }}">
                    <i class="fa-solid fa-mug-saucer mr-1.5"></i>Productos
                </a>
                <a href="{{ route('admin.categories.index') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md transition {{ request()->routeIs('admin.categories.*') ? 'bg-amber/15 text-amber border border-amber/30' : 'text-cream/70 hover:text-cream hover:bg-surface' }}">
                    <i class="fa-solid fa-layer-group mr-1.5"></i>Categorías
                </a>
                <a href="{{ route('admin.orders.index') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md transition {{ request()->routeIs('admin.orders.*') ? 'bg-amber/15 text-amber border border-amber/30' : 'text-cream/70 hover:text-cream hover:bg-surface' }}">
                    <i class="fa-solid fa-receipt mr-1.5"></i>Órdenes
                </a>
                <a href="{{ route('admin.pos.index') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md transition {{ request()->routeIs('admin.pos.*') ? 'bg-amber/15 text-amber border border-amber/30' : 'text-cream/70 hover:text-cream hover:bg-surface' }}">
                    <i class="fa-solid fa-cash-register mr-1.5"></i>POS
                </a>
                <a href="{{ route('admin.barista.index') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md transition {{ request()->routeIs('admin.barista.*') ? 'bg-amber/15 text-amber border border-amber/30' : 'text-cream/70 hover:text-cream hover:bg-surface' }}">
                    <i class="fa-solid fa-kitchen-set mr-1.5"></i>Barra
                </a>
                <a href="{{ route('admin.reservations.index') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md transition {{ request()->routeIs('admin.reservations.*') ? 'bg-amber/15 text-amber border border-amber/30' : 'text-cream/70 hover:text-cream hover:bg-surface' }}">
                    <i class="fa-solid fa-calendar-check mr-1.5"></i>Reservas
                </a>
                <a href="{{ route('admin.tables.index') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md transition {{ request()->routeIs('admin.tables.*') ? 'bg-amber/15 text-amber border border-amber/30' : 'text-cream/70 hover:text-cream hover:bg-surface' }}">
                    <i class="fa-solid fa-cube mr-1.5"></i>Mesas 3D
                </a>
                <a href="{{ route('admin.gallery.index') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md transition {{ request()->routeIs('admin.gallery.*') ? 'bg-amber/15 text-amber border border-amber/30' : 'text-cream/70 hover:text-cream hover:bg-surface' }}">
                    <i class="fa-solid fa-images mr-1.5"></i>Galería
                </a>
                <a href="{{ route('admin.messages.index') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md transition {{ request()->routeIs('admin.messages.*') ? 'bg-amber/15 text-amber border border-amber/30' : 'text-cream/70 hover:text-cream hover:bg-surface' }}">
                    <i class="fa-solid fa-inbox mr-1.5"></i>Mensajes
                </a>
                <a href="{{ route('admin.users.index') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md transition {{ request()->routeIs('admin.users.*') ? 'bg-amber/15 text-amber border border-amber/30' : 'text-cream/70 hover:text-cream hover:bg-surface' }}">
                    <i class="fa-solid fa-users mr-1.5"></i>Usuarios
                </a>
                <a href="{{ route('admin.settings.index') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md transition {{ request()->routeIs('admin.settings.*') ? 'bg-amber/15 text-amber border border-amber/30' : 'text-cream/70 hover:text-cream hover:bg-surface' }}">
                    <i class="fa-solid fa-gear mr-1.5"></i>Ajustes
                </a>
            </nav>

            {{-- Sub-navegación móvil --}}
            <div class="flex xl:hidden overflow-x-auto py-2.5 gap-2 border-t border-border/60 text-xs no-scrollbar">
                <a href="{{ route('admin.dashboard') }}" class="px-2.5 py-1 rounded whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'bg-amber/20 text-amber' : 'text-cream/70' }}">Resumen</a>
                <a href="{{ route('admin.products.index') }}" class="px-2.5 py-1 rounded whitespace-nowrap {{ request()->routeIs('admin.products.*') ? 'bg-amber/20 text-amber' : 'text-cream/70' }}">Productos</a>
                <a href="{{ route('admin.categories.index') }}" class="px-2.5 py-1 rounded whitespace-nowrap {{ request()->routeIs('admin.categories.*') ? 'bg-amber/20 text-amber' : 'text-cream/70' }}">Categorías</a>
                <a href="{{ route('admin.reservations.index') }}" class="px-2.5 py-1 rounded whitespace-nowrap {{ request()->routeIs('admin.reservations.*') ? 'bg-amber/20 text-amber' : 'text-cream/70' }}">Reservas</a>
                <a href="{{ route('admin.tables.index') }}" class="px-2.5 py-1 rounded whitespace-nowrap {{ request()->routeIs('admin.tables.*') ? 'bg-amber/20 text-amber' : 'text-cream/70' }}">Mesas 3D</a>
                <a href="{{ route('admin.orders.index') }}" class="px-2.5 py-1 rounded whitespace-nowrap {{ request()->routeIs('admin.orders.*') ? 'bg-amber/20 text-amber' : 'text-cream/70' }}">Órdenes</a>
                <a href="{{ route('admin.pos.index') }}" class="px-2.5 py-1 rounded whitespace-nowrap {{ request()->routeIs('admin.pos.*') ? 'bg-amber/20 text-amber' : 'text-cream/70' }}">POS</a>
                <a href="{{ route('admin.barista.index') }}" class="px-2.5 py-1 rounded whitespace-nowrap {{ request()->routeIs('admin.barista.*') ? 'bg-amber/20 text-amber' : 'text-cream/70' }}">Barra</a>
                <a href="{{ route('admin.gallery.index') }}" class="px-2.5 py-1 rounded whitespace-nowrap {{ request()->routeIs('admin.gallery.*') ? 'bg-amber/20 text-amber' : 'text-cream/70' }}">Galería</a>
                <a href="{{ route('admin.messages.index') }}" class="px-2.5 py-1 rounded whitespace-nowrap {{ request()->routeIs('admin.messages.*') ? 'bg-amber/20 text-amber' : 'text-cream/70' }}">Mensajes</a>
                <a href="{{ route('admin.users.index') }}" class="px-2.5 py-1 rounded whitespace-nowrap {{ request()->routeIs('admin.users.*') ? 'bg-amber/20 text-amber' : 'text-cream/70' }}">Usuarios</a>
                <a href="{{ route('admin.settings.index') }}" class="px-2.5 py-1 rounded whitespace-nowrap {{ request()->routeIs('admin.settings.*') ? 'bg-amber/20 text-amber' : 'text-cream/70' }}">Ajustes</a>
            </div>
        </div>
    </header>

    {{-- ALERTAS GLOBALES --}}
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        @if (session('status'))
        <div class="p-3.5 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                <span class="font-medium">{{ session('status') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400/60 hover:text-emerald-300">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        @endif

        @if ($errors->any())
        <div class="p-3.5 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-red-400 text-sm"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-400/60 hover:text-red-300">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        @endif

        {{-- CONTENIDO DE CADA VISTA --}}
        @yield('content')

    </main>

    {{-- PIE DE PÁGINA MINIMALISTA --}}
    <footer class="border-t border-border/60 py-4 mt-auto bg-dark/40 text-[11px] text-muted">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                <span>Raíz & Grano · Sistema de Gestión y Control Administrativo</span>
            </div>
            <div class="flex items-center gap-4 font-mono text-[10px]">
                <span class="text-emerald-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Sistema en línea
                </span>
                <span>Laravel 13 & PHP 8.3</span>
            </div>
        </div>
    </footer>

    @yield('scripts')

</body>
</html>
