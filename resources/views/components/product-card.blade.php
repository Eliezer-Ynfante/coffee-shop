@props(['producto', 'index' => 0])

<article class="prod-card menu-card bg-card border border-border rounded-lg overflow-hidden flex flex-col reveal" @style(['transition-delay: ' . ($index * 0.11) . 's'])>
    <div class="overflow-hidden relative h-60">
        <img
            src="{{ $producto['imagen'] }}"
            alt="{{ $producto['nombre'] }}"
            class="prod-img menu-card-img w-full h-full object-cover"
            loading="lazy"
            decoding="async"
        >
        <div class="absolute inset-0 bg-linear-to-t from-ink/55 to-transparent"></div>

        @if (!empty($producto['badge']))
        <span class="badge absolute top-3 right-3">{{ $producto['badge'] }}</span>
        @endif
    </div>

    <div class="p-5 flex flex-col flex-1">
        <h3 class="font-display text-xl font-semibold text-cream mb-1">
            {{ $producto['nombre'] }}
        </h3>
        <p class="text-muted text-sm leading-relaxed flex-1 mb-4">
            {{ $producto['descripcion'] }}
        </p>
        <div class="flex items-center justify-between border-t border-border pt-4">
            <span class="text-amber font-bold text-2xl shrink-0">
                {{ $producto['precio'] }}
            </span>
            <button
                type="button"
                class="add-to-cart-btn group/btn w-9 h-9 rounded-full bg-amber/20 border border-amber/40 text-amber hover:bg-gold hover:border-gold hover:text-ink active:scale-90 flex items-center justify-center transition-all duration-200 hover:scale-110 shrink-0 cursor-pointer text-sm shadow-sm"
                data-name="{{ $producto['nombre'] }}"
                data-price="{{ $producto['precio'] }}"
                title="Agregar {{ $producto['nombre'] }} al pedido"
                aria-label="Agregar {{ $producto['nombre'] }} al pedido"
            >
                <i class="fa-solid fa-plus text-amber group-hover/btn:text-ink transition-colors" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</article>
