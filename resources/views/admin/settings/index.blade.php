@extends('admin.layout')

@section('title', 'Ajustes de la Cafetería')

@section('content')

{{-- ENCABEZADO --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Ajustes</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Configuración General de la Cafetería</h1>
        <p class="text-xs text-muted mt-1">Modifica los datos comerciales, horarios, teléfonos y textos visibles en todo el sitio web.</p>
    </div>

    <div>
        <button type="submit" form="form-settings"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-medium rounded-lg bg-amber hover:bg-amberLight text-white shadow-sm transition">
            <i class="fa-solid fa-floppy-disk text-[11px]"></i>
            <span>Guardar Todos los Cambios</span>
        </button>
    </div>
</div>

<form id="form-settings" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    {{-- BLOQUE 1: IDENTIDAD COMERCIAL --}}
    <div class="bg-surface border border-border rounded-xl p-5 space-y-4">
        <div class="border-b border-border/60 pb-3 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-md bg-amber/10 text-amber flex items-center justify-center text-xs">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-cream">Identidad de Marca</h3>
                    <p class="text-[11px] text-muted">Nombres y eslóganes principales que encabezan la portada y navbar.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div>
                <label class="block text-muted mb-1 font-medium">Nombre de la Cafetería *</label>
                <input type="text" name="nombre" value="{{ setting('nombre', 'Raíz & Grano') }}" required
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Subtítulo de Marca / Ubicación</label>
                <input type="text" name="subtag" value="{{ setting('subtag', 'Café de especialidad · Piura, Perú') }}"
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Slogan Portada (Línea Cursiva)</label>
                <input type="text" name="slogan" value="{{ setting('slogan', 'Tómate un descanso') }}"
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Título Principal Portada (Mayúsculas)</label>
                <input type="text" name="titulo" value="{{ setting('titulo', 'y bebe café') }}"
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-muted mb-1 font-medium">Párrafo Introductorio de Bienvenida</label>
                <textarea name="subtitulo" rows="2" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">{{ setting('subtitulo') }}</textarea>
            </div>
        </div>
    </div>

    {{-- BLOQUE 2: DATOS DE CONTACTO Y ATENCIÓN --}}
    <div class="bg-surface border border-border rounded-xl p-5 space-y-4">
        <div class="border-b border-border/60 pb-3 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-md bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-address-book"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-cream">Canales de Contacto y Horarios</h3>
                    <p class="text-[11px] text-muted">Información visible en el pie de página, página de contacto y reservas.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div>
                <label class="block text-muted mb-1 font-medium">Teléfono Principal de Llamadas</label>
                <input type="text" name="telefono" value="{{ setting('telefono', '+51 999 999 999') }}"
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition font-mono">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Correo Electrónico de Contacto</label>
                <input type="email" name="email" value="{{ setting('email', 'hola@raizygrano.pe') }}"
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition font-mono">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Dirección Física del Local</label>
                <input type="text" name="direccion" value="{{ setting('direccion', 'Av. La Mar 620, Piura, Perú') }}"
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Horario de Atención Comercial</label>
                <input type="text" name="horario" value="{{ setting('horario', 'Lun – Vie: 7 am – 8 pm · Sáb – Dom: 8 am – 6 pm') }}"
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">
            </div>
        </div>
    </div>

    <div class="bg-surface border border-border rounded-xl p-5 space-y-4">
        <div class="border-b border-border/60 pb-3">
            <h3 class="text-sm font-semibold text-cream">Pagos con Yape y Plin</h3>
            <p class="text-[11px] text-muted">Configura el número receptor y carga el QR real de la cuenta de la cafetería.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div>
                <label class="block text-muted mb-1 font-medium" for="yape_phone">Número receptor</label>
                <input id="yape_phone" type="text" name="yape_phone" value="{{ setting('yape_phone', config('cafe.yape_phone')) }}"
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition font-mono">
            </div>
            <div>
                <label class="block text-muted mb-1 font-medium" for="payment_qr">Imagen del QR (JPEG, PNG o WebP)</label>
                <input id="payment_qr" type="file" name="payment_qr" accept="image/jpeg,image/png,image/webp"
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition">
                @if (setting('payment_qr', config('cafe.payment_qr')))
                    <img src="{{ asset('storage/' . setting('payment_qr', config('cafe.payment_qr'))) }}" alt="QR de pago configurado" class="mt-3 w-28 aspect-square object-contain bg-white rounded-md p-1">
                @endif
            </div>
        </div>
    </div>

    {{-- BLOQUE 3: REDES SOCIALES --}}
    @php
        $redes = setting('redes', config('cafe.redes', []));
        if (is_string($redes)) {
            $redes = json_decode($redes, true) ?? [];
        }
    @endphp
    <div class="bg-surface border border-border rounded-xl p-5 space-y-4">
        <div class="border-b border-border/60 pb-3 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-md bg-purple-500/10 text-purple-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-share-nodes"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-cream">Redes Sociales y WhatsApp</h3>
                    <p class="text-[11px] text-muted">Enlaces de botones sociales en navbar y footer (dejar vacío para ocultar).</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div>
                <label class="block text-muted mb-1 font-medium">Enlace WhatsApp Directo</label>
                <input type="text" name="redes[whatsapp]" value="{{ $redes['whatsapp'] ?? '' }}" placeholder="https://wa.me/51999999999"
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition font-mono">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Perfil de Instagram</label>
                <input type="text" name="redes[instagram]" value="{{ $redes['instagram'] ?? '' }}" placeholder="https://instagram.com/..."
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition font-mono">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Página de Facebook</label>
                <input type="text" name="redes[facebook]" value="{{ $redes['facebook'] ?? '' }}" placeholder="https://facebook.com/..."
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition font-mono">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Cuenta de TikTok</label>
                <input type="text" name="redes[tiktok]" value="{{ $redes['tiktok'] ?? '' }}" placeholder="https://tiktok.com/@..."
                       class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber transition font-mono">
            </div>
        </div>
    </div>

    {{-- BOTÓN FLOTANTE O AL PIE --}}
    <div class="flex items-center justify-end gap-3 pt-2">
        <button type="submit" class="px-5 py-2.5 text-xs font-semibold rounded-lg bg-amber hover:bg-amberLight text-white shadow-lg transition">
            <i class="fa-solid fa-floppy-disk mr-1.5"></i> Guardar Ajustes
        </button>
    </div>
</form>

@endsection
