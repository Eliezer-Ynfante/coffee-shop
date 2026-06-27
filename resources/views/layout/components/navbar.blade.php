<!-- ================================================================
     NAVBAR
     ================================================================ -->
<nav id="navbar" class="fixed top-0 left-0 w-full z-50 bg-transparent" aria-label="Navegación principal">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 flex items-center justify-between h-16">

        <!-- Logo -->
        <a href="/" class="flex items-center gap-2.5 shrink-0" aria-label="Inicio">
            <span class="w-9 h-9 rounded-full border border-amber/60 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-mug-hot text-amber text-sm" aria-hidden="true"></i>
            </span>
            <span class="font-display text-lg font-semibold text-cream tracking-wide">
                {{ config('cafe.nombre') ?? 'Raíz & Grano' }}
            </span>
        </a>

        <!-- Links desktop -->
        <div class="hidden md:flex items-center gap-7">
            <a href="/nosotros" class="nav-link">Sobre Nosotros</a>
            <a href="/carta"    class="nav-link">Menú</a>
            <a href="/reserva"  class="nav-link">Reserva</a>
            <a href="/galeria"  class="nav-link">Galería</a>
            <a href="/contacto" class="nav-link">Contacto</a>
        </div>

        <!-- Acciones desktop -->
        <div class="hidden md:flex items-center gap-4">
            <a href="/login" class="nav-link">
                <i class="fa-regular fa-circle-user mr-1.5" aria-hidden="true"></i>Login
            </a>
            <a href="/reserva" class="btn-amber py-2 px-5 text-xs">Reservar mesa</a>
        </div>

        <!-- Hamburger -->
        <button id="ham-btn" class="md:hidden text-cream p-2" aria-label="Abrir menú" aria-expanded="false">
            <i id="ham-open"  class="fa-solid fa-bars   text-lg" aria-hidden="true"></i>
            <i id="ham-close" class="fa-solid fa-xmark  text-lg hidden" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Menú mobile -->
    <div id="mob-menu" class="hidden md:hidden bg-ink/97 border-t border-border px-6 py-5 space-y-3">
        <a href="/nosotros" class="block nav-link py-1.5">Sobre Nosotros</a>
        <a href="/carta"    class="block nav-link py-1.5">Menú</a>
        <a href="/reserva"  class="block nav-link py-1.5">Reserva</a>
        <a href="/galeria"  class="block nav-link py-1.5">Galería</a>
        <a href="/contacto" class="block nav-link py-1.5">Contacto</a>
        <div class="flex gap-3 pt-2">
            <a href="/login" class="btn-ghost flex-1 text-center text-xs">Login</a>
            <a href="/admin" class="btn-amber flex-1 text-center text-xs">Admin</a>
        </div>
    </div>
</nav>