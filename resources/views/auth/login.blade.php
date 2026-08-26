@extends('layout.layout')

@section('content')

<section
    class="login-section grain"
    aria-label="Iniciar Sesión"
>
    {{-- Fondo negro base --}}
    <div class="absolute inset-0 bg-ink"></div>

    {{-- Imagen de fondo ambiental --}}
    <div
        class="absolute inset-0 login-bg"
        style="background-image:url('https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=1400&q=85'); opacity:.32"
    ></div>

    {{-- Gradientes en capas --}}
    <div class="absolute inset-0 bg-linear-to-t from-ink via-ink/80 to-ink/60"></div>
    <div class="absolute inset-0 bg-radial from-amber/5 via-transparent to-transparent pointer-events-none"></div>

    {{-- Glow decorativo ámbar --}}
    <div class="absolute w-96 h-96 bg-amber/8 rounded-full blur-3xl pointer-events-none -top-20 -left-20" aria-hidden="true"></div>
    <div class="absolute w-96 h-96 bg-amber/6 rounded-full blur-3xl pointer-events-none -bottom-20 -right-20" aria-hidden="true"></div>

    {{-- Tarjeta Principal de Login --}}
    <div class="login-card p-8 sm:p-11 reveal">
        {{-- Cabecera de la tarjeta --}}
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-full border border-amber/50 bg-amber/10 flex items-center justify-center mx-auto mb-4 text-amber text-xl shadow-lg">
                <i class="fa-solid fa-mug-hot" aria-hidden="true"></i>
            </div>
            
            <p class="amber-tag justify-center mb-2">Portal de Acceso</p>
            <h1 class="font-display text-3xl sm:text-4xl font-bold text-cream">
                Iniciar <em class="text-amber not-italic">Sesión</em>
            </h1>
            <p class="text-muted text-xs sm:text-sm mt-2 leading-relaxed max-w-xs mx-auto">
                Ingresa tus credenciales para acceder al sistema de gestión de {{ config('cafe.nombre') ?? 'Raíz & Grano' }}.
            </p>
        </div>

        {{-- Formulario de Login --}}
        <form action="/login" method="POST" class="space-y-6">
            @csrf

            {{-- Campo: Correo Electrónico --}}
            <div>
                <label class="f-label" for="login-email">
                    <i class="fa-regular fa-envelope mr-1.5" aria-hidden="true"></i> Correo Electrónico
                </label>
                <div class="relative">
                    <input
                        id="login-email"
                        type="email"
                        name="email"
                        class="f-input"
                        placeholder="ejemplo@raizygrano.pe"
                        required
                        autofocus
                        autocomplete="email"
                    >
                </div>
            </div>

            {{-- Campo: Contraseña con Toggle Mostrar/Ocultar --}}
            <div>
                <label class="f-label" for="login-password">
                    <i class="fa-solid fa-lock mr-1.5" aria-hidden="true"></i> Contraseña
                </label>
                <div class="relative">
                    <input
                        id="login-password"
                        type="password"
                        name="password"
                        class="f-input pr-10"
                        placeholder="••••••••••••"
                        required
                        autocomplete="current-password"
                    >
                    <button
                        id="toggle-password"
                        type="button"
                        class="password-toggle-btn"
                        aria-label="Mostrar u ocultar contraseña"
                        title="Mostrar u ocultar contraseña"
                    >
                        <i id="toggle-password-icon" class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            {{-- Opciones: Recordar sesión y Ayuda --}}
            <div class="flex items-center justify-between gap-2 pt-1 text-xs">
                <label class="flex items-center gap-2 cursor-pointer select-none text-cream/75 hover:text-cream transition">
                    <input
                        type="checkbox"
                        name="remember"
                        class="custom-checkbox"
                    >
                    <span>Recordar sesión</span>
                </label>

                <a
                    href="/contacto"
                    class="text-amber hover:text-gold transition font-medium hover:underline"
                    title="Contáctanos si necesitas asistencia"
                >
                    ¿Necesitas ayuda?
                </a>
            </div>

            {{-- Botón de Ingreso --}}
            <button
                type="submit"
                class="btn-amber w-full py-3.5 text-sm font-semibold tracking-wide shadow-xl flex items-center justify-center gap-2"
            >
                <i class="fa-solid fa-arrow-right-to-bracket" aria-hidden="true"></i>
                <span>Ingresar al Portal</span>
            </button>
        </form>

        {{-- Nota de Seguridad y Privacidad --}}
        <div class="mt-8 pt-6 border-t border-border/80 text-center">
            <p class="text-[11px] text-muted flex items-center justify-center gap-1.5 leading-relaxed">
                <i class="fa-solid fa-shield-halved text-amber text-xs" aria-hidden="true"></i>
                <span>Acceso seguro protegido para usuarios y personal autorizado.</span>
            </p>
        </div>
    </div>
</section>

@endsection