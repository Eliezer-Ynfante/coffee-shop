@props(['class' => 'flex gap-2.5'])

<div class="{{ $class }}">
    @if (!empty(config('cafe.redes')['instagram']))
    <a href="{{ config('cafe.redes')['instagram'] }}" target="_blank" rel="noopener" class="soc" aria-label="Instagram">
        <i class="fa-brands fa-instagram" aria-hidden="true"></i>
    </a>
    @endif
    @if (!empty(config('cafe.redes')['facebook']))
    <a href="{{ config('cafe.redes')['facebook'] }}" target="_blank" rel="noopener" class="soc" aria-label="Facebook">
        <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
    </a>
    @endif
    @if (!empty(config('cafe.redes')['tiktok']))
    <a href="{{ config('cafe.redes')['tiktok'] }}" target="_blank" rel="noopener" class="soc" aria-label="TikTok">
        <i class="fa-brands fa-tiktok" aria-hidden="true"></i>
    </a>
    @endif
    @if (!empty(config('cafe.redes')['whatsapp']))
    <a href="{{ config('cafe.redes')['whatsapp'] }}" target="_blank" rel="noopener" class="soc" aria-label="WhatsApp">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
    </a>
    @endif
</div>
