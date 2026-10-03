@props(['class' => 'flex gap-2.5'])

@php($redes = setting('redes', config('cafe.redes', [])))

<div class="{{ $class }}">
    @if (!empty($redes['instagram']))
    <a href="{{ $redes['instagram'] }}" target="_blank" rel="noopener" class="soc" aria-label="Instagram">
        <i class="fa-brands fa-instagram" aria-hidden="true"></i>
    </a>
    @endif
    @if (!empty($redes['facebook']))
    <a href="{{ $redes['facebook'] }}" target="_blank" rel="noopener" class="soc" aria-label="Facebook">
        <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
    </a>
    @endif
    @if (!empty($redes['tiktok']))
    <a href="{{ $redes['tiktok'] }}" target="_blank" rel="noopener" class="soc" aria-label="TikTok">
        <i class="fa-brands fa-tiktok" aria-hidden="true"></i>
    </a>
    @endif
    @if (!empty($redes['whatsapp']))
    <a href="{{ $redes['whatsapp'] }}" target="_blank" rel="noopener" class="soc" aria-label="WhatsApp">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
    </a>
    @endif
</div>
