@extends('layout.layout')

@section('content')

{{-- ================================================================
     CART OFFCANVAS — Overlay + Sidebar lateral
     ================================================================ --}}
<div id="cart-overlay" class="cart-overlay" aria-hidden="true"></div>

<aside
    id="cart-sidebar"
    class="cart-sidebar"
    role="dialog"
    aria-modal="true"
    aria-label="Carrito de compras"
    aria-hidden="true"
>

    {{-- Encabezado del carrito --}}
    <div class="cart-header">
        <h2 class="font-display text-xl font-semibold text-cream">
            <i class="fa-solid fa-cart-shopping text-amber mr-2" aria-hidden="true"></i>
            Tu pedido
        </h2>
        <button
            id="cart-close-btn"
            type="button"
            class="cart-close-btn"
            aria-label="Cerrar carrito"
        >
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>

    {{-- Estado vacío --}}
    <div id="cart-empty" class="cart-empty">
        <i class="fa-solid fa-mug-hot text-4xl mb-4" style="color:rgba(200,120,58,.35)" aria-hidden="true"></i>
        <p class="text-muted text-sm font-medium">Tu carrito está vacío</p>
        <p class="text-xs mt-1" style="color:rgba(122,101,80,.6)">Agrega productos desde el menú</p>
    </div>

    {{-- Lista de productos --}}
    <ul id="cart-items-list" class="cart-items-list hidden" aria-label="Productos en el carrito">
        {{-- Generado dinámicamente por JavaScript --}}
    </ul>

    {{-- Pie del carrito --}}
    <div id="cart-footer" class="cart-footer hidden">
        <div class="cart-total-row">
            <span class="text-cream/70 text-xs font-semibold uppercase tracking-[.15em]">Total</span>
            <span id="cart-total" class="text-amber font-bold text-2xl">S/ 0.00</span>
        </div>
        <button type="button" class="btn-amber w-full text-center mt-4" id="btn-pagar">
            <i class="fa-solid fa-credit-card mr-2" aria-hidden="true"></i>Pagar
        </button>
    </div>

</aside>


{{-- ================================================================
     HERO — Cabecera interior de la página Carta
     ================================================================ --}}
<section
    class="carta-hero relative flex items-center overflow-hidden grain"
    aria-label="Cabecera de la carta"
>

    {{-- Fondo negro base --}}
    <div class="absolute inset-0 bg-ink"></div>

    {{-- Imagen de fondo (viene de config) --}}
    <div
        class="absolute inset-0 carta-hero-bg"
        style="background-image:url('{{ config('cafe.hero_img') }}'); opacity:.55"
    ></div>

    {{-- Gradientes de composición --}}
    <div class="absolute inset-0 bg-linear-to-r from-ink via-ink/85 to-ink/35"></div>
    <div class="absolute inset-0 bg-linear-to-t from-ink/70 via-transparent to-ink/50"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 w-full pt-40 pb-28">
        <p class="amber-tag mb-5 reveal">{{ config('cafe.subtag') }}</p>

        <h1
            class="font-display text-cream font-bold leading-tight mb-4 reveal"
            style="font-size:clamp(2.8rem,7vw,5.2rem); transition-delay:.1s"
        >
            Nuestra <em class="text-amber not-italic">Carta</em>
        </h1>

        <p
            class="text-cream/55 text-sm md:text-base leading-relaxed max-w-sm reveal"
            style="transition-delay:.2s"
        >
            Bebidas de especialidad, repostería artesanal y mucho más,
            elaborados con los mejores granos de origen.
        </p>
    </div>

</section>


{{-- ================================================================
     PRODUCTOS DESTACADOS — Cards con foto (desde config('cafe.productos'))
     ================================================================ --}}
<section class="bg-surface py-24 px-6" aria-label="Especialidades de la carta">
    <div class="max-w-7xl mx-auto">

        {{-- Encabezado de sección --}}
        <div class="text-center mb-14 reveal">
            <p class="amber-tag justify-center mb-3">Especialidades</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream">
                Nuestras especialidades
            </h2>
        </div>

        {{-- Grid de cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-7">
            @foreach (config('cafe.productos') as $i => $prod)
            <article
                class="menu-card bg-card border border-border rounded-lg overflow-hidden flex flex-col reveal"
                style="transition-delay:{{ $i * .1 }}s"
            >

                {{-- Imagen con zoom en hover --}}
                <div class="overflow-hidden relative h-60">
                    <img
                        src="{{ $prod['imagen'] }}"
                        alt="{{ $prod['nombre'] }}"
                        class="menu-card-img w-full h-full object-cover"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-linear-to-t from-ink/55 to-transparent"></div>

                    @if (!empty($prod['badge']))
                    <span class="badge absolute top-3 right-3">{{ $prod['badge'] }}</span>
                    @endif
                </div>

                {{-- Contenido de la card --}}
                <div class="p-5 flex flex-col flex-1">
                    <h3 class="font-display text-xl font-semibold text-cream mb-1">
                        {{ $prod['nombre'] }}
                    </h3>
                    <p class="text-muted text-sm leading-relaxed flex-1 mb-4">
                        {{ $prod['descripcion'] }}
                    </p>
                    <div class="flex items-center justify-between border-t border-border pt-4">
                        <span class="text-amber font-bold text-2xl shrink-0">
                            {{ $prod['precio'] }}
                        </span>
                        <button
                            type="button"
                            class="btn-pedir"
                            data-nombre="{{ $prod['nombre'] }}"
                            data-precio="{{ $prod['precio'] }}"
                            aria-label="Agregar {{ $prod['nombre'] }} al carrito"
                        >
                            <i class="fa-solid fa-plus mr-1.5" aria-hidden="true"></i>Pedir
                        </button>
                    </div>
                </div>

            </article>
            @endforeach
        </div>

    </div>
</section>


{{-- ================================================================
     MENÚ / CARTA — Lista punteada por categorías
     (mismo estilo que welcome, con botón Pedir por ítem)
     ================================================================ --}}
<section id="carta" class="bg-ink py-24 px-6" aria-label="Carta del menú">
    <div class="max-w-7xl mx-auto">

        {{-- Encabezado --}}
        <div class="text-center mb-14 reveal">
            <p class="amber-tag justify-center mb-3">Bebidas y comida</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream uppercase tracking-wide">
                Lo más delicioso<br>de nuestra carta
            </h2>
        </div>

        {{-- Dos columnas de categorías --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16">
            @foreach (config('cafe.menu_categorias') as $ci => $cat)
            <div class="reveal" style="transition-delay:{{ $ci * .13 }}s">

                {{-- Encabezado de categoría con imagen circular --}}
                <div class="flex items-center gap-4 mb-6 pb-4 border-b border-amber/20">
                    <div class="w-16 h-16 rounded-full overflow-hidden shrink-0 border-2 border-amber/40">
                        <img
                            src="{{ $cat['imagen'] }}"
                            alt="{{ $cat['nombre'] }}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                    </div>
                    <h3 class="font-display text-2xl font-semibold text-amber uppercase tracking-wide">
                        {{ $cat['nombre'] }}
                    </h3>
                </div>

                {{-- Ítems con línea punteada y botón Pedir --}}
                <ul class="space-y-5">
                    @foreach ($cat['items'] as $item)
                    <li>

                        {{-- Fila: nombre · · · precio + botón Pedir --}}
                        <div class="flex items-end gap-2">
                            <div class="flex items-end gap-1 flex-1 min-w-0">
                                <span class="font-body font-medium text-cream text-sm uppercase tracking-wide shrink-0">
                                    {{ $item['nombre'] }}
                                </span>
                                <span class="dots-line"></span>
                                <span class="text-amber font-semibold text-sm shrink-0">
                                    {{ $item['precio'] }}
                                </span>
                            </div>
                            <button
                                type="button"
                                class="btn-pedir btn-pedir--sm"
                                data-nombre="{{ $item['nombre'] }}"
                                data-precio="{{ $item['precio'] }}"
                                aria-label="Agregar {{ $item['nombre'] }} al carrito"
                            >
                                <i class="fa-solid fa-plus" aria-hidden="true"></i>Pedir
                            </button>
                        </div>

                        {{-- Descripción --}}
                        <p class="text-muted text-xs mt-0.5 leading-relaxed">
                            {{ $item['descripcion'] }}
                        </p>

                    </li>
                    @endforeach
                </ul>

            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ================================================================
     BANNER PROMOCIONAL — Igual que en la vista Welcome
     ================================================================ --}}
<section class="grid grid-cols-1 md:grid-cols-2" aria-label="Promociones">

    {{-- Banner oscuro con imagen de fondo --}}
    <div
        class="relative h-64 flex items-center px-10 overflow-hidden"
        style="background-image:url('https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=900&q=80'); background-size:cover; background-position:center;"
    >
        <div class="absolute inset-0 bg-ink/70"></div>
        <div class="relative z-10">
            <p class="amber-tag mb-2 reveal">Oferta especial</p>
            <h3
                class="text-3xl font-bold text-cream mb-3 reveal"
                style="transition-delay:.1s"
            >
                2 Cold Brew<br>por el precio de 1
            </h3>
            <a
                href="#carta"
                class="btn-amber text-xs py-2.5 reveal"
                style="transition-delay:.2s"
            >
                <i class="fa-solid fa-tag mr-1.5" aria-hidden="true"></i>Ver promoción
            </a>
        </div>
    </div>

    {{-- Banner amber sólido — Deal del día --}}
    <div class="relative h-64 flex items-center px-10 overflow-hidden bg-amber">
        <div class="absolute -right-10 -bottom-10 w-56 h-56 rounded-full bg-white/10 pointer-events-none" aria-hidden="true"></div>
        <div class="absolute right-6 top-6 w-28 h-28 rounded-full bg-white/6 pointer-events-none" aria-hidden="true"></div>
        <div class="relative z-10">
            <p class="text-white/65 text-[10px] font-semibold uppercase tracking-[.18em] mb-1.5 reveal">Deal del día</p>
            <h3
                class="font-display text-3xl font-bold text-white mb-1.5 reveal"
                style="transition-delay:.1s"
            >
                Latte + Croissant
            </h3>
            <p class="text-white/75 text-sm mb-3 reveal" style="transition-delay:.15s">
                Combo perfecto para empezar el día
            </p>
            <div class="flex items-baseline gap-2 reveal" style="transition-delay:.2s">
                <span class="font-bold text-2xl text-white shrink-0">S/ 18</span>
                <span class="text-white/55 line-through font-bold text-lg shrink-0">S/ 23</span>
            </div>
        </div>
    </div>

</section>


{{-- ================================================================
     CTA FINAL — Información y contacto rápido
     ================================================================ --}}
<section class="bg-dark grain relative overflow-hidden py-20 px-6" aria-label="Contacto rápido">

    <div class="absolute top-0 right-0 w-80 h-80 bg-amber/4 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute bottom-0 left-0 w-60 h-60 bg-amber/4 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-3xl mx-auto text-center relative z-10">
        <p class="amber-tag justify-center mb-5 reveal">Te esperamos</p>
        <h2
            class="font-display text-5xl md:text-6xl font-bold text-cream leading-tight mb-5 reveal"
            style="transition-delay:.1s"
        >
            ¿Listo para vivir<br>la <em class="text-amber not-italic">experiencia</em>?
        </h2>
        <p
            class="text-cream/45 text-sm md:text-base mb-10 leading-relaxed reveal"
            style="transition-delay:.2s"
        >
            Visítanos, reserva tu mesa o pídenos por WhatsApp.<br>
            El mejor café de tu semana está a un clic.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-12 reveal" style="transition-delay:.28s">
            @if (!empty(config('cafe.redes')['whatsapp']))
            <a
                href="{{ htmlspecialchars(config('cafe.redes')['whatsapp']) }}"
                target="_blank"
                rel="noopener"
                class="btn-amber"
            >
                <i class="fa-brands fa-whatsapp mr-2 text-base" aria-hidden="true"></i>Pedir por WhatsApp
            </a>
            @endif
            <a href="/reserva" class="btn-ghost">
                <i class="fa-regular fa-calendar-check mr-2" aria-hidden="true"></i>Reservar mesa
            </a>
        </div>

        {{-- Info en 3 bloques --}}
        <div
            class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-10 border-t border-border reveal"
            style="transition-delay:.34s"
        >
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
                <a
                    href="mailto:{{ config('cafe.email') }}"
                    class="text-cream/45 text-xs hover:text-amber transition"
                >
                    {{ config('cafe.email') }}
                </a>
            </div>
        </div>
    </div>

</section>

@endsection
