<!-- ================================================================
     NAVBAR
     ================================================================ -->
<nav id="navbar" class="fixed top-0 left-0 w-full z-50 bg-transparent" aria-label="Navegación principal">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 flex items-center justify-between h-16">

        <!-- Logo -->
        <a href="{{ route('welcome') }}" class="flex items-center gap-2.5 shrink-0" aria-label="Inicio">
            <span class="w-9 h-9 rounded-full border border-amber/60 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-mug-hot text-amber text-sm" aria-hidden="true"></i>
            </span>
            <span class="font-display text-lg font-semibold text-cream tracking-wide">
                {{ config('cafe.nombre') ?? 'Raíz & Grano' }}
            </span>
        </a>

        <!-- Links desktop -->
        <div class="hidden md:flex items-center gap-7">
            <a href="{{ route('carta') }}"    class="nav-link {{ request()->routeIs('carta') ? 'text-amber' : '' }}">Carta</a>
            <a href="{{ route('galeria') }}"  class="nav-link {{ request()->routeIs('galeria') ? 'text-amber' : '' }}">Galería</a>
            <a href="{{ route('nosotros') }}" class="nav-link {{ request()->routeIs('nosotros') ? 'text-amber' : '' }}">Sobre Nosotros</a>
            <a href="{{ route('contacto') }}" class="nav-link {{ request()->routeIs('contacto') ? 'text-amber' : '' }}">Contacto</a>
        </div>

        <!-- Acciones desktop -->
        <div class="hidden md:flex items-center gap-4">
            @auth
                @if (Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center gap-1.5 {{ request()->routeIs('admin.*') ? 'text-amber font-semibold' : '' }}" title="Panel de Administración">
                    <span class="w-7 h-7 rounded-full bg-amber/20 border border-amber/50 flex items-center justify-center text-amber text-xs">
                        <i class="fa-solid fa-user-shield"></i>
                    </span>
                    <span class="text-xs">Admin ({{ Auth::user()->name }})</span>
                </a>
                @else
                <a href="{{ route('customer.orders') }}" class="nav-link flex items-center gap-1.5 {{ request()->routeIs('customer.*') ? 'text-amber font-semibold' : '' }}" title="Mis Pedidos">
                    <span class="w-7 h-7 rounded-full bg-amber/20 border border-amber/50 flex items-center justify-center text-amber text-xs">
                        <i class="fa-solid fa-receipt"></i>
                    </span>
                    <span class="text-xs">Mis Pedidos</span>
                </a>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-muted hover:text-red-400 transition cursor-pointer p-1" title="Cerrar sesión">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            @else
            <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'text-amber' : '' }}">
                <i class="fa-regular fa-circle-user mr-1.5" aria-hidden="true"></i>Login
            </a>
            @endauth

            <a href="{{ route('reserva') }}" class="btn-amber py-2 px-5 text-xs">Reservar mesa</a>
        </div>

        <!-- Hamburger -->
        <button id="ham-btn" class="md:hidden text-cream p-2" aria-label="Abrir menú" aria-expanded="false">
            <i id="ham-open"  class="fa-solid fa-bars   text-lg" aria-hidden="true"></i>
            <i id="ham-close" class="fa-solid fa-xmark  text-lg hidden" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Menú mobile -->
    <div id="mob-menu" class="hidden md:hidden bg-ink/97 border-t border-border px-6 py-5 space-y-3">
        <a href="{{ route('carta') }}"    class="block nav-link py-1.5 {{ request()->routeIs('carta') ? 'text-amber' : '' }}">Carta</a>
        <a href="{{ route('galeria') }}"  class="block nav-link py-1.5 {{ request()->routeIs('galeria') ? 'text-amber' : '' }}">Galería</a>
        <a href="{{ route('nosotros') }}" class="block nav-link py-1.5 {{ request()->routeIs('nosotros') ? 'text-amber' : '' }}">Sobre Nosotros</a>
        <a href="{{ route('contacto') }}" class="block nav-link py-1.5 {{ request()->routeIs('contacto') ? 'text-amber' : '' }}">Contacto</a>
        <div class="pt-2 space-y-2">
            <a href="{{ route('reserva') }}" class="btn-amber block text-center text-xs py-2">Reservar mesa</a>
            @auth
                @if (Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn-ghost block text-center text-xs">
                    <i class="fa-solid fa-user-shield mr-1"></i> Panel Admin
                </a>
                @else
                <a href="{{ route('customer.orders') }}" class="btn-ghost block text-center text-xs">
                    <i class="fa-solid fa-receipt mr-1"></i> Mis Pedidos
                </a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-center text-xs py-2 text-red-400 hover:text-red-300">Cerrar Sesión</button>
                </form>
            @else
            <a href="{{ route('login') }}" class="btn-ghost block text-center text-xs">Login</a>
            @endauth
        </div>
    </div>
</nav>