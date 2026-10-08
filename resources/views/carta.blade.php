@extends('layout.layout')

@section('content')


<x-hero-section
    heroClass="carta-hero"
    :subtag="setting('subtag', config('cafe.subtag'))"
    title="Nuestra "
    highlight="Carta"
    description="Bebidas de especialidad, repostería artesanal y mucho más, elaborados con los mejores granos de origen."
/>

{{-- ================================================================
    PRODUCTOS DESTACADOS — Cards con foto desde el catálogo
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
            @foreach (array_slice($productosCarta, 0, 3) as $i => $prod)
            <x-product-card :producto="$prod" :index="$i" />
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
            @foreach ($menuCategorias ?? config('cafe.menu_categorias') as $ci => $cat)
            <div class="reveal" @style(['transition-delay: ' . ($ci * 0.13) . 's'])>

                {{-- Encabezado de categoría con imagen circular --}}
                <div class="flex items-center gap-4 mb-6 pb-4 border-b border-amber/20">
                    <div class="w-16 h-16 rounded-full overflow-hidden shrink-0 border-2 border-amber/40">
                        <img
                            src="{{ $cat['imagen'] }}"
                            alt="{{ $cat['nombre'] }}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                            decoding="async"
                        >
                    </div>
                    <h3 class="font-display text-2xl font-semibold text-amber uppercase tracking-wide">
                        {{ $cat['nombre'] }}
                    </h3>
                </div>

                {{-- Ítems con línea punteada --}}
                <ul class="space-y-4">
                    @foreach ($cat['items'] as $item)
                    <li>
                        <div class="flex items-end gap-2">
                            <span class="font-body font-medium text-cream text-sm uppercase tracking-wide shrink-0">
                                {{ $item['nombre'] }}
                            </span>
                            <span class="dots-line"></span>
                            <span class="text-amber font-semibold text-sm shrink-0">
                                {{ $item['precio'] }}
                            </span>
                            <button
                                type="button"
                                class="add-to-cart-btn group/btn w-7 h-7 rounded-full bg-amber/20 border border-amber/40 text-amber hover:bg-gold hover:border-gold hover:text-ink active:scale-90 flex items-center justify-center transition-all duration-200 hover:scale-110 shrink-0 cursor-pointer text-xs shadow-xs"
                                data-name="{{ $item['nombre'] }}"
                                data-price="{{ $item['precio'] }}"
                                title="Agregar {{ $item['nombre'] }} al pedido"
                                aria-label="Agregar {{ $item['nombre'] }} al pedido"
                            >
                                <i class="fa-solid fa-plus text-amber group-hover/btn:text-ink transition-colors" aria-hidden="true"></i>
                            </button>
                        </div>
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


<x-promo-banner />

<x-cta-visitanos />

@endsection
