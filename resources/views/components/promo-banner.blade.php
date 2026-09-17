<section class="grid grid-cols-1 md:grid-cols-2" aria-label="Promociones">
    {{-- Banner oscuro con imagen de fondo --}}
    <div class="relative h-64 flex items-center px-10 overflow-hidden" style="background-image:url('https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=900&q=80'); background-size:cover; background-position:center;">
        <div class="absolute inset-0 bg-ink/70"></div>
        <div class="relative z-10">
            <p class="amber-tag mb-2 reveal">Oferta especial</p>
            <h3 class="text-3xl font-bold text-cream mb-3 reveal" style="transition-delay:.1s">
                2 Cold Brew<br>por el precio de 1
            </h3>
            <a href="{{ route('carta') }}" class="btn-amber text-xs py-2.5 reveal" style="transition-delay:.2s">
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
            <h3 class="font-display text-3xl font-bold text-white mb-1.5 reveal" style="transition-delay:.1s">
                Latte + Croissant
            </h3>
            <p class="text-white/75 text-sm mb-3 reveal" style="transition-delay:.15s">Combo perfecto para empezar el día</p>
            <div class="flex items-baseline gap-2 reveal" style="transition-delay:.2s">
                <span class="font-bold text-2xl text-white shrink-0">S/ 18</span>
                <span class="text-white/55 line-through font-bold text-lg shrink-0">S/ 23</span>
            </div>
        </div>
    </div>
</section>
