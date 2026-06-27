@extends('layout.layout')
@section('content')

<!-- ================================================================
     HERO — Texto a la izquierda, imagen cubriendo parte derecha
     (inspirado en la composición split de la imagen 1)
     ================================================================ -->
<section id="inicio" class="relative min-h-screen flex items-center overflow-hidden grain" aria-label="Portada">

    <!-- Fondo negro base -->
    <div class="absolute inset-0 bg-ink"></div>

    <!-- Imagen ocupa 60% derecho -->
    <div class="absolute right-0 top-0 w-full md:w-[62%] h-full hero-image" style="opacity:.8"></div>

    <!-- Gradiente izquierda→transparente + top/bottom -->
    <div class="absolute inset-0 bg-linear-to-r from-ink via-ink/90 to-transparent"></div>
    <div class="absolute inset-0 bg-linear-to-t from-ink/70 via-transparent to-ink/40"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 w-full pt-28 pb-20">
        <div class="max-w-130">

            <p class="amber-tag mb-6 reveal">{{config('cafe.subtag')}}</p>

            <h1 class="font-display leading-[1.05] mb-3 reveal" style="transition-delay:.1s">
                <span class="block text-cream/70 text-3xl md:text-4xl font-normal italic">
                    {{config('cafe.slogan')}}
                </span>
                <span class="block text-cream text-[clamp(3.5rem,8vw,6rem)] font-bold uppercase tracking-tight">
                    {{ strtoupper(config('cafe.titulo')) }}
                </span>
            </h1>

            <p class="text-cream/50 text-sm md:text-base leading-relaxed max-w-sm mb-10 reveal" style="transition-delay:.2s">
                {{config('cafe.subtitulo')}}
            </p>

            <div class="flex flex-wrap gap-3 reveal" style="transition-delay:.3s">
                <a href="#carta"    class="btn-amber">
                    <i class="fa-solid fa-book-open-reader mr-2" aria-hidden="true"></i>Ver Menú
                </a>
                <a href="#nosotros" class="btn-ghost">
                    <i class="fa-solid fa-circle-info mr-2" aria-hidden="true"></i>Más info
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll cue -->
    <div class="absolute bottom-7 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-1.5 animate-bounce opacity-35" aria-hidden="true">
        <span class="text-cream text-[9px] tracking-[0.22em] uppercase">Scroll</span>
        <i class="fa-solid fa-chevron-down text-amber text-xs"></i>
    </div>
</section>


<!-- ================================================================
     FEATURES — 4 bloques con íconos FA (ref. imagen 1, fila de iconos)
     ================================================================ -->
<section class="bg-surface border-y border-border py-14 px-6" aria-label="Ventajas">
    <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">
        @foreach (config('cafe.features') as $i => $f)
        <div class="feat-item pt-5 reveal" style="transition-delay:{{$i * .08 }}s">
            <div class="feat-icon">
                <i class="{{$f['icono']}}" aria-hidden="true"></i>
            </div>
            <h3 class="text-cream text-xs font-semibold uppercase tracking-widets mb-1.5">
                {{$f['titulo']}}
            </h3>
            <p class="text-muted text-sm leading-relaxed">
                {{$f['texto']}}
            </p>
        </div>
        @endforeach
    </div>
</section>


<!-- ================================================================
     SOBRE NOSOTROS + FORMULARIO DE RESERVA
     (ref. imagen 1: sección de 2 columnas — texto/foto + form)
     ================================================================ -->
<section id="nosotros" class="bg-dark py-24 px-6 relative grain overflow-hidden" aria-label="Sobre nosotros">

    <!-- Glow decorativo -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-14 items-start relative z-10">

        <!-- Columna: texto + stats + imagen -->
        <div class="reveal">
            <p class="amber-tag mb-4">Sobre nosotros</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream leading-tight mb-5">
                El mejor momento<br>para una <em class="text-amber not-italic">taza de café</em>
            </h2>
            <p class="text-cream/52 text-sm md:text-base leading-relaxed mb-8 max-w-md">
                {{config('cafe.descripcion')}}
            </p>

            <!-- Imagen con stats superpuestos -->
            <div class="relative rounded-lg overflow-hidden max-w-sm">
                <img
                    src="{{config('cafe.about_img')}}"
                    alt="Interior de {{config('cafe.nombre')}}"
                    class="w-full h-60 object-cover"
                    loading="lazy"
                >
                <div class="absolute inset-0 bg-linear-to-t from-ink/80 to-transparent"></div>
                <div class="absolute bottom-4 left-0 right-0 flex justify-around px-4">
                    <div class="text-center">
                        <div class="font-display text-3xl font-bold text-amber">12+</div>
                        <div class="text-cream/55 text-[10px] uppercase tracking-widest">Orígenes</div>
                    </div>
                    <div class="w-px bg-cream/15 self-stretch"></div>
                    <div class="text-center">
                        <div class="font-display text-3xl font-bold text-amber">5★</div>
                        <div class="text-cream/55 text-[10px] uppercase tracking-widest">Calidad</div>
                    </div>
                    <div class="w-px bg-cream/15 self-stretch"></div>
                    <div class="text-center">
                        <div class="font-display text-3xl font-bold text-amber">8+</div>
                        <div class="text-cream/55 text-[10px] uppercase tracking-widest">Años</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna: formulario reserva -->
        <div id="reserva" class="bg-card border border-border rounded-lg p-8 reveal" style="transition-delay:.14s">
            <h3 class="font-display text-2xl font-semibold text-cream mb-1">
                <i class="fa-regular fa-calendar-check text-amber mr-2 text-xl" aria-hidden="true"></i>
                Reservar una mesa
            </h3>
            <p class="text-muted text-sm mb-7">Asegura tu lugar favorito en nuestra cafetería</p>

            <form action="reserva.php" method="post" class="space-y-5" novalidate>
                <div>
                    <label class="f-label" for="r-nombre">
                        <i class="fa-regular fa-user mr-1" aria-hidden="true"></i> Nombre completo
                    </label>
                    <input id="r-nombre" type="text" name="nombre" class="f-input" placeholder="Tu nombre" required>
                </div>
                <div>
                    <label class="f-label" for="r-tel">
                        <i class="fa-solid fa-phone mr-1" aria-hidden="true"></i> Teléfono
                    </label>
                    <input id="r-tel" type="tel" name="telefono" class="f-input" placeholder="+51 000 000 000" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="f-label" for="r-fecha">
                            <i class="fa-regular fa-calendar mr-1" aria-hidden="true"></i> Fecha
                        </label>
                        <input id="r-fecha" type="date" name="fecha" class="f-input" required>
                    </div>
                    <div>
                        <label class="f-label" for="r-personas">
                            <i class="fa-solid fa-users mr-1" aria-hidden="true"></i> Personas
                        </label>
                        <input id="r-personas" type="number" name="personas" min="1" max="20" class="f-input" placeholder="2" required>
                    </div>
                </div>
                <div>
                    <label class="f-label" for="r-nota">
                        <i class="fa-regular fa-message mr-1" aria-hidden="true"></i> Nota adicional
                    </label>
                    <input id="r-nota" type="text" name="nota" class="f-input" placeholder="Cumpleaños, alergias...">
                </div>
                <button type="submit" class="btn-amber w-full text-center mt-1">
                    <i class="fa-solid fa-paper-plane mr-2" aria-hidden="true"></i>Enviar reserva
                </button>
            </form>
        </div>
    </div>
</section>


<!-- ================================================================
     MENÚ / CARTA — Lista punteada por categorías
     (ref. imagen 1: sección inferior con ítems y precios)
     ================================================================ -->
<section id="carta" class="bg-ink py-24 px-6" aria-label="Carta del menú">
    <div class="max-w-7xl mx-auto">

        <!-- Encabezado -->
        <div class="text-center mb-14 reveal">
            <p class="amber-tag justify-center mb-3">Bebidas y comida</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream uppercase tracking-wide">
                Lo más delicioso<br>de nuestra carta
            </h2>
        </div>

        <!-- Dos columnas de categorías -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16">
            @foreach (config('cafe.menu_categorias') as $ci => $cat)
            <div class="reveal" style="transition-delay:{{$ci * .13 }}s">

                <!-- Encabezado de categoría con imagen circular -->
                <div class="flex items-center gap-4 mb-6 pb-4 border-b border-amber/20">
                    <div class="w-16 h-16 rounded-full overflow-hidden shrink-0 border-2 border-amber/40">
                        <img
                            src="{{$cat['imagen']}}"
                            alt="{{$cat['nombre']}}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                    </div>
                    <h3 class="font-display text-2xl font-semibold text-amber uppercase tracking-wide">
                        {{$cat['nombre']}}
                    </h3>
                </div>

                <!-- Ítems con línea punteada -->
                <ul class="space-y-4">
                    @foreach ($cat['items'] as $item)
                    <li>
                        <div class="flex items-end gap-1">
                            <span class="font-body font-medium text-cream text-sm uppercase tracking-wide shrink-0">
                                {{$item['nombre']}}
                            </span>
                            <span class="dots-line"></span>
                            <span class="text-amber font-semibold text-sm shrink-0">
                                {{$item['precio']}}
                            </span>
                        </div>
                        <p class="text-muted text-xs mt-0.5 leading-relaxed">
                            {{$item['descripcion']}}
                        </p>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-14 reveal">
            <a href="#carta" class="btn-ghost">
                <i class="fa-solid fa-utensils mr-2" aria-hidden="true"></i>Ver carta completa
            </a>
        </div>
    </div>
</section>


<!-- ================================================================
     PRODUCTOS DESTACADOS — Cards con foto
     (ref. imagen 2: featured drinks en cards)
     ================================================================ -->
<section id="galeria" class="bg-surface py-24 px-6" aria-label="Especialidades">
    <div class="max-w-7xl mx-auto">

        <div class="text-center mb-14 reveal">
            <p class="amber-tag justify-center mb-3">Bebidas destacadas</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream">
                Nuestras especialidades
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-7">
            @foreach (config('cafe.productos') as $i => $prod)
            <article class="prod-card bg-card border border-border rounded-lg overflow-hidden flex flex-col reveal" style="transition-delay:{{$i * .11 }}s">

                <!-- Imagen con zoom en hover -->
                <div class="overflow-hidden relative h-60">
                    <img
                        src="{{$prod['imagen']}}"
                        alt="{{$prod['nombre']}}"
                        class="prod-img w-full h-full object-cover"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-linear-to-t from-ink/55 to-transparent"></div>

                    @if (!empty($prod['badge']))
                    <span class="badge absolute top-3 right-3">{{$prod['badge']}}</span>
                    @endif
                </div>

                <!-- Contenido -->
                <div class="p-5 flex flex-col flex-1">
                    <h3 class="font-display text-xl font-semibold text-cream mb-1">
                        {{$prod['nombre']}}
                    </h3>
                    <p class="text-muted text-sm leading-relaxed flex-1 mb-4">
                        {{$prod['descripcion']}}
                    </p>
                    <div class="flex items-center justify-between border-t border-border pt-4">
                        <span class="font-display text-2xl font-bold text-amber">
                            {{$prod['precio']}}
                        </span>
                        <button
                            class="text-xs font-medium text-cream border border-border px-3 py-1.5 rounded hover:bg-amber hover:border-amber hover:text-white transition-all duration-200"
                            aria-label="Pedir {{$prod['nombre']}}"
                        >
                            <i class="fa-solid fa-plus mr-1" aria-hidden="true"></i>Pedir
                        </button>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>


<!-- ================================================================
     BANNER PROMOCIONAL — 2 columnas (ref. imagen 2: promo banners)
     ================================================================ -->
<section class="grid grid-cols-1 md:grid-cols-2" aria-label="Promociones">

    <!-- Banner oscuro con imagen de fondo -->
    <div class="relative h-64 flex items-center px-10 overflow-hidden" style="background-image:url('https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=900&q=80'); background-size:cover; background-position:center;">
        <div class="absolute inset-0 bg-ink/70"></div>
        <div class="relative z-10">
            <p class="amber-tag mb-2 reveal">Oferta especial</p>
            <h3 class="font-display text-3xl font-bold text-cream mb-3 reveal" style="transition-delay:.1s">
                2 Cold Brew<br>por el precio de 1
            </h3>
            <a href="#carta" class="btn-amber text-xs py-2.5 reveal" style="transition-delay:.2s">
                <i class="fa-solid fa-tag mr-1.5" aria-hidden="true"></i>Ver promoción
            </a>
        </div>
    </div>

    <!-- Banner amber sólido — Daily deal -->
    <div class="relative h-64 flex items-center px-10 overflow-hidden bg-amber">
        <div class="absolute -right-10 -bottom-10 w-56 h-56 rounded-full bg-white/10 pointer-events-none" aria-hidden="true"></div>
        <div class="absolute right-6 top-6 w-28 h-28 rounded-full bg-white/6 pointer-events-none" aria-hidden="true"></div>
        <div class="relative z-10">
            <p class="text-white/65 text-[10px] font-semibold uppercase tracking-[.18em] mb-1.5 reveal">Deal del día</p>
            <h3 class="font-display text-3xl font-bold text-white mb-1.5 reveal" style="transition-delay:.1s">
                Latte + Croissant
            </h3>
            <p class="text-white/75 text-sm mb-3 reveal" style="transition-delay:.15s">Combo perfecto para empezar el día</p>
            <div class="flex items-baseline gap-2 reveal" style="transition-delay:.2s">
                <span class="font-display text-4xl font-bold text-white">S/ 18</span>
                <span class="text-white/55 line-through text-lg">S/ 23</span>
            </div>
        </div>
    </div>
</section>


<!-- ================================================================
     CTA — Visítanos / Encuéntranos
     ================================================================ -->
<section id="contacto" class="bg-dark grain relative overflow-hidden py-24 px-6" aria-label="Contacto">

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
            <?php if (!empty(config('cafe.redes')['whatsapp'])): ?>
            <a href="{{htmlspecialchars(config('cafe.redes')['whatsapp']) }}" target="_blank" rel="noopener" class="btn-amber">
                <i class="fa-brands fa-whatsapp mr-2 text-base" aria-hidden="true"></i>Pedir por WhatsApp
            </a>
            @endif
            <a href="#reserva" class="btn-ghost">
                <i class="fa-regular fa-calendar-check mr-2" aria-hidden="true"></i>Reservar mesa
            </a>
        </div>

        <!-- Info en 3 bloques -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-10 border-t border-border reveal" style="transition-delay:.34s">
            <div class="flex flex-col items-center gap-2">
                <i class="fa-solid fa-location-dot text-amber text-lg" aria-hidden="true"></i>
                <p class="text-cream/45 text-xs text-center leading-relaxed">{{config('cafe.direccion')}}</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <i class="fa-regular fa-clock text-amber text-lg" aria-hidden="true"></i>
                <p class="text-cream/45 text-xs text-center leading-relaxed">{{config('cafe.horario')}}</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <i class="fa-regular fa-envelope text-amber text-lg" aria-hidden="true"></i>
                <a href="mailto:{{config('cafe.email')}}" class="text-cream/45 text-xs hover:text-amber transition">
                    {{config('cafe.email')}}
                </a>
            </div>
        </div>
    </div>
</section>



@endsection
