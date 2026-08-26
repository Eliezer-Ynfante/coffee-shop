@extends('layout.layout')

@section('content')

{{-- ================================================================
     HERO — Cabecera interior de la página Galería
     ================================================================ --}}
<section
    class="galeria-hero relative flex items-center overflow-hidden grain"
    aria-label="Cabecera de la galería"
>
    {{-- Fondo negro base --}}
    <div class="absolute inset-0 bg-ink"></div>

    {{-- Imagen de fondo (hero tostaduría/café) --}}
    <div
        class="absolute inset-0 galeria-hero-bg"
        style="background-image:url('https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=1400&q=85'); opacity:.45"
    ></div>

    {{-- Gradientes de composición --}}
    <div class="absolute inset-0 bg-linear-to-r from-ink via-ink/85 to-ink/35"></div>
    <div class="absolute inset-0 bg-linear-to-t from-ink/70 via-transparent to-ink/50"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 w-full pt-40 pb-28">
        <p class="amber-tag mb-5 reveal">Momentos & Experiencia</p>

        <h1
            class="font-display text-cream font-bold leading-tight mb-4 reveal"
            style="font-size:clamp(2.8rem,7vw,5.2rem); transition-delay:.1s"
        >
            Nuestra <em class="text-amber not-italic">Galería</em>
        </h1>

        <p
            class="text-cream/55 text-sm md:text-base leading-relaxed max-w-lg reveal"
            style="transition-delay:.2s"
        >
            Un recorrido visual por nuestros métodos de extracción, arte latte, espacios acogedores y la pasión que ponemos en cada grano de café.
        </p>
    </div>
</section>


{{-- ================================================================
     GALERÍA — Filtros y Cuadrícula de Fotografías
     ================================================================ --}}
<section class="bg-surface py-20 px-6" aria-label="Galería fotográfica">
    <div class="max-w-7xl mx-auto">

        {{-- Filtros de categoría --}}
        <div class="flex flex-wrap items-center justify-center gap-3 mb-14 reveal">
            @foreach (config('cafe.galeria_categorias') as $cat)
            <button
                type="button"
                class="filter-btn {{ $cat['id'] === 'todos' ? 'active' : '' }}"
                data-filter="{{ $cat['id'] }}"
            >
                {{ $cat['nombre'] }}
            </button>
            @endforeach
        </div>

        {{-- Grid de fotografías --}}
        <div id="galeria-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach (config('cafe.galeria') as $i => $item)
            <article
                class="galeria-card reveal flex flex-col"
                data-category="{{ $item['categoria'] }}"
                data-img="{{ $item['imagen'] }}"
                data-title="{{ $item['titulo'] }}"
                data-desc="{{ $item['descripcion'] }}"
                data-badge="{{ $item['badge'] }}"
                style="transition-delay:{{ ($i % 3) * .12 }}s"
            >
                {{-- Contenedor de imagen --}}
                <div class="relative h-72 sm:h-80 overflow-hidden">
                    <img
                        src="{{ $item['imagen'] }}"
                        alt="{{ $item['titulo'] }}"
                        class="galeria-card-img w-full h-full object-cover"
                        loading="lazy"
                    >

                    {{-- Overlay con gradiente permanente y realce al hover --}}
                    <div class="galeria-card-overlay absolute inset-0 flex flex-col justify-between p-5">
                        {{-- Top: Badge + Icono expandir --}}
                        <div class="flex items-center justify-between">
                            <span class="badge">{{ $item['badge'] }}</span>
                            <span class="w-8 h-8 rounded-full bg-ink/70 border border-amber/40 text-amber flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fa-solid fa-up-right-and-down-left-from-center" aria-hidden="true"></i>
                            </span>
                        </div>

                        {{-- Bottom: Título y descripción breve --}}
                        <div>
                            <p class="text-amber text-xs font-semibold uppercase tracking-wider mb-1">
                                {{ $item['categoria_nombre'] }}
                            </p>
                            <h2 class="font-display text-xl font-semibold text-cream leading-snug mb-1">
                                {{ $item['titulo'] }}
                            </h2>
                            <p class="text-cream/65 text-xs line-clamp-2 leading-relaxed">
                                {{ $item['descripcion'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>


{{-- ================================================================
     LIGHTBOX MODAL — Visor de Imágenes a Pantalla Completa
     ================================================================ --}}
<div
    id="lightbox-modal"
    class="lightbox-modal"
    role="dialog"
    aria-modal="true"
    aria-hidden="true"
    aria-label="Visor de imagen"
>
    {{-- Botón Cerrar (Esquina Superior Derecha) --}}
    <button
        id="lightbox-close"
        type="button"
        class="absolute top-6 right-6 w-11 h-11 rounded-full bg-dark/80 border border-amber/40 text-cream hover:text-amber hover:border-amber flex items-center justify-center text-lg transition z-50"
        aria-label="Cerrar visor"
    >
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
    </button>

    {{-- Botón Anterior --}}
    <button
        id="lightbox-prev"
        type="button"
        class="lightbox-nav-btn absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 z-50"
        aria-label="Imagen anterior"
    >
        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
    </button>

    {{-- Botón Siguiente --}}
    <button
        id="lightbox-next"
        type="button"
        class="lightbox-nav-btn absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 z-50"
        aria-label="Imagen siguiente"
    >
        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
    </button>

    {{-- Contenido del Modal --}}
    <div class="lightbox-content bg-card border border-border rounded-xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
        <div class="relative bg-ink flex items-center justify-center overflow-hidden max-h-[62vh]">
            <img
                id="lightbox-img"
                src=""
                alt="Imagen ampliada"
                class="w-full h-full max-h-[62vh] object-contain"
            >
        </div>

        <div class="p-6 bg-surface border-t border-border flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1.5">
                    <span id="lightbox-badge" class="badge"></span>
                    <span id="lightbox-counter" class="text-xs text-muted font-medium"></span>
                </div>
                <h3 id="lightbox-title" class="font-display text-2xl font-semibold text-cream mb-1"></h3>
                <p id="lightbox-desc" class="text-muted text-sm leading-relaxed"></p>
            </div>

            <a
                href="/carta"
                class="btn-amber text-xs py-2.5 px-5 shrink-0"
            >
                <i class="fa-solid fa-book-open-reader mr-2" aria-hidden="true"></i>Ver en Carta
            </a>
        </div>
    </div>
</div>


{{-- ================================================================
     BANNER INSTAGRAM / SOCIAL — Comparte tu experiencia
     ================================================================ --}}
<section class="bg-dark py-20 px-6 relative grain overflow-hidden border-t border-border" aria-label="Redes sociales">
    {{-- Glow decorativo --}}
    <div class="absolute -top-24 -right-24 w-80 h-80 bg-amber/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-4xl mx-auto text-center relative z-10">
        <p class="amber-tag justify-center mb-4 reveal">Comunidad & Experiencias</p>
        <h2 class="font-display text-4xl md:text-5xl font-bold text-cream mb-4 reveal" style="transition-delay:.1s">
            Comparte tu momento con <em class="text-amber not-italic">#RaizYGrano</em>
        </h2>
        <p class="text-cream/50 text-sm md:text-base leading-relaxed max-w-xl mx-auto mb-9 reveal" style="transition-delay:.18s">
            Etiquétanos en tus fotos y videos en Instagram para ser parte de nuestra galería comunitaria y recibir sorpresas en tu próxima visita.
        </p>

        <div class="flex flex-wrap justify-center gap-4 reveal" style="transition-delay:.25s">
            @if (!empty(config('cafe.redes')['instagram']))
            <a
                href="{{ config('cafe.redes')['instagram'] }}"
                target="_blank"
                rel="noopener"
                class="btn-amber"
            >
                <i class="fa-brands fa-instagram mr-2 text-base" aria-hidden="true"></i>Seguir en Instagram
            </a>
            @endif

            <a href="/reserva" class="btn-ghost">
                <i class="fa-regular fa-calendar-check mr-2" aria-hidden="true"></i>Reservar una mesa
            </a>
        </div>
    </div>
</section>

@endsection

