@extends('layout.layout')

@section('content')

{{-- ================================================================
     HERO — Cabecera interior de la página Contacto
     ================================================================ --}}
<section
    class="contacto-hero relative flex items-center overflow-hidden grain"
    aria-label="Cabecera de contacto"
>
    {{-- Fondo negro base --}}
    <div class="absolute inset-0 bg-ink"></div>

    {{-- Imagen de fondo --}}
    <div
        class="absolute inset-0 contacto-hero-bg"
        style="background-image:url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400&q=85'); opacity:.45"
    ></div>

    {{-- Gradientes de composición --}}
    <div class="absolute inset-0 bg-linear-to-r from-ink via-ink/85 to-ink/35"></div>
    <div class="absolute inset-0 bg-linear-to-t from-ink/70 via-transparent to-ink/50"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 w-full pt-40 pb-28">
        <p class="amber-tag mb-5 reveal">Estamos para Atenderte</p>

        <h1
            class="font-display text-cream font-bold leading-tight mb-4 reveal max-w-3xl"
            style="font-size:clamp(2.6rem,6.5vw,4.8rem); transition-delay:.1s"
        >
            Ponte en <em class="text-amber not-italic">Contacto</em> con Nosotros
        </h1>

        <p
            class="text-cream/55 text-sm md:text-base leading-relaxed max-w-xl reveal"
            style="transition-delay:.2s"
        >
            Escríbenos para consultas, eventos privados, compras corporativas o simplemente para conversar sobre buen café.
        </p>
    </div>
</section>


{{-- ================================================================
     SECCIÓN PRINCIPAL — Canales de Contacto + Formulario
     ================================================================ --}}
<section class="bg-surface py-24 px-6 relative grain overflow-hidden" aria-label="Información de contacto y formulario">
    {{-- Glow decorativo --}}
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start relative z-10">

        {{-- Columna izquierda: Canales directos e información (5 columnas) --}}
        <div class="lg:col-span-5 space-y-6 reveal">
            <div>
                <p class="amber-tag mb-3">Atención Directa</p>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-cream mb-4">
                    ¿Prefieres escribirnos<br>directamente?
                </h2>
                <p class="text-muted text-sm leading-relaxed mb-6">
                    Elige el medio que te resulte más cómodo. Nuestro equipo de hospitalidad te responderá con gusto.
                </p>
            </div>

            {{-- Tarjeta: Ubicación y Horario --}}
            <div class="contact-info-card">
                <div class="contact-icon-box">
                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="font-display text-lg font-semibold text-cream mb-1">Nuestra Cafetería</h3>
                    <p class="text-cream/70 text-xs leading-relaxed mb-1">{{ config('cafe.direccion') }}</p>
                    <p class="text-muted text-xs flex items-center gap-1.5 mt-2">
                        <i class="fa-regular fa-clock text-amber" aria-hidden="true"></i>
                        <span>{{ config('cafe.horario') }}</span>
                    </p>
                </div>
            </div>

            {{-- Tarjeta: Teléfono y WhatsApp --}}
            <div class="contact-info-card">
                <div class="contact-icon-box">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-display text-lg font-semibold text-cream mb-1">WhatsApp & Teléfono</h3>
                    <p class="text-muted text-xs leading-relaxed mb-2">Pedidos rápidos, reservas y consultas inmediatas:</p>
                    <div class="flex flex-wrap gap-2.5">
                        @if (!empty(config('cafe.redes')['whatsapp']))
                        <a
                            href="{{ config('cafe.redes')['whatsapp'] }}"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex items-center gap-1.5 text-xs text-amber font-medium hover:underline"
                        >
                            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>Chatear por WhatsApp
                        </a>
                        @endif
                        <span class="text-border">|</span>
                        <a
                            href="tel:{{ preg_replace('/\s+/', '', config('cafe.telefono') ?? '') }}"
                            class="text-xs text-cream/70 hover:text-amber transition"
                        >
                            {{ config('cafe.telefono') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Tarjeta: Correo Electrónico --}}
            <div class="contact-info-card">
                <div class="contact-icon-box">
                    <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="font-display text-lg font-semibold text-cream mb-1">Correo Electrónico</h3>
                    <p class="text-muted text-xs leading-relaxed mb-1">Para temas corporativos, alianzas o eventos:</p>
                    <a
                        href="mailto:{{ config('cafe.email') }}"
                        class="text-amber text-xs font-medium hover:underline break-all"
                    >
                        {{ config('cafe.email') }}
                    </a>
                </div>
            </div>

            {{-- Redes Sociales --}}
            <div class="pt-4 border-t border-border">
                <p class="text-xs font-semibold uppercase tracking-wider text-cream/60 mb-3">Síguenos en redes sociales</p>
                <div class="flex gap-3">
                    @if (!empty(config('cafe.redes')['instagram']))
                    <a href="{{ config('cafe.redes')['instagram'] }}" target="_blank" rel="noopener" class="soc" aria-label="Instagram">
                        <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if (!empty(config('cafe.redes')['facebook']))
                    <a href="{{ config('cafe.redes')['facebook'] }}" target="_blank" rel="noopener" class="soc" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if (!empty(config('cafe.redes')['tiktok']))
                    <a href="{{ config('cafe.redes')['tiktok'] }}" target="_blank" rel="noopener" class="soc" aria-label="TikTok">
                        <i class="fa-brands fa-tiktok" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if (!empty(config('cafe.redes')['whatsapp']))
                    <a href="{{ config('cafe.redes')['whatsapp'] }}" target="_blank" rel="noopener" class="soc" aria-label="WhatsApp">
                        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Columna derecha: Formulario interactivo (7 columnas) --}}
        <div class="lg:col-span-7 reveal" style="transition-delay:.15s">
            <div class="bg-card border border-border rounded-xl p-8 sm:p-10 shadow-2xl relative">
                
                {{-- Encabezado del Formulario --}}
                <div class="mb-8">
                    <h3 class="font-display text-2xl sm:text-3xl font-semibold text-cream mb-1">
                        Envíanos un mensaje
                    </h3>
                    <p class="text-muted text-sm">Completa los campos y te responderemos a la brevedad.</p>
                </div>

                {{-- Formulario --}}
                <form id="contacto-form" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Nombre completo --}}
                        <div>
                            <label class="f-label" for="c-nombre">
                                <i class="fa-regular fa-user mr-1" aria-hidden="true"></i> Nombre completo *
                            </label>
                            <input
                                id="c-nombre"
                                type="text"
                                name="nombre"
                                class="f-input"
                                placeholder="Ej. Juan Pérez"
                                required
                            >
                        </div>

                        {{-- Correo electrónico --}}
                        <div>
                            <label class="f-label" for="c-email">
                                <i class="fa-regular fa-envelope mr-1" aria-hidden="true"></i> Correo electrónico *
                            </label>
                            <input
                                id="c-email"
                                type="email"
                                name="email"
                                class="f-input"
                                placeholder="tuemail@ejemplo.com"
                                required
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Teléfono / WhatsApp --}}
                        <div>
                            <label class="f-label" for="c-tel">
                                <i class="fa-solid fa-phone mr-1" aria-hidden="true"></i> Teléfono o WhatsApp
                            </label>
                            <input
                                id="c-tel"
                                type="tel"
                                name="telefono"
                                class="f-input"
                                placeholder="+51 999 000 000"
                            >
                        </div>

                        {{-- Motivo de contacto --}}
                        <div>
                            <label class="f-label" for="c-motivo">
                                <i class="fa-solid fa-list-check mr-1" aria-hidden="true"></i> Motivo del mensaje *
                            </label>
                            <select id="c-motivo" name="motivo" class="f-select" required>
                                <option value="" disabled selected>Selecciona una opción...</option>
                                @foreach (config('cafe.motivos_contacto') as $mot)
                                <option value="{{ $mot['id'] }}">{{ $mot['nombre'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Mensaje --}}
                    <div>
                        <label class="f-label" for="c-mensaje">
                            <i class="fa-regular fa-message mr-1" aria-hidden="true"></i> Tu mensaje o consulta *
                        </label>
                        <textarea
                            id="c-mensaje"
                            name="mensaje"
                            class="f-textarea"
                            placeholder="Cuéntanos cómo podemos ayudarte..."
                            required
                        ></textarea>
                    </div>

                    {{-- Botón Enviar --}}
                    <button type="submit" class="btn-amber w-full text-center py-3.5 text-sm font-medium">
                        <i class="fa-solid fa-paper-plane mr-2" aria-hidden="true"></i>Enviar Mensaje
                    </button>
                </form>

                {{-- Mensaje de Éxito (Oculto inicialmente) --}}
                <div id="contacto-success" class="hidden text-center py-10">
                    <div class="w-16 h-16 rounded-full border-2 border-amber/60 bg-amber/10 flex items-center justify-center text-amber text-2xl mx-auto mb-4">
                        <i class="fa-solid fa-check" aria-hidden="true"></i>
                    </div>
                    <h4 class="font-display text-2xl font-semibold text-cream mb-2">¡Mensaje Enviado con Éxito!</h4>
                    <p class="text-muted text-sm max-w-md mx-auto mb-6 leading-relaxed">
                        Muchas gracias por contactarnos. Hemos recibido tu mensaje y nuestro equipo se comunicará contigo lo antes posible.
                    </p>
                    <button
                        type="button"
                        onclick="document.getElementById('contacto-form').reset(); document.getElementById('contacto-form').classList.remove('hidden'); document.getElementById('contacto-success').classList.add('hidden');"
                        class="btn-ghost text-xs"
                    >
                        <i class="fa-solid fa-rotate-left mr-1.5" aria-hidden="true"></i>Enviar otro mensaje
                    </button>
                </div>

            </div>
        </div>

    </div>
</section>


{{-- ================================================================
     MAPA & CÓMO LLEGAR — Localización
     ================================================================ --}}
<section class="bg-ink py-20 px-6 border-t border-border" aria-label="Ubicación y cómo llegar">
    <div class="max-w-7xl mx-auto">

        <div class="text-center max-w-2xl mx-auto mb-12 reveal">
            <p class="amber-tag justify-center mb-3">Encuéntranos</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream">
                Visita nuestra cafetería
            </h2>
            <p class="text-muted text-sm mt-3 leading-relaxed">
                Estamos ubicados en una zona accesible y tranquila, ideal para desconectar y saborear café de origen.
            </p>
        </div>

        {{-- Tarjeta de mapa estilizado --}}
        <div class="rounded-2xl overflow-hidden border border-border bg-card relative shadow-2xl reveal">
            <div class="aspect-21/9 w-full min-h-72 relative bg-surface">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.578650275813!2d-80.6358!3d-5.1945!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMTEnNDAuMiJTIDgwwrAzOCcwOC45Ilc!5e0!3m2!1ses!2spe!4v1620000000000!5m2!1ses!2spe"
                    class="w-full h-full border-0 filter grayscale invert contrast-125 opacity-75 hover:opacity-100 transition-opacity"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Ubicación en Google Maps"
                ></iframe>
            </div>

            {{-- 3 Comodidades de llegada --}}
            <div class="p-6 bg-surface/90 border-t border-border grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full border border-amber/40 flex items-center justify-center text-amber text-sm shrink-0">
                        <i class="fa-solid fa-square-parking" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h4 class="text-cream text-xs font-semibold uppercase tracking-wide">Estacionamiento</h4>
                        <p class="text-muted text-xs">Espacios reservados para clientes.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full border border-amber/40 flex items-center justify-center text-amber text-sm shrink-0">
                        <i class="fa-solid fa-bicycle" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h4 class="text-cream text-xs font-semibold uppercase tracking-wide">Ciclovía & Bici-rack</h4>
                        <p class="text-muted text-xs">Aparcamiento seguro para ciclistas.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full border border-amber/40 flex items-center justify-center text-amber text-sm shrink-0">
                        <i class="fa-solid fa-paw" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h4 class="text-cream text-xs font-semibold uppercase tracking-wide">100% Pet Friendly</h4>
                        <p class="text-muted text-xs">Tus mascotas siempre son bienvenidas.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>


{{-- ================================================================
     PREGUNTAS FRECUENTES (FAQS) — Acordeón Interactivo
     ================================================================ --}}
<section class="bg-surface py-24 px-6 border-t border-border" aria-label="Preguntas frecuentes">
    <div class="max-w-4xl mx-auto">

        <div class="text-center mb-16 reveal">
            <p class="amber-tag justify-center mb-3">Dudas Comunes</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream">
                Preguntas frecuentes
            </h2>
            <p class="text-muted text-sm mt-3 leading-relaxed">
                Todo lo que necesitas saber antes de tu visita o sobre nuestros productos de café.
            </p>
        </div>

        {{-- Acordeón --}}
        <div class="space-y-4 reveal">
            @foreach (config('cafe.faqs') as $fi => $faq)
            <div class="faq-item">
                <button
                    type="button"
                    class="faq-trigger"
                    aria-expanded="false"
                >
                    <span class="flex items-center gap-3 font-medium text-sm sm:text-base">
                        <i class="{{ $faq['icono'] }} text-amber text-sm" aria-hidden="true"></i>
                        <span>{{ $faq['pregunta'] }}</span>
                    </span>
                    <i class="fa-solid fa-chevron-down faq-chevron" aria-hidden="true"></i>
                </button>
                <div class="faq-answer">
                    <p class="text-cream/70 text-xs sm:text-sm leading-relaxed border-t border-border/60 pt-3">
                        {{ $faq['respuesta'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ================================================================
     CTA FINAL — Reservar Mesa o Ver Carta
     ================================================================ --}}
<section class="bg-dark grain relative overflow-hidden py-24 px-6 border-t border-border" aria-label="Llamado a la acción final">
    {{-- Glow decorativo --}}
    <div class="absolute top-0 right-0 w-80 h-80 bg-amber/4 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute bottom-0 left-0 w-60 h-60 bg-amber/4 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-3xl mx-auto text-center relative z-10">
        <p class="amber-tag justify-center mb-5 reveal">Te esperamos</p>
        <h2 class="font-display text-5xl md:text-6xl font-bold text-cream leading-tight mb-5 reveal" style="transition-delay:.1s">
            El café perfecto te está <em class="text-amber not-italic">esperando</em>
        </h2>
        <p class="text-cream/50 text-sm md:text-base mb-10 leading-relaxed reveal" style="transition-delay:.2s">
            Asegura tu mesa para hoy o pide tus bebidas favoritas para llevar.
        </p>

        <div class="flex flex-wrap justify-center gap-4 reveal" style="transition-delay:.28s">
            <a href="/reserva" class="btn-amber">
                <i class="fa-regular fa-calendar-check mr-2" aria-hidden="true"></i>Reservar Mesa
            </a>
            <a href="/carta" class="btn-ghost">
                <i class="fa-solid fa-book-open-reader mr-2" aria-hidden="true"></i>Ver Nuestra Carta
            </a>
        </div>
    </div>
</section>

@endsection

