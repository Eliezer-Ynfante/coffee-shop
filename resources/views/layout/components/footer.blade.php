<!-- ================================================================
     FOOTER
     ================================================================ -->
<footer class="bg-ink border-t border-border pt-14 pb-8 px-6" aria-label="Pie de página">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10 pb-10 border-b border-border">

            <!-- Marca + redes -->
            <div class="sm:col-span-2">
                <div class="flex items-center gap-2.5 mb-3">
                    <span class="w-9 h-9 rounded-full border border-amber/50 flex items-center justify-center">
                        <i class="fa-solid fa-mug-hot text-amber text-sm" aria-hidden="true"></i>
                    </span>
                    <span class="font-display text-lg font-semibold text-cream">
                        {{ config('cafe.nombre') ?? 'Raíz & Grano' }}
                    </span>
                </div>
                <p class="text-muted text-sm leading-relaxed max-w-xs mb-5">
                    Café de especialidad tostado con amor en Lima, directo del campo peruano a tu taza.
                </p>
                <x-social-links />
            </div>

            <!-- Navegación -->
            <div>
                <h4 class="text-cream text-[10px] font-semibold uppercase tracking-[.16em] mb-4">Navegación</h4>
                <ul class="space-y-2 text-sm text-muted">
                    <li><a href="{{ route('welcome') }}"  class="hover:text-amber transition">Inicio</a></li>
                    <li><a href="{{ route('nosotros') }}" class="hover:text-amber transition">Sobre Nosotros</a></li>
                    <li><a href="{{ route('carta') }}"    class="hover:text-amber transition">Menú</a></li>
                    <li><a href="{{ route('galeria') }}"  class="hover:text-amber transition">Galería</a></li>
                    <li><a href="{{ route('reserva') }}"  class="hover:text-amber transition">Reservas</a></li>
                    <li><a href="{{ route('contacto') }}" class="hover:text-amber transition">Contacto</a></li>
                </ul>
            </div>

            <!-- Contacto -->
            <div>
                <h4 class="text-cream text-[10px] font-semibold uppercase tracking-[.16em] mb-4">Contacto</h4>
                <ul class="space-y-3 text-sm text-muted">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-location-dot text-amber mt-0.5 shrink-0 text-xs" aria-hidden="true"></i>
                        {{ config('cafe.direccion') ?? 'Dirección' }}
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-phone text-amber shrink-0 text-xs" aria-hidden="true"></i>
                        <a href="tel:{{ preg_replace('/\s+/', '', config('cafe.telefono') ?? '') }}" class="hover:text-amber transition">
                            {{ config('cafe.telefono') ?? 'Teléfono' }}
                        </a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-regular fa-envelope text-amber shrink-0 text-xs" aria-hidden="true"></i>
                        <a href="mailto:{{ config('cafe.email') ?? '' }}" class="hover:text-amber transition break-all">
                            {{ config('cafe.email') ?? 'Email' }}
                        </a>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-regular fa-clock text-amber mt-0.5 shrink-0 text-xs" aria-hidden="true"></i>
                        <span class="leading-snug">{{ config('cafe.horario') ?? 'Horario' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-muted text-xs">
            <span>&copy; {{ date('Y') }} {{ config('cafe.nombre') ?? 'Cafetería' }}. Todos los derechos reservados.</span>
            <span class="flex items-center gap-1.5">
                <i class="fa-solid fa-mug-hot text-amber text-[10px]" aria-hidden="true"></i>
                Hecho con café en Lima, Perú
            </span>
        </div>
    </div>
</footer>