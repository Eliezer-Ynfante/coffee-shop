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
    {{-- Fondo negro base --}}
    <div class="absolute inset-0 bg-ink"></div>

    {{-- Imagen de fondo --}}
    @if ($bgImage)
    <div
        class="absolute inset-0"
        style="background-image:url('{{ $bgImage }}'); background-size:cover; background-position:center; background-attachment:fixed; opacity:.45"
    ></div>
    @endif

    {{-- Gradientes de composición --}}
    <div class="absolute inset-0 bg-linear-to-r from-ink via-ink/85 to-ink/35"></div>
    <div class="absolute inset-0 bg-linear-to-t from-ink/70 via-transparent to-ink/50"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 w-full pt-40 pb-28">
        @if ($subtag)
        <p class="amber-tag mb-5 reveal">{{ $subtag }}</p>
        @endif

        <h1
            class="font-display text-cream font-bold leading-tight mb-4 reveal max-w-3xl"
            style="font-size:clamp(2.6rem,6.5vw,4.8rem); transition-delay:.1s"
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
