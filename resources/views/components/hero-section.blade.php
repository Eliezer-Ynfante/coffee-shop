@props([
    'subtag' => null,
    'title',
    'highlight' => null,
    'description' => null,
    'bgImage' => null,
    'heroClass' => ''
])

<section
    class="{{ $heroClass }} relative flex items-center overflow-hidden grain"
    aria-label="{{ $title }}"
>
    {{-- Fondo base --}}
    <div class="absolute inset-0 bg-ink"></div>

    @if ($bgImage)
    {{-- Imagen de fondo (solo cuando se proporciona explícitamente) --}}
    <div
        class="absolute inset-0"
        @style(["background-image: url('{$bgImage}'); background-size: cover; background-position: center; background-attachment: fixed; opacity: .45"])
    ></div>
    {{-- Gradientes de composición sobre imagen --}}
    <div class="absolute inset-0 bg-linear-to-r from-ink via-ink/85 to-ink/35"></div>
    <div class="absolute inset-0 bg-linear-to-t from-ink/70 via-transparent to-ink/50"></div>
    @else
    {{-- Fondo sólido con degradado cálido del tema (sin imagen, carga instantánea) --}}
    <div class="absolute inset-0" style="background: linear-gradient(135deg, #0A0704 0%, #1A120A 30%, #2E1F10 60%, rgba(200,120,58,0.15) 100%);"></div>
    <div class="absolute -bottom-24 -right-24 w-[500px] h-[500px] bg-amber/8 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -top-20 -left-20 w-80 h-80 bg-amber/5 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>
    @endif

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 w-full pt-40 pb-28">
        @if ($subtag)
        <p class="amber-tag mb-5 reveal">{{ $subtag }}</p>
        @endif

        <h1
            class="font-display text-cream font-bold leading-tight mb-4 max-w-3xl"
            style="font-size:clamp(2.6rem,6.5vw,4.8rem)"
        >
            {{ $title }}
            @if ($highlight)
            <em class="text-amber not-italic">{{ $highlight }}</em>
            @endif
        </h1>

        @if ($description)
        <p
            class="text-cream/55 text-sm md:text-base leading-relaxed max-w-xl reveal"
            style="transition-delay:.2s"
        >
            {{ $description }}
        </p>
        @endif

        {{ $slot ?? '' }}
    </div>
</section>
