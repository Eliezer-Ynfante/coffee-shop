<section class="bg-dark grain relative overflow-hidden py-24 px-6 border-t border-border" aria-label="Contacto y reservas">
    <div class="absolute top-0 right-0 w-80 h-80 bg-amber/4 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute bottom-0 left-0 w-60 h-60 bg-amber/4 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-3xl mx-auto text-center relative z-10">
        <p class="amber-tag justify-center mb-5 reveal">Te esperamos</p>
        <h2 class="font-display text-5xl md:text-6xl font-bold text-cream leading-tight mb-5 reveal" style="transition-delay:.1s">
            ¿Listo para vivir<br>la <em class="text-amber not-italic">experiencia</em>?
        </h2>
        <p class="text-cream/45 text-sm md:text-base mb-10 leading-relaxed reveal" style="transition-delay:.2s">
            Visítanos, reserva tu mesa o pídenos por WhatsApp.<br>El mejor café de tu semana está a un clic.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-12 reveal" style="transition-delay:.28s">
            @if (!empty(config('cafe.redes')['whatsapp']))
            <a href="{{ config('cafe.redes')['whatsapp'] }}" target="_blank" rel="noopener" class="btn-amber">
                <i class="fa-brands fa-whatsapp mr-2 text-base" aria-hidden="true"></i>Pedir por WhatsApp
            </a>
            @endif
            <a href="{{ route('reserva') }}" class="btn-ghost">
                <i class="fa-regular fa-calendar-check mr-2" aria-hidden="true"></i>Reservar mesa
            </a>
        </div>

        {{-- Info en 3 bloques --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-10 border-t border-border reveal" style="transition-delay:.34s">
            <div class="flex flex-col items-center gap-2">
                <i class="fa-solid fa-location-dot text-amber text-lg" aria-hidden="true"></i>
                <p class="text-cream/45 text-xs text-center leading-relaxed">{{ config('cafe.direccion') }}</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <i class="fa-regular fa-clock text-amber text-lg" aria-hidden="true"></i>
                <p class="text-cream/45 text-xs text-center leading-relaxed">{{ config('cafe.horario') }}</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <i class="fa-regular fa-envelope text-amber text-lg" aria-hidden="true"></i>
                <a href="mailto:{{ config('cafe.email') }}" class="text-cream/45 text-xs hover:text-amber transition">
                    {{ config('cafe.email') }}
                </a>
            </div>
        </div>
    </div>
</section>
