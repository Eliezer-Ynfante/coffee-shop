@props(['producto', 'index' => 0])

<article class="prod-card menu-card bg-card border border-border rounded-lg overflow-hidden flex flex-col reveal" style="transition-delay:{{ $index * .11 }}s">
    <div class="overflow-hidden relative h-60">
        <img
            src="{{ $producto['imagen'] }}"
            alt="{{ $producto['nombre'] }}"
            class="prod-img menu-card-img w-full h-full object-cover"
            loading="lazy"
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
        </div>
    </div>
</article>
