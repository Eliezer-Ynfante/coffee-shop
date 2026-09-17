@extends('layout.layout')
@section('content')

<!-- ================================================================
     HERO
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
    
</section>


<!-- ================================================================
     FEATURES
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
                        <div class="text-amber font-bold text-2xl shrink-0">12+</div>
                        <div class="text-cream/55 text-[10px] uppercase tracking-widest">Orígenes</div>
                    </div>
                    <div class="w-px bg-cream/15 self-stretch"></div>
                    <div class="text-center">
                        <div class="text-amber font-bold text-2xl shrink-0">5★</div>
                        <div class="text-cream/55 text-[10px] uppercase tracking-widest">Calidad</div>
                    </div>
                    <div class="w-px bg-cream/15 self-stretch"></div>
                    <div class="text-center">
                        <div class="text-amber font-bold text-2xl shrink-0">8+</div>
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

            <form action="{{ route('reserva.store') }}" method="POST" class="space-y-5">
                @csrf
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
            <x-product-card :producto="$prod" :index="$i" />
            @endforeach
        </div>
    </div>
</section>


<x-promo-banner />

<x-cta-visitanos />

@endsection
