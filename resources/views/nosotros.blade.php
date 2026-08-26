@extends('layout.layout')

@section('content')

{{-- ================================================================
     HERO — Cabecera interior de la página Sobre Nosotros
     ================================================================ --}}
<section
    class="nosotros-hero relative flex items-center overflow-hidden grain"
    aria-label="Cabecera sobre nosotros"
>
    {{-- Fondo negro base --}}
    <div class="absolute inset-0 bg-ink"></div>

    {{-- Imagen de fondo --}}
    <div
        class="absolute inset-0 nosotros-hero-bg"
        style="background-image:url('https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=1400&q=85'); opacity:.5"
    ></div>

    {{-- Gradientes de composición --}}
    <div class="absolute inset-0 bg-linear-to-r from-ink via-ink/85 to-ink/35"></div>
    <div class="absolute inset-0 bg-linear-to-t from-ink/70 via-transparent to-ink/50"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 w-full pt-40 pb-28">
        <p class="amber-tag mb-5 reveal">Nuestra Historia & Esencia</p>

        <h1
            class="font-display text-cream font-bold leading-tight mb-4 reveal max-w-3xl"
            style="font-size:clamp(2.6rem,6.5vw,4.8rem); transition-delay:.1s"
        >
            Honramos la <em class="text-amber not-italic">raíz</em> y cuidamos cada <em class="text-amber not-italic">grano</em>
        </h1>

        <p
            class="text-cream/55 text-sm md:text-base leading-relaxed max-w-xl reveal"
            style="transition-delay:.2s"
        >
            Nacimos con el propósito de conectar el esfuerzo de los caficultores de altura del Perú con quienes buscan en cada taza una experiencia sensorial auténtica.
        </p>
    </div>
</section>


{{-- ================================================================
     HISTORIA & MANIFIESTO — Dos Columnas con Métricas Clave
     ================================================================ --}}
<section class="bg-dark py-24 px-6 relative grain overflow-hidden" aria-label="Nuestra historia">
    {{-- Glow ambiental --}}
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center relative z-10">

        {{-- Columna izquierda: Narrativa --}}
        <div class="lg:col-span-7 reveal">
            <p class="amber-tag mb-4">Manifiesto Raíz & Grano</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream leading-tight mb-6">
                Más que una cafetería,<br>un <em class="text-amber not-italic">tributo al origen</em>
            </h2>

            <div class="space-y-4 text-cream/65 text-sm md:text-base leading-relaxed mb-8">
                <p>
                    En <strong class="text-cream font-semibold">{{ config('cafe.nombre') ?? 'Raíz & Grano' }}</strong> entendemos que un café memorable no empieza en la máquina de espresso, sino en los suelos fértiles de las altas cordilleras peruanas a más de 1,600 metros sobre el nivel del mar.
                </p>
                <p>
                    Nuestro nombre nace de la profunda admiración por la <span class="text-amber font-medium">raíz</span> —el árbol, la tierra viva y las manos que lo cultivan con sabiduría ancestral— y el <span class="text-amber font-medium">grano</span> —el fruto seleccionado minuciosamente que tostamos y calibramos con rigor científico.
                </p>
                <p>
                    Trabajamos directamente con familias caficultoras de Cajamarca, Cusco, Junín y Piura. Al eliminar intermediarios innecesarios, aseguramos una compensación justa y constante, permitiéndonos traer a tu mesa micro-lotes de perfil limpio, acidez viva y notas aromáticas complejas.
                </p>
            </div>

            {{-- Cita destacada --}}
            <blockquote class="border-l-2 border-amber pl-5 py-2 my-6 bg-amber/5 rounded-r-lg">
                <p class="font-display text-xl italic text-cream">
                    “Un buen café despierta los sentidos; un café con trazabilidad y propósito transforma comunidades.”
                </p>
                <cite class="block text-xs uppercase tracking-widest text-amber font-semibold mt-2 not-italic">
                    — Filosofía Raíz & Grano
                </cite>
            </blockquote>
        </div>

        {{-- Columna derecha: Composición fotográfica y estadísticas --}}
        <div class="lg:col-span-5 reveal" style="transition-delay:.15s">
            <div class="relative rounded-xl overflow-hidden border border-border bg-card p-4 shadow-2xl">
                {{-- Foto principal --}}
                <div class="relative rounded-lg overflow-hidden h-72 sm:h-80 mb-6">
                    <img
                        src="{{ config('cafe.about_img') }}"
                        alt="Proceso de tueste y granos en {{ config('cafe.nombre') }}"
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-linear-to-t from-ink/70 via-transparent to-transparent"></div>
                    <span class="badge absolute top-3 right-3">Comercio Directo</span>
                </div>

                {{-- Grid de 4 estadísticas clave --}}
                <div class="grid grid-cols-2 gap-3">
                    @foreach (config('cafe.nosotros_stats') as $st)
                    <div class="bg-surface/80 border border-border/80 rounded-lg p-4 text-center">
                        <span class="font-display text-3xl font-bold text-amber block mb-0.5">{{ $st['valor'] }}</span>
                        <span class="text-cream text-xs font-medium block uppercase tracking-wider mb-1">{{ $st['etiqueta'] }}</span>
                        <span class="text-[11px] text-muted block leading-snug">{{ $st['subtexto'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</section>


{{-- ================================================================
     NUESTROS 4 PILARES — Valores Fundamentales
     ================================================================ --}}
<section class="bg-ink py-24 px-6 border-t border-border" aria-label="Nuestros pilares">
    <div class="max-w-7xl mx-auto">

        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <p class="amber-tag justify-center mb-3">Nuestra Filosofía</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream">
                Los 4 pilares de nuestra calidad
            </h2>
            <p class="text-muted text-sm mt-3 leading-relaxed">
                Cada decisión que tomamos, desde el origen en finca hasta el vertido final en tu taza, se rige por estos principios innegociables.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-7">
            @foreach (config('cafe.pilares') as $i => $pilar)
            <div
                class="pilar-card flex flex-col reveal"
                style="transition-delay:{{ $i * .1 }}s"
            >
                <div class="pilar-icon-box">
                    <i class="{{ $pilar['icono'] }}" aria-hidden="true"></i>
                </div>
                <h3 class="font-display text-2xl font-semibold text-cream mb-2.5">
                    {{ $pilar['titulo'] }}
                </h3>
                <p class="text-muted text-sm leading-relaxed flex-1">
                    {{ $pilar['desc'] }}
                </p>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ================================================================
     EL VIAJE DEL CAFÉ — De la Finca a tu Taza (4 Fases)
     ================================================================ --}}
<section class="bg-surface py-24 px-6" aria-label="Proceso artesanal del café">
    <div class="max-w-7xl mx-auto">

        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <p class="amber-tag justify-center mb-3">Trazabilidad & Cuidado</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream">
                El viaje del grano a tu taza
            </h2>
            <p class="text-muted text-sm mt-3 leading-relaxed">
                Un proceso riguroso y artesanal donde la ciencia de la extracción y el amor por el detalle se encuentran en cada etapa.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-7">
            @foreach (config('cafe.proceso_cafe') as $pi => $proc)
            <article
                class="process-card flex flex-col reveal"
                style="transition-delay:{{ $pi * .12 }}s"
            >
                {{-- Foto con número de fase --}}
                <div class="relative h-48 overflow-hidden">
                    <img
                        src="{{ $proc['imagen'] }}"
                        alt="{{ $proc['titulo'] }}"
                        class="process-card-img w-full h-full object-cover"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-linear-to-t from-card via-transparent to-transparent"></div>
                    <span class="absolute top-3 left-3 bg-amber text-white font-display text-xs font-bold px-2.5 py-1 rounded">
                        Fase {{ $proc['fase'] }}
                    </span>
                </div>

                {{-- Contenido --}}
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-center gap-2 mb-2 text-amber text-xs font-semibold uppercase tracking-wider">
                        <i class="{{ $proc['icono'] }}" aria-hidden="true"></i>
                        <span>Paso {{ $proc['fase'] }}</span>
                    </div>
                    <h3 class="font-display text-xl font-semibold text-cream mb-2">
                        {{ $proc['titulo'] }}
                    </h3>
                    <p class="text-muted text-xs leading-relaxed flex-1">
                        {{ $proc['desc'] }}
                    </p>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>


{{-- ================================================================
     NUESTRO EQUIPO — Especialistas y Baristas
     ================================================================ --}}
<section class="bg-dark py-24 px-6 border-t border-border" aria-label="Nuestro equipo">
    <div class="max-w-7xl mx-auto">

        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <p class="amber-tag justify-center mb-3">Maestría & Pasión</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream">
                Las manos detrás de cada taza
            </h2>
            <p class="text-muted text-sm mt-3 leading-relaxed">
                Profesionales certificados con años de dedicación al café de especialidad y a la hospitalidad.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach (config('cafe.equipo') as $ti => $eq)
            <div
                class="team-card flex flex-col reveal"
                style="transition-delay:{{ $ti * .14 }}s"
            >
                {{-- Foto con badge de especialidad --}}
                <div class="relative h-72 overflow-hidden">
                    <img
                        src="{{ $eq['imagen'] }}"
                        alt="{{ $eq['nombre'] }} - {{ $eq['rol'] }}"
                        class="team-card-img w-full h-full object-cover"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-linear-to-t from-surface via-transparent to-transparent"></div>
                    <span class="badge absolute top-3 right-3">{{ $eq['especialidad'] }}</span>
                </div>

                {{-- Info del especialista --}}
                <div class="p-6 flex flex-col flex-1 bg-surface">
                    <h3 class="font-display text-2xl font-semibold text-cream mb-0.5">
                        {{ $eq['nombre'] }}
                    </h3>
                    <p class="text-amber text-xs font-semibold uppercase tracking-wider mb-3">
                        {{ $eq['rol'] }}
                    </p>
                    <p class="text-muted text-sm leading-relaxed flex-1">
                        {{ $eq['bio'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ================================================================
     COMPROMISO SOSTENIBLE & COMUNIDAD
     ================================================================ --}}
<section class="bg-surface py-20 px-6" aria-label="Compromiso ambiental">
    <div class="max-w-7xl mx-auto">
        <div class="bg-card border border-border rounded-2xl p-8 sm:p-12 relative overflow-hidden reveal">
            {{-- Glow decorativo --}}
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-amber/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center relative z-10">
                <div class="lg:col-span-1">
                    <p class="amber-tag mb-3">Sostenibilidad</p>
                    <h3 class="font-display text-3xl md:text-4xl font-bold text-cream leading-tight">
                        Cuidamos el entorno<br>que nos da el café
                    </h3>
                </div>

                <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="flex flex-col gap-2">
                        <div class="w-10 h-10 rounded-full border border-amber/40 flex items-center justify-center text-amber text-sm mb-1">
                            <i class="fa-solid fa-leaf" aria-hidden="true"></i>
                        </div>
                        <h4 class="text-cream font-medium text-sm">Empaques 100% Eco</h4>
                        <p class="text-muted text-xs leading-relaxed">Vasos y bolsas de café biodegradables y compostables.</p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <div class="w-10 h-10 rounded-full border border-amber/40 flex items-center justify-center text-amber text-sm mb-1">
                            <i class="fa-solid fa-handshake-angle" aria-hidden="true"></i>
                        </div>
                        <h4 class="text-cream font-medium text-sm">Precios Éticos</h4>
                        <p class="text-muted text-xs leading-relaxed">Pagos por encima del estándar de mercado a los productores.</p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <div class="w-10 h-10 rounded-full border border-amber/40 flex items-center justify-center text-amber text-sm mb-1">
                            <i class="fa-solid fa-recycle" aria-hidden="true"></i>
                        </div>
                        <h4 class="text-cream font-medium text-sm">Economía Circular</h4>
                        <p class="text-muted text-xs leading-relaxed">Donación de la borra de café como abono orgánico a huertos locales.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ================================================================
     CTA FINAL — Visítanos / Reserva
     ================================================================ --}}
<section class="bg-dark grain relative overflow-hidden py-24 px-6 border-t border-border" aria-label="Visítanos">
    {{-- Glow decorativo --}}
    <div class="absolute top-0 right-0 w-80 h-80 bg-amber/4 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute bottom-0 left-0 w-60 h-60 bg-amber/4 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-3xl mx-auto text-center relative z-10">
        <p class="amber-tag justify-center mb-5 reveal">Te esperamos</p>
        <h2 class="font-display text-5xl md:text-6xl font-bold text-cream leading-tight mb-5 reveal" style="transition-delay:.1s">
            Ven a vivir la <em class="text-amber not-italic">experiencia</em>
        </h2>
        <p class="text-cream/50 text-sm md:text-base mb-10 leading-relaxed reveal" style="transition-delay:.2s">
            Visítanos en nuestra barra de especialidad, prueba nuestros métodos artesanales o reserva una mesa para tu próxima reunión.
        </p>

        <div class="flex flex-wrap justify-center gap-4 reveal" style="transition-delay:.28s">
            <a href="/carta" class="btn-amber">
                <i class="fa-solid fa-book-open-reader mr-2" aria-hidden="true"></i>Ver Nuestra Carta
            </a>
            <a href="/reserva" class="btn-ghost">
                <i class="fa-regular fa-calendar-check mr-2" aria-hidden="true"></i>Reservar una Mesa
            </a>
        </div>
    </div>
</section>

@endsection

